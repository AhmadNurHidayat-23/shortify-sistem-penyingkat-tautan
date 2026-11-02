# Shortify

**Tautan lebih singkat. Berbagi lebih mudah.**

Shortify adalah aplikasi penyingkat URL yang mengubah alamat web panjang menjadi tautan pendek agar lebih praktis dibagikan. Aplikasi ini menyediakan satu tempat untuk membuat tautan, mengelola riwayat, dan melihat jumlah kunjungan.

Seluruh antarmuka menggunakan bahasa Indonesia dengan tampilan minimalis yang menyesuaikan ukuran layar desktop, tablet, dan ponsel.

## Fitur Utama

- **Persingkat tautan** — Ubah URL panjang menjadi tautan pendek dengan kode unik.
- **Salin dan bagikan** — Salin tautan pendek melalui tombol yang tersedia, disertai notifikasi hasil penyalinan.
- **Pengalihan otomatis** — Pengunjung yang membuka tautan pendek akan diarahkan ke alamat aslinya.
- **Penghitung kunjungan** — Lihat jumlah klik pada setiap tautan yang telah dibuat.
- **Riwayat tautan** — Telusuri URL asli, URL pendek, tanggal pembuatan, dan jumlah kunjungan, dengan urutan terbaru terlebih dahulu.
- **Hapus tautan** — Hapus tautan yang tidak lagi diperlukan melalui dialog konfirmasi.
- **Statistik ringkas** — Pantau total tautan dan total kunjungan dari seluruh tautan yang masih tersimpan.

## Cara Kerja

1. Pengguna memasukkan URL dengan awalan `http://` atau `https://`.
2. Sistem memvalidasi alamat tersebut dan membuat kode pendek yang unik.
3. URL asli dan kode pendek disimpan ke database, kemudian tautan pendek ditampilkan untuk disalin atau dibuka.
4. Saat tautan pendek dikunjungi, sistem menambahkan jumlah klik dan mengarahkan pengunjung ke URL asli.
5. Pengguna dapat melihat dan mengelola tautan melalui halaman **Riwayat Tautan**.

Tautan yang dihapus atau kode yang tidak ditemukan akan menampilkan halaman 404. Penghapusan juga mengeluarkan tautan beserta jumlah kunjungannya dari statistik.

## Halaman Aplikasi

### Beranda

Halaman utama untuk mempersingkat URL. Beranda menampilkan formulir input, hasil penyingkatan, statistik keseluruhan, dan lima tautan terbaru.

### Riwayat Tautan

Halaman pengelolaan seluruh tautan dengan informasi tanggal pembuatan dan jumlah klik. Setiap tautan memiliki tindakan salin dan hapus. Riwayat ditampilkan dalam halaman berisi maksimal sepuluh tautan.

## Teknologi

Shortify dibangun menggunakan **Laravel 13** dan **PHP**, dengan **Blade** untuk tampilan serta **SQLite** untuk penyimpanan data. Antarmuka menggunakan **HTML, CSS, dan JavaScript murni**, sementara **Vite** mengelola aset aplikasi.

## Ruang Lingkup

Versi ini dirancang untuk penggunaan lokal tanpa login. Riwayat dan pengelolaan tautan digunakan bersama oleh siapa pun yang dapat mengakses aplikasi.

Tautan dengan alamat `localhost` atau `127.0.0.1` hanya dapat diakses dari lingkungan lokal tempat aplikasi berjalan. Penggunaan publik memerlukan perlindungan akses dan pembatasan penyalahgunaan tambahan.
