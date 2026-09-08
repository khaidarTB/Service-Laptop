# 📘 Dokumentasi Aplikasi LaptopCare

**Sistem Informasi Manajemen & Pelayanan Servis Laptop**

Dokumen ini merupakan dokumentasi teknis dan fungsional lengkap untuk aplikasi **LaptopCare**, sistem manajemen layanan servis laptop yang mencakup *booking online*, pelacakan status perbaikan *real-time*, manajemen tiket servis, manajemen sparepart/inventori, transaksi pembayaran, hingga laporan keuangan.

| | |
|---|---|
| **Nama Aplikasi** | LaptopCare |
| **Versi** | 1.0.0 |
| **Framework** | Laravel 12 |
| **Bahasa Server** | PHP >= 8.2 |
| **Basis Data** | MySQL / SQLite (mendukung keduanya) |
| **Lisensi** | MIT |

---

## Daftar Isi

1. [Ringkasan Proyek](#1-ringkasan-proyek)
2. [Fitur Utama](#2-fitur-utama)
3. [Arsitektur & Stack Teknologi](#3-arsitektur--stack-teknologi)
4. [Struktur Proyek](#4-struktur-proyek)
5. [Model Data & Relasi Database](#5-model-data--relasi-database)
6. [Sistem Autentikasi & Peran (Role)](#6-sistem-autentikasi--peran-role)
7. [Alur Bisnis](#7-alur-bisnis)
8. [Routing (Daftar Endpoint)](#8-routing-daftar-endpoint)
9. [Logika Bisnis Utama](#9-logika-bisnis-utama)
10. [Fitur Khusus & Otomasi](#10-fitur-khusus--otomasi)
11. [Antarmuka Pengguna (Views)](#11-antarmuka-pengguna-views)
12. [Instalasi & Konfigurasi](#12-instalasi--konfigurasi)
13. [Akun Demo / Seeder](#13-akun-demo--seeder)
14. [Testing](#14-testing)
15. [Deployment](#15-deployment)
16. [Pemeliharaan & Troubleshooting](#16-pemeliharaan--troubleshooting)
17. [Roadmap / Pengembangan Lanjut](#17-roadmap--pengembangan-lanjut)
18. [Kontribusi & Lisensi](#18-kontribusi--lisensi)

---

## 1. Ringkasan Proyek

**LaptopCare** adalah aplikasi *web-based* (monolitik) yang dibangun dengan **Laravel 12** untuk mengelola seluruh operasional pusat servis laptop: penerimaan unit, diagnosa, perbaikan, penggantian sparepart, pembayaran, hingga penyerahan kembali ke pelanggan.

Aplikasi dirancang dengan **tiga peran pengguna (role)** yang memiliki area kerja terpisah:

- **Admin** — mengelola seluruh data master, tiket servis, keuangan, dan laporan.
- **Teknisi** — mengerjakan tiket servis yang ditugaskan kepadanya.
- **Customer (Pelanggan)** — memantau status perbaikan laptop miliknya.

Selain itu tersedia **halaman publik** yang memungkinkan calon pelanggan *booking* servis tanpa login, memilih sparepart dari katalog, dan melacak status perbaikan hanya dengan nomor tiket atau nomor WhatsApp.

### 1.1 Tujuan Aplikasi

1. Menyediakan **booking servis online** dengan nomor tiket otomatis.
2. Memberikan **transparansi progres perbaikan** secara *real-time*.
3. Mengelola **inventori sparepart** beserta peringatan stok menipis (*low stock*).
4. Mencatat **transaksi pembayaran** (Tunai / Transfer / QRIS) dan mencetak **faktur/invoice**.
5. Menghasilkan **laporan keuangan & analitik** (grafik bulanan, merek terlaris, top teknisi).
6. Menjaga **jejak audit (audit trail)** melalui log perubahan status tiap tiket.

### 1.2 Target Pengguna

- Pemilik / operator pusat servis laptop (Admin).
- Teknisi perbaikan laptop (Teknisi).
- Pelanggan yang menitipkan laptop untuk diperbaiki (Customer).

---

## 2. Fitur Utama

### 2.1 Halaman Publik (Tanpa Login)

| Fitur | Keterangan |
|---|---|
| Landing Page | Halaman promosi layanan, keunggulan, dan *live status workshop*. |
| Katalog Sparepart | Menampilkan sparepart yang tersedia beserta harga jual dan stok. |
| Keranjang Sparepart | Keranjang interaktif (Alpine.js + `localStorage`) untuk memilih part sebelum booking. |
| Form Booking Online | Form registrasi servis; menghasilkan **nomor tiket otomatis** `SRV-YYYYMMDD-XXX`. |
| Lacak Tiket | Pelacakan status servis menggunakan nomor tiket **atau** nomor WhatsApp. |

### 2.2 Autentikasi

- Registrasi, login, logout.
- Lupa kata sandi (reset password).
- Verifikasi email.
- Konfirmasi kata sandi.
- *Rate limiting* login (maks. 5 percobaan) untuk mencegah *brute force*.

### 2.3 Dashboard Admin (Analitik)

- Metrik: servis hari ini, servis dalam proses, servis selesai, pendapatan hari ini, pendapatan bulanan.
- Grafik **Chart.js**: jumlah servis per bulan (bar) dan pendapatan per bulan (line) 6 bulan terakhir.
- Daftar **sparepart hampir habis** (`stock <= min_stock`).
- Daftar **teknisi paling produktif** (jumlah servis selesai).
- 5 tiket servis terbaru.

### 2.4 Manajemen Customer (Admin)

- CRUD data customer (nama, WhatsApp, alamat).
- Pencarian + pagination.
- Opsi membuatkan akun login (email + password) untuk customer.
- Menampilkan jumlah servis per customer.

### 2.5 Manajemen Sparepart / Inventori (Admin)

- CRUD sparepart (nama, deskripsi, gambar, stok, stok minimum, harga modal, harga jual).
- Upload gambar dari file **atau** URL.
- Filter pencarian & filter *low stock*.
- Stok otomatis berkurang saat part dipasang ke tiket servis, dan bertambah saat dihapus.

### 2.6 Manajemen Servis / Tiket (Admin & Teknisi)

- CRUD tiket servis (Admin) dengan nomor tiket unik otomatis.
- Update status perbaikan beserta catatan.
- **Timeline visual** 7 status perbaikan.
- Menetapkan teknisi penanggung jawab.
- Menambah / menghapus sparepart ke tiket (mengurangi stok otomatis).
- Rekalkulasi **total biaya** otomatis = biaya jasa + total sparepart.
- Upload **foto dokumentasi** (sebelum/sesudah) dari file atau URL.
- **Riwayat log perubahan status** (siapa, kapan, catatan).
- Tombol **notifikasi WhatsApp** (link `wa.me` siap kirim).

### 2.7 Transaksi & Invoice (Admin)

- Membuat transaksi untuk tiket yang belum memiliki transaksi.
- Nomor invoice otomatis `INV-YYYYMMDD-XXX`.
- Metode pembayaran: **Tunai**, **Transfer**, **QRIS**.
- Status pembayaran: **Belum Bayar**, **DP**, **Lunas**.
- Halaman **invoice/faktur** yang dapat dicetak (print-friendly).
- CRUD transaksi + filter status pembayaran.

### 2.8 Manajemen User / Pengguna (Admin)

- CRUD user dengan role: `admin`, `teknisi`, `customer`.
- Proteksi: admin **tidak dapat menghapus akun sendiri**.
- Filter role + pencarian.

### 2.9 Laporan & Analitik (Admin)

- Filter laporan berdasarkan **rentang tanggal**.
- Ringkasan: total pendapatan (lunas), total servis masuk, servis selesai.
- Analitik: merek laptop terlaris, sparepart terlaris, top teknisi.
- Tabel rincian transaksi.
- **Cetak PDF** (print-friendly) & **Export CSV**.

### 2.10 Dashboard Teknisi

- Daftar tugas aktif miliknya.
- Statistik: jumlah tugas selesai, jumlah diagnosa tertunda.
- Workbench per tiket: diagnosa, estimasi biaya, pemasangan part, upload foto, update status.
- Proteksi: teknisi hanya dapat mengakses tiket yang ditugaskan kepadanya (HTTP 403 jika bukan miliknya).

### 2.11 Dashboard Customer

- Daftar servis miliknya (aktif & selesai).
- Detail progres, biaya, foto, dan riwayat status.
- Halaman pelacakan publik (`/lacak`) berlaku untuk siapa saja.

### 2.12 Notifikasi WhatsApp

- Saat status berubah ke `pemeriksaan`, `menunggu_persetujuan`, `perbaikan`, atau `selesai`, tersedia **link WhatsApp** (`https://wa.me/<nomor>?text=<pesan>`) untuk memberi tahu pelanggan.
- Link dibuka di tab baru; dapat dikirim ulang kapan saja dari halaman detail tiket.

---

## 3. Arsitektur & Stack Teknologi

### 3.1 Arsitektur

Aplikasi mengikuti arsitektur **MVC (Model–View–Controller)** standar Laravel dengan **Blade** (server-side rendering) dan sedikit interaktivitas *client-side* menggunakan **Alpine.js**.

```
┌────────────────────────────────────────────────────────────┐
│                      Browser / Client                      │
│     Blade Templates + Tailwind CSS + Alpine.js + Chart.js  │
└───────────────────────────┬────────────────────────────────┘
                            │ HTTP (GET/POST/PUT/DELETE)
┌───────────────────────────▼────────────────────────────────┐
│                         Laravel 12                         │
│   routes/web.php → Middleware (auth, role) → Controllers   │
│   Controllers → Requests (Validasi) → Eloquent Models      │
│   ServiceObserver → ServiceStatusLog (audit + notifikasi)  │
└───────────────────────────┬────────────────────────────────┘
                            │
┌───────────────────────────▼────────────────────────────────┐
│                     Database (MySQL / SQLite)              │
│  users, customers, spareparts, services, service_details,  │
│  transactions, service_photos, service_status_logs, dll.   │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Stack Teknologi

| Lapisan | Teknologi |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Template | Blade (Laravel) |
| Frontend | Tailwind CSS 3, Alpine.js 3, Vite 7 |
| Grafik | Chart.js (CDN) |
| Notifikasi UI | SweetAlert2 (CDN) |
| Ikon | Font Awesome 6.4 (CDN) |
| Database | MySQL atau SQLite |
| Session / Cache / Queue | Database driver |
| Testing | PHPUnit 11 |

### 3.3 Paket Composer (Dependencies)

| Paket | Versi | Kegunaan |
|---|---|---|
| `laravel/framework` | ^12.0 | Framework utama |
| `laravel/tinker` | ^2.10 | REPL untuk debugging |
| `barryvdh/laravel-dompdf` | ^3.1 | Terpasang; siap generate PDF (laporan PDF saat ini memakai tampilan print browser) |
| `maatwebsite/excel` | ^3.1 | Terpasang; siap ekspor Excel (saat ini ekspor menggunakan CSV stream) |
| `laravel/breeze` | ^2.4 | Autentikasi (dev) |
| `laravel/pint` | ^1.24 | Linter / formatter (dev) |
| `laravel/sail` | ^1.41 | Docker dev environment (dev) |
| `phpunit/phpunit` | ^11.5 | Framework testing (dev) |
| `fakerphp/faker` | ^1.23 | Generator data dummy (dev) |

### 3.4 Paket NPM (Frontend)

| Paket | Versi |
|---|---|
| `tailwindcss` | ^3.1 |
| `@tailwindcss/forms` | ^0.5 |
| `alpinejs` | ^3.4 |
| `laravel-vite-plugin` | ^2.0 |
| `vite` | ^7.0 |

---

## 4. Struktur Proyek

```
Website-Service-Laptop/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Dashboard, Customer, Sparepart, Service, Transaction, User, Report
│   │   │   ├── Auth/           # Autentikasi (login, register, reset password, dll.)
│   │   │   ├── Customer/       # Dashboard & pelacakan milik customer
│   │   │   ├── Teknisi/        # Dashboard & TaskController (workbench)
│   │   │   ├── Controller.php
│   │   │   ├── LandingController.php   # Halaman publik + booking online
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php       # Alias 'role'
│   │   └── Requests/
│   │       ├── Auth/LoginRequest.php    # Login dengan rate limiting
│   │       ├── ProfileUpdateRequest.php
│   │       └── Store*/Update*Request.php  # (belum aktif / authorize = false)
│   ├── Models/
│   │   ├── Customer.php
│   │   ├── Service.php
│   │   ├── ServiceDetail.php
│   │   ├── ServicePhoto.php
│   │   ├── ServiceStatusLog.php
│   │   ├── Sparepart.php
│   │   ├── Transaction.php
│   │   └── User.php
│   ├── Observers/
│   │   └── ServiceObserver.php           # Auto tiket, status log, notifikasi WA
│   ├── Providers/
│   │   └── AppServiceProvider.php        # Registrasi ServiceObserver
│   └── View/Components/
│       ├── AppLayout.php                 # Layout halaman ber-login
│       └── GuestLayout.php               # Layout halaman auth
├── bootstrap/app.php                     # Konfigurasi aplikasi & alias middleware
├── config/                               # Konfigurasi Laravel
├── database/
│   ├── migrations/                       # 10 file migrasi skema DB
│   ├── factories/                        # User, Customer, Sparepart
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   └── DummyDataSeeder.php           # Data demo lengkap
│   └── database.sqlite
├── public/index.php
├── resources/
│   ├── css/app.css                       # @tailwind directives
│   ├── js/app.js                         # Bootstrap + Alpine.js
│   └── views/
│       ├── index.blade.php               # Landing page + booking
│       ├── admin/                        # dashboard, customers, spareparts, services, transactions, users, reports
│       ├── customer/                     # dashboard, track, track_result
│       ├── teknisi/                      # dashboard, tasks/*
│       ├── auth/                         # login, register, reset-password, dll.
│       ├── components/                   # Komponen Blade (button, input, dll.)
│       ├── layouts/                      # app.blade.php, guest.blade.php, navigation
│       └── profile/                      # Halaman profil
├── routes/
│   ├── web.php                           # Routing utama aplikasi
│   └── auth.php                          # Routing autentikasi
├── tests/
│   ├── Feature/                          # ExampleTest, ProfileTest, Auth/*
│   └── Unit/ExampleTest.php
├── composer.json / composer.lock
├── package.json / package-lock.json
├── tailwind.config.js
├── vite.config.js
├── postcss.config.js
├── phpunit.xml
└── README.md
```

---

## 5. Model Data & Relasi Database

Aplikasi menggunakan **9 tabel bisnis utama** (ditambah tabel infrastruktur Laravel: `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `sessions`, `password_reset_tokens`).

### 5.1 Diagram Relasi

```
┌────────────┐          ┌──────────────────┐          ┌──────────────┐
│   users    │          │    customers     │          │  spareparts  │
├────────────┤          ├──────────────────┤          ├──────────────┤
│ id         │          │ id               │          │ id           │
│ name       │          │ user_id (FK) ────┼─────────►│ part_name    │
│ email      │          │ name             │          │ description  │
│ password   │          │ whatsapp         │          │ image        │
│ role       │          │ address          │          │ stock        │
│ email_...  │          └─────────┬────────┘          │ min_stock    │
└─────┬──────┘                    │                   │ cost_price   │
      │                           │                   │ selling_price│
      │ 1:N (assigned_technician) │                   └──────┬───────┘
      │                           │                          │
      │  ┌────────────────────────▼──────────────────┐       │
      │  │                 services                 │       │
      │  │  id                                      │       │
      └──│── assigned_technician_id (FK → users)    │       │
         │  customer_id (FK → customers)            │       │
         │  ticket_number (unique)                  │       │
         │  laptop_brand, laptop_type, serial_number│       │
         │  equipment, complaint, diagnosis         │       │
         │  service_fee, total_cost, estimated_cost │       │
         │  estimated_finish, status, date_received │       │
         │  date_completed                          │       │
         └──────┬──────────────┬──────────────┬─────┴───────┘
                │              │              │
         ┌──────▼──────┐ ┌─────▼──────┐ ┌─────▼────────────┐
         │service_     │ │service_    │ │service_photos    │
         │details      │ │status_logs │ ├──────────────────┤
         ├─────────────┤ ├────────────┤ │ id, service_id   │
         │ id          │ │ id         │ │ image, desc      │
         │ service_id  │ │ service_id │ │ photo_type       │
         │ sparepart_id│ │ old_status │ │ ('before'/'after')│
         │ quantity    │ │ new_status │ └──────────────────┘
         │ price_at_...│ │ notes      │
         │ subtotal    │ │ changed_by │
         └─────────────┘ │ (FK→users) │
                         └────────────┘
```

### 5.2 Skema Tabel (Migrasi)

#### `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | string | Nama lengkap |
| email | string UNIQUE | Email login |
| email_verified_at | timestamp nullable | Waktu verifikasi email |
| password | string | Hash (bcrypt) |
| role | enum('admin','teknisi','customer') default 'customer' | Peran pengguna |
| remember_token | string nullable | Autentikasi "ingat saya" |
| timestamps | | |

#### `customers`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users, nullable, ON DELETE SET NULL | Tautan ke akun login (jika ada) |
| name | string | Nama pelanggan |
| whatsapp | string nullable | Nomor WhatsApp |
| address | text nullable | Alamat |
| timestamps | | |

#### `spareparts`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| part_name | string | Nama sparepart |
| description | text nullable | Deskripsi |
| image | string nullable | URL/path gambar |
| stock | integer default 0 | Jumlah stok |
| min_stock | integer default 0 | Ambang stok minimum (alarm low stock) |
| cost_price | decimal(15,2) default 0 | Harga modal |
| selling_price | decimal(15,2) default 0 | Harga jual |
| timestamps | | |

#### `services`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| ticket_number | string UNIQUE | Nomor tiket `SRV-YYYYMMDD-XXX` |
| customer_id | bigint FK → customers, ON DELETE CASCADE | Pelanggan |
| assigned_technician_id | bigint FK → users, nullable, ON DELETE SET NULL | Teknisi penanggung jawab |
| laptop_brand | string | Merek laptop |
| laptop_type | string | Tipe/seri |
| serial_number | string nullable | Nomor seri |
| equipment | text nullable | Kelengkapan yang dititipkan |
| complaint | text | Keluhan pelanggan |
| diagnosis | text nullable | Diagnosa teknisi |
| service_fee | decimal(15,2) default 0 | Biaya jasa |
| total_cost | decimal(15,2) default 0 | Total tagihan (jasa + part) |
| estimated_cost | decimal(15,2) default 0 | Estimasi biaya |
| estimated_finish | date nullable | Estimasi selesai |
| status | enum('antrean','pemeriksaan','menunggu_persetujuan','perbaikan','selesai','diambil','batal') default 'antrean' | Status perbaikan |
| date_received | datetime | Tanggal unit diterima |
| date_completed | datetime nullable | Tanggal selesai/diambil |
| timestamps | | |

#### `service_details`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| service_id | bigint FK → services, ON DELETE CASCADE | Tiket terkait |
| sparepart_id | bigint FK → spareparts, nullable, ON DELETE SET NULL | Sparepart yang dipasang |
| quantity | integer default 1 | Jumlah |
| price_at_time | decimal(15,2) default 0 | Harga saat pemasangan (snapshot) |
| subtotal | decimal(15,2) default 0 | qty × price_at_time |
| timestamps | | |

#### `transactions`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| invoice_number | string UNIQUE | Nomor invoice `INV-YYYYMMDD-XXX` |
| service_id | bigint FK → services, ON DELETE CASCADE | Tiket terkait (unik, 1 tiket = 1 transaksi) |
| amount_paid | decimal(15,2) default 0 | Jumlah dibayar |
| payment_method | enum('Tunai','Transfer','QRIS') default 'Tunai' | Metode bayar |
| payment_status | enum('Belum Bayar','DP','Lunas') default 'Belum Bayar' | Status bayar |
| transaction_date | datetime nullable | Tanggal transaksi |
| timestamps | | |

#### `service_photos`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| service_id | bigint FK → services, ON DELETE CASCADE | Tiket terkait |
| image | string | URL/path foto |
| description | text nullable | Keterangan |
| photo_type | enum('before','after') default 'before' | Jenis foto |
| timestamps | | |

#### `service_status_logs`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| service_id | bigint FK → services, ON DELETE CASCADE | Tiket terkait |
| old_status | string nullable | Status sebelum perubahan |
| new_status | string | Status setelah perubahan |
| notes | text nullable | Catatan perubahan |
| changed_by | bigint FK → users, nullable, ON DELETE SET NULL | Pengguna yang mengubah |
| timestamps | | |

### 5.3 Relasi Eloquent (Ringkasan)

| Model | Relasi | Target |
|---|---|---|
| `User` | `assignedServices()` hasMany | `Service` (via `assigned_technician_id`) |
| `User` | `statusLogs()` hasMany | `ServiceStatusLog` (via `changed_by`) |
| `User` | `customerProfile()` hasOne | `Customer` (via `user_id`) |
| `Customer` | `user()` belongsTo | `User` |
| `Customer` | `services()` hasMany | `Service` |
| `Service` | `customer()` belongsTo | `Customer` |
| `Service` | `technician()` belongsTo | `User` |
| `Service` | `details()` hasMany | `ServiceDetail` |
| `Service` | `transaction()` hasOne | `Transaction` |
| `Service` | `statusLogs()` hasMany | `ServiceStatusLog` |
| `Service` | `photos()` hasMany | `ServicePhoto` |
| `ServiceDetail` | `service()` belongsTo | `Service` |
| `ServiceDetail` | `sparepart()` belongsTo | `Sparepart` |
| `Sparepart` | `serviceDetails()` hasMany | `ServiceDetail` |
| `Transaction` | `service()` belongsTo | `Service` |
| `ServiceStatusLog` | `service()` belongsTo | `Service` |
| `ServiceStatusLog` | `user()` belongsTo | `User` |
| `ServicePhoto` | `service()` belongsTo | `Service` |

---

## 6. Sistem Autentikasi & Peran (Role)

### 6.1 Peran Pengguna

Tabel `users.role` adalah `enum` dengan 3 nilai:

| Role | Deskripsi | Area utama |
|---|---|---|
| `admin` | Pemilik / operator | Semua modul admin + laporan |
| `teknisi` | Teknisi perbaikan | Tugas servis yang ditugaskan |
| `customer` | Pelanggan | Dashboard servis miliknya |

### 6.2 Middleware `role`

Alias `role` didaftarkan di `bootstrap/app.php`:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

Implementasi di `app/Http/Middleware/RoleMiddleware.php`:

```php
public function handle(Request $request, Closure $next, string $role): Response
{
    if (! $request->user() || ! $request->user()->role) {
        return redirect('/login');
    }
    $roles = explode('|', $role);
    if (! in_array($request->user()->role, $roles)) {
        abort(403, 'Unauthorized action.');
    }
    return $next($request);
}
```

- Mendukung multi-role sekaligus, contoh `role:admin|teknisi`.
- Pengguna tidak terautentikasi diarahkan ke `/login`.
- Role tidak sesuai → **HTTP 403 Forbidden**.

### 6.3 Router Dashboard Berdasarkan Role

`GET /dashboard` (perlu login) mengarahkan pengguna ke dashboard sesuai role:

```php
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin')    return redirect()->route('admin.dashboard');
    if ($role === 'teknisi')  return redirect()->route('teknisi.dashboard');
    if ($role === 'customer') return redirect()->route('customer.dashboard');
    return redirect('/');
})->middleware(['auth'])->name('dashboard');
```

### 6.4 Keamanan Autentikasi

- Password disimpan sebagai **hash bcrypt** (cast `'password' => 'hashed'` di model `User`).
- Login dibatasi **maksimal 5 percobaan** per email+IP dengan `RateLimiter` (lihat `LoginRequest`).
- CSRF token otomatis pada semua form (`@csrf`).
- Proteksi tambahan di kontroller teknisi: `abort(403)` jika tiket bukan miliknya.

---

## 7. Alur Bisnis

### 7.1 Siklus Hidup Status Perbaikan

```
        ┌──────────┐      ┌────────────┐      ┌────────────────────┐
Masuk → │ antrean  │ ───► │ pemeriksaan│ ───► │menunggu_persetujuan│
        └──────────┘      └────────────┘      └────────────────────┘
                                                       │
        ┌──────────┐      ┌────────────┐               │
Diambil │  diambil │ ◄─── │  selesai   │ ◄─── ┌────────▼────────┐
        └──────────┘      └────────────┘      │    perbaikan    │
                                              └─────────────────┘
   (status "batal" dapat muncul dari status mana pun)
```

| Status | Label | Deskripsi |
|---|---|---|
| `antrean` | Antrean | Unit masuk & menunggu dijadwalkan |
| `pemeriksaan` | Pemeriksaan | Teknisi diagnosa kerusakan |
| `menunggu_persetujuan` | Menunggu Persetujuan | Menunggu persetujuan estimasi biaya dari pelanggan |
| `perbaikan` | Perbaikan | Proses pengerjaan perbaikan |
| `selesai` | Selesai | Servis selesai, siap diambil (mengisi `date_completed`) |
| `diambil` | Sudah Diambil | Unit telah diambil pelanggan (mengisi `date_completed`) |
| `batal` | Dibatalkan | Servis dibatalkan |

### 7.2 Alur Booking Online (Halaman Publik)

```
1. Pelanggan mengunjungi landing page (/)
2. [Opsional] Memilih sparepart dari katalog → keranjang (localStorage)
3. Mengisi form booking (nama, WA, alamat, merek/tipe laptop, keluhan)
4. Submit → POST /booking
   ├─ Membuat/memperbarui data Customer (match by whatsapp)
   ├─ Membuat Service dengan nomor tiket otomatis SRV-YYYYMMDD-XXX
   ├─ Membuat ServiceStatusLog awal (status: antrean)
   ├─ [Jika ada item keranjang] Membuat ServiceDetail per part
   │   (stok dicek, subtotal = selling_price × qty; estimated_cost = total part)
   └─ Commit transaksi database
5. Redirect ke halaman dengan pesan sukses berisi nomor tiket
```

### 7.3 Alur Pelacakan (Tracking)

```
1. Pelanggan membuka halaman /lacak
2. Menginput nomor tiket ATAU nomor WhatsApp
3. Sistem mencari Service (by ticket_number atau customer.whatsapp)
4. Jika ditemukan → tampilkan halaman track_result:
   - Status & badge warna          - Rincian sparepart & biaya
   - Timeline progres visual      - Galeri foto dokumentasi
   - Keluhan & diagnosa            - Status faktur (jika ada transaksi)
5. Jika tidak ditemukan → kembali ke form dengan pesan error
```

### 7.4 Alur Pengerjaan Teknisi

```
1. Admin membuat tiket servis & menugaskan teknisi (assigned_technician_id)
2. Teknisi login → melihat daftar tugas di Dashboard / Daftar Servis
3. Teknisi membuka workbench tiket → mengisi diagnosis, biaya jasa, estimasi
4. Teknisi memasang sparepart dari stok (stok berkurang otomatis)
5. Teknisi mengunggah foto dokumentasi before/after
6. Teknisi memperbarui status → tercatat di status_logs
7. Status tertentu → opsi kirim WhatsApp ke pelanggan
```

### 7.5 Alur Transaksi & Invoice

```
1. Admin membuka Transaksi → "Tambah Transaksi"
2. Hanya tiket yang BELUM memiliki transaksi yang dapat dipilih
3. Input: jumlah dibayar, metode bayar, status bayar
4. Nomor invoice otomatis INV-YYYYMMDD-XXX
5. Transaksi tersimpan → diarahkan ke halaman invoice (printable)
6. Invoice menampilkan: identitas pelanggan, detail unit, jasa servis,
   rincian sparepart, total biaya, jumlah dibayar, kolom tanda tangan
```

### 7.6 Alur Laporan

```
1. Admin membuka Rekap Laporan
2. Pilih rentang tanggal (default: 1 bulan berjalan)
3. Lihat ringkasan & analitik
4. Export:
   - PDF   → halaman print-friendly (browser print / simpan sebagai PDF)
   - Excel → unduh file .csv berisi daftar transaksi
```

---

## 8. Routing (Daftar Endpoint)

### 8.1 Rute Publik — `routes/web.php`

| Metode | URI | Nama Route | Controller@Method | Middleware |
|---|---|---|---|---|
| GET | `/` | `home` | `LandingController@index` | — |
| POST | `/booking` | `booking.store` | `LandingController@booking` | — |
| GET | `/lacak` | `customer.trackForm` | `Customer\ServiceController@trackForm` | — |
| POST | `/lacak` | `customer.track` | `Customer\ServiceController@track` | — |
| GET | `/dashboard` | `dashboard` | (closure) redirect by role | `auth` |
| GET | `/up` | `health` | (framework) | — |

### 8.2 Rute Admin — prefix `/admin`, nama `admin.`, middleware `auth` + `role:admin`

| Metode | URI | Nama Route | Controller@Method |
|---|---|---|---|
| GET | `/admin/dashboard` | `admin.dashboard` | `Admin\DashboardController@index` |
| GET/POST | `/admin/customers` | `admin.customers.index` / `.store` | `Admin\CustomerController@index`/`store` |
| GET | `/admin/customers/create` | `admin.customers.create` | `CustomerController@create` |
| GET/PUT | `/admin/customers/{customer}` | `admin.customers.update` | `CustomerController@update` |
| GET | `/admin/customers/{customer}/edit` | `admin.customers.edit` | `CustomerController@edit` |
| DELETE | `/admin/customers/{customer}` | `admin.customers.destroy` | `CustomerController@destroy` |
| GET/POST | `/admin/spareparts` | `admin.spareparts.index` / `.store` | `SparepartController@index`/`store` |
| GET | `/admin/spareparts/create` | `admin.spareparts.create` | `SparepartController@create` |
| GET/PUT | `/admin/spareparts/{sparepart}` | `admin.spareparts.update` | `SparepartController@update` |
| GET | `/admin/spareparts/{sparepart}/edit` | `admin.spareparts.edit` | `SparepartController@edit` |
| DELETE | `/admin/spareparts/{sparepart}` | `admin.spareparts.destroy` | `SparepartController@destroy` |
| GET/POST | `/admin/services` | `admin.services.index` / `.store` | `ServiceController@index`/`store` |
| GET | `/admin/services/create` | `admin.services.create` | `ServiceController@create` |
| GET | `/admin/services/{service}` | `admin.services.show` | `ServiceController@show` |
| GET/PUT | `/admin/services/{service}` | `admin.services.update` | `ServiceController@update` |
| GET | `/admin/services/{service}/edit` | `admin.services.edit` | `ServiceController@edit` |
| DELETE | `/admin/services/{service}` | `admin.services.destroy` | `ServiceController@destroy` |
| POST | `/admin/services/{service}/status` | `admin.services.updateStatus` | `ServiceController@updateStatus` |
| POST | `/admin/services/{service}/spareparts` | `admin.services.addSparepart` | `ServiceController@addSparepart` |
| DELETE | `/admin/services/{service}/spareparts/{detail}` | `admin.services.removeSparepart` | `ServiceController@removeSparepart` |
| POST | `/admin/services/{service}/photos` | `admin.services.uploadPhoto` | `ServiceController@uploadPhoto` |
| DELETE | `/admin/services/{service}/photos/{photo}` | `admin.services.deletePhoto` | `ServiceController@deletePhoto` |
| GET/POST | `/admin/transactions` | `admin.transactions.index` / `.store` | `TransactionController@index`/`store` |
| GET | `/admin/transactions/create` | `admin.transactions.create` | `TransactionController@create` |
| GET/PUT | `/admin/transactions/{transaction}` | `admin.transactions.update` | `TransactionController@update` |
| GET | `/admin/transactions/{transaction}/edit` | `admin.transactions.edit` | `TransactionController@edit` |
| GET | `/admin/transactions/{transaction}/invoice` | `admin.transactions.invoice` | `TransactionController@invoice` |
| DELETE | `/admin/transactions/{transaction}` | `admin.transactions.destroy` | `TransactionController@destroy` |
| GET/POST | `/admin/users` | `admin.users.index` / `.store` | `UserController@index`/`store` |
| GET | `/admin/users/create` | `admin.users.create` | `UserController@create` |
| GET/PUT | `/admin/users/{user}` | `admin.users.update` | `UserController@update` |
| GET | `/admin/users/{user}/edit` | `admin.users.edit` | `UserController@edit` |
| DELETE | `/admin/users/{user}` | `admin.users.destroy` | `UserController@destroy` |
| GET | `/admin/reports` | `admin.reports.index` | `ReportController@index` |
| GET | `/admin/reports/pdf` | `admin.reports.pdf` | `ReportController@pdf` |
| GET | `/admin/reports/excel` | `admin.reports.excel` | `ReportController@excel` |

> **Catatan**: `Route::resource` otomatis juga mendaftarkan route `show` untuk customers/spareparts/services/transactions/users meskipun belum digunakan di UI.

### 8.3 Rute Teknisi — prefix `/teknisi`, nama `teknisi.`, middleware `auth` + `role:teknisi`

| Metode | URI | Nama Route | Controller@Method |
|---|---|---|---|
| GET | `/teknisi/dashboard` | `teknisi.dashboard` | `Teknisi\DashboardController@index` |
| GET | `/teknisi/tasks` | `teknisi.tasks.index` | `TaskController@index` |
| GET | `/teknisi/tasks/{task}` | `teknisi.tasks.show` | `TaskController@show` |
| GET | `/teknisi/tasks/{task}/edit` | `teknisi.tasks.edit` | `TaskController@edit` |
| PUT | `/teknisi/tasks/{task}` | `teknisi.tasks.update` | `TaskController@update` |
| POST | `/teknisi/tasks/{service}/status` | `teknisi.tasks.updateStatus` | `TaskController@updateStatus` |
| POST | `/teknisi/tasks/{service}/spareparts` | `teknisi.tasks.addSparepart` | `TaskController@addSparepart` |
| DELETE | `/teknisi/tasks/{service}/spareparts/{detail}` | `teknisi.tasks.removeSparepart` | `TaskController@removeSparepart` |
| POST | `/teknisi/tasks/{service}/photos` | `teknisi.tasks.uploadPhoto` | `TaskController@uploadPhoto` |
| DELETE | `/teknisi/tasks/{service}/photos/{photo}` | `teknisi.tasks.deletePhoto` | `TaskController@deletePhoto` |

> **Catatan**: Parameter model resource bernama `task`, namun route aksi tambahan memakai `service`. Keduanya menunjuk model `Service`.

### 8.4 Rute Customer — prefix `/customer`, nama `customer.`, middleware `auth` + `role:customer`

| Metode | URI | Nama Route | Controller@Method |
|---|---|---|---|
| GET | `/customer/dashboard` | `customer.dashboard` | `Customer\DashboardController@index` |
| GET | `/customer/services/{service}` | `customer.services.show` | `Customer\ServiceController@show` |

### 8.5 Rute Profil — middleware `auth`

| Metode | URI | Nama Route | Controller@Method |
|---|---|---|---|
| GET | `/profile` | `profile.edit` | `ProfileController@edit` |
| PATCH | `/profile` | `profile.update` | `ProfileController@update` |
| DELETE | `/profile` | `profile.destroy` | `ProfileController@destroy` |

### 8.6 Rute Autentikasi — `routes/auth.php`

| Metode | URI | Nama Route |
|---|---|---|
| GET/POST | `/register` | `register` |
| GET/POST | `/login` | `login` |
| GET/POST | `/forgot-password` | `password.request` / `password.email` |
| GET/POST | `/reset-password/{token}` | `password.reset` / `password.store` |
| GET | `/verify-email` | `verification.notice` |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` |
| POST | `/email/verification-notification` | `verification.send` |
| GET/POST | `/confirm-password` | `password.confirm` |
| PUT | `/password` | `password.update` |
| POST | `/logout` | `logout` |

---

## 9. Logika Bisnis Utama

### 9.1 `LandingController` (Publik)

- **`index()`** — Menampilkan landing page beserta daftar sparepart berstok > 0.
- **`booking(Request $request)`** — Memproses booking online:
  - Validasi data pelanggan, laptop, dan `cart_items` (JSON sparepart).
  - `Customer::firstOrCreate` berdasarkan nomor WhatsApp (menautkan ke akun login jika customer sedang login).
  - Membuat `Service` berstatus `antrean` dengan `date_received = now()`.
  - Membuat `ServiceStatusLog` awal.
  - Membaca `cart_items` (JSON), menambah `ServiceDetail` per part (validasi stok), mengisi `estimated_cost`.
  - Seluruh proses dibungkus **transaksi database** (`beginTransaction`/`commit`/`rollBack`).

### 9.2 `Admin\DashboardController`

Menghitung metrik dashboard:
- `totalServisHariIni` — servis `date_received` hari ini.
- `servisDalamProses` — status `antrean|pemeriksaan|menunggu_persetujuan|perbaikan`.
- `servisSelesai` — status `selesai`.
- `pendapatanHariIni` / `pendapatanBulanan` — jumlah `amount_paid` transaksi berstatus `Lunas`.
- `sparepartHampirHabis` — `stock <= min_stock`.
- `teknisiProduktif` — 5 teknisi dengan `assignedServices` berstatus `selesai` terbanyak.
- Data grafik 6 bulan: `months`, `serviceChartData`, `revenueChartData`.
- `recentServices` — 5 tiket terbaru.

### 9.3 `Admin\CustomerController`

- Index: pencarian (nama/WA/alamat) + pagination 10 + `withCount('services')`.
- Store: validasi; jika email+password diisi, otomatis membuat `User` role `customer` dan menautkan `user_id`.
- Update: validasi unique WhatsApp (kecuali dirinya sendiri).
- Destroy: hapus customer (servis terkait ikut terhapus via cascade).

### 9.4 `Admin\SparepartController`

- Index: pencarian + filter `low_stock` (`stock <= min_stock`) + pagination.
- Store/Update: validasi; upload gambar via `Storage::store('spareparts','public')` atau `image_url`; simpan URL dengan `Storage::url()`.
- Destroy: hapus sparepart.

### 9.5 `Admin\ServiceController`

- **`index()`** — filter status & pencarian (ticket/laptop/serial/nama customer/WA) + pagination.
- **`create()`/`store()`** — form tiket; `total_cost` diinisialisasi = `service_fee`; `date_received = now()`; menulis status log "Tiket servis dibuat oleh Admin."
- **`show()`** — memuat relasi lengkap: customer, technician, details.sparepart, statusLogs.user, photos, transaction.
- **`update()`** — merekalkulasi `total_cost = service_fee + sum(subtotal detail)`.
- **`updateStatus()`**:
  - Menolak perubahan status yang sama.
  - Dalam `DB::transaction`: update status, set `date_completed` saat `selesai`/`diambil`, tulis `ServiceStatusLog`.
  - Status trigger notifikasi → redirect dengan `wa_url` (memunculkan SweetAlert konfirmasi kirim WhatsApp).
- **`addSparepart()`** — cek stok, `decrement` stok, tambah/update `ServiceDetail` (gabung jika part sama), rekalkulasi `total_cost`.
- **`removeSparepart()`** — `increment` stok kembali, hapus detail, rekalkulasi `total_cost`.
- **`uploadPhoto()`** — validasi file (jpeg/png/jpg/webp, maks 3MB) atau URL; simpan ke `storage/app/public/service_photos`.
- **`deletePhoto()`** — hapus foto.
- **`destroy()`** — hapus tiket (cascade ke detail, log, foto, transaksi).

### 9.6 `Admin\TransactionController`

- **`create()`** — hanya menampilkan tiket yang **belum memiliki transaksi** (`doesntHave('transaction')`).
- **`store()`** — validasi `service_id` unik per transaksi; `invoice_number` otomatis; `transaction_date = now()`.
- **`update()`** — `transaction_date` diperbarui hanya ketika status menjadi `Lunas`.
- **`invoice()`** — memuat relasi untuk halaman invoice printable.
- **`index()`** — filter `payment_status` + pencarian (invoice/tiket/nama customer).
- **`destroy()`** — hapus transaksi.

### 9.7 `Admin\UserController`

- CRUD pengguna dengan role.
- `destroy()` — menolak menghapus akun sendiri (pesan error via redirect).

### 9.8 `Admin\ReportController`

- **`index()`** — default periode: awal–akhir bulan berjalan. Menghitung:
  - `totalIncome` (transaksi Lunas), `totalServices` (created_at), `completedServices` (date_completed).
  - `topBrands` (5 merek terservis), `topSpareparts` (5 part terlaris), `topTechnicians` (teknisi dengan servis selesai terbanyak).
- **`pdf()`** — menampilkan `admin.reports.pdf` (print-friendly, memicu `window.print()`).
- **`excel()`** — menstreaming file **CSV** dengan header: No Invoice, No Tiket, Pelanggan, Metode Bayar, Status Bayar, Tanggal, Jumlah Pembayaran.

### 9.9 `Teknisi\DashboardController`

- `activeTasks` — tugas milik teknisi berstatus proses.
- `completedTasksCount` — jumlah tugas selesai.
- `pendingDiagnosisCount` — tugas berstatus `pemeriksaan`.

### 9.10 `Teknisi\TaskController`

Mirip dengan `Admin\ServiceController`, namun **selalu memeriksa kepemilikan**:

```php
if ($task->assigned_technician_id !== auth()->id()) {
    abort(403, '...');
}
```

Fitur: `update` (diagnosa + estimasi + biaya jasa → rekalkulasi total), `updateStatus`, `addSparepart`, `removeSparepart`, `uploadPhoto`, `deletePhoto`.

### 9.11 `Customer\DashboardController`

- Mencari profil `Customer` berdasarkan `user_id` atau `whatsapp = email` pengguna.
- Mengambil seluruh servis milik customer beserta relasi.
- Membagi menjadi `activeServices` (proses) dan `completedServices` (selesai/diambil).

### 9.12 `Customer\ServiceController`

- `trackForm()` — menampilkan form pelacakan publik.
- `track()` — mencari servis via `ticket_number` atau `customer.whatsapp`; jika tidak ditemukan → error redirect.
- `show()` — detail servis (dipakai juga dashboard customer).

### 9.13 `ProfileController`

- Edit profil (nama & email).
- Update: jika email berubah, `email_verified_at` di-null-kan.
- Delete: memerlukan password saat ini; logout + invalidasi session setelah hapus.

---

## 10. Fitur Khusus & Otomasi

### 10.1 Generator Nomor Tiket Otomatis

Terdapat **dua mekanisme** penghasil nomor tiket (pengaman ganda):

1. **Model `Service::generateTicketNumber()`**
   - Format: `SRV-YYYYMMDD-XXX` (contoh `SRV-20260804-001`).
   - Mengambil nomor urut terakhir dengan prefix tanggal yang sama, lalu menambah 1.

2. **`ServiceObserver@creating`**
   - Jika `ticket_number` belum diisi, observer mengisi otomatis dengan pola yang sama.
   - Kolom `ticket_number` memiliki constraint `UNIQUE`.

### 10.2 Generator Nomor Invoice Otomatis

`Transaction::generateInvoiceNumber()` — format `INV-YYYYMMDD-XXX`, menaikkan urutan harian.

### 10.3 ServiceObserver (`app/Observers/ServiceObserver.php`)

Terdaftar di `AppServiceProvider@boot`:

```php
\App\Models\Service::observe(\App\Observers\ServiceObserver::class);
```

| Event | Aksi |
|---|---|
| `creating` | Menjamin `ticket_number` terisi otomatis |
| `created` | Membuat `ServiceStatusLog` awal ("Servis didaftarkan") |
| `updated` | Jika `status` berubah: menulis `ServiceStatusLog` (old → new). Untuk status `pemeriksaan`, `menunggu_persetujuan`, `perbaikan`, `selesai`: memanggil `sendWhatsAppNotification()` |

`sendWhatsAppNotification()` saat ini **placeholder** (mencatat ke `Log::info`) dengan TODO integrasi API WhatsApp sungguhan (Fonnte, Wazzup, Twilio, dll.).

### 10.4 Notifikasi WhatsApp (`getWhatsappUrlAttribute`)

Model `Service` menyediakan accessor `whatsapp_url`:

```
https://wa.me/<62xxxxxxxxxx>?text=<urlencoded pesan>
```

- Nomor dinormalisasi: `0xxx` → `62xxx`.
- Pesan berisi: nama pelanggan, merek/tipe laptop, nomor tiket, status baru, dan estimasi biaya.
- Dipakai di halaman detail servis (tombol "Kirim Notifikasi WA") dan flash `wa_url` setelah update status.

### 10.5 Keranjang Sparepart Interaktif (Landing Page)

- Diimplementasikan dengan **Alpine.js** dan **`localStorage`** (key `laptopcare_cart`).
- Fungsi: `addToCart`, `updateQty`, `removeFromCart`, `saveCart`, getter `totalCartItems` & `cartTotal`, `formatRupiah`.
- Tampil sebagai **drawer slide-over**.
- Saat submit booking, isi keranjang diserialisasi ke input tersembunyi `cart_items` (JSON) lalu diproses di `LandingController@booking`.

### 10.6 Rekalkulasi Biaya Otomatis

```php
total_cost = service_fee + Σ (subtotal service_details)
```

Diperbarui pada: update tiket, tambah part, hapus part, dan saat teknisi menyimpan diagnosa.

### 10.7 Manajemen Stok Terintegrasi

- Menambah part ke tiket → `stock -= quantity`.
- Menghapus part dari tiket → `stock += quantity`.
- Validasi stok sebelum pemasangan.
- Alarm low stock di dashboard + filter di halaman sparepart.

### 10.8 Tampilan Print-friendly

- **Invoice**: `@media print` menyembunyikan kontrol; siap dicetak.
- **Laporan PDF**: halaman `admin.reports.pdf` memanggil `window.print()` saat load.

---

## 11. Antarmuka Pengguna (Views)

### 11.1 Struktur View

| Lokasi | Halaman |
|---|---|
| `views/index.blade.php` | Landing page (hero, katalog, booking, keranjang, footer) |
| `views/layouts/app.blade.php` | Layout utama ber-login (sidebar + navbar + flash + popup WA) |
| `views/layouts/guest.blade.php` | Layout halaman auth (login/register) |
| `views/layouts/navigation.blade.php` | Navigasi (Breeze) |
| `views/admin/dashboard.blade.php` | Dashboard admin + grafik Chart.js |
| `views/admin/customers/*` | Index / Create / Edit customer |
| `views/admin/spareparts/*` | Index / Create / Edit sparepart |
| `views/admin/services/*` | Index / Create / Edit / Show (timeline, part, foto, log) |
| `views/admin/transactions/*` | Index / Create / Invoice (printable) |
| `views/admin/users/*` | Index / Create / Edit user |
| `views/admin/reports/*` | Index / PDF |
| `views/teknisi/dashboard.blade.php` | Dashboard teknisi |
| `views/teknisi/tasks/*` | Index / Show (workbench) |
| `views/customer/dashboard.blade.php` | Dashboard customer |
| `views/customer/track.blade.php` | Form pelacakan publik |
| `views/customer/track_result.blade.php` | Hasil pelacakan (timeline visual) |
| `views/auth/*` | Login, register, forgot/reset password, verifikasi |
| `views/profile/*` | Edit profil, ubah password, hapus akun |

### 11.2 Desain & Komponen Frontend

- **Tailwind CSS** untuk seluruh styling (warna biru/slate, `rounded-3xl`, shadow).
- **Font**: Plus Jakarta Sans (Google Fonts).
- **Ikon**: Font Awesome 6.4.
- **Interaktivitas**: Alpine.js (`x-data`, `x-show`, `x-for`, `x-transition`).
- **Grafik**: Chart.js (dashboard admin).
- **Alert**: SweetAlert2 (toast keranjang, konfirmasi WhatsApp).
- **Responsif**: sidebar collapsible pada mobile (`sidebarOpen`), grid responsif.

### 11.3 Komponen Blade Breeze

`application-logo`, `auth-session-status`, `danger-button`, `dropdown`, `dropdown-link`, `input-error`, `input-label`, `modal`, `nav-link`, `primary-button`, `responsive-nav-link`, `secondary-button`, `text-input`.

---

## 12. Instalasi & Konfigurasi

### 12.1 Prasyarat

| Software | Versi Minimal |
|---|---|
| PHP | 8.2+ (ekstensi `pdo_mysql`/`pdo_sqlite`, `mbstring`, `fileinfo`, `openssl`) |
| Composer | 2.x |
| Node.js & npm | 18+ |
| Database | MySQL 8 / MariaDB **atau** SQLite |
| Git | — |

### 12.2 Instalasi (Pengembangan)

**Langkah 1 — Clone repositori**

```bash
git clone <url-repositori> Website-Service-Laptop
cd Website-Service-Laptop
```

**Langkah 2 — Install dependensi PHP**

```bash
composer install
```

**Langkah 3 — Siapkan file environment**

```bash
cp .env.example .env
```

> Catatan: `.env.example` memakai SQLite. Untuk MySQL, ubah blok berikut di `.env`:
> ```
> DB_CONNECTION=mysql
> DB_HOST=127.0.0.1
> DB_PORT=3306
> DB_DATABASE=laptopcare
> DB_USERNAME=root
> DB_PASSWORD=secret
> ```
> Buat database-nya lebih dulu: `CREATE DATABASE laptopcare;`

**Langkah 4 — Generate app key**

```bash
php artisan key:generate
```

**Langkah 5 — Jalankan migrasi**

```bash
php artisan migrate        # struktur saja
php artisan migrate --seed # struktur + data demo
```

**Langkah 6 — Install dependensi frontend & build**

```bash
npm install
npm run build   # untuk production build
# atau
npm run dev     # untuk pengembangan (hot reload)
```

**Langkah 7 — Jalankan server**

```bash
php artisan serve
```

Akses aplikasi di **http://localhost:8000**.

> **Alternatif one-command (setup awal)**
> ```bash
> composer run setup
> ```
> Menjalankan `composer install`, membuat `.env`, `key:generate`, `migrate`, `npm install`, `npm run build`.

### 12.3 Konfigurasi Penting `.env`

| Variabel | Contoh | Keterangan |
|---|---|---|
| `APP_NAME` | `LaptopCare` | Nama aplikasi |
| `APP_URL` | `http://localhost` | URL aplikasi (mempengaruhi link absolut) |
| `APP_ENV` | `local` / `production` | Lingkungan |
| `APP_DEBUG` | `true` / `false` | Tampilkan error detail (nonaktifkan di produksi) |
| `DB_CONNECTION` | `mysql` / `sqlite` | Driver database |
| `SESSION_DRIVER` | `database` | Driver session |
| `CACHE_STORE` | `database` | Driver cache |
| `QUEUE_CONNECTION` | `database` | Driver queue |
| `FILESYSTEM_DISK` | `local` | Disk penyimpanan file |

### 12.4 Penyimpanan File (Foto)

- Foto disimpan ke disk `public` (`storage/app/public/...`) lalu diakses via `Storage::url()`.
- Wajib menjalankan symlink storage:

```bash
php artisan storage:link
```

Direktori tujuan: `storage/app/public/spareparts` dan `storage/app/public/service_photos`.

### 12.5 Skrip Composer

| Skrip | Perintah |
|---|---|
| `setup` | Instalasi otomatis penuh |
| `dev` | Jalankan server + queue + pail + vite sekaligus (concurrently) |
| `test` | `config:clear` lalu `php artisan test` |

---

## 13. Akun Demo / Seeder

Jalankan seeder untuk mendapat data contoh lengkap (3 tiket servis, 6 sparepart, transaksi, log status, dan foto):

```bash
php artisan db:seed
```

### 13.1 Akun Login Bawaan (password semua: `password`)

| Role | Nama | Email |
|---|---|---|
| Admin | Admin Utama | `admin@laptopcare.com` |
| Teknisi | Budi Teknisi | `budi@laptopcare.com` |
| Teknisi | Agus Teknisi | `agus@laptopcare.com` |
| Customer | Bambang Pamungkas | `bambang@gmail.com` |
| Customer | Siti Nurhaliza | `siti@gmail.com` |
| Customer | Dian Sastrowardoyo | `dian@gmail.com` |

### 13.2 Data Demo yang Dibuat

- **6 sparepart** (SSD NVMe, RAM DDR4, LCD 14", thermal paste, baterai ROG, keyboard ThinkPad).
- **3 tiket servis** dengan variasi status:
  1. `SRV-YYYYMMDD-001` — **selesai & lunas** (Asus ROG; lengkap dengan detail part, transaksi QRIS, 4 log status, 2 foto before/after).
  2. `SRV-YYYYMMDD-002` — **perbaikan** (Lenovo ThinkPad; transaksi DP via transfer, 3 log status).
  3. `SRV-YYYYMMDD-003` — **menunggu_persetujuan** (Acer Swift; 2 log status).
- Nomor urut tanggal mengikuti tanggal saat seed dijalankan.

> **Tips**: Gunakan nomor tiket dari data demo (mis. `SRV-20260729-001`) atau nomor WhatsApp (`081234567890`) untuk menguji halaman `/lacak`.

---

## 14. Testing

### 14.1 Menjalankan Test

```bash
php artisan test
# atau
composer test
```

### 14.2 Test yang Ada

| File | Isi |
|---|---|
| `tests/Feature/ExampleTest.php` | Halaman `/` merespons 200 |
| `tests/Feature/ProfileTest.php` | Halaman profil tampil, update profil, verifikasi email, hapus akun (dengan & tanpa password benar) |
| `tests/Unit/ExampleTest.php` | Smoke test dasar |

Selain itu terdapat skeleton test auth Breeze di `tests/Feature/Auth/`.

### 14.3 Lint / Format Kode

```bash
./vendor/bin/pint
```

---

## 15. Deployment

### 15.1 Persiapan Production

1. **Environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Set `APP_ENV=production`, `APP_DEBUG=false`, konfigurasi database, dan `APP_URL`.

2. **Optimasi & cache**

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Migrasi & storage**

   ```bash
   php artisan migrate --force
   php artisan storage:link
   ```

4. **Build frontend**

   ```bash
   npm install
   npm run build
   ```

5. **Izin direktori (Linux)**

   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

### 15.2 Opsi Hosting

- **Shared Hosting (cPanel)**: upload file via FTP/File Manager, arahkan domain ke `public/`, jalankan langkah persiapan di atas.
- **VPS**: pasang PHP 8.2+, Nginx/Apache, MySQL; gunakan PHP-FPM.
- **Laravel Forge / Ploi**: integrasi deploy otomatis dari Git.
- **Docker**: gunakan Laravel Sail (`./vendor/bin/sail up`) untuk konsistensi environment.
- **Queue worker**: jalankan `php artisan queue:listen` agar job/queue diproses (untuk fitur masa depan).

---

## 16. Pemeliharaan & Troubleshooting

### 16.1 Masalah Umum & Solusinya

| Gejala | Penyebab | Solusi |
|---|---|---|
| Foto tidak tampil | Symlink storage belum dibuat | `php artisan storage:link` |
| Error koneksi database | Konfigurasi `.env` salah / DB belum dibuat | Periksa `DB_*`, buat database, jalankan `php artisan migrate` |
| 403 Forbidden di halaman role | Role pengguna tidak sesuai route | Login dengan akun role yang tepat |
| CSS/JS tidak tampil | Aset belum dibuild | `npm install && npm run build` |
| 419 Page Expired | CSRF token expired / session | Logout → login kembali; perpanjang `SESSION_LIFETIME` |
| Tidak bisa upload > 2–3 MB | `upload_max_filesize` / `post_max_size` PHP | Perbesar di `php.ini` |
| Nomor tiket dobel/duplikat | Konkurensi pembuatan tiket bersamaan | Constraint `UNIQUE` sudah ada; retry logika observer bila diperlukan |
| Pesan "Stok tidak mencukupi" | Stok part kurang dari qty yang diminta | Tambah stok via halaman Sparepart |
| Error tampil di layar (production) | `APP_DEBUG=true` | Set `APP_DEBUG=false` |
| Session/cache tidak konsisten | Cache lama | `php artisan config:clear && php artisan cache:clear` |

### 16.2 Perintah Berguna

```bash
php artisan migrate:fresh --seed   # reset DB + seed ulang (hati-hati: menghapus data)
php artisan optimize:clear        # bersihkan semua cache
php artisan route:list            # lihat seluruh route
php artisan tinker                # REPL untuk debugging
php artisan make:model ...        # scaffolding
```

> ⚠️ `migrate:fresh` menghapus seluruh data. Gunakan hanya di environment pengembangan.

### 16.3 Pencadangan (Backup)

- **Database**: `mysqldump -u <user> -p laptopcare > backup.sql` (MySQL) atau salin file `database/database.sqlite` (SQLite).
- **File upload**: salin `storage/app/public`.
- **File penting**: `.env` (jangan ikut di-commit ke Git — sudah masuk `.gitignore`).

---

## 17. Roadmap / Pengembangan Lanjut

Fitur yang disarankan untuk dikembangkan selanjutnya (berdasarkan catatan TODO di kode):

1. **Integrasi API WhatsApp sungguhan** — ganti placeholder `Log::info` di `ServiceObserver@sendWhatsAppNotification` dengan layanan seperti Fonnte, Wazzup, atau Twilio WhatsApp.
2. **Gunakan DomPDF** — `barryvdh/laravel-dompdf` sudah terpasang; pindahkan laporan PDF dari *print browser* ke generate PDF server-side agar unduhan otomatis.
3. **Gunakan Maatwebsite/Excel** — `maatwebsite/excel` sudah terpasang; tingkatkan ekspor dari CSV polos menjadi file `.xlsx` dengan styling.
4. **Dashboard Admin** — tambahkan opsi periode kustom pada grafik (bukan hanya 6 bulan terakhir).
5. **Multi-workshop / cabang** — dukungan banyak lokasi servis.
6. **Notifikasi email / real-time** — WebSocket (Laravel Echo + Pusher/Reverb) untuk update status live.
7. **Rating & review pelanggan** setelah servis selesai.
8. **Aktifkan FormRequest** `Store*/Update*Request` yang masih `authorize() = false` agar validasi dipindah dari kontroller ke request class.
9. **Pagination & filter lanjutan** pada semua halaman index.
10. **Unit test tambahan** untuk kontroller admin, teknisi, dan alur booking.

---

## 18. Kontribusi & Lisensi

### 18.1 Kontribusi

1. *Fork* repositori dan buat branch fitur.
2. Terapkan standar kode dengan `./vendor/bin/pint`.
3. Tambahkan/update test untuk perubahan yang dilakukan.
4. Ajukan *pull request* dengan deskripsi perubahan yang jelas.

### 18.2 Standar Kode

- PHP: PSR-12 (dijalankan via Laravel Pint).
- Nama route: `area.modul.aksi` (contoh `admin.services.updateStatus`).
- Bahasa UI: Bahasa Indonesia.
- Semua formulir memakai `@csrf`; semua interaksi data melewati validasi.

### 18.3 Lisensi

Aplikasi dilisensikan di bawah **MIT License** (lihat file `LICENSE`). Framework Laravel sendiri dilisensikan MIT.

---

*Dokumen ini disusun berdasarkan kondisi kode saat ini dan akan diperbarui mengikuti perkembangan aplikasi.*




