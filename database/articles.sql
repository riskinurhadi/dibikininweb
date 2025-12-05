 

-- Tabel Kategori Artikel
CREATE TABLE IF NOT EXISTS article_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT,
    meta_title VARCHAR(70),           -- SEO: max 70 karakter untuk title tag
    meta_description VARCHAR(160),     -- SEO: max 160 karakter untuk meta description
    parent_id INT DEFAULT NULL,        -- Untuk kategori bertingkat
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (parent_id) REFERENCES article_categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Penulis/Author
CREATE TABLE IF NOT EXISTS article_authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    email VARCHAR(255),
    bio TEXT,                          -- SEO: untuk author schema
    avatar VARCHAR(255),               -- URL foto penulis
    website VARCHAR(255),              -- Link website penulis
    social_facebook VARCHAR(255),
    social_twitter VARCHAR(255),
    social_linkedin VARCHAR(255),
    social_instagram VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Artikel Utama
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Konten Utama
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) NOT NULL UNIQUE, -- SEO: URL friendly, unique untuk canonical
    excerpt TEXT,                       -- Ringkasan untuk preview/list
    content LONGTEXT NOT NULL,          -- Isi artikel lengkap
    
    -- SEO Meta Tags
    meta_title VARCHAR(70),             -- SEO: Title tag (max 70 karakter)
    meta_description VARCHAR(160),      -- SEO: Meta description (max 160 karakter)
    meta_keywords VARCHAR(255),         -- SEO: Keywords (comma separated)
    canonical_url VARCHAR(500),         -- SEO: Canonical URL jika berbeda
    
    -- Featured Image dengan SEO
    featured_image VARCHAR(500),        -- URL gambar utama
    featured_image_alt VARCHAR(255),    -- SEO: Alt text untuk gambar
    featured_image_caption TEXT,        -- Caption gambar
    
    -- Open Graph & Social Media
    og_title VARCHAR(95),               -- Open Graph title (max 95 karakter)
    og_description VARCHAR(200),        -- Open Graph description
    og_image VARCHAR(500),              -- Open Graph image (1200x630 recommended)
    twitter_card ENUM('summary', 'summary_large_image') DEFAULT 'summary_large_image',
    
    -- Relasi
    category_id INT,
    author_id INT,
    
    -- Status & Visibility
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    visibility ENUM('public', 'private', 'password') DEFAULT 'public',
    password_hash VARCHAR(255),          -- Jika visibility = password
    is_featured TINYINT(1) DEFAULT 0,   -- Artikel unggulan
    is_sticky TINYINT(1) DEFAULT 0,     -- Artikel yang selalu di atas
    allow_comments TINYINT(1) DEFAULT 1,
    
    -- SEO Advanced
    robots_index TINYINT(1) DEFAULT 1,  -- SEO: index/noindex
    robots_follow TINYINT(1) DEFAULT 1, -- SEO: follow/nofollow
    schema_type ENUM('Article', 'NewsArticle', 'BlogPosting', 'TechArticle') DEFAULT 'BlogPosting',
    
    -- Analytics & Engagement
    view_count INT UNSIGNED DEFAULT 0,
    share_count INT UNSIGNED DEFAULT 0,
    reading_time INT UNSIGNED DEFAULT 0, -- Estimasi waktu baca (menit)
    
    -- Timestamps
    published_at TIMESTAMP NULL,         -- Tanggal publish (untuk scheduling)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (category_id) REFERENCES article_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES article_authors(id) ON DELETE SET NULL,
    
    -- Indexes untuk performa & SEO
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_author (author_id),
    INDEX idx_published (published_at),
    INDEX idx_featured (is_featured),
    INDEX idx_status_published (status, published_at),
    FULLTEXT idx_search (title, content, excerpt) -- SEO: untuk pencarian internal
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Tags
CREATE TABLE IF NOT EXISTS article_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(60) NOT NULL UNIQUE,
    description TEXT,
    meta_title VARCHAR(70),
    meta_description VARCHAR(160),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Relasi Artikel - Tags (Many to Many)
CREATE TABLE IF NOT EXISTS article_tag_relations (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES article_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk Related Articles (Artikel Terkait) - SEO: internal linking
CREATE TABLE IF NOT EXISTS article_relations (
    article_id INT NOT NULL,
    related_article_id INT NOT NULL,
    sort_order INT DEFAULT 0,
    
    PRIMARY KEY (article_id, related_article_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (related_article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Revisi Artikel (Untuk tracking perubahan)
CREATE TABLE IF NOT EXISTS article_revisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    revision_note VARCHAR(255),
    revised_by INT,                     -- ID author yang merevisi
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (revised_by) REFERENCES article_authors(id) ON DELETE SET NULL,
    INDEX idx_article (article_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Komentar (Opsional - untuk engagement)
CREATE TABLE IF NOT EXISTS article_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    parent_id INT DEFAULT NULL,        -- Untuk reply/nested comments
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(255) NOT NULL,
    author_website VARCHAR(255),
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'spam', 'trash') DEFAULT 'pending',
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES article_comments(id) ON DELETE CASCADE,
    INDEX idx_article (article_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Data Default
-- ============================================

-- Kategori Default
INSERT INTO article_categories (name, slug, description, meta_title, meta_description) VALUES
('Berita', 'berita', 'Berita terbaru seputar web development dan teknologi', 'Berita Teknologi & Web Development | DibikininWeb', 'Baca berita terbaru seputar web development, teknologi, dan tips digital untuk bisnis Anda.'),
('Tutorial', 'tutorial', 'Tutorial dan panduan lengkap web development', 'Tutorial Web Development | DibikininWeb', 'Pelajari tutorial dan panduan lengkap web development dari dasar hingga mahir.'),
('Tips & Trik', 'tips-trik', 'Tips dan trik untuk optimasi website dan bisnis online', 'Tips & Trik Website | DibikininWeb', 'Temukan tips dan trik untuk mengoptimalkan website dan mengembangkan bisnis online Anda.');

-- Author Default
INSERT INTO article_authors (name, slug, email, bio) VALUES
('Admin DibikininWeb', 'admin', 'admin@dibikininweb.com', 'Tim admin DibikininWeb yang berpengalaman dalam web development dan digital marketing.');

-- ============================================
-- View untuk Query Artikel yang Sering Digunakan
-- ============================================

-- View artikel yang sudah dipublish
CREATE OR REPLACE VIEW v_published_articles AS
SELECT 
    a.*,
    c.name AS category_name,
    c.slug AS category_slug,
    au.name AS author_name,
    au.slug AS author_slug,
    au.avatar AS author_avatar,
    GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ', ') AS tag_names,
    GROUP_CONCAT(DISTINCT t.slug ORDER BY t.name SEPARATOR ',') AS tag_slugs
FROM articles a
LEFT JOIN article_categories c ON a.category_id = c.id
LEFT JOIN article_authors au ON a.author_id = au.id
LEFT JOIN article_tag_relations atr ON a.id = atr.article_id
LEFT JOIN article_tags t ON atr.tag_id = t.id
WHERE a.status = 'published' 
    AND a.published_at <= NOW()
GROUP BY a.id
ORDER BY a.is_sticky DESC, a.published_at DESC;

