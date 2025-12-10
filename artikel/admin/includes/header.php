<?php
/**
 * Header Admin - Modern Design
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tanggal Indonesia
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');

$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!-- Header -->
<header class="content-header">
    <div class="header-left">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        <div class="header-info">
            <span><i class="bi bi-calendar3 me-1"></i><?php echo $tanggal; ?></span>
            <span class="status-dot"></span>
            <span>Online</span>
        </div>
    </div>
    <div class="header-right">
        <a href="tulis-berita.php" class="btn-tulis-berita">
            <i class="bi bi-pencil"></i>
            Tulis Artikel
        </a>
    </div>
</header>
