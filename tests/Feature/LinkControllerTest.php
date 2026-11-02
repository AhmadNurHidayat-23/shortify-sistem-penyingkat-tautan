<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_home_displays_indonesian_empty_state_and_zero_statistics(): void
    {
        $this->get(route('home'))
            ->assertSee('Tautan panjang?')
            ->assertSee('Awali dengan satu tautan.')
            ->assertViewHas('totalLinks', 0)
            ->assertViewHas('totalClicks', 0);
    }

    #[DataProvider('validUrls')]
    public function test_creates_short_link_for_http_and_https_urls(string $url): void
    {
        $response = $this->post(route('links.store'), [
            'original_url' => $url,
            'clicks' => 900,
            'short_code' => 'injected',
        ]);

        $response->assertRedirectToRoute('home')
            ->assertSessionHas('status', 'Tautan berhasil dipersingkat. Siap dibagikan!');
        $this->assertDatabaseCount('links', 1);
        $link = Link::query()->sole();
        $this->assertDatabaseHas('links', ['original_url' => $url, 'clicks' => 0]);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{7}$/', $link->short_code);
        $response->assertSessionHas('created_link_id', $link->id);
        $this->get(route('home'))->assertSee($link->shortUrl())->assertSee('Salin Tautan')->assertSee('Buka Tautan');
    }

    /** @return array<string, array{string}> */
    public static function validUrls(): array
    {
        return [
            'https' => ['https://example.com/artikel?bahasa=id&halaman=2#ringkasan'],
            'http' => ['http://example.com/artikel'],
        ];
    }

    #[DataProvider('invalidUrls')]
    public function test_rejects_invalid_input_with_indonesian_feedback(mixed $url, string $message): void
    {
        $this->from(route('home'))->post(route('links.store'), ['original_url' => $url])
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors(['original_url' => $message]);

        $this->assertDatabaseEmpty('links');
    }

    /** @return array<string, array{mixed, string}> */
    public static function invalidUrls(): array
    {
        $invalid = 'Masukkan URL yang valid dengan awalan http:// atau https://.';

        return [
            'empty' => ['', 'Masukkan tautan yang ingin dipersingkat.'],
            'array' => [['https://example.com'], 'Tautan harus berupa teks.'],
            'too long' => ['https://example.com/'.str_repeat('a', 2048), 'Tautan tidak boleh lebih dari 2.048 karakter.'],
            'plain text' => ['bukan tautan', $invalid],
            'missing protocol' => ['example.com', $invalid],
            'javascript' => ['javascript:alert(1)', $invalid],
            'data' => ['data:text/html,hello', $invalid],
            'file' => ['file:///etc/passwd', $invalid],
            'ftp' => ['ftp://example.com/file', $invalid],
            'protocol relative' => ['//example.com', $invalid],
            'missing host' => ['https://', $invalid],
        ];
    }

    public function test_retries_a_collision_without_overwriting_an_existing_link(): void
    {
        $existing = Link::factory()->create(['short_code' => 'taken01']);
        $codes = ['taken01', 'fresh02'];
        Str::createRandomStringsUsing(function (int $length) use (&$codes): string {
            return $length === 7 ? array_shift($codes) : str_repeat('a', $length);
        });

        try {
            $this->post(route('links.store'), ['original_url' => 'https://example.com/new'])
                ->assertRedirectToRoute('home');

            $this->assertDatabaseCount('links', 2);
            $this->assertDatabaseHas('links', ['short_code' => 'fresh02', 'original_url' => 'https://example.com/new']);
            $this->assertSame($existing->original_url, $existing->fresh()->original_url);
        } finally {
            Str::createRandomStringsNormally();
        }
    }

    public function test_does_not_generate_a_code_reserved_for_the_history_page(): void
    {
        $codes = ['riwayat', 'fresh02'];
        Str::createRandomStringsUsing(function (int $length) use (&$codes): string {
            return $length === 7 ? array_shift($codes) : str_repeat('a', $length);
        });

        try {
            $this->post(route('links.store'), ['original_url' => 'https://example.com/new'])
                ->assertRedirectToRoute('home');

            $this->assertDatabaseHas('links', ['short_code' => 'fresh02']);
            $this->assertDatabaseMissing('links', ['short_code' => 'riwayat']);
        } finally {
            Str::createRandomStringsNormally();
        }
    }

    public function test_returns_feedback_when_all_generated_codes_collide(): void
    {
        Link::factory()->create(['short_code' => 'taken01']);
        Str::createRandomStringsUsing(fn (): string => 'taken01');

        try {
            $this->post(route('links.store'), ['original_url' => 'https://example.com/new'])
                ->assertSessionHasErrors(['original_url' => 'Tautan belum dapat dibuat. Silakan coba lagi.']);

            $this->assertDatabaseCount('links', 1);
        } finally {
            Str::createRandomStringsNormally();
        }
    }

    public function test_redirects_and_counts_each_get_visit_without_caching(): void
    {
        $link = Link::factory()->create(['clicks' => 3]);

        $this->get($link->shortUrl())->assertRedirect($link->original_url)
            ->assertHeader('Cache-Control', 'no-store, private');
        $this->get($link->shortUrl())->assertRedirect($link->original_url);

        $this->assertSame(5, $link->fresh()->clicks);
    }

    public function test_head_request_does_not_inflate_visit_count(): void
    {
        $link = Link::factory()->create();

        $this->head($link->shortUrl())->assertRedirect($link->original_url);

        $this->assertSame(0, $link->fresh()->clicks);
    }

    public function test_rejects_unsafe_destination_even_if_already_stored(): void
    {
        $link = Link::factory()->create(['original_url' => 'javascript:alert(1)']);

        $this->get($link->shortUrl())->assertNotFound();

        $this->assertSame(0, $link->fresh()->clicks);
    }

    public function test_missing_code_displays_indonesian_404_page(): void
    {
        $this->get('/missing')->assertNotFound()->assertSee('Tautan tidak ditemukan.');
    }

    public function test_history_paginates_newest_first_and_statistics_include_all_links(): void
    {
        Link::factory()->count(11)->create(['clicks' => 2]);
        $latest = Link::query()->latest('id')->firstOrFail();
        $oldest = Link::query()->oldest('id')->firstOrFail();

        $this->get(route('links.history'))
            ->assertViewHas('totalLinks', 11)->assertViewHas('totalClicks', 22)
            ->assertSee($latest->shortUrl())->assertDontSee($oldest->shortUrl())
            ->assertSee('Halaman 1 dari 2')->assertSee('Berikutnya');
        $this->get(route('links.history', ['page' => 2]))
            ->assertSee($oldest->shortUrl())->assertDontSee($latest->shortUrl());
    }

    public function test_home_displays_only_five_newest_links(): void
    {
        Link::factory()->count(6)->create();
        $oldest = Link::query()->oldest('id')->firstOrFail();

        $this->get(route('home'))->assertViewHas('links', fn ($links): bool => $links->count() === 5)
            ->assertDontSee($oldest->shortUrl())->assertViewHas('totalLinks', 6);
    }

    public function test_history_escapes_original_url(): void
    {
        $url = 'https://example.com/?q=<script>alert("xss")</script>';
        Link::factory()->create(['original_url' => $url]);

        $this->get(route('links.history'))->assertSee($url)->assertDontSee($url, false);
    }

    public function test_deleting_link_removes_it_and_updates_statistics(): void
    {
        $link = Link::factory()->create(['clicks' => 8]);

        $this->delete(route('links.destroy', $link))
            ->assertRedirectToRoute('links.history')
            ->assertSessionHas('status', 'Tautan berhasil dihapus.');

        $this->assertModelMissing($link);
        $this->get($link->shortUrl())->assertNotFound();
        $this->get(route('links.history'))->assertViewHas('totalLinks', 0)->assertViewHas('totalClicks', 0);
    }

    public function test_deleting_missing_link_returns_404(): void
    {
        $this->delete(route('links.destroy', 999))->assertNotFound();
    }

    public function test_deleted_result_is_not_displayed_from_session(): void
    {
        $this->withSession(['created_link_id' => 999])->get(route('home'))->assertOk()
            ->assertDontSee('Tautan Anda siap dibagikan');
    }
}
