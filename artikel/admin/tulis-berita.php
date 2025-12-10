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
    
    <style>
        /* Custom Styles untuk Halaman Tulis Berita */
        .main-content {
            background-color: var(--main-bg);
        }
        
        .content-body {
            padding: 32px;
        }
        
        /* Alert Styling */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        /* Card Styling */
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .form-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            background: #fafafa;
        }
        
        .form-card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }
        
        .form-card-body {
            padding: 24px;
        }
        
        /* Form Controls */
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: var(--info-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        .form-control-lg {
            font-size: 16px;
            padding: 12px 16px;
        }
        
        .form-control::placeholder {
            color: var(--text-secondary);
            opacity: 0.6;
        }
        
        /* Button Styling */
        .btn-action {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-publish {
            background: var(--success-color);
            color: white;
        }
        
        .btn-publish:hover {
            background: #059669;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }
        
        .btn-draft {
            background: var(--text-secondary);
            color: white;
        }
        
        .btn-draft:hover {
            background: #4a5568;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(113, 128, 150, 0.3);
        }
        
        /* Divider */
        .form-divider {
            border: none;
            border-top: 1px solid var(--border-color);
            margin: 24px 0;
        }
        
        /* Help Text */
        .form-text {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 6px;
        }
        
        /* Character Count */
        .char-count {
            text-align: right;
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
        }
        
        /* Summernote Editor Styling - Pastikan tidak terganggu */
        .note-editor {
            border-radius: 8px;
            border: 1px solid var(--border-color) !important;
        }
        
        .note-editor.note-frame .note-editing-area .note-editable {
            min-height: 400px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            padding: 16px;
        }
        
        .note-toolbar {
            background: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
            border-radius: 8px 8px 0 0;
            padding: 8px;
        }
        
        .note-btn {
            border-radius: 4px;
        }
        
        .note-btn:hover {
            background: #e9ecef;
        }
        
        /* Fix untuk dropdown Summernote */
        .note-dropdown-menu {
            z-index: 9999 !important;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .content-body {
                padding: 20px;
            }
            
            .form-card-body {
                padding: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .content-body {
                padding: 16px;
            }
            
            .form-card-body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'includes/header.php'; ?>

        <div class="content-body">
            <!-- Alert Section -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Error!</strong> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Berhasil!</strong> <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="">
                <div class="row">
                    <!-- Kolom Kiri: Editor Utama -->
                    <div class="col-lg-8">
                        <div class="form-card">
                            <div class="form-card-header">
                                <h5 class="form-card-title">
                                    <i class="bi bi-file-text me-2"></i>Konten Berita
                                </h5>
                            </div>
                            <div class="form-card-body">
                                <div class="mb-4">
                                    <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           id="judul"
                                           name="judul" 
                                           class="form-control form-control-lg" 
                                           placeholder="Masukkan judul artikel yang menarik..." 
                                           value="<?= isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : '' ?>" 
                                           required>
                                    <div class="form-text">Judul yang menarik akan meningkatkan engagement pembaca</div>
                                </div>

                                <div class="mb-4">
                                    <label for="summernote" class="form-label">Isi Berita <span class="text-danger">*</span></label>
                                    <textarea id="summernote" name="konten" required><?= isset($_POST['konten']) ? htmlspecialchars($_POST['konten']) : '' ?></textarea>
                                    <div class="form-text">Gunakan editor untuk memformat konten artikel Anda</div>
                                </div>

                                <div class="mb-3">
                                    <label for="ringkasan" class="form-label">Ringkasan</label>
                                    <textarea id="ringkasan"
                                              name="ringkasan" 
                                              class="form-control" 
                                              rows="3" 
                                              maxlength="300" 
                                              placeholder="Ringkasan singkat artikel (opsional, akan di-generate otomatis jika kosong)..."><?= isset($_POST['ringkasan']) ? htmlspecialchars($_POST['ringkasan']) : '' ?></textarea>
                                    <div class="char-count">
                                        <span id="ringkasanCount">0</span>/300 karakter
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Sidebar Pengaturan -->
                    <div class="col-lg-4">
                        <div class="form-card">
                            <div class="form-card-header">
                                <h5 class="form-card-title">
                                    <i class="bi bi-gear me-2"></i>Pengaturan
                                </h5>
                            </div>
                            <div class="form-card-body">
                                <div class="mb-4">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select id="kategori_id" name="kategori_id" class="form-select">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($kategories as $kat): ?>
                                            <option value="<?= $kat['id'] ?>" <?= (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kat['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($kat['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="penulis" class="form-label">Penulis</label>
                                    <input type="text" 
                                           id="penulis"
                                           name="penulis" 
                                           class="form-control" 
                                           placeholder="Nama penulis"
                                           value="<?= isset($_POST['penulis']) ? htmlspecialchars($_POST['penulis']) : 'Admin' ?>">
                                </div>

                                <div class="mb-4">
                                    <label for="gambar" class="form-label">URL Gambar Utama</label>
                                    <input type="url" 
                                           id="gambar"
                                           name="gambar" 
                                           class="form-control" 
                                           placeholder="https://example.com/gambar.jpg"
                                           value="<?= isset($_POST['gambar']) ? htmlspecialchars($_POST['gambar']) : '' ?>">
                                    <div class="form-text">Masukkan URL gambar untuk artikel</div>
                                </div>

                                <hr class="form-divider">

                                <div class="d-grid gap-2">
                                    <button type="submit" name="action" value="publish" class="btn btn-action btn-publish">
                                        <i class="bi bi-send"></i>
                                        <span>Publikasikan</span>
                                    </button>
                                    <button type="submit" name="action" value="draft" class="btn btn-action btn-draft">
                                        <i class="bi bi-save"></i>
                                        <span>Simpan Draft</span>
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
            
            // Character count untuk ringkasan
            const ringkasanInput = document.getElementById('ringkasan');
            const ringkasanCount = document.getElementById('ringkasanCount');
            
            if (ringkasanInput && ringkasanCount) {
                // Update count on input
                ringkasanInput.addEventListener('input', function() {
                    ringkasanCount.textContent = this.value.length;
                });
                
                // Update count on load
                ringkasanCount.textContent = ringkasanInput.value.length;
            }
        });
    </script>
</body>
</html>