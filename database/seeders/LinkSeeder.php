<?php

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    public function run(): void
    {
        Link::query()->firstOrCreate(
            ['short_code' => 'contoh1'],
            ['original_url' => 'https://laravel.com/docs/13.x'],
        );
    }
}
