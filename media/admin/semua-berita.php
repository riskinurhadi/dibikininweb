<?php
/**
 * Halaman List Semua Artikel (Admin)
 * Modern & Professional Design
 */

session_start();

// Cek login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../koneksi.php';

$articles = [];
$message = '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';

// Proses hapus artikel
if (isset($_GET['hapus']) && $pdo) {
    $id = (int)$_GET['hapus'];
    try {
        $stmt = $pdo->prepare("DELETE FROM artikel WHERE id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i>Artikel berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle-fill me-2"></i>Error: ' . htmlspecialchars($e->getMessage()) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

// Ambil semua artikel
if ($pdo) {
    try {
        $where = [];
        $params = [];
        
        // Filter search
        if ($search) {
            $where[] = "(a.judul LIKE ? OR a.konten LIKE ? OR a.ringkasan LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Filter status
        if ($filterStatus && in_array($filterStatus, ['published', 'draft'])) {
            $where[] = "a.status = ?";
            $params[] = $filterStatus;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                $whereClause
                ORDER BY a.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $articles = $stmt->fetchAll();
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle-fill me-2"></i>Error: ' . htmlspecialchars($e->getMessage()) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

$pageTitle = 'Semua Artikel';
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
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .page-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 18px;
            box-shadow: var(--card-shadow);
        }
        
        .article-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        
        .article-title-cell {
            max-width: 300px;
        }
        
        .article-title-cell a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .article-title-cell a:hover {
            color: var(--primary-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <?php include 'includes/header.php'; ?>
        
        <div class="content-body">
            <?php echo $message; ?>
            
            <div class="page-header">
                <h2>Semua Artikel</h2>
                <a href="tulis-berita.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tulis Artikel Baru
                </a>
            </div>
            
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Cari artikel..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="published" <?php echo $filterStatus === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="draft" <?php echo $filterStatus === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Cari
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Articles Table -->
            <div class="article-table">
                <?php if (empty($articles)): ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4 class="mt-3">Belum ada artikel</h4>
                        <p class="text-muted">Mulai dengan menulis artikel baru.</p>
                        <a href="tulis-berita.php" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Tulis Artikel Baru
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 35%;">Judul</th>
                                    <th style="width: 15%;">Kategori</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 10%;">Views</th>
                                    <th style="width: 15%;">Tanggal</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($articles as $index => $article): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td class="article-title-cell">
                                            <a href="edit-berita.php?id=<?php echo $article['id']; ?>">
                                                <?php echo htmlspecialchars($article['judul']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if ($article['kategori_nama']): ?>
                                                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($article['kategori_nama']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($article['status'] === 'published'): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <i class="bi bi-eye me-1"></i><?php echo number_format($article['dilihat'] ?? 0); ?>
                                        </td>
                                        <td>
                                            <small><?php echo date('d M Y', strtotime($article['created_at'])); ?></small>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="../detail.php?slug=<?php echo htmlspecialchars($article['slug']); ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   target="_blank"
                                                   title="Lihat">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit-berita.php?id=<?php echo $article['id']; ?>" 
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="?hapus=<?php echo $article['id']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Yakin ingin menghapus artikel ini?')"
                                                   title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
