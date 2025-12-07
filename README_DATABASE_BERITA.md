# Dokumentasi Database Blog Berita

## Deskripsi
File SQL ini berisi struktur database untuk sistem blog berita yang dioptimalkan untuk SEO.

## Cara Menggunakan

### 1. Import ke Database
Jalankan file `database_berita.sql` melalui phpMyAdmin atau command line MySQL:

```bash
mysql -u root -p dibikininweb_db < database_berita.sql
```

Atau melalui phpMyAdmin:
- Buka phpMyAdmin
- Pilih database `dibikininweb_db`
- Klik tab "Import"
- Pilih file `database_berita.sql`
- Klik "Go"

### 2. Struktur Tabel

#### Tabel `kategori_berita`
Menyimpan kategori artikel/berita.

**Kolom Utama:**
- `id` - Primary key
- `nama_kategori` - Nama kategori
- `slug` - URL-friendly slug (unik)
- `deskripsi` - Deskripsi kategori
- `meta_keywords` - Keywords untuk SEO
- `meta_description` - Meta description untuk SEO

#### Tabel `berita`
Tabel utama untuk menyimpan artikel/berita.

**Kolom Utama:**
- `id` - Primary key
- `judul` - Judul artikel
- `slug` - URL-friendly slug (unik) - **PENTING untuk SEO**
- `konten` - Isi artikel lengkap
- `excerpt` - Ringkasan artikel untuk preview
- `thumbnail` - Gambar utama artikel
- `gambar_og` - Gambar untuk social media sharing
- `kategori_id` - Foreign key ke tabel kategori
- `author_name` - Nama penulis
- `status` - Status artikel (draft/published/archived)
- `featured` - Artikel unggulan (0/1)
- `view_count` - Jumlah view artikel

**Kolom SEO:**
- `meta_title` - Title khusus untuk SEO (jika berbeda dengan judul)
- `meta_description` - Meta description untuk SEO
- `meta_keywords` - Meta keywords untuk SEO
- `og_title` - Open Graph title untuk social media
- `og_description` - Open Graph description
- `canonical_url` - Canonical URL untuk menghindari duplicate content
- `tags` - Tags artikel (comma separated)

**Index yang Dibuat:**
- Index pada `slug` (unik) - untuk pencarian cepat
- Index pada `status` - untuk filter artikel published
- Index pada `featured` - untuk artikel unggulan
- Index pada `published_at` - untuk sorting berdasarkan tanggal
- Fulltext index pada `judul`, `konten`, `excerpt` - untuk pencarian teks lengkap

#### Tabel `tags`
Menyimpan tag artikel.

**Kolom:**
- `id` - Primary key
- `nama_tag` - Nama tag (unik)
- `slug` - URL-friendly slug (unik)

#### Tabel `berita_tags`
Tabel relasi many-to-many antara berita dan tags.

#### Tabel `berita_views`
Tracking view artikel (opsional, untuk analitik).

## Fitur SEO yang Dioptimalkan

1. **Slug URL-Friendly**: Setiap artikel memiliki slug unik untuk URL yang SEO-friendly
2. **Meta Tags Lengkap**: Meta title, description, dan keywords untuk setiap artikel
3. **Open Graph Tags**: Untuk optimasi sharing di social media
4. **Canonical URL**: Mencegah duplicate content
5. **Fulltext Search**: Index fulltext untuk pencarian artikel yang cepat
6. **Kategori dengan SEO**: Setiap kategori juga memiliki meta tags sendiri
7. **Tags System**: Sistem tag untuk grouping artikel terkait
8. **View Tracking**: Tracking jumlah view untuk analitik

## Contoh Query

### Menampilkan Artikel Published dengan SEO
```sql
SELECT 
    b.id,
    b.judul,
    b.slug,
    b.excerpt,
    b.thumbnail,
    b.meta_title,
    b.meta_description,
    b.meta_keywords,
    b.view_count,
    b.published_at,
    k.nama_kategori,
    k.slug as kategori_slug
FROM berita b
LEFT JOIN kategori_berita k ON b.kategori_id = k.id
WHERE b.status = 'published'
ORDER BY b.published_at DESC;
```

### Mencari Artikel dengan Fulltext Search
```sql
SELECT * FROM berita
WHERE status = 'published'
AND MATCH(judul, konten, excerpt) AGAINST('web development' IN NATURAL LANGUAGE MODE)
ORDER BY published_at DESC;
```

### Menampilkan Artikel dengan Tags
```sql
SELECT 
    b.*,
    GROUP_CONCAT(t.nama_tag) as tags
FROM berita b
LEFT JOIN berita_tags bt ON b.id = bt.berita_id
LEFT JOIN tags t ON bt.tag_id = t.id
WHERE b.status = 'published'
GROUP BY b.id;
```

## Catatan Penting

1. **Slug Harus Unik**: Pastikan slug setiap artikel unik untuk menghindari konflik URL
2. **Meta Description**: Disarankan 150-160 karakter untuk hasil optimal di search engine
3. **Thumbnail**: Gunakan gambar dengan ukuran optimal (disarankan 1200x630px untuk OG image)
4. **Status Artikel**: Gunakan `published` untuk artikel yang sudah siap ditampilkan
5. **Published Date**: Set `published_at` saat artikel dipublish untuk sorting yang benar

## Next Steps

Setelah tabel dibuat, Anda perlu membuat:
1. Halaman admin untuk mengelola artikel
2. Halaman frontend untuk menampilkan artikel
3. Halaman detail artikel dengan URL slug
4. Halaman kategori dan tag
5. Sitemap XML untuk SEO

