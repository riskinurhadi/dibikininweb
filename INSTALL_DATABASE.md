# Cara Install Database Artikel

## Langkah-langkah Instalasi

### 1. Buat Database (jika belum ada)

Buka phpMyAdmin atau MySQL client, lalu jalankan:

```sql
CREATE DATABASE IF NOT EXISTS dibikininweb_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Atau jika menggunakan command line:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS dibikininweb_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Import File SQL

**Opsi A: Via phpMyAdmin**
1. Buka phpMyAdmin
2. Pilih database `dibikininweb_db`
3. Klik tab "Import"
4. Pilih file `database_artikel.sql`
5. Klik "Go" untuk menjalankan

**Opsi B: Via Command Line**
```bash
mysql -u root -p dibikininweb_db < database_artikel.sql
```

**Opsi C: Copy-Paste Manual**
1. Buka file `database_artikel.sql` dengan text editor
2. Copy semua isinya
3. Buka phpMyAdmin → Pilih database `dibikininweb_db` → Tab "SQL"
4. Paste dan klik "Go"

### 3. Konfigurasi Koneksi

Pastikan file `koneksi.php` sudah dikonfigurasi dengan benar:

```php
$host = 'localhost';
$dbname = 'dibikininweb_db';
$username = 'root';      // Sesuaikan dengan username MySQL Anda
$password = '';          // Sesuaikan dengan password MySQL Anda
```

### 4. Verifikasi Instalasi

Setelah import berhasil, Anda seharusnya melihat:
- Tabel `kategori_artikel` dengan 4 kategori default
- Tabel `artikel` dengan 3 artikel contoh

Cek di phpMyAdmin atau jalankan:

```sql
USE dibikininweb_db;
SHOW TABLES;
SELECT COUNT(*) FROM kategori_artikel;
SELECT COUNT(*) FROM artikel;
```

## Struktur Database

### Tabel `kategori_artikel`
- `id` - ID kategori (Primary Key, Auto Increment)
- `nama` - Nama kategori
- `slug` - URL-friendly slug
- `deskripsi` - Deskripsi kategori
- `created_at` - Tanggal dibuat
- `updated_at` - Tanggal diupdate

### Tabel `artikel`
- `id` - ID artikel (Primary Key, Auto Increment)
- `judul` - Judul artikel
- `slug` - URL-friendly slug
- `konten` - Isi artikel lengkap (HTML)
- `ringkasan` - Ringkasan artikel untuk preview
- `gambar` - URL gambar utama artikel
- `kategori_id` - ID kategori (Foreign Key)
- `penulis` - Nama penulis
- `status` - Status: 'draft' atau 'published'
- `dilihat` - Jumlah view
- `waktu_baca` - Estimasi waktu baca (menit)
- `published_at` - Tanggal publish
- `created_at` - Tanggal dibuat
- `updated_at` - Tanggal diupdate

## Data Default

Setelah install, akan tersedia:

**Kategori:**
1. Berita
2. Tutorial
3. Tips & Trik
4. Web Development

**Artikel Contoh:**
1. Panduan Lengkap Membuat Website dengan HTML, CSS, dan JavaScript
2. Tips Meningkatkan SEO Website Anda
3. Teknologi Terbaru dalam Web Development 2025

## Troubleshooting

### Error: "Access denied for user"
- Pastikan username dan password di `koneksi.php` sudah benar
- Pastikan user MySQL memiliki akses ke database

### Error: "Unknown database 'dibikininweb_db'"
- Pastikan database sudah dibuat terlebih dahulu (lihat langkah 1)

### Error: "Table already exists"
- Jika tabel sudah ada, hapus dulu atau gunakan `DROP TABLE IF EXISTS` sebelum create

### Error: "Foreign key constraint fails"
- Pastikan data kategori sudah di-insert sebelum insert artikel
- File SQL sudah mengurutkan insert dengan benar

## Catatan Penting

- Database ini menggunakan **struktur sederhana** yang mudah dipahami
- Nama tabel menggunakan bahasa Indonesia: `artikel`, `kategori_artikel`
- Field juga menggunakan bahasa Indonesia untuk kemudahan
- Untuk production, sebaiknya ubah password default dan tambahkan user khusus untuk aplikasi

