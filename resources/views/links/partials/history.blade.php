<div class="history-table">
    @if($links->isEmpty())
        <div class="empty-state">
            <span class="empty-icon" aria-hidden="true">↗</span>
            <h3>Awali dengan satu tautan.</h3>
            <p>Belum ada tautan di sini. Persingkat tautan pertama Anda<br class="desktop-break"> dan mulai berbagi dengan lebih mudah.</p>
            <a class="text-link" href="{{ route('home') }}#original_url">Buat tautan pertama <span aria-hidden="true">→</span></a>
        </div>
    @else
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th scope="col">TAUTAN</th>
                        <th scope="col">DIBUAT</th>
                        <th scope="col">KUNJUNGAN</th>
                        <th scope="col"><span class="sr-only">Tindakan</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $link)
                        <tr>
                            <td>
                                <div class="link-cell">
                                    <span class="link-symbol" aria-hidden="true">↗</span>
                                    <div class="link-addresses">
                                        <a class="short-address" href="{{ $link->shortUrl() }}" target="_blank" rel="noopener noreferrer">
                                            {{ $link->shortUrl() }} <span aria-hidden="true">↗</span>
                                        </a>
                                        <span class="original-address" title="{{ $link->original_url }}">{{ $link->original_url }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="date-cell">
                                <time datetime="{{ $link->created_at->toIso8601String() }}">{{ $link->created_at->translatedFormat('d M Y') }}</time>
                                <span>{{ $link->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                            </td>
                            <td>
                                <span class="click-count">{{ number_format($link->clicks, 0, ',', '.') }} <span>klik</span></span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="icon-button" type="button" data-copy="{{ $link->shortUrl() }}" aria-label="Salin tautan {{ $link->short_code }}" title="Salin Tautan">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                            <rect x="8" y="8" width="12" height="12" rx="2"/>
                                            <path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('links.destroy', $link) }}" method="POST" data-delete-form>
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon-button delete-button" type="submit" aria-label="Hapus tautan {{ $link->short_code }}" title="Hapus Tautan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                                <path d="M3 6h18M9 6V3h6v3M6 6l1 15h10l1-15M10 10v7M14 10v7"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
