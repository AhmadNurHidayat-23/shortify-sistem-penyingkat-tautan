@extends('layouts.app')
@section('title', 'Riwayat Tautan')

@section('content')
    <section class="history-page" aria-labelledby="history-title">
        <a class="back-link" href="{{ route('home') }}">← Kembali ke beranda</a>
        <div class="page-heading"><div><p class="section-kicker">RUANG TAUTAN ANDA</p><h1 id="history-title">Riwayat Tautan</h1><p>Kelola tautan Anda, lihat kunjungannya, dan bagikan kembali.</p></div><a class="button button-primary" href="{{ route('home') }}#original_url">Buat Tautan <span aria-hidden="true">+</span></a></div>
        @include('links.partials.stats')
        @include('links.partials.history')
        @if($links->hasPages())
            <nav class="pagination" aria-label="Navigasi halaman riwayat">
                @if($links->onFirstPage())<span class="pagination-disabled">← Sebelumnya</span>@else<a href="{{ $links->previousPageUrl() }}">← Sebelumnya</a>@endif
                <span>Halaman {{ $links->currentPage() }} dari {{ $links->lastPage() }}</span>
                @if($links->hasMorePages())<a href="{{ $links->nextPageUrl() }}">Berikutnya →</a>@else<span class="pagination-disabled">Berikutnya →</span>@endif
            </nav>
        @endif
    </section>
@endsection
