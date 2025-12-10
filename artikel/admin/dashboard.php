<?php
/**
 * Dashboard Admin
 * Modern & Professional Design
 */

session_start();

// Cek login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../koneksi.php';

// Inisialisasi stats dengan nilai default
$stats = [
    'total_artikel' => 0,
    'artikel_published' => 0,
    'artikel_draft' => 0,
    'total_views' => 0,
    'artikel_minggu_ini' => 0
];

$recentArticles = [];
$popularArticles = [];

// Query stats jika database tersedia
$errorMessage = '';
if ($pdo) {
    try {
        // Total artikel
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel");
        $row = $stmt->fetch();
        $stats['total_artikel'] = isset($row['total']) ? (int)$row['total'] : 0;
        
        // Artikel published
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel WHERE status = 'published'");
        $row = $stmt->fetch();
        $stats['artikel_published'] = isset($row['total']) ? (int)$row['total'] : 0;
        
        // Artikel draft
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel WHERE status = 'draft'");
        $row = $stmt->fetch();
        $stats['artikel_draft'] = isset($row['total']) ? (int)$row['total'] : 0;
        
        // Total views
        $stmt = $pdo->query("SELECT COALESCE(SUM(dilihat), 0) as total FROM artikel");
        $row = $stmt->fetch();
        $stats['total_views'] = isset($row['total']) ? (int)$row['total'] : 0;
        
        // Artikel minggu ini
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel WHERE status = 'published' AND YEARWEEK(published_at) = YEARWEEK(CURDATE())");
        $row = $stmt->fetch();
        $stats['artikel_minggu_ini'] = isset($row['total']) ? (int)$row['total'] : 0;
        
        // Artikel terbaru
        $stmt = $pdo->query("SELECT a.*, k.nama AS kategori_nama 
                            FROM artikel a 
                            LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                            ORDER BY a.created_at DESC 
                            LIMIT 5");
        $recentArticles = $stmt->fetchAll() ?: [];
        
        // Artikel populer
        $stmt = $pdo->query("SELECT a.*, k.nama AS kategori_nama 
                            FROM artikel a 
                            LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                            WHERE a.status = 'published'
                            ORDER BY a.dilihat DESC 
                            LIMIT 5");
        $popularArticles = $stmt->fetchAll() ?: [];
        
    } catch (Exception $e) {
        // Tampilkan error untuk debugging
        $errorMessage = 'Error: ' . $e->getMessage();
        error_log("Dashboard Error: " . $e->getMessage());
        
        // Pastikan semua key tetap terdefinisi jika error
        $stats['total_artikel'] = 0;
        $stats['artikel_published'] = 0;
        $stats['artikel_draft'] = 0;
        $stats['total_views'] = 0;
        $stats['artikel_minggu_ini'] = 0;
        $recentArticles = [];
        $popularArticles = [];
    }
} else {
    $errorMessage = 'Koneksi database tidak tersedia';
    // Jika $pdo tidak tersedia, pastikan semua key terdefinisi
    $stats['total_artikel'] = 0;
    $stats['artikel_published'] = 0;
    $stats['artikel_draft'] = 0;
    $stats['total_views'] = 0;
    $stats['artikel_minggu_ini'] = 0;
    $recentArticles = [];
    $popularArticles = [];
}

// Pastikan semua key selalu terdefinisi (double check)
$stats['total_artikel'] = isset($stats['total_artikel']) ? (int)$stats['total_artikel'] : 0;
$stats['artikel_published'] = isset($stats['artikel_published']) ? (int)$stats['artikel_published'] : 0;
$stats['artikel_draft'] = isset($stats['artikel_draft']) ? (int)$stats['artikel_draft'] : 0;
$stats['total_views'] = isset($stats['total_views']) ? (int)$stats['total_views'] : 0;
$stats['artikel_minggu_ini'] = isset($stats['artikel_minggu_ini']) ? (int)$stats['artikel_minggu_ini'] : 0;
$recentArticles = isset($recentArticles) && is_array($recentArticles) ? $recentArticles : [];
$popularArticles = isset($popularArticles) && is_array($popularArticles) ? $popularArticles : [];

// Format angka
function formatNumber($number) {
    // Pastikan selalu integer dan tidak null
    $number = isset($number) ? (int)$number : 0;
    $number = max(0, $number); // Pastikan tidak negatif
    
    if ($number >= 1000000) {
        return number_format($number / 1000000, 1, '.', ',') . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1, '.', ',') . 'K';
    }
    return number_format($number, 0, '.', ',');
}

$pageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <?php include 'includes/styles.php'; ?>
    
    <style>
        .stat-card-change {
            font-size: 12px;
            color: var(--success-color);
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .recent-article-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: inherit;
            transition: background 0.2s;
        }
        
        .recent-article-item:last-child {
            border-bottom: none;
        }
        
        .recent-article-item:hover {
            background: var(--main-bg);
            color: inherit;
            border-radius: 8px;
        }
        
        .recent-article-content h6 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-primary);
        }
        
        .recent-article-content .meta {
            font-size: 12px;
            color: var(--text-secondary);
        }
        
        .section-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title i {
            color: var(--primary-color);
        }
        
        .recent-article-item {
            padding: 12px;
        }
        
        .recent-article-content h6 {
            font-size: 14px;
        }
        
        .recent-article-content .meta {
            font-size: 11px;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <?php include 'includes/header.php'; ?>
        
        <div class="content-body">
            <?php if ($errorMessage): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Peringatan:</strong> <?php echo htmlspecialchars($errorMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Debug Info (Hapus setelah fix) -->
            <?php if (isset($_GET['debug'])): ?>
                <div class="alert alert-info">
                    <strong>Debug Info:</strong><br>
                    Total Artikel: <?php var_dump($stats['total_artikel']); ?><br>
                    Published: <?php var_dump($stats['artikel_published']); ?><br>
                    Draft: <?php var_dump($stats['artikel_draft']); ?><br>
                    Views: <?php var_dump($stats['total_views']); ?><br>
                    PDO Available: <?php echo $pdo ? 'Yes' : 'No'; ?>
                </div>
            <?php endif; ?>
            
            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="stat-card-content">
                            <div class="stat-card-value"><?php echo number_format($stats['total_artikel'] ?? 0); ?></div>
                            <div class="stat-card-label">Total Berita</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="border-left-color: var(--success-color);">
                        <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-card-content">
                            <div class="stat-card-value"><?php echo number_format($stats['artikel_published'] ?? 0); ?></div>
                            <div class="stat-card-label">Artikel Published</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="border-left-color: var(--warning-color);">
                        <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                            <i class="bi bi-pencil"></i>
                        </div>
                        <div class="stat-card-content">
                            <div class="stat-card-value"><?php echo number_format($stats['artikel_draft'] ?? 0); ?></div>
                            <div class="stat-card-label">Draft Artikel</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="border-left-color: var(--info-color);">
                        <div class="stat-card-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info-color);">
                            <i class="bi bi-eye"></i>
                        </div>
                        <div class="stat-card-content">
                            <div class="stat-card-value"><?php echo formatNumber($stats['total_views'] ?? 0); ?></div>
                            <div class="stat-card-label">Total Views</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Articles & Popular Articles -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="section-title mb-0">
                                <i class="bi bi-clock-history"></i>
                                Artikel Terbaru
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($recentArticles)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 48px; color: #dee2e6;"></i>
                                    <p class="text-muted mt-3">Belum ada artikel</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($recentArticles as $article): ?>
                                    <a href="edit-berita.php?id=<?php echo $article['id']; ?>" class="recent-article-item">
                                        <div class="flex-grow-1">
                                            <h6 class="recent-article-content">
                                                <?php echo htmlspecialchars($article['judul']); ?>
                                            </h6>
                                            <div class="meta">
                                                <span><i class="bi bi-calendar me-1"></i><?php echo date('d M Y', strtotime($article['created_at'])); ?></span>
                                                <span class="ms-3">
                                                    <?php if ($article['status'] === 'published'): ?>
                                                        <span class="badge bg-success">Published</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Draft</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-secondary"></i>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="section-title mb-0">
                                <i class="bi bi-fire"></i>
                                Artikel Populer
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($popularArticles)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 48px; color: #dee2e6;"></i>
                                    <p class="text-muted mt-3">Belum ada artikel populer</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($popularArticles as $article): ?>
                                    <a href="../detail.php?slug=<?php echo htmlspecialchars($article['slug']); ?>" 
                                       target="_blank"
                                       class="recent-article-item">
                                        <div class="flex-grow-1">
                                            <h6 class="recent-article-content">
                                                <?php echo htmlspecialchars($article['judul']); ?>
                                            </h6>
                                            <div class="meta">
                                                <span><i class="bi bi-eye me-1"></i><?php echo number_format($article['dilihat'] ?? 0); ?> views</span>
                                                <span class="ms-3"><i class="bi bi-calendar me-1"></i><?php echo date('d M Y', strtotime($article['published_at'])); ?></span>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-secondary"></i>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="section-title mb-0">
                                <i class="bi bi-lightning"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <a href="tulis-berita.php" class="btn btn-primary w-100">
                                        <i class="bi bi-pencil-square me-2"></i>Tulis Artikel Baru
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="semua-berita.php" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-list-ul me-2"></i>Lihat Semua Artikel
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="../index.php" target="_blank" class="btn btn-outline-success w-100">
                                        <i class="bi bi-eye me-2"></i>Lihat Website
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="logout.php" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
