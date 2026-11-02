<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Link> */
class LinkFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'original_url' => fake()->url(),
            'short_code' => Str::random(7),
            'clicks' => 0,
        ];
    }
}
