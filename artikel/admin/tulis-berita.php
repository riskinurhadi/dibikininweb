<?php
session_start();

// 1. Cek Login & Include Database
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../database/ArticleModel.php';

$pageTitle = 'Tulis Berita Baru';
$error = '';
$success = '';

// 2. Logic Ambil Kategori
$kategories = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM kategori_artikel ORDER BY nama");
        $kategories = $stmt->fetchAll();
    } catch (Exception $e) { /* Ignore */ }
}

// 3. Logic Proses Simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $articleModel = new ArticleModel($pdo);
        
        // Ambil data form
        $judul      = trim($_POST['judul'] ?? '');
        $konten     = $_POST['konten'] ?? '';
        $ringkasan  = trim($_POST['ringkasan'] ?? '');
        $kategori_id= !empty($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : null;
        $penulis    = trim($_POST['penulis'] ?? 'Admin');
        $gambar     = trim($_POST['gambar'] ?? '');
        
        // Tentukan status berdasarkan tombol yang diklik (name="action")
        $action = $_POST['action'] ?? 'draft'; 
        $status = ($action === 'publish') ? 'published' : 'draft';

        if (empty($judul) || empty($konten)) {
            $error = 'Judul dan Konten wajib diisi!';
        } else {
            $slug = $articleModel->generateSlug($judul);
            if (empty($ringkasan)) {
                $ringkasan = $articleModel->generateExcerpt($konten);
            }

            $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;

            $data = [
                'judul'         => $judul,
                'slug'          => $slug,
                'konten'        => $konten,
                'ringkasan'     => $ringkasan,
                'gambar'        => $gambar ?: null,
                'kategori_id'   => $kategori_id,
                'penulis'       => $penulis,
                'status'        => $status,
                'waktu_baca'    => $articleModel->calculateReadingTime($konten),
                'published_at'  => $published_at
            ];

            if ($articleModel->create($data)) {
                $success = ($status === 'published') ? 'Artikel berhasil dipublikasikan!' : 'Draft berhasil disimpan!';
                // Redirect jika publish, stay jika draft
                if ($status === 'published') {
                    echo "<script>alert('Berhasil dipublikasikan!'); window.location='semua-berita.php';</script>";
                    exit;
                }
            } else {
                $error = 'Gagal menyimpan ke database.';
            }
        }
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Summernote CSS (Versi Lite lebih stabil untuk standalone) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <?php include 'includes/styles.php'; ?>
</head>
<body class="bg-light">
    
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content p-4">
        <?php include 'includes/header.php'; ?>

        <div class="container-fluid mt-4">
            <!-- Alert Section -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <form method="POST" action="">
                <div class="row">
                    <!-- Kolom Kiri: Editor Utama -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Konten Berita</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Judul Artikel</label>
                                    <input type="text" name="judul" class="form-control form-control-lg" placeholder="Masukkan judul menarik..." value="<?= isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : '' ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Isi Berita</label>
                                    <textarea id="summernote" name="konten" required><?= isset($_POST['konten']) ? htmlspecialchars($_POST['konten']) : '' ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ringkasan (Opsional)</label>
                                    <textarea name="ringkasan" class="form-control" rows="3" maxlength="300" placeholder="Ringkasan singkat..."><?= isset($_POST['ringkasan']) ? htmlspecialchars($_POST['ringkasan']) : '' ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Sidebar Pengaturan -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Pengaturan</h5>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-select">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($kategories as $kat): ?>
                                            <option value="<?= $kat['id'] ?>" <?= (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kat['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($kat['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Penulis</label>
                                    <input type="text" name="penulis" class="form-control" value="<?= isset($_POST['penulis']) ? htmlspecialchars($_POST['penulis']) : 'Admin' ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">URL Gambar Utama</label>
                                    <input type="url" name="gambar" class="form-control" placeholder="https://..." value="<?= isset($_POST['gambar']) ? htmlspecialchars($_POST['gambar']) : '' ?>">
                                </div>

                                <hr>

                                <div class="d-grid gap-2">
                                    <button type="submit" name="action" value="publish" class="btn btn-success fw-bold">
                                        <i class="bi bi-send me-1"></i> Publikasikan
                                    </button>
                                    <button type="submit" name="action" value="draft" class="btn btn-secondary">
                                        <i class="bi bi-save me-1"></i> Simpan Draft
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Script Wajib: jQuery -> Bootstrap -> Summernote -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote Lite JS (Versi Lite lebih ringan dan jarang konflik dengan Bootstrap 5) -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote Sederhana
            $('#summernote').summernote({
                placeholder: 'Tulis konten berita di sini...',
                tabsize: 2,
                height: 400, // Tinggi editor
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
</body>
</html>