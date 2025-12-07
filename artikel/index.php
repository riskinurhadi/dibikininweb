<?php
/**
 * Landing Page Artikel/Blog
 * Menampilkan daftar artikel yang telah dipublish
 */

session_start();
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../database/ArticleModel.php';

// Inisialisasi variabel
$articles = [];
$categories = [];
$popularArticles = [];
$totalArticles = 0;
$totalPages = 1;
$dbError = false;

// Cek koneksi database
if (!$pdo) {
    $dbError = true;
    $errorMessage = "Database belum dikonfigurasi atau belum dibuat. Silakan buat database terlebih dahulu dengan menjalankan file database_artikel.sql";
} else {
    try {
        // Inisialisasi ArticleModel
        $articleModel = new ArticleModel($pdo);

        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 9; // 9 artikel per halaman
        $kategori_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;

        // Ambil artikel
        $articles = $articleModel->getList([
            'status' => 'published',
            'kategori_id' => $kategori_id,
            'search' => $search,
            'page' => $page,
            'limit' => $limit,
            'order_by' => 'published_at',
            'order_dir' => 'DESC'
        ]);

        $totalArticles = $articleModel->getCount([
            'status' => 'published',
            'kategori_id' => $kategori_id,
            'search' => $search
        ]);

        $totalPages = max(1, ceil($totalArticles / $limit));

        // Ambil kategori untuk filter
        $categoriesStmt = $pdo->query("SELECT * FROM kategori_artikel ORDER BY nama");
        $categories = $categoriesStmt->fetchAll();

        // Ambil artikel populer
        $popularArticles = $articleModel->getPopularArticles(5);
    } catch (Exception $e) {
        $dbError = true;
        $errorMessage = "Error: " . $e->getMessage();
    }
}

// Base URL
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];
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
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        .article-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d6efd 100%);
            color: white;
            padding: 80px 0 60px;
            margin-bottom: 40px;
        }
        
        .article-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .article-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        
        .article-card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .article-category {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .article-card-title {
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
        
        .article-card-title a {
            color: #32353a;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .article-card-title a:hover {
            color: var(--primary-color);
        }
        
        .article-excerpt {
            color: #6c757d;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 12px;
            color: #6c757d;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        
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
        
        .popular-article-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
            text-decoration: none;
            color: inherit;
            transition: background 0.3s;
        }
        
        .popular-article-item:last-child {
            border-bottom: none;
        }
        
        .popular-article-item:hover {
            background: #f8f9fa;
            border-radius: 8px;
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .popular-article-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .popular-article-content h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #32353a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .popular-article-content .meta {
            font-size: 11px;
            color: #6c757d;
        }
        
        .category-filter {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .category-filter li {
            margin-bottom: 8px;
        }
        
        .category-filter a {
            display: block;
            padding: 10px 15px;
            color: #495057;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .category-filter a:hover,
        .category-filter a.active {
            background: var(--primary-color);
            color: white;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding-right: 45px;
        }
        
        .search-box button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--primary-color);
        }
        
        .pagination .page-link {
            color: var(--primary-color);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .no-image-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="article-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../index.php" style="color: rgba(255,255,255,0.8); text-decoration: none;">Home</a></li>
                            <li class="breadcrumb-item active" style="color: white;" aria-current="page">Artikel</li>
                        </ol>
                    </nav>
                    <h1 class="display-5 fw-bold mb-3">Artikel & Berita</h1>
                    <p class="lead mb-0">Baca artikel terbaru seputar web development, teknologi, dan tips digital marketing</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="../index.php" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4 mb-4">
                <!-- Search Box -->
                <div class="sidebar-card">
                    <h5><i class="bi bi-search me-2"></i>Cari Artikel</h5>
                    <form method="GET" action="">
                        <div class="search-box">
                            <input type="text" name="search" class="form-control" placeholder="Cari artikel..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                            <button type="submit"><i class="bi bi-search"></i></button>
                        </div>
                        <?php if (!empty($kategori_id)): ?>
                            <input type="hidden" name="category" value="<?php echo $kategori_id; ?>">
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Categories -->
                <?php if (!empty($categories)): ?>
                <div class="sidebar-card">
                    <h5><i class="bi bi-folder me-2"></i>Kategori</h5>
                    <ul class="category-filter">
                        <li><a href="index.php" class="<?php echo !$category_id ? 'active' : ''; ?>">Semua Kategori</a></li>
                        <?php foreach ($categories as $cat): ?>
                            <li><a href="?category=<?php echo $cat['id']; ?>" class="<?php echo ($kategori_id ?? null) == $cat['id'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($cat['nama']); ?>
                            </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Popular Articles -->
                <?php if (!empty($popularArticles)): ?>
                <div class="sidebar-card">
                    <h5><i class="bi bi-fire me-2"></i>Artikel Populer</h5>
                        <?php foreach ($popularArticles as $pop): ?>
                        <a href="detail.php?slug=<?php echo htmlspecialchars($pop['slug']); ?>" class="popular-article-item">
                            <?php if (!empty($pop['gambar'])): ?>
                                <img src="<?php echo htmlspecialchars($pop['gambar']); ?>" alt="<?php echo htmlspecialchars($pop['judul']); ?>">
                            <?php else: ?>
                                <div class="no-image-placeholder" style="width: 80px; height: 80px; font-size: 24px;">
                                    <i class="bi bi-file-text"></i>
                                </div>
                            <?php endif; ?>
                            <div class="popular-article-content">
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

            <!-- Articles List -->
            <div class="col-lg-8">
                <?php if ($dbError): ?>
                    <div class="alert alert-warning" role="alert">
                        <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Database Error</h5>
                        <p><?php echo htmlspecialchars($errorMessage ?? 'Terjadi kesalahan pada database.'); ?></p>
                        <hr>
                        <p class="mb-0"><strong>Cara mengatasi:</strong></p>
                        <ol class="mb-0">
                            <li>Buat database dengan nama <code>dibikininweb_db</code></li>
                            <li>Jalankan file SQL: <code>database_artikel.sql</code></li>
                            <li>Pastikan konfigurasi di <code>koneksi.php</code> sudah benar</li>
                        </ol>
                    </div>
                <?php elseif (!empty($articles)): ?>
                    <div class="row g-4">
                        <?php foreach ($articles as $article): ?>
                            <div class="col-md-6">
                                <div class="article-card">
                                    <?php if (!empty($article['gambar'])): ?>
                                        <img src="<?php echo htmlspecialchars($article['gambar']); ?>" alt="<?php echo htmlspecialchars($article['judul']); ?>">
                                    <?php else: ?>
                                        <div class="no-image-placeholder">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="article-card-body">
                                        <?php if (!empty($article['kategori_nama'])): ?>
                                            <span class="article-category"><?php echo htmlspecialchars($article['kategori_nama']); ?></span>
                                        <?php endif; ?>
                                        
                                        <h3 class="article-card-title">
                                            <a href="detail.php?slug=<?php echo htmlspecialchars($article['slug']); ?>">
                                                <?php echo htmlspecialchars($article['judul']); ?>
                                            </a>
                                        </h3>
                                        
                                        <p class="article-excerpt">
                                            <?php echo htmlspecialchars($article['ringkasan'] ?? ''); ?>
                                        </p>
                                        
                                        <div class="article-meta">
                                            <?php if (!empty($article['published_at'])): ?>
                                                <span><i class="bi bi-calendar me-1"></i><?php echo date('d M Y', strtotime($article['published_at'])); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($article['penulis'])): ?>
                                                <span><i class="bi bi-person me-1"></i><?php echo htmlspecialchars($article['penulis']); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($article['waktu_baca'])): ?>
                                                <span><i class="bi bi-clock me-1"></i><?php echo $article['waktu_baca']; ?> menit</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
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
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> dibikininweb. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="../index.php" class="text-white text-decoration-none">Kembali ke Home</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

