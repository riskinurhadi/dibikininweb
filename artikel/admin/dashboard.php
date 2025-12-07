<?php
/**
 * Dashboard Admin - Placeholder
 * Halaman ini akan digunakan untuk manage artikel/berita
 */

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | dibikininweb</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #18A7D2;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        .dashboard-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d6efd 100%);
            color: white;
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .info-card h5 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h4>
                <div>
                    <span class="me-3">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong></span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="welcome-card">
            <h2><i class="bi bi-check-circle-fill me-2"></i>Login Berhasil!</h2>
            <p class="mb-0">Anda telah berhasil login ke dashboard admin. Dashboard untuk mengelola artikel/berita akan segera tersedia.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-card">
                    <h5><i class="bi bi-info-circle me-2"></i>Informasi</h5>
                    <p>Halaman dashboard admin untuk manage artikel/berita sedang dalam pengembangan. Fitur yang akan tersedia:</p>
                    <ul>
                        <li>Manajemen Artikel (Tambah, Edit, Hapus)</li>
                        <li>Manajemen Kategori</li>
                        <li>Manajemen Tags</li>
                        <li>Statistik Artikel</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <h5><i class="bi bi-lightbulb me-2"></i>Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="../index.php" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>Lihat Halaman Artikel
                        </a>
                        <a href="../admin/index.php" class="btn btn-outline-primary">
                            <i class="bi bi-gear me-2"></i>Pengaturan Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

