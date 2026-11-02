<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        return view('links.index', [
            ...$this->statistics(),
            'links' => Link::query()->latest('id')->limit(5)->get(),
            'createdLink' => Link::query()->find($request->session()->get('created_link_id')),
        ]);
    }

    public function history(): View
    {
        return view('links.history', [
            ...$this->statistics(),
            'links' => Link::query()->latest('id')->paginate(10),
        ]);
    }

    public function store(StoreLinkRequest $request): RedirectResponse
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $shortCode = Str::random(7);

            if ($shortCode === 'riwayat') {
                continue;
            }

            try {
                $link = Link::query()->create([
                    'original_url' => $request->validated('original_url'),
                    'short_code' => $shortCode,
                ]);

                return to_route('home')->with([
                    'created_link_id' => $link->id,
                    'status' => 'Tautan berhasil dipersingkat. Siap dibagikan!',
                ]);
            } catch (UniqueConstraintViolationException) {
                continue;
            }
        }

        throw ValidationException::withMessages([
            'original_url' => 'Tautan belum dapat dibuat. Silakan coba lagi.',
        ]);
    }

    public function redirect(Request $request, Link $link): RedirectResponse
    {
        abort_unless(Validator::make(
            ['url' => $link->original_url],
            ['url' => ['required', 'url:http,https']],
        )->passes(), 404);

        if ($request->isMethod('GET')) {
            $link->increment('clicks');
        }

        return redirect()->away($link->original_url)->withHeaders([
            'Cache-Control' => 'no-store, private',
            'Referrer-Policy' => 'no-referrer',
        ]);
    }

    public function destroy(Link $link): RedirectResponse
    {
        $link->delete();

        return to_route('links.history')->with('status', 'Tautan berhasil dihapus.');
    }

    /** @return array{totalLinks: int, totalClicks: int} */
    private function statistics(): array
    {
        return [
            'totalLinks' => Link::query()->count(),
            'totalClicks' => (int) Link::query()->sum('clicks'),
        ];
    }
}
