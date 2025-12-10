<?php
/**
 * Halaman Tulis Artikel Baru (Admin)
 */

session_start();

// Cek login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../koneksi.php';

$pageTitle = 'Tulis Artikel Baru';
$error = '';
$success = '';

// Helper Functions
function generateSlug($judul, $pdo) {
    $slug = strtolower(trim($judul));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    
    // Cek apakah slug sudah ada
    $originalSlug = $slug;
    $counter = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM artikel WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) {
            break;
        }
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
    return $slug;
}

function generateExcerpt($konten, $length = 160) {
    $text = strip_tags($konten);
    $text = preg_replace('/\s+/', ' ', $text);
    if (strlen($text) <= $length) {
        return $text;
    }
    $excerpt = substr($text, 0, $length);
    $lastSpace = strrpos($excerpt, ' ');
    if ($lastSpace !== false) {
        $excerpt = substr($excerpt, 0, $lastSpace);
    }
    return $excerpt . '...';
}

// Ambil Kategori
$kategories = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM kategori_artikel ORDER BY nama");
        $kategories = $stmt->fetchAll();
    } catch (Exception $e) { /* Ignore */ }
}

// Proses Simpan Artikel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    try {
        // Ambil data form
        $judul = trim($_POST['judul'] ?? '');
        $konten = $_POST['konten'] ?? '';
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $kategori_id = !empty($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : null;
        $penulis = trim($_POST['penulis'] ?? 'Admin');
        $gambar = trim($_POST['gambar'] ?? '');
        
        // Tentukan status
        $action = $_POST['action'] ?? 'draft';
        $status = ($action === 'publish') ? 'published' : 'draft';
        
        // Validasi
        if (empty($judul) || empty($konten)) {
            $error = 'Judul dan Konten wajib diisi!';
        } else {
            // Generate slug
            $slug = generateSlug($judul, $pdo);
            
            // Generate ringkasan jika kosong
            if (empty($ringkasan)) {
                $ringkasan = generateExcerpt($konten);
            }
            
            // Tentukan published_at
            $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
            
            // Insert ke database
            $sql = "INSERT INTO artikel (judul, slug, konten, ringkasan, gambar, kategori_id, penulis, status, published_at, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $judul,
                $slug,
                $konten,
                $ringkasan,
                $gambar ?: null,
                $kategori_id,
                $penulis,
                $status,
                $published_at
            ]);
            
            if ($result) {
                $success = ($status === 'published') ? 'Artikel berhasil dipublikasikan!' : 'Draft berhasil disimpan!';
                if ($status === 'published') {
                    header('Location: semua-berita.php?success=Artikel berhasil dipublikasikan');
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
    <title><?php echo htmlspecialchars($pageTitle); ?> | Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
    <?php include 'includes/styles.php'; ?>
    
    <style>
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
        
        .note-editor {
            border-radius: 8px;
            border: 1px solid var(--border-color) !important;
        }
        
        .note-dropdown-menu {
            z-index: 9999 !important;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <?php include 'includes/header.php'; ?>
        
        <div class="content-body">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-card">
                            <div class="form-card-header">
                                <h5 class="form-card-title"><i class="bi bi-file-text me-2"></i>Konten Artikel</h5>
                            </div>
                            <div class="form-card-body">
                                <div class="mb-4">
                                    <label for="judul" class="form-label fw-bold">Judul Artikel <span class="text-danger">*</span></label>
                                    <input type="text" id="judul" name="judul" class="form-control form-control-lg" 
                                           placeholder="Masukkan judul artikel..." 
                                           value="<?php echo isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : ''; ?>" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="summernote" class="form-label fw-bold">Isi Artikel <span class="text-danger">*</span></label>
                                    <textarea id="summernote" name="konten" required><?php echo isset($_POST['konten']) ? htmlspecialchars($_POST['konten']) : ''; ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="ringkasan" class="form-label fw-bold">Ringkasan</label>
                                    <textarea id="ringkasan" name="ringkasan" class="form-control" rows="3" maxlength="300" 
                                              placeholder="Ringkasan singkat (opsional)..."><?php echo isset($_POST['ringkasan']) ? htmlspecialchars($_POST['ringkasan']) : ''; ?></textarea>
                                    <div class="form-text text-end">
                                        <span id="ringkasanCount">0</span>/300 karakter
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="form-card">
                            <div class="form-card-header">
                                <h5 class="form-card-title"><i class="bi bi-gear me-2"></i>Pengaturan</h5>
                            </div>
                            <div class="form-card-body">
                                <div class="mb-4">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select id="kategori_id" name="kategori_id" class="form-select">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($kategories as $kat): ?>
                                            <option value="<?php echo $kat['id']; ?>" 
                                                    <?php echo (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kat['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($kat['nama']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="penulis" class="form-label">Penulis</label>
                                    <input type="text" id="penulis" name="penulis" class="form-control" 
                                           placeholder="Nama penulis"
                                           value="<?php echo isset($_POST['penulis']) ? htmlspecialchars($_POST['penulis']) : 'Admin'; ?>">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="gambar" class="form-label">URL Gambar</label>
                                    <input type="url" id="gambar" name="gambar" class="form-control" 
                                           placeholder="https://example.com/gambar.jpg"
                                           value="<?php echo isset($_POST['gambar']) ? htmlspecialchars($_POST['gambar']) : ''; ?>">
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" name="action" value="publish" class="btn btn-success fw-bold">
                                        <i class="bi bi-send me-2"></i>Publikasikan
                                    </button>
                                    <button type="submit" name="action" value="draft" class="btn btn-secondary">
                                        <i class="bi bi-save me-2"></i>Simpan Draft
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    
    <!-- jQuery & Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote
            $('#summernote').summernote({
                height: 400,
                placeholder: 'Tulis konten artikel di sini...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
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
                ringkasanInput.addEventListener('input', function() {
                    ringkasanCount.textContent = this.value.length;
                });
                ringkasanCount.textContent = ringkasanInput.value.length;
            }
        });
    </script>
</body>
</html>
