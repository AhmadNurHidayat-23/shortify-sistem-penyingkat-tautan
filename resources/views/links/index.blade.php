@extends('layouts.app')

@section('content')
    <section class="hero" aria-labelledby="hero-title">
        <div class="eyebrow"><span class="eyebrow-line"></span>SEDERHANA. SINGKAT. SIAP DIBAGIKAN.</div>
        <h1 id="hero-title">Tautan panjang?<br>Buat lebih <span>singkat.</span></h1>
        <p class="hero-description">Ubah tautan panjang menjadi lebih ringkas, mudah dibagikan,<br class="desktop-break"> dan praktis dalam hitungan detik.</p>
        <div class="shortener-card">
            <form action="{{ route('links.store') }}" method="POST" id="shorten-form">
                @csrf
                <label for="original_url">Tempel tautan panjang Anda</label>
                <div class="url-input-group">
                    <span class="input-icon" aria-hidden="true">↗</span>
                    <input type="url" name="original_url" id="original_url" placeholder="https://contoh.com/tautan-yang-panjang" value="{{ old('original_url') }}" required maxlength="2048" autocomplete="url" spellcheck="false" aria-describedby="url-hint @error('original_url') url-error @enderror" @error('original_url') aria-invalid="true" @enderror>
                    <button class="button button-primary" type="submit" id="shorten-button">Persingkat Tautan <span aria-hidden="true">↗</span></button>
                </div>
                @error('original_url')<p class="field-error" id="url-error" role="alert">{{ $message }}</p>@enderror
                <p class="input-hint" id="url-hint"><span aria-hidden="true">ⓘ</span> Gunakan tautan dengan awalan http:// atau https://.</p>
            </form>
            @if($createdLink)
                <div class="result" aria-labelledby="result-title">
                    <div class="result-heading"><span class="success-dot" aria-hidden="true">✓</span><h2 id="result-title">Tautan Anda siap dibagikan</h2></div>
                    <div class="result-content">
                        <a class="result-url" href="{{ $createdLink->shortUrl() }}" target="_blank" rel="noopener noreferrer">{{ $createdLink->shortUrl() }}</a>
                        <button class="button button-primary button-small" type="button" data-copy="{{ $createdLink->shortUrl() }}">Salin Tautan</button>
                        <a class="button button-light button-small" href="{{ $createdLink->shortUrl() }}" target="_blank" rel="noopener noreferrer">Buka Tautan <span aria-hidden="true">↗</span></a>
                    </div>
                </div>
            @endif
        </div>
        <div class="hero-benefits"><span><span aria-hidden="true">✓</span> Tanpa perlu akun</span><span><span aria-hidden="true">✓</span> Siap dalam hitungan detik</span><span><span aria-hidden="true">✓</span> Mudah dibagikan</span></div>
    </section>
    @include('links.partials.stats')
    <section class="history-section" aria-labelledby="history-title">
        <div class="section-heading"><div><p class="section-kicker">RUANG TAUTAN ANDA</p><h2 id="history-title">Tautan terbaru</h2><p>Semua tautan penting, lebih ringkas dalam satu tempat.</p></div><a class="text-link" href="{{ route('links.history') }}">Lihat semua riwayat <span aria-hidden="true">↗</span></a></div>
        @include('links.partials.history')
    </section>
    <aside class="local-note"><span class="local-note-symbol" aria-hidden="true">⌘</span><div><strong>Dibuat di sini, tersimpan di sini.</strong><p>Tautan tersimpan di perangkat ini. Alamat localhost hanya dapat diakses dari lingkungan lokal Anda.</p></div><span class="note-label">DEMO LOKAL</span></aside>
@endsection
