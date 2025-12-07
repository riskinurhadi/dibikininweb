<?php
/**
 * Sidebar Admin - External Component
 * Sidebar untuk semua halaman admin
 */

// Pastikan session sudah dimulai (akan dipanggil dari halaman yang sudah start session)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil statistik untuk badge menu
$stats = [
    'semua_berita' => 0,
    'komentar' => 0
];

if (isset($pdo) && $pdo) {
    try {
        // Semua berita
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel");
        $result = $stmt->fetch();
        $stats['semua_berita'] = $result['total'] ?? 0;
        
        // Komentar (simulasi)
        $stats['komentar'] = rand(0, 10);
    } catch (Exception $e) {
        // Ignore error
    }
}

// Tentukan halaman aktif berdasarkan nama file
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Mobile Menu Toggle -->
<button class="mobile-menu-toggle" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <i class="bi bi-newspaper"></i>
        <span class="sidebar-logo-text">Redaksi.</span>
    </div>
    
    <nav class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-section-title">MAIN</div>
            <a href="dashboard.php" class="menu-item <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            <a href="semua-berita.php" class="menu-item <?php echo $currentPage === 'semua-berita.php' ? 'active' : ''; ?>">
                <i class="bi bi-file-text"></i>
                <span class="menu-item-text">Semua Berita</span>
                <?php if ($stats['semua_berita'] > 0): ?>
                    <span class="badge-menu"><?php echo $stats['semua_berita']; ?></span>
                <?php endif; ?>
            </a>
            <a href="tulis-berita.php" class="menu-item <?php echo $currentPage === 'tulis-berita.php' ? 'active' : ''; ?>">
                <i class="bi bi-pencil-square"></i>
                <span class="menu-item-text">Tulis Baru</span>
            </a>
            <a href="kategori.php" class="menu-item <?php echo $currentPage === 'kategori.php' ? 'active' : ''; ?>">
                <i class="bi bi-folder"></i>
                <span class="menu-item-text">Kategori</span>
            </a>
        </div>
        
        <div class="menu-section">
            <div class="menu-section-title">INTERAKSI</div>
            <a href="komentar.php" class="menu-item <?php echo $currentPage === 'komentar.php' ? 'active' : ''; ?>">
                <i class="bi bi-chat-left-text"></i>
                <span class="menu-item-text">Komentar</span>
                <?php if ($stats['komentar'] > 0): ?>
                    <span class="badge-menu"><?php echo $stats['komentar']; ?></span>
                <?php endif; ?>
            </a>
            <a href="pembaca.php" class="menu-item <?php echo $currentPage === 'pembaca.php' ? 'active' : ''; ?>">
                <i class="bi bi-people"></i>
                <span class="menu-item-text">Pembaca</span>
            </a>
        </div>
        
        <div class="menu-section">
            <div class="menu-section-title">SISTEM</div>
            <a href="media-library.php" class="menu-item <?php echo $currentPage === 'media-library.php' ? 'active' : ''; ?>">
                <i class="bi bi-images"></i>
                <span class="menu-item-text">Media Library</span>
            </a>
            <a href="pengaturan.php" class="menu-item <?php echo $currentPage === 'pengaturan.php' ? 'active' : ''; ?>">
                <i class="bi bi-gear"></i>
                <span class="menu-item-text">Pengaturan</span>
            </a>
            <a href="logout.php" class="menu-item">
                <i class="bi bi-box-arrow-right"></i>
                <span class="menu-item-text">Logout</span>
            </a>
        </div>
    </nav>
</aside>

<script>
    // Toggle Sidebar Mobile
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.querySelector('.mobile-menu-toggle');
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = toggle && toggle.contains(event.target);
        
        if (window.innerWidth <= 992 && !isClickInsideSidebar && !isClickOnToggle) {
            sidebar.classList.remove('show');
        }
    });
</script>

