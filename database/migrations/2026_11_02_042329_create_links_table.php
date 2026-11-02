<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table): void {
            $table->id();
            $table->text('original_url');
            $table->string('short_code', 7)->unique();
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
