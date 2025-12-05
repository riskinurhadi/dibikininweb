<?php
/**
 * Article Model
 * Helper class untuk mengelola artikel dengan fitur SEO
 */

require_once __DIR__ . '/../koneksi.php';

class ArticleModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Generate slug dari judul
     */
    public function generateSlug($title, $excludeId = null) {
        // Konversi ke lowercase dan ganti spasi dengan dash
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Cek apakah slug sudah ada
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Cek apakah slug sudah ada
     */
    private function slugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM articles WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Hitung estimasi waktu baca
     */
    public function calculateReadingTime($content) {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200); // Rata-rata 200 kata per menit
        return max(1, $readingTime);
    }
    
    /**
     * Generate excerpt otomatis dari konten
     */
    public function generateExcerpt($content, $length = 160) {
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', ' ', $text);
        
        if (strlen($text) <= $length) {
            return $text;
        }
        
        $excerpt = substr($text, 0, $length);
        $lastSpace = strrpos($excerpt, ' ');
        
        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }
        
        return $excerpt . '...';
    }
    
    /**
     * Simpan artikel baru
     */
    public function create($data) {
        // Generate slug jika tidak ada
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        // Generate excerpt jika tidak ada
        if (empty($data['excerpt']) && !empty($data['content'])) {
            $data['excerpt'] = $this->generateExcerpt($data['content']);
        }
        
        // Hitung reading time
        if (!empty($data['content'])) {
            $data['reading_time'] = $this->calculateReadingTime($data['content']);
        }
        
        // Default meta title dan description dari title dan excerpt
        if (empty($data['meta_title'])) {
            $data['meta_title'] = substr($data['title'], 0, 70);
        }
        if (empty($data['meta_description'])) {
            $data['meta_description'] = $data['excerpt'] ?? $this->generateExcerpt($data['content'], 160);
        }
        
        $sql = "INSERT INTO articles (
            title, slug, excerpt, content,
            meta_title, meta_description, meta_keywords, canonical_url,
            featured_image, featured_image_alt, featured_image_caption,
            og_title, og_description, og_image, twitter_card,
            category_id, author_id,
            status, visibility, is_featured, is_sticky, allow_comments,
            robots_index, robots_follow, schema_type,
            reading_time, published_at
        ) VALUES (
            :title, :slug, :excerpt, :content,
            :meta_title, :meta_description, :meta_keywords, :canonical_url,
            :featured_image, :featured_image_alt, :featured_image_caption,
            :og_title, :og_description, :og_image, :twitter_card,
            :category_id, :author_id,
            :status, :visibility, :is_featured, :is_sticky, :allow_comments,
            :robots_index, :robots_follow, :schema_type,
            :reading_time, :published_at
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':excerpt' => $data['excerpt'] ?? null,
            ':content' => $data['content'],
            ':meta_title' => $data['meta_title'] ?? null,
            ':meta_description' => $data['meta_description'] ?? null,
            ':meta_keywords' => $data['meta_keywords'] ?? null,
            ':canonical_url' => $data['canonical_url'] ?? null,
            ':featured_image' => $data['featured_image'] ?? null,
            ':featured_image_alt' => $data['featured_image_alt'] ?? null,
            ':featured_image_caption' => $data['featured_image_caption'] ?? null,
            ':og_title' => $data['og_title'] ?? null,
            ':og_description' => $data['og_description'] ?? null,
            ':og_image' => $data['og_image'] ?? null,
            ':twitter_card' => $data['twitter_card'] ?? 'summary_large_image',
            ':category_id' => $data['category_id'] ?? null,
            ':author_id' => $data['author_id'] ?? null,
            ':status' => $data['status'] ?? 'draft',
            ':visibility' => $data['visibility'] ?? 'public',
            ':is_featured' => $data['is_featured'] ?? 0,
            ':is_sticky' => $data['is_sticky'] ?? 0,
            ':allow_comments' => $data['allow_comments'] ?? 1,
            ':robots_index' => $data['robots_index'] ?? 1,
            ':robots_follow' => $data['robots_follow'] ?? 1,
            ':schema_type' => $data['schema_type'] ?? 'BlogPosting',
            ':reading_time' => $data['reading_time'] ?? 0,
            ':published_at' => $data['published_at'] ?? null
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Update artikel
     */
    public function update($id, $data) {
        // Generate slug baru jika title berubah
        if (!empty($data['title']) && empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title'], $id);
        }
        
        // Recalculate reading time jika konten berubah
        if (!empty($data['content'])) {
            $data['reading_time'] = $this->calculateReadingTime($data['content']);
        }
        
        $fields = [];
        $params = [':id' => $id];
        
        $allowedFields = [
            'title', 'slug', 'excerpt', 'content',
            'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
            'featured_image', 'featured_image_alt', 'featured_image_caption',
            'og_title', 'og_description', 'og_image', 'twitter_card',
            'category_id', 'author_id',
            'status', 'visibility', 'is_featured', 'is_sticky', 'allow_comments',
            'robots_index', 'robots_follow', 'schema_type',
            'reading_time', 'published_at'
        ];
        
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE articles SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * Ambil artikel berdasarkan ID
     */
    public function getById($id) {
        $sql = "SELECT a.*, 
                c.name AS category_name, c.slug AS category_slug,
                au.name AS author_name, au.slug AS author_slug, au.avatar AS author_avatar, au.bio AS author_bio
                FROM articles a
                LEFT JOIN article_categories c ON a.category_id = c.id
                LEFT JOIN article_authors au ON a.author_id = au.id
                WHERE a.id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $article = $stmt->fetch();
        
        if ($article) {
            $article['tags'] = $this->getArticleTags($id);
        }
        
        return $article;
    }
    
    /**
     * Ambil artikel berdasarkan slug (untuk halaman artikel)
     */
    public function getBySlug($slug) {
        $sql = "SELECT a.*, 
                c.name AS category_name, c.slug AS category_slug,
                au.name AS author_name, au.slug AS author_slug, au.avatar AS author_avatar, au.bio AS author_bio
                FROM articles a
                LEFT JOIN article_categories c ON a.category_id = c.id
                LEFT JOIN article_authors au ON a.author_id = au.id
                WHERE a.slug = ? AND a.status = 'published' AND a.published_at <= NOW()";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$slug]);
        $article = $stmt->fetch();
        
        if ($article) {
            $article['tags'] = $this->getArticleTags($article['id']);
            // Increment view count
            $this->incrementViewCount($article['id']);
        }
        
        return $article;
    }
    
    /**
     * Ambil tags dari artikel
     */
    public function getArticleTags($articleId) {
        $sql = "SELECT t.* FROM article_tags t
                JOIN article_tag_relations atr ON t.id = atr.tag_id
                WHERE atr.article_id = ?
                ORDER BY t.name";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$articleId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Set tags untuk artikel
     */
    public function setArticleTags($articleId, $tagIds) {
        // Hapus tags lama
        $stmt = $this->pdo->prepare("DELETE FROM article_tag_relations WHERE article_id = ?");
        $stmt->execute([$articleId]);
        
        // Insert tags baru
        if (!empty($tagIds)) {
            $sql = "INSERT INTO article_tag_relations (article_id, tag_id) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($tagIds as $tagId) {
                $stmt->execute([$articleId, $tagId]);
            }
        }
    }
    
    /**
     * Increment view count
     */
    public function incrementViewCount($id) {
        $stmt = $this->pdo->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Ambil daftar artikel dengan pagination
     */
    public function getList($options = []) {
        $defaults = [
            'status' => 'published',
            'category_id' => null,
            'tag_id' => null,
            'author_id' => null,
            'search' => null,
            'page' => 1,
            'limit' => 10,
            'order_by' => 'published_at',
            'order_dir' => 'DESC'
        ];
        
        $options = array_merge($defaults, $options);
        
        $where = ['1=1'];
        $params = [];
        
        if ($options['status']) {
            $where[] = "a.status = ?";
            $params[] = $options['status'];
            
            if ($options['status'] === 'published') {
                $where[] = "a.published_at <= NOW()";
            }
        }
        
        if ($options['category_id']) {
            $where[] = "a.category_id = ?";
            $params[] = $options['category_id'];
        }
        
        if ($options['author_id']) {
            $where[] = "a.author_id = ?";
            $params[] = $options['author_id'];
        }
        
        if ($options['search']) {
            $where[] = "(a.title LIKE ? OR a.content LIKE ? OR a.excerpt LIKE ?)";
            $searchTerm = '%' . $options['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = implode(' AND ', $where);
        $offset = ($options['page'] - 1) * $options['limit'];
        
        // Join dengan tags jika filter by tag
        $tagJoin = '';
        if ($options['tag_id']) {
            $tagJoin = "JOIN article_tag_relations atr ON a.id = atr.article_id AND atr.tag_id = ?";
            array_unshift($params, $options['tag_id']);
        }
        
        $sql = "SELECT a.*, 
                c.name AS category_name, c.slug AS category_slug,
                au.name AS author_name, au.slug AS author_slug, au.avatar AS author_avatar
                FROM articles a
                LEFT JOIN article_categories c ON a.category_id = c.id
                LEFT JOIN article_authors au ON a.author_id = au.id
                $tagJoin
                WHERE $whereClause
                ORDER BY a.is_sticky DESC, a.{$options['order_by']} {$options['order_dir']}
                LIMIT ? OFFSET ?";
        
        $params[] = $options['limit'];
        $params[] = $offset;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Hitung total artikel
     */
    public function getCount($options = []) {
        $defaults = [
            'status' => 'published',
            'category_id' => null,
            'tag_id' => null,
            'author_id' => null,
            'search' => null
        ];
        
        $options = array_merge($defaults, $options);
        
        $where = ['1=1'];
        $params = [];
        
        if ($options['status']) {
            $where[] = "status = ?";
            $params[] = $options['status'];
            
            if ($options['status'] === 'published') {
                $where[] = "published_at <= NOW()";
            }
        }
        
        if ($options['category_id']) {
            $where[] = "category_id = ?";
            $params[] = $options['category_id'];
        }
        
        if ($options['author_id']) {
            $where[] = "author_id = ?";
            $params[] = $options['author_id'];
        }
        
        if ($options['search']) {
            $where[] = "(title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
            $searchTerm = '%' . $options['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT COUNT(*) FROM articles WHERE $whereClause";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Ambil artikel terkait
     */
    public function getRelatedArticles($articleId, $limit = 5) {
        // Coba ambil dari tabel relasi manual dulu
        $sql = "SELECT a.*, c.name AS category_name, c.slug AS category_slug
                FROM articles a
                JOIN article_relations ar ON a.id = ar.related_article_id
                LEFT JOIN article_categories c ON a.category_id = c.id
                WHERE ar.article_id = ? AND a.status = 'published' AND a.published_at <= NOW()
                ORDER BY ar.sort_order
                LIMIT ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$articleId, $limit]);
        $related = $stmt->fetchAll();
        
        // Jika tidak ada, ambil dari kategori yang sama
        if (empty($related)) {
            $sql = "SELECT a.*, c.name AS category_name, c.slug AS category_slug
                    FROM articles a
                    LEFT JOIN article_categories c ON a.category_id = c.id
                    WHERE a.category_id = (SELECT category_id FROM articles WHERE id = ?)
                    AND a.id != ?
                    AND a.status = 'published' 
                    AND a.published_at <= NOW()
                    ORDER BY a.published_at DESC
                    LIMIT ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$articleId, $articleId, $limit]);
            $related = $stmt->fetchAll();
        }
        
        return $related;
    }
    
    /**
     * Ambil artikel populer
     */
    public function getPopularArticles($limit = 5) {
        $sql = "SELECT a.*, c.name AS category_name, c.slug AS category_slug
                FROM articles a
                LEFT JOIN article_categories c ON a.category_id = c.id
                WHERE a.status = 'published' AND a.published_at <= NOW()
                ORDER BY a.view_count DESC
                LIMIT ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$limit]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Hapus artikel
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Generate sitemap data untuk artikel
     */
    public function getSitemapData() {
        $sql = "SELECT slug, updated_at, published_at
                FROM articles
                WHERE status = 'published' 
                AND published_at <= NOW()
                AND robots_index = 1
                ORDER BY published_at DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Generate JSON-LD Schema untuk artikel
     */
    public function generateSchema($article, $baseUrl) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $article['schema_type'] ?? 'BlogPosting',
            'headline' => $article['title'],
            'description' => $article['meta_description'] ?? $article['excerpt'],
            'image' => $article['featured_image'] ? $baseUrl . $article['featured_image'] : null,
            'datePublished' => $article['published_at'],
            'dateModified' => $article['updated_at'],
            'author' => [
                '@type' => 'Person',
                'name' => $article['author_name'] ?? 'Admin'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'DibikininWeb',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $baseUrl . '/assets/img/dibikininweb.png'
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $baseUrl . '/artikel/' . $article['slug']
            ]
        ];
        
        if ($article['reading_time']) {
            $schema['timeRequired'] = 'PT' . $article['reading_time'] . 'M';
        }
        
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

