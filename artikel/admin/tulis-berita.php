<?php
/**
 * Halaman Tulis Berita Baru
 * Form untuk membuat artikel baru
 */

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../database/ArticleModel.php';

$pageTitle = 'Tulis Berita Baru';
$error = '';
$success = '';

// Proses submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    if (!$pdo) {
        $error = 'Database tidak tersedia';
    } else {
        try {
            $articleModel = new ArticleModel($pdo);
            
            $judul = trim($_POST['judul'] ?? '');
            $konten = $_POST['konten'] ?? '';
            $ringkasan = trim($_POST['ringkasan'] ?? '');
            $kategori_id = !empty($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : null;
            $penulis = trim($_POST['penulis'] ?? 'Admin');
            $status = $_POST['status'] ?? 'draft';
            $gambar = trim($_POST['gambar'] ?? '');
            
            // Validasi
            if (empty($judul)) {
                $error = 'Judul artikel harus diisi!';
            } elseif (empty($konten)) {
                $error = 'Konten artikel harus diisi!';
            } else {
                // Generate slug dari judul
                $slug = $articleModel->generateSlug($judul);
                
                // Generate ringkasan otomatis jika kosong
                if (empty($ringkasan)) {
                    $ringkasan = $articleModel->generateExcerpt($konten);
                }
                
                // Hitung waktu baca
                $waktu_baca = $articleModel->calculateReadingTime($konten);
                
                // Tentukan published_at berdasarkan status
                $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
                
                // Simpan artikel
                $data = [
                    'judul' => $judul,
                    'slug' => $slug,
                    'konten' => $konten,
                    'ringkasan' => $ringkasan,
                    'gambar' => $gambar ?: null,
                    'kategori_id' => $kategori_id,
                    'penulis' => $penulis,
                    'status' => $status,
                    'waktu_baca' => $waktu_baca,
                    'published_at' => $published_at
                ];
                
                $id = $articleModel->create($data);
                
                if ($id) {
                    $success = 'Artikel berhasil disimpan!';
                    // Reset form atau redirect
                    if ($status === 'published') {
                        header('Location: semua-berita.php?success=Artikel berhasil dipublikasikan');
                        exit;
                    }
                } else {
                    $error = 'Gagal menyimpan artikel';
                }
            }
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

// Ambil kategori untuk dropdown
$kategories = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM kategori_artikel ORDER BY nama");
        $kategories = $stmt->fetchAll();
    } catch (Exception $e) {
        // Ignore
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | dibikininweb</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Summernote Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
    
    <!-- Include Styles -->
    <?php include 'includes/styles.php'; ?>
    
    <style>
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .form-section {
            margin-bottom: 32px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        
        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: var(--info-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-control::placeholder {
            color: var(--text-secondary);
        }
        
        .help-text {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 6px;
        }
        
        .btn-group-actions {
            display: flex;
            gap: 12px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }
        
        .btn-save-draft {
            background: var(--text-secondary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-save-draft:hover {
            background: #4a5568;
            color: white;
        }
        
        .btn-publish {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-publish:hover {
            background: #059669;
            color: white;
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            margin-top: 12px;
            border: 1px solid var(--border-color);
        }
        
        .character-count {
            text-align: right;
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .btn-group-actions {
                flex-direction: column;
            }
            
            .btn-group-actions button {
                width: 100%;
            }
        }
        
        /* Summernote Editor Styling */
        .note-editor {
            border-radius: 8px;
            border: 1px solid var(--border-color) !important;
        }
        
        .note-editor.note-frame .note-editing-area .note-editable {
            min-height: 400px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }
        
        .note-toolbar {
            background: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
            border-radius: 8px 8px 0 0;
        }
        
        .note-btn {
            border-radius: 4px;
        }
        
        .note-btn:hover {
            background: #e9ecef;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <?php include 'includes/header.php'; ?>
        
        <!-- Content Body -->
        <div class="content-body">
            <div class="form-container">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="formArtikel">
                    <!-- Informasi Dasar -->
                    <div class="form-section">
                        <h2 class="section-title">Informasi Dasar</h2>
                        
                        <div class="mb-4">
                            <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="judul" 
                                   name="judul" 
                                   placeholder="Masukkan judul artikel"
                                   value="<?php echo isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : ''; ?>"
                                   required>
                            <div class="help-text">Judul yang menarik akan meningkatkan engagement pembaca</div>
                        </div>
                        
                        <div class="form-row">
                            <div class="mb-4">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori_id" name="kategori_id">
                                    <option value="">Pilih Kategori</option>
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
                                <input type="text" 
                                       class="form-control" 
                                       id="penulis" 
                                       name="penulis" 
                                       placeholder="Nama penulis"
                                       value="<?php echo isset($_POST['penulis']) ? htmlspecialchars($_POST['penulis']) : 'Admin'; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="ringkasan" class="form-label">Ringkasan</label>
                            <textarea class="form-control" 
                                      id="ringkasan" 
                                      name="ringkasan" 
                                      rows="3" 
                                      placeholder="Ringkasan artikel (opsional, akan di-generate otomatis jika kosong)"
                                      maxlength="300"><?php echo isset($_POST['ringkasan']) ? htmlspecialchars($_POST['ringkasan']) : ''; ?></textarea>
                            <div class="character-count">
                                <span id="ringkasanCount">0</span>/300 karakter
                            </div>
                        </div>
                    </div>
                    
                    <!-- Konten Artikel -->
                    <div class="form-section">
                        <h2 class="section-title">Konten Artikel</h2>
                        
                        <div class="mb-4">
                            <label for="konten" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="konten" 
                                      name="konten" 
                                      rows="15" 
                                      required><?php echo isset($_POST['konten']) ? htmlspecialchars($_POST['konten']) : ''; ?></textarea>
                            <div class="help-text">Gunakan editor untuk memformat konten artikel Anda</div>
                        </div>
                    </div>
                    
                    <!-- Media & Pengaturan -->
                    <div class="form-section">
                        <h2 class="section-title">Media & Pengaturan</h2>
                        
                        <div class="mb-4">
                            <label for="gambar" class="form-label">Gambar Utama</label>
                            <input type="url" 
                                   class="form-control" 
                                   id="gambar" 
                                   name="gambar" 
                                   placeholder="https://example.com/gambar.jpg"
                                   value="<?php echo isset($_POST['gambar']) ? htmlspecialchars($_POST['gambar']) : ''; ?>">
                            <div class="help-text">Masukkan URL gambar untuk artikel</div>
                            <div id="previewContainer" style="display: none;">
                                <img src="" alt="Preview" class="preview-image" id="previewImage">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft" <?php echo (isset($_POST['status']) && $_POST['status'] === 'draft') ? 'selected' : 'selected'; ?>>Draft</option>
                                <option value="published" <?php echo (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : ''; ?>>Publikasikan Sekarang</option>
                            </select>
                            <div class="help-text">Draft: Simpan sebagai draft. Publikasikan: Artikel akan langsung tampil di website</div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="btn-group-actions">
                        <button type="submit" name="simpan" value="draft" class="btn btn-save-draft">
                            <i class="bi bi-save me-2"></i>Simpan sebagai Draft
                        </button>
                        <button type="submit" name="simpan" value="publish" class="btn btn-publish">
                            <i class="bi bi-check-circle me-2"></i>Publikasikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <!-- jQuery (required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Summernote Editor
        $(document).ready(function() {
            $('#konten').summernote({
                height: 500,
                placeholder: 'Tulis konten artikel Anda di sini...',
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
                ],
                fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Inter', 'Times New Roman', 'Verdana'],
                fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '36', '48'],
                callbacks: {
                    onInit: function() {
                        $('.note-editor').css('border-radius', '8px');
                    }
                }
            });
        });
        
        // Character count untuk ringkasan
        const ringkasanInput = document.getElementById('ringkasan');
        const ringkasanCount = document.getElementById('ringkasanCount');
        
        ringkasanInput.addEventListener('input', function() {
            ringkasanCount.textContent = this.value.length;
        });
        
        // Update count on load
        ringkasanCount.textContent = ringkasanInput.value.length;
        
        // Preview gambar
        const gambarInput = document.getElementById('gambar');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        
        gambarInput.addEventListener('input', function() {
            if (this.value) {
                previewImage.src = this.value;
                previewContainer.style.display = 'block';
            } else {
                previewContainer.style.display = 'none';
            }
        });
        
        // Handle form submit untuk menentukan status
        document.getElementById('formArtikel').addEventListener('submit', function(e) {
            const submitButton = document.activeElement;
            if (submitButton && submitButton.name === 'simpan') {
                const statusInput = document.getElementById('status');
                if (submitButton.value === 'draft') {
                    statusInput.value = 'draft';
                } else if (submitButton.value === 'publish') {
                    statusInput.value = 'published';
                }
            }
        });
    </script>
</body>
</html>

