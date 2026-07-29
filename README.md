# Astra To-Do List

Aplikasi manajemen tugas (to-do list) berbasis Laravel, dengan tampilan terinspirasi dari desain studio "Astra" (nuansa gradasi ungu-pink, tipografi serif elegan).

## Fitur

- **Dashboard**: total tugas, selesai, belum selesai, progress bar persentase.
- **Autentikasi**: login, register, logout, profil, ubah password.
- **Manajemen Tugas**: tambah, edit, hapus, detail, tandai selesai/belum selesai.
- **Kategori**: tambah, edit, hapus kategori (Kuliah, Pribadi, Organisasi, Kerja, dll).
- **Tag**: tambah, edit, hapus tag; satu tugas bisa punya banyak tag (many-to-many).
- **Sub-Tugas (checklist)**: tambah, tandai selesai, hapus item checklist per tugas.
- **Lampiran**: unggah dan hapus file lampiran per tugas.
- **Komentar**: tambah dan hapus komentar per tugas.
- **Log Aktivitas**: riwayat otomatis setiap perubahan pada sebuah tugas.
- **Prioritas**: Tinggi 🔴 / Sedang 🟡 / Rendah 🟢.
- **Deadline**: tanggal dibuat, deadline, tanggal selesai, dan status "Terlambat" otomatis.
- **Pencarian & Filter**: cari judul, filter kategori/prioritas/status, urutkan berdasarkan deadline.
- **Riwayat**: daftar semua tugas yang sudah selesai.
- **Dark mode** menggunakan Tailwind CSS + Alpine.js.
- **phpMyAdmin** untuk inspeksi basis data secara langsung.

## Struktur Database (9 tabel aplikasi)

| Tabel | Relasi |
|---|---|
| `users` | 1—N ke `tasks`, `task_comments`, `attachments`, `activity_logs` |
| `categories` | 1—N ke `tasks` |
| `tags` | N—N ke `tasks` (via `task_tag`) |
| `task_tag` | pivot `tasks` ↔ `tags` |
| `tasks` | N—1 ke `users`, `categories`; 1—N ke `subtasks`, `task_comments`, `attachments`, `activity_logs` |
| `subtasks` | N—1 ke `tasks` |
| `task_comments` | N—1 ke `tasks`, `users` |
| `attachments` | N—1 ke `tasks`, `users` |
| `activity_logs` | N—1 ke `tasks`, `users` |

## Struktur Proyek

```
.
├── compose.yaml               # Orkestrasi Docker (app, webserver, db)
├── Dockerfile                 # Multi-stage build (composer -> node -> php-fpm)
├── .dockerignore
├── docker/
│   ├── nginx/default.conf     # Konfigurasi Nginx
│   └── php/entrypoint.sh      # Auto migrate/seed/key:generate saat container start
└── src/                       # Source code Laravel
```

## Menjalankan dengan Docker

1. Pastikan Docker & Docker Compose sudah terpasang.
2. Build dan jalankan container:

   ```bash
   docker compose up -d --build
   ```

3. Tunggu beberapa saat (container `app` otomatis menjalankan `migrate` & `db:seed`
   yang akan mengisi kategori default: Kuliah, Pribadi, Organisasi, Kerja).
4. Buka aplikasi di browser: **http://localhost:8090**
5. Buka phpMyAdmin untuk cek tabel database: **http://localhost:8091** (server: `db`, user/password sesuai `.env`)
6. Daftar akun baru melalui halaman **Register**, lalu mulai kelola tugas Anda.

### Konfigurasi Port / Database (opsional)

Buat file `.env` di root proyek (sejajar dengan `compose.yaml`) untuk override default:

```env
APP_PORT=8090
DB_DATABASE=todo_db
DB_USERNAME=todo_user
DB_PASSWORD=todo_pass
DB_ROOT_PASSWORD=root_secret
```

### Perintah berguna

```bash
# Melihat log aplikasi
docker compose logs -f app

# Masuk ke container app
docker compose exec app sh

# Menjalankan artisan command manual
docker compose exec app php artisan migrate:fresh --seed

# Menghentikan seluruh container
docker compose down

# Menghentikan & menghapus volume database (reset total)
docker compose down -v
```

## Menjalankan Secara Lokal (tanpa Docker)

```bash
cd src
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
# sesuaikan DB_HOST=127.0.0.1 di .env
php artisan migrate --seed
php artisan serve
```
