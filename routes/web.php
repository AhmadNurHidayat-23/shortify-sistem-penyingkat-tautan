<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LinkController::class, 'index'])->name('home');
Route::get('/riwayat', [LinkController::class, 'history'])->name('links.history');
Route::post('/tautan', [LinkController::class, 'store'])->name('links.store');
Route::delete('/tautan/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
Route::get('/{link:short_code}', [LinkController::class, 'redirect'])
    ->where('link', '[A-Za-z0-9]{7}')->name('links.redirect');
