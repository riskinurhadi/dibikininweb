<?php
/**
 * Header Admin - External Component
 * Header untuk semua halaman admin
 */

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tanggal Indonesia
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');

// Tentukan judul halaman
$pageTitle = $pageTitle ?? 'Dashboard Redaksi';
?>
<!-- Header -->
<header class="content-header">
    <div class="header-left">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        <div class="header-info">
            <span><?php echo $tanggal; ?></span>
            <span class="status-dot"></span>
            <span>Live Server</span>
        </div>
    </div>
    <div class="header-right">
        <button class="notification-btn">
            <i class="bi bi-bell"></i>
            <span class="notification-dot"></span>
        </button>
        <a href="tulis-berita.php" class="btn-tulis-berita">
            <i class="bi bi-pencil"></i>
            Tulis Berita
        </a>
    </div>
</header>

