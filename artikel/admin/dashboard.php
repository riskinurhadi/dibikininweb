<?php
/**
 * Dashboard Admin Redaksi
 * Dashboard untuk manage artikel/berita
 */

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../database/ArticleModel.php';

// Inisialisasi
$stats = [
    'total_tayangan_hari_ini' => 0,
    'artikel_terbit_minggu_ini' => 0,
    'draft_review' => 0,
    'social_shares' => 0,
    'semua_berita' => 0,
    'komentar' => 0
];

if ($pdo) {
    try {
        $articleModel = new ArticleModel($pdo);
        
        // Total tayangan hari ini
        $stmt = $pdo->query("SELECT SUM(dilihat) as total FROM artikel WHERE DATE(updated_at) = CURDATE()");
        $result = $stmt->fetch();
        $stats['total_tayangan_hari_ini'] = $result['total'] ?? 0;
        
        // Artikel terbit minggu ini
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel WHERE status = 'published' AND YEARWEEK(published_at) = YEARWEEK(CURDATE())");
        $result = $stmt->fetch();
        $stats['artikel_terbit_minggu_ini'] = $result['total'] ?? 0;
        
        // Draft / Menunggu Review
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel WHERE status = 'draft'");
        $result = $stmt->fetch();
        $stats['draft_review'] = $result['total'] ?? 0;
        
        // Social shares (simulasi)
        $stats['social_shares'] = rand(5000, 10000);
        
        // Semua berita
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel");
        $result = $stmt->fetch();
        $stats['semua_berita'] = $result['total'] ?? 0;
        
        // Komentar (simulasi - karena belum ada tabel komentar)
        $stats['komentar'] = rand(0, 10);
        
        // Data grafik (last 24 hours - simulasi)
        $chartData = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = date('H:i', strtotime("-$i hours"));
            $chartData[] = [
                'time' => $hour,
                'value' => rand(1000, 10000)
            ];
        }
        
    } catch (Exception $e) {
        $chartData = [];
    }
} else {
    $chartData = [];
}

// Format angka
function formatNumber($number) {
    if ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    }
    return number_format($number);
}

// Trending topics (simulasi)
$trendingTopics = [
    ['name' => '#TimnasDay', 'count' => 12500, 'trend' => 'up'],
    ['name' => '#Pilpres2024', 'count' => 8200, 'trend' => 'up'],
    ['name' => '#CryptoCrash', 'count' => 5100, 'trend' => 'down']
];

// Tanggal Indonesia
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Redaksi | dibikininweb</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Include Styles -->
    <?php include 'includes/styles.php'; ?>
    
    <style>
        :root {
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s;
        }
        
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar-logo i {
            font-size: 28px;
            color: #ef4444;
        }
        
        .sidebar-logo-text {
            font-size: 20px;
            font-weight: 700;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-section {
            margin-bottom: 30px;
        }
        
        .menu-section-title {
            padding: 0 20px;
            margin-bottom: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }
        
        .menu-item:hover {
            background: var(--sidebar-hover);
            color: white;
        }
        
        .menu-item.active {
            background: var(--sidebar-active);
            color: white;
        }
        
        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #ef4444;
        }
        
        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .menu-item-text {
            flex: 1;
        }
        
        .badge-menu {
            background: #ef4444;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }
        
        /* Header */
        .content-header {
            background: white;
            padding: 24px 32px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        
        .header-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 8px;
        }
        
        .notification-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
        }
        
        .btn-tulis-berita {
            background: #ef4444;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .btn-tulis-berita:hover {
            background: #dc2626;
            color: white;
        }
        
        /* Content Body */
        .content-body {
            padding: 32px;
        }
        
        /* Stat Cards */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .stat-icon.blue { background: #dbeafe; color: #1e40af; }
        .stat-icon.green { background: #d1fae5; color: #065f46; }
        .stat-icon.yellow { background: #fef3c7; color: #92400e; }
        .stat-icon.light-blue { background: #e0f2fe; color: #0c4a6e; }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
        }
        
        /* Chart Section */
        .chart-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .chart-select {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 14px;
            background: white;
            color: var(--text-primary);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        /* Trending Section */
        .trending-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .trending-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .trending-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .trending-list {
            list-style: none;
            padding: 0;
        }
        
        .trending-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .trending-item:last-child {
            border-bottom: none;
        }
        
        .trending-item-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .trending-number {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-secondary);
            min-width: 24px;
        }
        
        .trending-name {
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .trending-count {
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .trend-arrow {
            font-size: 16px;
            margin-left: 8px;
        }
        
        .trend-arrow.up {
            color: #10b981;
        }
        
        .trend-arrow.down {
            color: #ef4444;
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--sidebar-bg);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 20px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .content-header {
                padding: 20px;
            }
            
            .content-body {
                padding: 20px;
            }
            
            .stat-cards {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
        
        @media (max-width: 576px) {
            .stat-cards {
                grid-template-columns: 1fr;
            }
            
            .header-left h1 {
                font-size: 24px;
            }
            
            .stat-value {
                font-size: 24px;
            }
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
            <!-- Stat Cards -->
            <div class="stat-cards">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value"><?php echo formatNumber($stats['total_tayangan_hari_ini']); ?></div>
                            <div class="stat-label">Total Tayangan (Hari ini)</div>
                        </div>
                        <div class="stat-icon blue">
                            <i class="bi bi-eye"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value"><?php echo $stats['artikel_terbit_minggu_ini']; ?></div>
                            <div class="stat-label">Artikel Terbit (Minggu ini)</div>
                        </div>
                        <div class="stat-icon green">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value"><?php echo $stats['draft_review']; ?></div>
                            <div class="stat-label">Draft / Menunggu Review</div>
                        </div>
                        <div class="stat-icon yellow">
                            <i class="bi bi-pencil"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value"><?php echo formatNumber($stats['social_shares']); ?></div>
                            <div class="stat-label">Social Shares</div>
                        </div>
                        <div class="stat-icon light-blue">
                            <i class="bi bi-share"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chart Section -->
            <div class="chart-section">
                <div class="chart-header">
                    <h2 class="chart-title">Statistik Pembaca (Realtime)</h2>
                    <select class="chart-select">
                        <option>24 Jam Terakhir</option>
                        <option>7 Hari Terakhir</option>
                        <option>30 Hari Terakhir</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="readerChart"></canvas>
                </div>
            </div>
            
            <!-- Trending Topics -->
            <div class="trending-section">
                <div class="trending-header">
                    <h2 class="trending-title">Trending Topik</h2>
                    <i class="bi bi-fire text-danger"></i>
                </div>
                <ul class="trending-list">
                    <?php foreach ($trendingTopics as $index => $topic): ?>
                    <li class="trending-item">
                        <div class="trending-item-left">
                            <span class="trending-number"><?php echo $index + 1; ?>.</span>
                            <div>
                                <div class="trending-name"><?php echo htmlspecialchars($topic['name']); ?></div>
                                <div class="trending-count">
                                    <?php echo formatNumber($topic['count']); ?> Artikel
                                    <i class="bi bi-arrow-<?php echo $topic['trend'] === 'up' ? 'up' : 'down'; ?> trend-arrow <?php echo $topic['trend']; ?>"></i>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Chart.js
        const ctx = document.getElementById('readerChart').getContext('2d');
        const chartData = <?php echo json_encode($chartData); ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.time),
                datasets: [{
                    label: 'Pembaca',
                    data: chartData.map(item => item.value),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000) {
                                    return (value / 1000).toFixed(1) + 'k';
                                }
                                return value;
                            }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
