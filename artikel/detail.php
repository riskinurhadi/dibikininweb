<?php
/**
 * Halaman Detail Artikel
 */

require_once __DIR__ . '/../koneksi.php';

// Ambil slug dari URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: index.php');
    exit;
}

$article = null;
$relatedArticles = [];

// Query artikel jika database tersedia
if ($pdo) {
    try {
        // Ambil artikel berdasarkan slug
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                WHERE a.slug = ? AND a.status = 'published' 
                AND (a.published_at IS NULL OR a.published_at <= NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$slug]);
        $article = $stmt->fetch();
        
        if (!$article) {
            header('Location: index.php');
            exit;
        }
        
        // Increment view count
        $updateStmt = $pdo->prepare("UPDATE artikel SET dilihat = dilihat + 1 WHERE id = ?");
        $updateStmt->execute([$article['id']]);
        
        // Ambil artikel terkait (berdasarkan kategori yang sama)
        if ($article['kategori_id']) {
            $relatedSql = "SELECT a.*, k.nama AS kategori_nama 
                           FROM artikel a 
                           LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                           WHERE a.kategori_id = ? 
                           AND a.id != ? 
                           AND a.status = 'published' 
                           AND (a.published_at IS NULL OR a.published_at <= NOW())
                           ORDER BY a.published_at DESC 
                           LIMIT 3";
            $relatedStmt = $pdo->prepare($relatedSql);
            $relatedStmt->execute([$article['kategori_id'], $article['id']]);
            $relatedArticles = $relatedStmt->fetchAll();
        }
        
    } catch (Exception $e) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['judul']); ?> | dibikininweb</title>
    <meta name="description" content="<?php echo htmlspecialchars($article['ringkasan'] ?? ''); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .article-content {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .article-title {
            font-size: 36px;
            font-weight: 700;
            color: #32353a;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .article-category {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 20px;
        }
        
        .article-featured-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .article-body {
            font-size: 16px;
            line-height: 1.8;
            color: #495057;
        }
        
        .article-body h2,
        .article-body h3,
        .article-body h4 {
            margin-top: 30px;
            margin-bottom: 15px;
            color: #32353a;
            font-weight: 600;
        }
        
        .article-body p {
            margin-bottom: 20px;
        }
        
        .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .related-articles {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="article-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="../index.php" style="color: rgba(255,255,255,0.8); text-decoration: none;">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php" style="color: rgba(255,255,255,0.8); text-decoration: none;">Artikel</a></li>
                    <li class="breadcrumb-item active" style="color: white;"><?php echo htmlspecialchars($article['judul']); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-8">
                <article class="article-content">
                    <?php if (!empty($article['kategori_nama'])): ?>
                        <a href="index.php" class="article-category">
                            <?php echo htmlspecialchars($article['kategori_nama']); ?>
                        </a>
                    <?php endif; ?>
                    
                    <h1 class="article-title"><?php echo htmlspecialchars($article['judul']); ?></h1>
                    
                    <div class="article-meta">
                        <?php if (!empty($article['published_at'])): ?>
                            <div class="article-meta-item">
                                <i class="bi bi-calendar"></i>
                                <span><?php echo date('d F Y', strtotime($article['published_at'])); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($article['penulis'])): ?>
                            <div class="article-meta-item">
                                <i class="bi bi-person"></i>
                                <span><?php echo htmlspecialchars($article['penulis']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="article-meta-item">
                            <i class="bi bi-eye"></i>
                            <span><?php echo number_format($article['dilihat'] ?? 0); ?> views</span>
                        </div>
                    </div>
                    
                    <?php if (!empty($article['gambar'])): ?>
                        <img src="<?php echo htmlspecialchars($article['gambar']); ?>" 
                             alt="<?php echo htmlspecialchars($article['judul']); ?>"
                             class="article-featured-image">
                    <?php endif; ?>
                    
                    <div class="article-body">
                        <?php echo $article['konten']; ?>
                    </div>
                </article>
                
                <!-- Related Articles -->
                <?php if (!empty($relatedArticles)): ?>
                    <div class="related-articles">
                        <h3 class="mb-4">Artikel Terkait</h3>
                        <div class="row g-4">
                            <?php foreach ($relatedArticles as $related): ?>
                                <div class="col-md-4">
                                    <a href="detail.php?slug=<?php echo htmlspecialchars($related['slug']); ?>" class="article-card">
                                        <?php if (!empty($related['gambar'])): ?>
                                            <img src="<?php echo htmlspecialchars($related['gambar']); ?>" alt="<?php echo htmlspecialchars($related['judul']); ?>">
                                        <?php else: ?>
                                            <div class="no-image-placeholder" style="height: 200px;">
                                                <i class="bi bi-file-text"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="article-card-body">
                                            <h5 class="article-card-title"><?php echo htmlspecialchars($related['judul']); ?></h5>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="text-center mb-4">
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Artikel
                    </a>
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
