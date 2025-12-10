<?php
/**
 * Halaman List Semua Artikel (Admin)
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

// Proses hapus artikel
if (isset($_GET['hapus']) && $pdo) {
    $id = (int)$_GET['hapus'];
    try {
        $stmt = $pdo->prepare("DELETE FROM artikel WHERE id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Artikel berhasil dihapus!</div>';
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Ambil semua artikel
if ($pdo) {
    try {
        $sql = "SELECT a.*, k.nama AS kategori_nama 
                FROM artikel a 
                LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
                ORDER BY a.created_at DESC";
        $stmt = $pdo->query($sql);
        $articles = $stmt->fetchAll();
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
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
    
    <?php include 'includes/styles.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <?php include 'includes/header.php'; ?>
        
        <div class="content-body">
            <?php echo $message; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Semua Artikel</h2>
                <a href="tulis-berita.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tulis Artikel Baru
                </a>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($articles)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 64px; color: #dee2e6;"></i>
                            <h4 class="mt-3">Belum ada artikel</h4>
                            <p class="text-muted">Mulai dengan menulis artikel baru.</p>
                            <a href="tulis-berita.php" class="btn btn-primary">Tulis Artikel Baru</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Dilihat</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articles as $article): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($article['judul']); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($article['kategori_nama'] ?? '-'); ?>
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
                                                <?php echo date('d M Y', strtotime($article['created_at'])); ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="../detail.php?slug=<?php echo htmlspecialchars($article['slug']); ?>" 
                                                       class="btn btn-outline-primary" 
                                                       target="_blank"
                                                       title="Lihat">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="edit-berita.php?id=<?php echo $article['id']; ?>" 
                                                       class="btn btn-outline-warning"
                                                       title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="?hapus=<?php echo $article['id']; ?>" 
                                                       class="btn btn-outline-danger"
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
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

