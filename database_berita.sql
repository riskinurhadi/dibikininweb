-- ============================================
-- Tabel Database untuk Blog Berita
-- Dibuat untuk optimasi SEO
-- ============================================

-- Tabel Kategori Berita
CREATE TABLE IF NOT EXISTS `kategori_berita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Berita/Artikel
CREATE TABLE IF NOT EXISTS `berita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `konten` longtext NOT NULL,
  `excerpt` text DEFAULT NULL COMMENT 'Ringkasan artikel untuk preview',
  `thumbnail` varchar(255) DEFAULT NULL COMMENT 'Gambar utama artikel',
  `gambar_og` varchar(255) DEFAULT NULL COMMENT 'Gambar untuk Open Graph (social media)',
  `kategori_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL COMMENT 'ID penulis artikel',
  `author_name` varchar(100) DEFAULT NULL COMMENT 'Nama penulis',
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `featured` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Artikel unggulan',
  `view_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Jumlah view artikel',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Title khusus untuk SEO',
  `meta_description` varchar(255) DEFAULT NULL COMMENT 'Meta description untuk SEO',
  `meta_keywords` varchar(255) DEFAULT NULL COMMENT 'Meta keywords untuk SEO',
  `og_title` varchar(255) DEFAULT NULL COMMENT 'Open Graph title',
  `og_description` text DEFAULT NULL COMMENT 'Open Graph description',
  `canonical_url` varchar(255) DEFAULT NULL COMMENT 'Canonical URL untuk SEO',
  `tags` varchar(255) DEFAULT NULL COMMENT 'Tags artikel (comma separated)',
  `published_at` datetime DEFAULT NULL COMMENT 'Tanggal publish artikel',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_kategori` (`kategori_id`),
  KEY `idx_status` (`status`),
  KEY `idx_featured` (`featured`),
  KEY `idx_published_at` (`published_at`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_judul` (`judul`),
  FULLTEXT KEY `idx_fulltext_search` (`judul`,`konten`,`excerpt`),
  CONSTRAINT `fk_berita_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_berita` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Tags (untuk relasi many-to-many dengan berita)
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tag` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `nama_tag` (`nama_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Relasi Berita dengan Tags (Many-to-Many)
CREATE TABLE IF NOT EXISTS `berita_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `berita_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_berita_tag` (`berita_id`,`tag_id`),
  KEY `idx_tag` (`tag_id`),
  CONSTRAINT `fk_berita_tags_berita` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_berita_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk tracking view per artikel (opsional, untuk analitik)
CREATE TABLE IF NOT EXISTS `berita_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `berita_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_berita` (`berita_id`),
  KEY `idx_viewed_at` (`viewed_at`),
  CONSTRAINT `fk_views_berita` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert data kategori contoh
INSERT INTO `kategori_berita` (`nama_kategori`, `slug`, `deskripsi`, `meta_keywords`, `meta_description`) VALUES
('Web Development', 'web-development', 'Artikel tentang pengembangan website dan teknologi web', 'web development, pembuatan website, jasa website', 'Kumpulan artikel tentang web development dan teknologi website terkini'),
('Tips & Trik', 'tips-trik', 'Tips dan trik seputar website dan digital marketing', 'tips website, trik seo, digital marketing', 'Tips dan trik praktis untuk mengoptimalkan website dan digital marketing'),
('Berita Teknologi', 'berita-teknologi', 'Berita terkini seputar teknologi dan inovasi digital', 'berita teknologi, teknologi terbaru, inovasi digital', 'Berita dan update terkini seputar dunia teknologi dan inovasi digital'),
('SEO & Marketing', 'seo-marketing', 'Artikel tentang SEO dan strategi digital marketing', 'seo, digital marketing, optimasi website', 'Panduan lengkap tentang SEO dan strategi digital marketing untuk meningkatkan visibilitas online');

