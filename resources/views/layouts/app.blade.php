<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Shortify membantu Anda mempersingkat, membagikan, dan mengelola tautan dengan mudah.">
    <meta name="color-scheme" content="light">
    <title>@yield('title', 'Tautan singkat, langkah mudah') — Shortify</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#konten">Langsung ke konten</a>
    <header class="site-header">
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Shortify — beranda">
                <span class="brand-mark" aria-hidden="true">↗</span>shortify<span class="brand-dot">.</span>
            </a>
            <nav aria-label="Navigasi utama">
                <a href="{{ route('home') }}" @class(['nav-link', 'active' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
                <a href="{{ route('links.history') }}" @class(['nav-link', 'active' => request()->routeIs('links.history')]) @if(request()->routeIs('links.history')) aria-current="page" @endif>Riwayat Tautan</a>
            </nav>
            <span class="local-badge"><span></span>Ruang lokal Anda</span>
        </div>
    </header>
    <main id="konten" class="container">
        @if(session('status'))
            <div class="notice" role="status"><span aria-hidden="true">✓</span>{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
    <footer class="site-footer">
        <div class="container footer-inner">
            <div><a class="brand footer-brand" href="{{ route('home') }}">shortify.</a><p>Tautan lebih singkat. Berbagi lebih mudah.</p></div>
            <div class="footer-meta"><span>Dibuat untuk hal-hal sederhana.</span><span>© {{ date('Y') }} Shortify. Seluruh hak cipta dilindungi.</span></div>
        </div>
    </footer>
    <div id="toast" class="toast" role="status" aria-live="polite" hidden></div>
    <dialog id="delete-dialog" aria-labelledby="delete-title" aria-describedby="delete-description">
        <form method="dialog" class="dialog-content">
            <span class="dialog-icon" aria-hidden="true">×</span>
            <h2 id="delete-title">Hapus tautan ini?</h2>
            <p id="delete-description">Tautan pendek akan berhenti berfungsi. Tindakan ini tidak dapat dibatalkan.</p>
            <div class="dialog-actions"><button class="button button-light" value="cancel" autofocus>Batal</button><button class="button button-danger" value="confirm">Ya, Hapus Tautan</button></div>
        </form>
    </dialog>
</body>
</html>
