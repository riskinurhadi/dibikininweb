-- ============================================
-- DATABASE ARTIKEL - VERSI SEDERHANA
-- ============================================
-- Hapus tabel lama jika ada
DROP TABLE IF EXISTS `artikel`;
DROP TABLE IF EXISTS `kategori_artikel`;

-- ============================================
-- TABEL: kategori_artikel
-- ============================================
CREATE TABLE `kategori_artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert kategori default
INSERT INTO `kategori_artikel` (`nama`, `slug`) VALUES
('Teknologi', 'teknologi'),
('Berita', 'berita'),
('Tips & Trik', 'tips-trik');

-- ============================================
-- TABEL: artikel
-- ============================================
CREATE TABLE `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `ringkasan` text DEFAULT NULL,
  `gambar` varchar(500) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `penulis` varchar(100) DEFAULT 'Admin',
  `status` enum('draft','published') DEFAULT 'draft',
  `dilihat` int(11) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `kategori_id` (`kategori_id`),
  KEY `status` (`status`),
  KEY `published_at` (`published_at`),
  CONSTRAINT `artikel_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_artikel` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert artikel contoh
INSERT INTO `artikel` (`judul`, `slug`, `konten`, `ringkasan`, `kategori_id`, `penulis`, `status`, `published_at`) VALUES
('Selamat Datang di Blog Kami', 'selamat-datang-di-blog-kami', '<p>Ini adalah artikel contoh untuk blog Anda. Anda dapat mengedit atau menghapus artikel ini melalui halaman admin.</p>', 'Artikel contoh untuk blog Anda.', 1, 'Admin', 'published', NOW()),
('Tips Membuat Website Profesional', 'tips-membuat-website-profesional', '<p>Berikut adalah beberapa tips untuk membuat website yang profesional dan menarik.</p>', 'Panduan lengkap membuat website profesional.', 3, 'Admin', 'published', NOW());

