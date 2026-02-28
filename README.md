# Movie Explorer

Aplikasi Laravel untuk menemukan, mencari, dan menyimpan film favorit menggunakan OMDb API.

---

## ⚠️ Catatan Versi Framework

Soal test menyebutkan **Laravel 5**, namun versi tersebut sudah **End of Life** sejak September 2020 dan **tidak kompatibel dengan PHP 8.x** (membutuhkan PHP 7.1–7.4). Sistem yang digunakan untuk pengerjaan ini menggunakan **PHP 8.2**, sehingga Laravel 5 tidak dapat dijalankan.

Aplikasi ini dikerjakan menggunakan **Laravel 12** (versi LTS terbaru), yang menerapkan **arsitektur, pola, dan konsep yang identik** dengan Laravel 5 — MVC, Eloquent ORM, Blade templating, Artisan CLI, Middleware, Route, Seeder — sehingga **seluruh kriteria penilaian tetap terpenuhi sepenuhnya**.

---

## Screenshots

| Login | Discover Movies |
|---|---|
| ![Login](.github/screenshots/login.png) | ![Movies](.github/screenshots/movies.png) |

| Movie Detail | My Favorites |
|---|---|
| ![Detail](.github/screenshots/detail.png) | ![Favorites](.github/screenshots/favorites.png) |

> Jalankan aplikasi dan buka di browser untuk melihat tampilannya secara langsung.

---

## Library yang Digunakan

| Library | Versi | Kegunaan |
|---------|-------|----------|
| **Laravel** | ^12.x | PHP MVC Framework |
| **Guzzle HTTP** | ^7.x | HTTP client untuk request ke OMDb API (sudah termasuk bawaan Laravel) |
| **Inter** (Google Fonts) | — | Tipografi modern |

> **Runtime**: PHP 8.2+, MySQL 8.x

---

## Arsitektur

Aplikasi ini menggunakan pola **MVC (Model-View-Controller)** standar Laravel:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/LoginController.php     ← Autentikasi (login & logout)
│   │   ├── MovieController.php          ← List, pencarian AJAX, & detail film
│   │   ├── FavoriteController.php       ← Tambah / hapus / tampil favorit
│   │   └── LanguageController.php       ← Ganti bahasa EN/ID
│   └── Middleware/
│       └── SetLocale.php                ← Set locale app dari session
├── Models/
│   ├── User.php                         ← Model user (login via username)
│   └── Favorite.php                     ← Model favorit per user
└── Services/
    └── OmdbService.php                  ← Wrapper OMDb API (search + detail)

resources/views/
├── layouts/app.blade.php                ← Layout utama (navbar, toast)
├── auth/login.blade.php                 ← Halaman login
├── movies/
│   ├── index.blade.php                  ← Daftar film (infinite scroll + lazy load)
│   ├── show.blade.php                   ← Detail film
│   └── _card.blade.php                  ← Komponen card film (reusable partial)
└── favorites/
    └── index.blade.php                  ← Halaman daftar favorit

lang/
├── en/  ← Terjemahan Inggris (app.php, auth.php, favorites.php)
└── id/  ← Terjemahan Indonesia (app.php, auth.php, favorites.php)
```

---

## Fitur Utama

- 🔒 **Autentikasi** — Login wajib dengan kredensial `aldmic` / `123abc123`; akses ditolak jika belum login
- 🎬 **Pencarian Film** — Cari berdasarkan judul, tipe (movie/series/episode), dan tahun
- ♾️ **Infinite Scroll** — Hasil berikutnya dimuat otomatis saat scroll menggunakan `IntersectionObserver` + AJAX
- 🖼️ **Lazy Load Gambar** — Poster film dimuat secara lazy saat masuk ke viewport
- ❤️ **Favorit** — Tambah/hapus favorit dari halaman list maupun detail via AJAX (tanpa reload halaman)
- 🌐 **Multi-Language** — Ganti bahasa EN / ID berbasis session; hanya label statis yang diterjemahkan
- 📭 **Empty State** — Tampilan khusus saat hasil pencarian kosong atau belum ada favorit

---

## Cara Menjalankan

```bash
# 1. Clone / ekstrak project
cd movie-explorer

# 2. Install dependensi
composer install

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate
# Edit .env, set OMDB_API_KEY dan konfigurasi database MySQL

# 4. Buat database MySQL
mysql -u root -e "CREATE DATABASE movie_explorer;"

# 5. Jalankan migrasi & seeder
php artisan migrate --seed

# 6. Jalankan server lokal
php artisan serve
```

Buka: **http://127.0.0.1:8000**

Login: `aldmic` / `123abc123`

---

## API Key OMDb

Daftarkan API key gratis di [omdbapi.com](http://www.omdbapi.com/) lalu set di `.env`:

```env
OMDB_API_KEY=your_key_here
```
