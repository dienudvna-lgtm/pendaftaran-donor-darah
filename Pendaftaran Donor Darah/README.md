# BloodConnect - Sistem Pendaftaran Donor Darah PMI

Aplikasi web untuk manajemen pendaftaran donor darah — memungkinkan
calon pendonor mendaftar secara online, dan panitia PMI mengelola
data pendaftar (approve/reject), memantau jadwal event donor darah,
serta melihat laporan melalui dashboard khusus panitia.

## Tech Stack

- PHP (native, tanpa framework)
- MySQL / MariaDB
- HTML, CSS, JavaScript
- PHPMailer (pengiriman email notifikasi)
- Apache (Debian 13 sebagai server produksi)
- GitHub Actions (CI/CD — cek otomatis syntax PHP setiap push)

## Fitur Utama

- Registrasi & login pengguna (calon pendonor)
- Login panitia terpisah dengan dashboard khusus
- Approve/Reject pendaftaran donor darah beserta notifikasi email otomatis
- Manajemen jadwal event donor darah
- Export data peserta ke Excel
- Health check endpoint untuk monitoring status server & database
- Automated backup database
- Environment configuration terpisah dari kode (`.env`)
- Logging aktivitas login pengguna

## Screenshot

![Halaman Login](./screenshots/login.png)
![Dashboard Panitia](./screenshots/dashboard-panitia.png)

## Cara Menjalankan

1. Clone repository ini:

git clone https://github.com/dienudvna-lgtm/pendaftaran-donor-darah.git

2. Pastikan sudah terinstall **XAMPP** (Apache + PHP + MySQL) untuk testing lokal
3. Import database: buka phpMyAdmin, buat database baru bernama `pmi_connect`, lalu import file `pmi_connect.sql` yang ada di root folder project
4. Copy file `.env.example` menjadi `.env`, sesuaikan konfigurasi database jika perlu
5. Buat file `config.php` di root folder, isi kredensial SMTP Gmail untuk fitur notifikasi email
6. Jalankan server:

php -S localhost:8000

7. Buka browser ke `http://localhost:8000/login.html`

## Demo Live

[Coba aplikasi di sini](https://observing-crested-husband.ngrok-free.dev/login.html)

> Catatan: aplikasi dideploy di server Debian 13, diakses melalui ngrok

## Portofolio Lengkap

*Link Edusoft Portfolio akan ditambahkan setelah project ini disubmit