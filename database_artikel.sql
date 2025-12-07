-- ============================================
-- DATABASE ARTIKEL/BERITA - VERSI SEDERHANA
-- Dibuat ulang dari 0 untuk kemudahan
-- ============================================

-- Pastikan database sudah dibuat terlebih dahulu:
-- CREATE DATABASE IF NOT EXISTS dibikininweb_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE dibikininweb_db;

-- ============================================
-- TABEL KATEGORI ARTIKEL
-- ============================================
CREATE TABLE IF NOT EXISTS `kategori_artikel` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `deskripsi` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL ARTIKEL
-- ============================================
CREATE TABLE IF NOT EXISTS `artikel` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `judul` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(280) NOT NULL UNIQUE,
  `konten` LONGTEXT NOT NULL,
  `ringkasan` TEXT DEFAULT NULL COMMENT 'Ringkasan artikel untuk preview',
  `gambar` VARCHAR(500) DEFAULT NULL COMMENT 'URL gambar utama artikel',
  `kategori_id` INT DEFAULT NULL,
  `penulis` VARCHAR(100) DEFAULT 'Admin' COMMENT 'Nama penulis',
  `status` ENUM('draft', 'published') DEFAULT 'draft' COMMENT 'Status artikel',
  `dilihat` INT UNSIGNED DEFAULT 0 COMMENT 'Jumlah view',
  `waktu_baca` INT UNSIGNED DEFAULT 0 COMMENT 'Estimasi waktu baca dalam menit',
  `published_at` TIMESTAMP NULL COMMENT 'Tanggal publish',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`kategori_id`) REFERENCES `kategori_artikel`(`id`) ON DELETE SET NULL,
  
  INDEX `idx_slug` (`slug`),
  INDEX `idx_status` (`status`),
  INDEX `idx_kategori` (`kategori_id`),
  INDEX `idx_published` (`published_at`),
  FULLTEXT `idx_search` (`judul`, `konten`, `ringkasan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DATA DEFAULT
-- ============================================

-- Insert Kategori Default
INSERT INTO `kategori_artikel` (`nama`, `slug`, `deskripsi`) VALUES
('Berita', 'berita', 'Berita terbaru seputar web development dan teknologi'),
('Tutorial', 'tutorial', 'Tutorial dan panduan lengkap web development'),
('Tips & Trik', 'tips-trik', 'Tips dan trik untuk optimasi website dan bisnis online'),
('Web Development', 'web-development', 'Artikel tentang pengembangan website');

-- Insert Contoh Artikel (Untuk Testing)
INSERT INTO `artikel` (`judul`, `slug`, `konten`, `ringkasan`, `gambar`, `kategori_id`, `penulis`, `status`, `waktu_baca`, `published_at`) VALUES
(
    'Panduan Lengkap Membuat Website dengan HTML, CSS, dan JavaScript',
    'panduan-lengkap-membuat-website',
    '<h2>Pendahuluan</h2><p>Dalam era digital seperti sekarang, memiliki website adalah hal yang sangat penting untuk bisnis Anda. Website tidak hanya berfungsi sebagai identitas digital, tetapi juga sebagai sarana untuk menjangkau lebih banyak pelanggan.</p><h2>Langkah-Langkah Membuat Website</h2><h3>1. Persiapan</h3><p>Sebelum membuat website, Anda perlu menyiapkan beberapa hal:</p><ul><li>Domain dan hosting</li><li>Editor kode</li><li>Gambar dan konten</li></ul><h3>2. Struktur HTML</h3><p>HTML adalah struktur dasar dari sebuah website. Gunakan tag-tag HTML yang sesuai untuk membuat struktur yang baik.</p><h3>3. Styling dengan CSS</h3><p>CSS digunakan untuk mempercantik tampilan website Anda. Dengan CSS, Anda dapat mengatur warna, font, layout, dan berbagai aspek visual lainnya.</p><h3>4. Interaktivitas dengan JavaScript</h3><p>JavaScript membuat website menjadi lebih interaktif dan dinamis. Dengan JavaScript, Anda dapat menambahkan berbagai fitur menarik.</p><h2>Kesimpulan</h2><p>Membuat website memerlukan pemahaman tentang HTML, CSS, dan JavaScript. Dengan latihan yang cukup, Anda dapat membuat website yang profesional.</p>',
    'Pelajari cara membuat website profesional dari awal dengan HTML, CSS, dan JavaScript. Panduan lengkap untuk pemula.',
    NULL,
    2,
    'Admin DibikininWeb',
    'published',
    5,
    NOW()
),
(
    'Tips Meningkatkan SEO Website Anda',
    'tips-meningkatkan-seo-website',
    '<h2>Apa itu SEO?</h2><p>SEO (Search Engine Optimization) adalah teknik untuk meningkatkan visibilitas website di mesin pencari seperti Google.</p><h2>Tips SEO yang Efektif</h2><h3>1. Optimasi Kata Kunci</h3><p>Gunakan kata kunci yang relevan di judul, meta description, dan konten artikel Anda.</p><h3>2. Konten Berkualitas</h3><p>Buat konten yang informatif, original, dan bermanfaat untuk pengunjung. Google lebih menyukai konten yang berkualitas tinggi.</p><h3>3. Optimasi Gambar</h3><p>Gunakan alt text pada gambar dan kompres ukuran file agar loading website lebih cepat.</p><h3>4. Backlink Berkualitas</h3><p>Dapatkan backlink dari website-website terpercaya untuk meningkatkan otoritas website Anda.</p><h2>Kesimpulan</h2><p>Dengan menerapkan tips SEO di atas, website Anda akan lebih mudah ditemukan di mesin pencari dan mendapatkan lebih banyak traffic organik.</p>',
    'Pelajari tips-tips praktis untuk meningkatkan SEO website Anda dan mendapatkan ranking lebih baik di Google.',
    NULL,
    3,
    'Admin DibikininWeb',
    'published',
    3,
    NOW()
),
(
    'Teknologi Terbaru dalam Web Development 2025',
    'teknologi-terbaru-web-development-2025',
    '<h2>Perkembangan Teknologi Web</h2><p>Dunia web development terus berkembang dengan cepat. Setiap tahun, teknologi-teknologi baru muncul untuk memudahkan proses pengembangan website.</p><h2>Teknologi Populer 2025</h2><h3>1. Framework JavaScript Modern</h3><p>Framework seperti React, Vue.js, dan Angular terus berkembang dengan fitur-fitur baru yang memudahkan pengembangan aplikasi web yang kompleks.</p><h3>2. Serverless Architecture</h3><p>Arsitektur serverless menjadi pilihan banyak developer karena kemudahan deployment dan skalabilitas yang tinggi.</p><h3>3. Progressive Web Apps (PWA)</h3><p>PWA memungkinkan website berfungsi seperti aplikasi native di mobile, memberikan pengalaman yang lebih baik untuk pengguna.</p><h3>4. JAMstack</h3><p>JAMstack (JavaScript, APIs, Markup) menjadi trend baru untuk membuat website yang cepat dan aman.</p><h2>Kesimpulan</h2><p>Sebagai web developer, penting untuk selalu update dengan teknologi terbaru agar dapat memberikan solusi terbaik untuk klien.</p>',
    'Update terbaru tentang teknologi web development yang populer di tahun 2025 dan tren yang akan datang.',
    NULL,
    1,
    'Admin DibikininWeb',
    'published',
    4,
    NOW()
);

