<?php
/**
 * Halaman Utama Artikel/Blog
 * Menampilkan daftar artikel yang telah dipublish
 */

require_once __DIR__ . '/../koneksi.php';

// Inisialisasi variabel
$mainArticle = null;
$nextArticles = [];
$featuredArticles = [];
$trendingArticles = [];
$editorChoiceArticles = [];
$popularArticles = [];
$categories = [];

// Query artikel jika database tersedia
if ($pdo) {
    try {
        // Query dasar untuk artikel published - Sederhanakan query
        // Hanya cek status = 'published', tanpa kondisi published_at yang kompleks
        $baseWhere = "WHERE a.status = 'published'";
        
        // Ambil artikel utama (terbaru)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY COALESCE(a.published_at, a.created_at) DESC 
                LIMIT 1";
        $stmt = $pdo->query($sql);
        $mainArticle = $stmt->fetch();
        
        // Ambil 2 artikel berikutnya
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY COALESCE(a.published_at, a.created_at) DESC 
                LIMIT 3 OFFSET 1";
        $stmt = $pdo->query($sql);
        $nextArticles = $stmt->fetchAll();
        $nextArticles = array_slice($nextArticles, 0, 2);
        
        // Ambil Featured Articles (4 artikel terbaru)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY COALESCE(a.published_at, a.created_at) DESC 
                LIMIT 4";
        $stmt = $pdo->query($sql);
        $featuredArticles = $stmt->fetchAll();
        
        // Ambil Trending Articles (berdasarkan dilihat)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY a.dilihat DESC 
                LIMIT 4";
        $stmt = $pdo->query($sql);
        $trendingArticles = $stmt->fetchAll();
        
        // Ambil Editor's Choice (6 artikel terbaru)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY COALESCE(a.published_at, a.created_at) DESC 
                LIMIT 6";
        $stmt = $pdo->query($sql);
        $editorChoiceArticles = $stmt->fetchAll();
        
        // Ambil Popular Articles (5 artikel)
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $baseWhere 
                ORDER BY a.dilihat DESC 
                LIMIT 5";
        $stmt = $pdo->query($sql);
        $popularArticles = $stmt->fetchAll();
        
        // Ambil kategori
        $stmt = $pdo->query("SELECT * FROM kategori_artikel ORDER BY nama");
        $categories = $stmt->fetchAll();
        
    } catch (Exception $e) {
        // Error handling - log error untuk debugging
        error_log("Artikel Index Error: " . $e->getMessage());
    }
}

// Helper function untuk format tanggal
function formatDate($date) {
    if (empty($date)) return '';
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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <div class="article-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="../index.php" style="color: rgba(255,255,255,0.8); text-decoration: none;">Home</a></li>
                            <li class="breadcrumb-item active" style="color: white;">Artikel</li>
                        </ol>
                    </nav>
                    <h1>Artikel & Berita</h1>
                    <p>Baca artikel terbaru seputar web development, teknologi, dan tips digital marketing</p>
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
    <div class="container-fluid py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="row">
                <!-- Main Content Area -->
                <div class="col-lg-8">
                    <?php if ($mainArticle): ?>
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
                        
                        <!-- Next Articles -->
                        <?php if (!empty($nextArticles)): ?>
                            <div class="row g-4 mb-4">
                                <?php foreach ($nextArticles as $next): ?>
                                    <div class="col-md-6">
                                        <a href="detail.php?slug=<?php echo htmlspecialchars($next['slug']); ?>" class="article-card">
                                            <?php if (!empty($next['gambar'])): ?>
                                                <img src="<?php echo htmlspecialchars($next['gambar']); ?>" alt="<?php echo htmlspecialchars($next['judul']); ?>">
                                            <?php else: ?>
                                                <div class="no-image-placeholder" style="height: 200px;">
                                                    <i class="bi bi-file-text"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="article-card-body">
                                                <h5 class="article-card-title"><?php echo htmlspecialchars($next['judul']); ?></h5>
                                                <div class="article-card-meta">
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
                                    <?php foreach ($editorChoiceArticles as $choice): ?>
                                        <div class="col-md-4">
                                            <a href="detail.php?slug=<?php echo htmlspecialchars($choice['slug']); ?>" class="article-card">
                                                <?php if (!empty($choice['gambar'])): ?>
                                                    <img src="<?php echo htmlspecialchars($choice['gambar']); ?>" alt="<?php echo htmlspecialchars($choice['judul']); ?>">
                                                <?php else: ?>
                                                    <div class="no-image-placeholder" style="height: 200px;">
                                                        <i class="bi bi-file-text"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="article-card-body">
                                                    <h5 class="article-card-title"><?php echo htmlspecialchars($choice['judul']); ?></h5>
                                                    <div class="article-card-meta">
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
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 64px; color: #dee2e6;"></i>
                            <h4 class="mt-3">Belum ada artikel</h4>
                            <p class="text-muted">Artikel akan muncul di sini setelah dipublish.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Featured Story -->
                    <?php if (!empty($featuredArticles)): ?>
                        <div class="sidebar-card">
                            <h5>Featured story</h5>
                            <?php foreach ($featuredArticles as $featured): ?>
                                <a href="detail.php?slug=<?php echo htmlspecialchars($featured['slug']); ?>" class="featured-item">
                                    <?php if (!empty($featured['gambar'])): ?>
                                        <img src="<?php echo htmlspecialchars($featured['gambar']); ?>" alt="<?php echo htmlspecialchars($featured['judul']); ?>">
                                    <?php else: ?>
                                        <div class="no-image-placeholder" style="width: 100px; height: 100px; font-size: 24px;">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="featured-content">
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
                                <a href="detail.php?slug=<?php echo htmlspecialchars($pop['slug']); ?>" class="featured-item">
                                    <?php if (!empty($pop['gambar'])): ?>
                                        <img src="<?php echo htmlspecialchars($pop['gambar']); ?>" alt="<?php echo htmlspecialchars($pop['judul']); ?>">
                                    <?php else: ?>
                                        <div class="no-image-placeholder" style="width: 100px; height: 100px; font-size: 24px;">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="featured-content">
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
