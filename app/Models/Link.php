<?php

namespace App\Models;

use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['original_url', 'short_code'])]
class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['clicks' => 'integer'];
    }

    public function shortUrl(): string
    {
        return route('links.redirect', ['link' => $this->short_code]);
    }
}
