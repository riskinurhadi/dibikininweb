<?php
/**
 * Landing Page Artikel/Blog
 * Menampilkan daftar artikel yang telah dipublish
 */

session_start();
require_once __DIR__ . '/../koneksi.php';

// Inisialisasi variabel
$articles = [];
$categories = [];
$popularArticles = [];
$featuredArticles = [];
$trendingArticles = [];
$editorChoiceArticles = [];
$mainArticle = null;
$nextArticles = [];
$totalArticles = 0;
$totalPages = 1;
$dbError = false;

// Cek koneksi database
if (!$pdo) {
    $dbError = true;
    $errorMessage = "Database belum dikonfigurasi atau belum dibuat.";
} else {
    try {
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 9;
        $kategori_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        
        // Query dasar untuk artikel published
        $baseWhere = "WHERE a.status = 'published' AND (a.published_at IS NULL OR a.published_at <= NOW())";
        $params = [];
        
        // Filter kategori
        if ($kategori_id) {
            $baseWhere .= " AND a.kategori_id = ?";
            $params[] = $kategori_id;
        }
        
        // Filter search
        if ($search) {
            $baseWhere .= " AND (a.judul LIKE ? OR a.konten LIKE ? OR a.ringkasan LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Ambil artikel utama (terbaru)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY a.published_at DESC, a.created_at DESC 
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $mainArticle = $stmt->fetch();
        
        // Ambil 2 artikel berikutnya
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY a.published_at DESC, a.created_at DESC 
                LIMIT 3 OFFSET 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $nextArticles = $stmt->fetchAll();
        $nextArticles = array_slice($nextArticles, 0, 2);
        
        // Ambil Featured Articles (4 artikel terbaru)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY a.published_at DESC, a.created_at DESC 
                LIMIT 4";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $featuredArticles = $stmt->fetchAll();
        
        // Ambil Trending Articles (berdasarkan dilihat)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY a.dilihat DESC 
                LIMIT 4";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $trendingArticles = $stmt->fetchAll();
        
        // Ambil Editor's Choice (6 artikel terbaru)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY a.published_at DESC, a.created_at DESC 
                LIMIT 6";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $editorChoiceArticles = $stmt->fetchAll();
        
        // Ambil artikel untuk list (jika ada filter/search/pagination)
        if ($kategori_id || $search || $page > 1) {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT a.*, k.nama AS kategori_nama 
                    FROM artikel a 
                    LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                    $baseWhere 
                    ORDER BY a.published_at DESC, a.created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $pdo->prepare($sql);
            $listParams = array_merge($params, [$limit, $offset]);
            $stmt->execute($listParams);
            $articles = $stmt->fetchAll();
            
            // Hitung total untuk pagination
            $countSql = "SELECT COUNT(*) FROM artikel a $baseWhere";
            $countStmt = $pdo->prepare($countSql);
            $countStmt->execute($params);
            $totalArticles = (int)$countStmt->fetchColumn();
            $totalPages = max(1, ceil($totalArticles / $limit));
        }
        
        // Ambil kategori
        $stmt = $pdo->query("SELECT * FROM kategori_artikel ORDER BY nama");
        $categories = $stmt->fetchAll();
        
        // Ambil artikel populer (5 artikel)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                WHERE a.status = 'published' AND (a.published_at IS NULL OR a.published_at <= NOW())
                ORDER BY a.dilihat DESC 
                LIMIT 5";
        $stmt = $pdo->query($sql);
        $popularArticles = $stmt->fetchAll();
        
    } catch (Exception $e) {
        $dbError = true;
        $errorMessage = "Error: " . $e->getMessage();
    }
}

// Base URL
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];

// Helper function untuk format tanggal
function formatDate($date) {
    if (empty($date)) return '';
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $timestamp = strtotime($date);
    return strtoupper(date('d', $timestamp)) . ' ' . strtoupper($bulan[date('n', $timestamp)]) . ' ' . date('Y', $timestamp);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel & Berita Terbaru | dibikininweb</title>
    <meta name="description" content="Baca artikel dan berita terbaru seputar web development, teknologi, tips digital marketing, dan SEO dari dibikininweb.">
    <meta name="keywords" content="artikel web development, berita teknologi, tips seo, digital marketing, tutorial website">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Artikel & Berita Terbaru | dibikininweb">
    <meta property="og:description" content="Baca artikel dan berita terbaru seputar web development, teknologi, tips digital marketing, dan SEO dari dibikininweb.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($currentUrl); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #18A7D2;
            --primary-dark: #0d6efd;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
        }
        
        /* Main Article Banner */
        .main-article-banner {
            position: relative;
            height: 500px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .main-article-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .main-article-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
            padding: 40px;
            color: white;
        }
        
        .main-article-overlay .article-meta {
            color: rgba(255,255,255,0.9);
            font-size: 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .main-article-overlay h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            line-height: 1.3;
        }
        
        .main-article-overlay h2 a {
            color: white;
            text-decoration: none;
        }
        
        .main-article-overlay h2 a:hover {
            color: var(--primary-color);
        }
        
        /* Next Articles */
        .next-article-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .next-article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: inherit;
        }
        
        .next-article-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .next-article-card-body {
            padding: 20px;
        }
        
        .next-article-card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #32353a;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .next-article-meta {
            font-size: 12px;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Featured Story Sidebar */
        .featured-story-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
            text-decoration: none;
            color: inherit;
            transition: background 0.3s;
        }
        
        .featured-story-item:last-child {
            border-bottom: none;
        }
        
        .featured-story-item:hover {
            background: #f8f9fa;
            border-radius: 8px;
            padding-left: 10px;
            padding-right: 10px;
            color: inherit;
        }
        
        .featured-story-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .featured-story-content h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #32353a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .featured-story-content .meta {
            font-size: 11px;
            color: #6c757d;
        }
        
        /* Trending Now Section */
        .trending-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .trending-section h5 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #32353a;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .trending-section h5 i {
            color: var(--primary-color);
        }
        
        .trending-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
            text-decoration: none;
            color: inherit;
            transition: background 0.3s;
        }
        
        .trending-item:last-child {
            border-bottom: none;
        }
        
        .trending-item:hover {
            background: #f8f9fa;
            border-radius: 8px;
            padding-left: 10px;
            padding-right: 10px;
            color: inherit;
        }
        
        .trending-item img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .trending-content h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #32353a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .trending-content .meta {
            font-size: 11px;
            color: #6c757d;
        }
        
        /* Newsletter Section */
        .newsletter-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
        }
        
        .newsletter-section h5 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .newsletter-section p {
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .newsletter-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .newsletter-form input {
            flex: 1;
            min-width: 200px;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
        }
        
        .newsletter-form button {
            padding: 12px 24px;
            background: white;
            color: var(--primary-color);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .newsletter-form button:hover {
            transform: translateY(-2px);
        }
        
        /* Editor's Choice Section */
        .editor-choice-section {
            margin-top: 40px;
        }
        
        .editor-choice-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .editor-choice-header h4 {
            font-weight: 600;
            color: #32353a;
            margin: 0;
        }
        
        .editor-choice-header i {
            color: var(--primary-color);
            font-size: 24px;
        }
        
        .editor-choice-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .editor-choice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: inherit;
        }
        
        .editor-choice-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .editor-choice-card-body {
            padding: 20px;
        }
        
        .editor-choice-card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #32353a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .editor-choice-meta {
            font-size: 12px;
            color: #6c757d;
        }
        
        /* Sidebar Card */
        .sidebar-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .sidebar-card h5 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #32353a;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* No Image Placeholder */
        .no-image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .main-article-banner {
                height: 400px;
            }
            
            .main-article-overlay h2 {
                font-size: 24px;
            }
        }
        
        @media (max-width: 768px) {
            .main-article-banner {
                height: 300px;
            }
            
            .main-article-overlay {
                padding: 20px;
            }
            
            .main-article-overlay h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="container-fluid py-5" style="background: #f8f9fa;">
        <div class="container">
            <?php if ($dbError): ?>
                <div class="alert alert-warning" role="alert">
                    <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Database Error</h5>
                    <p><?php echo htmlspecialchars($errorMessage ?? 'Terjadi kesalahan pada database.'); ?></p>
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Main Content Area -->
                    <div class="col-lg-8">
                        <?php if ($mainArticle && !$kategori_id && !$search && $page == 1): ?>
                            <!-- Main Article Banner -->
                            <div class="main-article-banner">
                                <?php if (!empty($mainArticle['gambar'])): ?>
                                    <img src="<?php echo htmlspecialchars($mainArticle['gambar']); ?>" alt="<?php echo htmlspecialchars($mainArticle['judul']); ?>">
                                <?php else: ?>
                                    <div class="no-image-placeholder">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="main-article-overlay">
                                    <div class="article-meta">
                                        <span>BY <?php echo strtoupper(htmlspecialchars($mainArticle['penulis'] ?? 'ADMIN')); ?></span>
                                        <span><?php echo formatDate($mainArticle['published_at']); ?></span>
                                    </div>
                                    <h2>
                                        <a href="detail.php?slug=<?php echo htmlspecialchars($mainArticle['slug']); ?>">
                                            <?php echo htmlspecialchars($mainArticle['judul']); ?>
                                        </a>
                                    </h2>
                                </div>
                            </div>
                            
                            <!-- Next Articles (2 cards) -->
                            <?php if (!empty($nextArticles)): ?>
                                <div class="row g-4 mb-4">
                                    <?php foreach ($nextArticles as $next): ?>
                                        <div class="col-md-6">
                                            <a href="detail.php?slug=<?php echo htmlspecialchars($next['slug']); ?>" class="next-article-card">
                                                <?php if (!empty($next['gambar'])): ?>
                                                    <img src="<?php echo htmlspecialchars($next['gambar']); ?>" alt="<?php echo htmlspecialchars($next['judul']); ?>">
                                                <?php else: ?>
                                                    <div class="no-image-placeholder" style="height: 200px;">
                                                        <i class="bi bi-file-text"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="next-article-card-body">
                                                    <h5 class="next-article-card-title"><?php echo htmlspecialchars($next['judul']); ?></h5>
                                                    <div class="next-article-meta">
                                                        <span><i class="bi bi-calendar me-1"></i><?php echo date('d M Y', strtotime($next['published_at'])); ?></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Trending Now Section -->
                            <?php if (!empty($trendingArticles)): ?>
                                <div class="trending-section">
                                    <h5>
                                        <i class="bi bi-graph-up-arrow"></i>
                                        Trending now
                                    </h5>
                                    <?php foreach ($trendingArticles as $trending): ?>
                                        <a href="detail.php?slug=<?php echo htmlspecialchars($trending['slug']); ?>" class="trending-item">
                                            <?php if (!empty($trending['gambar'])): ?>
                                                <img src="<?php echo htmlspecialchars($trending['gambar']); ?>" alt="<?php echo htmlspecialchars($trending['judul']); ?>">
                                            <?php else: ?>
                                                <div class="no-image-placeholder" style="width: 120px; height: 80px; font-size: 24px;">
                                                    <i class="bi bi-file-text"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="trending-content">
                                                <h6><?php echo htmlspecialchars($trending['judul']); ?></h6>
                                                <div class="meta">
                                                    <span><?php echo date('d M Y', strtotime($trending['published_at'])); ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Editor's Choice Section -->
                            <?php if (!empty($editorChoiceArticles)): ?>
                                <div class="editor-choice-section">
                                    <div class="editor-choice-header">
                                        <i class="bi bi-newspaper"></i>
                                        <h4>Pilihan Redaktur Headline Media</h4>
                                    </div>
                                    <div class="row g-4">
                                        <?php foreach (array_slice($editorChoiceArticles, 0, 6) as $choice): ?>
                                            <div class="col-md-4">
                                                <a href="detail.php?slug=<?php echo htmlspecialchars($choice['slug']); ?>" class="editor-choice-card">
                                                    <?php if (!empty($choice['gambar'])): ?>
                                                        <img src="<?php echo htmlspecialchars($choice['gambar']); ?>" alt="<?php echo htmlspecialchars($choice['judul']); ?>">
                                                    <?php else: ?>
                                                        <div class="no-image-placeholder" style="height: 200px;">
                                                            <i class="bi bi-file-text"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="editor-choice-card-body">
                                                        <h5 class="editor-choice-card-title"><?php echo htmlspecialchars($choice['judul']); ?></h5>
                                                        <div class="editor-choice-meta">
                                                            <span><?php echo date('d M Y', strtotime($choice['published_at'])); ?></span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Articles List (when filtered/searched) -->
                            <?php if (!empty($articles)): ?>
                                <div class="row g-4">
                                    <?php foreach ($articles as $article): ?>
                                        <div class="col-md-6">
                                            <a href="detail.php?slug=<?php echo htmlspecialchars($article['slug']); ?>" class="next-article-card">
                                                <?php if (!empty($article['gambar'])): ?>
                                                    <img src="<?php echo htmlspecialchars($article['gambar']); ?>" alt="<?php echo htmlspecialchars($article['judul']); ?>">
                                                <?php else: ?>
                                                    <div class="no-image-placeholder" style="height: 200px;">
                                                        <i class="bi bi-file-text"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="next-article-card-body">
                                                    <h5 class="next-article-card-title"><?php echo htmlspecialchars($article['judul']); ?></h5>
                                                    <div class="next-article-meta">
                                                        <span><i class="bi bi-calendar me-1"></i><?php echo date('d M Y', strtotime($article['published_at'])); ?></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Pagination -->
                                <?php if ($totalPages > 1): ?>
                                    <nav aria-label="Page navigation" class="mt-5">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo ($kategori_id ?? null) ? '&category=' . $kategori_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo ($kategori_id ?? null) ? '&category=' . $kategori_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            
                                            <?php if ($page < $totalPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo ($kategori_id ?? null) ? '&category=' . $kategori_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 64px; color: #dee2e6;"></i>
                                    <h4 class="mt-3">Tidak ada artikel ditemukan</h4>
                                    <p class="text-muted">Coba cari dengan kata kunci lain atau pilih kategori yang berbeda.</p>
                                    <a href="index.php" class="btn btn-primary">Lihat Semua Artikel</a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Featured Story -->
                        <?php if (!empty($featuredArticles) && !$kategori_id && !$search && $page == 1): ?>
                            <div class="sidebar-card">
                                <h5>Featured story</h5>
                                <?php foreach (array_slice($featuredArticles, 0, 4) as $featured): ?>
                                    <a href="detail.php?slug=<?php echo htmlspecialchars($featured['slug']); ?>" class="featured-story-item">
                                        <?php if (!empty($featured['gambar'])): ?>
                                            <img src="<?php echo htmlspecialchars($featured['gambar']); ?>" alt="<?php echo htmlspecialchars($featured['judul']); ?>">
                                        <?php else: ?>
                                            <div class="no-image-placeholder" style="width: 100px; height: 100px; font-size: 24px;">
                                                <i class="bi bi-file-text"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="featured-story-content">
                                            <h6><?php echo htmlspecialchars($featured['judul']); ?></h6>
                                            <div class="meta">
                                                <span><?php echo date('d M Y', strtotime($featured['published_at'])); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Newsletter Subscription -->
                        <div class="newsletter-section">
                            <h5>Get daily news updates to your inbox!</h5>
                            <p>Dapatkan update artikel terbaru langsung ke email Anda.</p>
                            <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Terima kasih! Anda akan menerima update artikel terbaru.');">
                                <input type="text" placeholder="Name..." required>
                                <input type="email" placeholder="Email address..." required>
                                <button type="submit">SUBSCRIBE</button>
                            </form>
                        </div>
                        
                        <!-- Popular Articles -->
                        <?php if (!empty($popularArticles)): ?>
                            <div class="sidebar-card">
                                <h5><i class="bi bi-fire me-2"></i>Artikel Populer</h5>
                                <?php foreach ($popularArticles as $pop): ?>
                                    <a href="detail.php?slug=<?php echo htmlspecialchars($pop['slug']); ?>" class="featured-story-item">
                                        <?php if (!empty($pop['gambar'])): ?>
                                            <img src="<?php echo htmlspecialchars($pop['gambar']); ?>" alt="<?php echo htmlspecialchars($pop['judul']); ?>">
                                        <?php else: ?>
                                            <div class="no-image-placeholder" style="width: 100px; height: 100px; font-size: 24px;">
                                                <i class="bi bi-file-text"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="featured-story-content">
                                            <h6><?php echo htmlspecialchars($pop['judul']); ?></h6>
                                            <div class="meta">
                                                <i class="bi bi-eye me-1"></i><?php echo number_format($pop['dilihat'] ?? 0); ?> views
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
