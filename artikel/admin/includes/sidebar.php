<?php
/**
 * Sidebar Admin - Modern Design
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil statistik
$stats = [
    'semua_berita' => 0,
    'komentar' => 0
];

if (isset($pdo) && $pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel");
        $result = $stmt->fetch();
        $stats['semua_berita'] = $result['total'] ?? 0;
        $stats['komentar'] = 0; // Simulasi
    } catch (Exception $e) {
        // Ignore
    }
}

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
        <span class="sidebar-logo-text">Admin Panel</span>
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
                <span class="menu-item-text">Semua Artikel</span>
                <?php if ($stats['semua_berita'] > 0): ?>
                    <span class="badge-menu"><?php echo $stats['semua_berita']; ?></span>
                <?php endif; ?>
            </a>
            <a href="tulis-berita.php" class="menu-item <?php echo $currentPage === 'tulis-berita.php' ? 'active' : ''; ?>">
                <i class="bi bi-pencil-square"></i>
                <span class="menu-item-text">Tulis Artikel</span>
            </a>
        </div>
        
        <div class="menu-section">
            <div class="menu-section-title">AKSI CEPAT</div>
            <a href="../index.php" target="_blank" class="menu-item">
                <i class="bi bi-eye"></i>
                <span class="menu-item-text">Lihat Website</span>
            </a>
            <a href="logout.php" class="menu-item">
                <i class="bi bi-box-arrow-right"></i>
                <span class="menu-item-text">Logout</span>
            </a>
        </div>
    </nav>
</aside>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
    
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
