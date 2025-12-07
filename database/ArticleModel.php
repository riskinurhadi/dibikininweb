<?php
/**
 * Article Model - Versi Sederhana
 * Model untuk mengelola artikel/berita
 */

class ArticleModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Cek apakah koneksi database tersedia
     */
    private function checkConnection() {
        if (!$this->pdo) {
            throw new Exception("Koneksi database tidak tersedia. Pastikan database sudah dibuat dan dikonfigurasi dengan benar.");
        }
    }
    
    /**
     * Generate slug dari judul
     */
    public function generateSlug($judul, $excludeId = null) {
        $this->checkConnection();
        
        // Konversi ke lowercase
        $slug = strtolower(trim($judul));
        
        // Ganti karakter khusus dengan dash
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
        $sql = "SELECT COUNT(*) FROM artikel WHERE slug = ?";
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
    public function calculateReadingTime($konten) {
        $wordCount = str_word_count(strip_tags($konten));
        $readingTime = ceil($wordCount / 200); // Rata-rata 200 kata per menit
        return max(1, $readingTime);
    }
    
    /**
     * Generate ringkasan otomatis dari konten
     */
    public function generateExcerpt($konten, $length = 160) {
        $text = strip_tags($konten);
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
     * Ambil artikel berdasarkan slug
     */
    public function getBySlug($slug) {
        $this->checkConnection();
        
        $sql = "SELECT a.*, 
                k.nama AS kategori_nama, k.slug AS kategori_slug
                FROM artikel a
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id
                WHERE a.slug = ? AND a.status = 'published' 
                AND (a.published_at IS NULL OR a.published_at <= NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$slug]);
        $article = $stmt->fetch();
        
        if ($article) {
            // Increment view count
            $this->incrementViewCount($article['id']);
        }
        
        return $article;
    }
    
    /**
     * Ambil daftar artikel dengan pagination
     */
    public function getList($options = []) {
        $this->checkConnection();
        
        $defaults = [
            'status' => 'published',
            'kategori_id' => null,
            'search' => null,
            'page' => 1,
            'limit' => 10,
            'order_by' => 'published_at',
            'order_dir' => 'DESC'
        ];
        
        $options = array_merge($defaults, $options);
        
        $where = [];
        $params = [];
        
        if ($options['status']) {
            $where[] = "a.status = ?";
            $params[] = $options['status'];
            
            if ($options['status'] === 'published') {
                $where[] = "(a.published_at IS NULL OR a.published_at <= NOW())";
            }
        }
        
        if ($options['kategori_id']) {
            $where[] = "a.kategori_id = ?";
            $params[] = $options['kategori_id'];
        }
        
        if ($options['search']) {
            $where[] = "(a.judul LIKE ? OR a.konten LIKE ? OR a.ringkasan LIKE ?)";
            $searchTerm = '%' . $options['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        $offset = ($options['page'] - 1) * $options['limit'];
        
        // Pastikan limit dan offset adalah integer untuk keamanan
        $limit = (int)$options['limit'];
        $offset = (int)$offset;
        
        // Validasi order_by untuk mencegah SQL injection
        $allowedOrderBy = ['published_at', 'created_at', 'updated_at', 'judul', 'dilihat'];
        $orderBy = in_array($options['order_by'], $allowedOrderBy) ? $options['order_by'] : 'published_at';
        $orderDir = strtoupper($options['order_dir']) === 'ASC' ? 'ASC' : 'DESC';
        
        $sql = "SELECT a.*, 
                k.nama AS kategori_nama, k.slug AS kategori_slug
                FROM artikel a
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id
                $whereClause
                ORDER BY a.{$orderBy} {$orderDir}
                LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Hitung total artikel
     */
    public function getCount($options = []) {
        $this->checkConnection();
        
        $defaults = [
            'status' => 'published',
            'kategori_id' => null,
            'search' => null
        ];
        
        $options = array_merge($defaults, $options);
        
        $where = [];
        $params = [];
        
        if ($options['status']) {
            $where[] = "status = ?";
            $params[] = $options['status'];
            
            if ($options['status'] === 'published') {
                $where[] = "(published_at IS NULL OR published_at <= NOW())";
            }
        }
        
        if ($options['kategori_id']) {
            $where[] = "kategori_id = ?";
            $params[] = $options['kategori_id'];
        }
        
        if ($options['search']) {
            $where[] = "(judul LIKE ? OR konten LIKE ? OR ringkasan LIKE ?)";
            $searchTerm = '%' . $options['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT COUNT(*) FROM artikel $whereClause";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return (int)$stmt->fetchColumn();
    }
    
    /**
     * Ambil artikel populer
     */
    public function getPopularArticles($limit = 5) {
        $this->checkConnection();
        
        // Pastikan limit adalah integer
        $limit = (int)$limit;
        
        $sql = "SELECT a.*, 
                k.nama AS kategori_nama, k.slug AS kategori_slug
                FROM artikel a
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id
                WHERE a.status = 'published' 
                AND (a.published_at IS NULL OR a.published_at <= NOW())
                ORDER BY a.dilihat DESC
                LIMIT {$limit}";
        
        $stmt = $this->pdo->query($sql);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Ambil artikel terkait (berdasarkan kategori yang sama)
     */
    public function getRelatedArticles($artikelId, $limit = 3) {
        $this->checkConnection();
        
        // Pastikan limit adalah integer
        $limit = (int)$limit;
        $artikelId = (int)$artikelId;
        
        $sql = "SELECT a.*, 
                k.nama AS kategori_nama, k.slug AS kategori_slug
                FROM artikel a
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id
                WHERE a.kategori_id = (SELECT kategori_id FROM artikel WHERE id = ?)
                AND a.id != ?
                AND a.status = 'published' 
                AND (a.published_at IS NULL OR a.published_at <= NOW())
                ORDER BY a.published_at DESC
                LIMIT {$limit}";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$artikelId, $artikelId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Increment view count
     */
    public function incrementViewCount($id) {
        $this->checkConnection();
        
        $stmt = $this->pdo->prepare("UPDATE artikel SET dilihat = dilihat + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Simpan artikel baru
     */
    public function create($data) {
        $this->checkConnection();
        
        // Generate slug jika tidak ada
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['judul']);
        }
        
        // Generate ringkasan jika tidak ada
        if (empty($data['ringkasan']) && !empty($data['konten'])) {
            $data['ringkasan'] = $this->generateExcerpt($data['konten']);
        }
        
        // Hitung waktu baca
        if (!empty($data['konten'])) {
            $data['waktu_baca'] = $this->calculateReadingTime($data['konten']);
        }
        
        $sql = "INSERT INTO artikel (
            judul, slug, konten, ringkasan, gambar,
            kategori_id, penulis, status, waktu_baca, published_at
        ) VALUES (
            :judul, :slug, :konten, :ringkasan, :gambar,
            :kategori_id, :penulis, :status, :waktu_baca, :published_at
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':judul' => $data['judul'],
            ':slug' => $data['slug'],
            ':konten' => $data['konten'],
            ':ringkasan' => $data['ringkasan'] ?? null,
            ':gambar' => $data['gambar'] ?? null,
            ':kategori_id' => $data['kategori_id'] ?? null,
            ':penulis' => $data['penulis'] ?? 'Admin',
            ':status' => $data['status'] ?? 'draft',
            ':waktu_baca' => $data['waktu_baca'] ?? 0,
            ':published_at' => $data['published_at'] ?? null
        ]) ? $this->pdo->lastInsertId() : false;
    }
    
    /**
     * Update artikel
     */
    public function update($id, $data) {
        $this->checkConnection();
        
        // Generate slug baru jika judul berubah
        if (!empty($data['judul']) && empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['judul'], $id);
        }
        
        // Recalculate waktu baca jika konten berubah
        if (!empty($data['konten'])) {
            $data['waktu_baca'] = $this->calculateReadingTime($data['konten']);
        }
        
        $fields = [];
        $params = [':id' => $id];
        
        $allowedFields = [
            'judul', 'slug', 'konten', 'ringkasan', 'gambar',
            'kategori_id', 'penulis', 'status', 'waktu_baca', 'published_at'
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
        
        $sql = "UPDATE artikel SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * Hapus artikel
     */
    public function delete($id) {
        $this->checkConnection();
        
        $stmt = $this->pdo->prepare("DELETE FROM artikel WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
