# Shortify

Aplikasi penyingkat URL berbasis Laravel 13 dengan antarmuka bahasa Indonesia. Ringan, tanpa akun, dan dirancang sebagai demo lokal.

## Fitur

- Persingkat URL HTTP/HTTPS dengan kode acak 7 karakter, indeks unik, dan percobaan ulang saat terjadi benturan kode.
- Salin dan buka tautan pendek; tampilkan notifikasi hasil penyalinan.
- Alihkan pengunjung dan hitung kunjungan secara atomik. Permintaan HEAD tidak menambah kunjungan.
- Riwayat terbaru, pagination 10 tautan per halaman, tanggal pembuatan, dan konfirmasi hapus.
- Statistik total tautan dan kunjungan dari seluruh tautan yang masih tersimpan.
- Validasi server, proteksi CSRF, escaping Blade, dan halaman 404 bahasa Indonesia.
- Tampilan responsif dengan font Inter yang disajikan secara lokal setelah build.

## Teknologi dan persyaratan

Laravel 13, PHP 8.5, Blade, SQLite, CSS, JavaScript murni, dan Vite 8. Tidak menggunakan React, Vue, autentikasi, atau API eksternal untuk fitur aplikasi.

Siapkan PHP 8.5 dengan PDO SQLite, Composer 2, Node.js 22.12+ (atau versi LTS yang kompatibel dengan Vite 8), dan npm. Instalasi ini telah diuji dengan PHP 8.5.12 dan Node.js 26.12.1.

## Instalasi

```sh
git clone https://github.com/AhmadNurHidayat-23/shortify.git
cd shortify
composer install
cp .env.example .env
php artisan key:generate --no-interaction
touch database/database.sqlite
php artisan migrate --no-interaction
npm ci --ignore-scripts
npm run build
php artisan serve
```

Buka http://127.0.0.1:8000. Di Mac ini proyek berada di `/Applications/MAMP/htdocs/shortify`. Untuk menjalankan proyek yang sudah terpasang, cukup masuk ke folder tersebut dan jalankan `php artisan serve`.

Font Inter disertakan sebagai aset lokal dengan lisensi SIL Open Font License di `resources/fonts/OFL.txt`. Koneksi internet dibutuhkan saat memasang dependensi. Build dan pemakaian aplikasi setelah instalasi tidak memerlukan layanan eksternal.

## Konfigurasi SQLite

`.env.example` sudah menggunakan `DB_CONNECTION=sqlite`. Tanpa `DB_DATABASE`, Laravel memakai `database/database.sqlite`. Sesi dan cache memakai file; antrean memakai mode sinkron. Tidak perlu MySQL atau layanan MAMP untuk menjalankan `php artisan serve`.

File `.env`, database SQLite, kredensial, dependensi terpasang, dan hasil build tidak disertakan dalam Git. Pengujian memakai SQLite `:memory:` agar data lokal tetap aman.

Data contoh bersifat opsional:

```sh
php artisan db:seed --no-interaction
```

## Penggunaan

1. Tempel URL lengkap dengan awalan `http://` atau `https://`.
2. Klik **Persingkat Tautan**, lalu **Salin Tautan** atau **Buka Tautan**.
3. Buka **Riwayat Tautan** untuk melihat jumlah klik, menyalin kembali, atau menghapus tautan.
4. Setujui dialog penghapusan hanya jika tautan tidak lagi diperlukan. Tautan yang dihapus akan menghasilkan 404 dan kunjungannya tidak lagi masuk statistik.

Penyalinan otomatis didukung pada browser yang menyediakan Clipboard API di localhost/HTTPS. Tersedia fallback untuk browser yang mendukung penyalinan lama; jika tidak tersedia, aplikasi menampilkan pesan untuk menyalin alamat secara manual.

## Pengembangan dan pengujian

```sh
npm run dev
php artisan test --compact
vendor/bin/pint --dirty --format agent
```

Jalankan server Laravel pada terminal lain saat memakai Vite dalam mode pengembangan. Untuk memakai hasil build kembali, hentikan Vite, hapus `public/hot` bila tersisa, dan jalankan `npm run build`.

## Batas penggunaan

**Demo lokal tanpa autentikasi.** Semua pengunjung aplikasi berbagi riwayat dan hak pengelolaan. Jangan melakukan deployment publik dengan endpoint pengelolaan terbuka tanpa perlindungan akses, pembatasan permintaan, dan perlindungan penyalahgunaan.

URL `localhost` atau `127.0.0.1` hanya dapat diakses dari lingkungan lokal yang menjalankan aplikasi. Mengirim URL tersebut ke perangkat lain tidak membuatnya otomatis dapat diakses. Gunakan host dan `APP_URL` yang sesuai jika lingkungan pengembangan berubah.
