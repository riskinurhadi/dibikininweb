# Instalasi Database Artikel - Versi Sederhana

## Langkah-langkah Instalasi

### 1. Hapus Tabel Lama (Jika Ada)
Jika sebelumnya sudah ada tabel `artikel` atau `kategori_artikel`, hapus terlebih dahulu melalui phpMyAdmin atau MySQL command line.

### 2. Import Database
Jalankan file SQL berikut di phpMyAdmin atau MySQL command line:

```
database/artikel_simple.sql
```

**Cara Import via phpMyAdmin:**
1. Buka phpMyAdmin
2. Pilih database `dibikininweb`
3. Klik tab "Import"
4. Pilih file `database/artikel_simple.sql`
5. Klik "Go" untuk import

**Cara Import via Command Line:**
```bash
mysql -u dibikininweb -p dibikininweb < database/artikel_simple.sql
```

### 3. Struktur Database

#### Tabel: `kategori_artikel`
- `id` (int, primary key, auto increment)
- `nama` (varchar 100)
- `slug` (varchar 100, unique)
- `created_at` (timestamp)

#### Tabel: `artikel`
- `id` (int, primary key, auto increment)
- `judul` (varchar 255)
- `slug` (varchar 255, unique)
- `konten` (text)
- `ringkasan` (text, nullable)
- `gambar` (varchar 500, nullable)
- `kategori_id` (int, foreign key ke kategori_artikel)
- `penulis` (varchar 100, default: 'Admin')
- `status` (enum: 'draft', 'published', default: 'draft')
- `dilihat` (int, default: 0)
- `published_at` (datetime, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

### 4. File-file yang Dibuat

#### Frontend (Public):
- `artikel/index.php` - Halaman utama artikel
- `artikel/detail.php` - Halaman detail artikel
- `artikel/assets/css/style.css` - CSS terpusat

#### Backend (Admin):
- `artikel/admin/semua-berita.php` - List semua artikel
- `artikel/admin/tulis-berita.php` - Tulis artikel baru
- `artikel/admin/edit-berita.php` - Edit artikel

### 5. Fitur CRUD

#### Create (Tambah):
- Halaman: `artikel/admin/tulis-berita.php`
- Form dengan Summernote editor
- Pilihan: Publish atau Simpan Draft

#### Read (Baca):
- Frontend: `artikel/index.php` dan `artikel/detail.php`
- Admin: `artikel/admin/semua-berita.php`

#### Update (Edit):
- Halaman: `artikel/admin/edit-berita.php`
- Edit semua field artikel
- Update slug otomatis jika judul berubah

#### Delete (Hapus):
- Dari halaman `artikel/admin/semua-berita.php`
- Konfirmasi sebelum hapus

### 6. Catatan Penting

- **Status Artikel:**
  - `draft`: Artikel belum dipublish (tidak muncul di frontend)
  - `published`: Artikel sudah dipublish (muncul di frontend)

- **Published At:**
  - Jika status = `published`, `published_at` akan di-set ke waktu sekarang
  - Artikel hanya muncul di frontend jika `status = 'published'` dan `published_at <= NOW()`

- **Slug:**
  - Otomatis di-generate dari judul
  - Unik (tidak boleh duplikat)
  - Jika duplikat, akan ditambahkan angka di belakang

### 7. Testing

Setelah instalasi, coba:
1. Login ke admin
2. Tulis artikel baru dan publish
3. Cek apakah artikel muncul di halaman utama (`artikel/index.php`)
4. Klik artikel untuk melihat detail
5. Edit artikel dari admin
6. Hapus artikel (jika perlu)

### 8. Troubleshooting

**Artikel tidak muncul di frontend:**
- Pastikan status = `'published'`
- Pastikan `published_at` sudah di-set
- Cek query di `artikel/index.php`

**Error koneksi database:**
- Cek file `koneksi.php`
- Pastikan database sudah dibuat
- Pastikan username dan password benar

**Error import SQL:**
- Pastikan database sudah dibuat
- Pastikan user memiliki permission untuk create table
- Cek apakah tabel lama sudah dihapus

