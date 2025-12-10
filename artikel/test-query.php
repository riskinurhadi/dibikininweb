<?php
/**
 * Test Query Artikel Published
 */
require_once __DIR__ . '/../koneksi.php';

echo "<h2>Test Query Artikel Published</h2>";

if ($pdo) {
    echo "<p style='color: green;'>✓ Koneksi database berhasil</p>";
    
    try {
        // Test 1: Semua artikel
        echo "<h3>1. Semua Artikel di Database:</h3>";
        $stmt = $pdo->query("SELECT id, judul, status, published_at, created_at FROM artikel ORDER BY id DESC");
        $allArticles = $stmt->fetchAll();
        
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Judul</th><th>Status</th><th>Published At</th><th>Created At</th></tr>";
        foreach($allArticles as $a) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($a['id']) . "</td>";
            echo "<td>" . htmlspecialchars($a['judul']) . "</td>";
            echo "<td>" . htmlspecialchars($a['status']) . "</td>";
            echo "<td>" . ($a['published_at'] ? htmlspecialchars($a['published_at']) : 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($a['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test 2: Query dengan kondisi published
        echo "<h3>2. Query dengan kondisi: status = 'published' AND (published_at IS NULL OR published_at <= NOW()):</h3>";
        $baseWhere = "WHERE status = 'published' AND (published_at IS NULL OR published_at <= NOW())";
        $sql = "SELECT id, judul, status, published_at, created_at FROM artikel $baseWhere ORDER BY published_at DESC, created_at DESC";
        $stmt = $pdo->query($sql);
        $publishedArticles = $stmt->fetchAll();
        
        echo "<p><strong>Jumlah artikel yang ditemukan:</strong> " . count($publishedArticles) . "</p>";
        
        if (count($publishedArticles) > 0) {
            echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Judul</th><th>Status</th><th>Published At</th><th>Created At</th></tr>";
            foreach($publishedArticles as $a) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($a['id']) . "</td>";
                echo "<td>" . htmlspecialchars($a['judul']) . "</td>";
                echo "<td>" . htmlspecialchars($a['status']) . "</td>";
                echo "<td>" . ($a['published_at'] ? htmlspecialchars($a['published_at']) : 'NULL') . "</td>";
                echo "<td>" . htmlspecialchars($a['created_at']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>⚠ Tidak ada artikel yang ditemukan dengan query ini!</p>";
        }
        
        // Test 3: Query hanya status published
        echo "<h3>3. Query hanya status = 'published' (tanpa kondisi published_at):</h3>";
        $sql = "SELECT id, judul, status, published_at, created_at FROM artikel WHERE status = 'published' ORDER BY published_at DESC, created_at DESC";
        $stmt = $pdo->query($sql);
        $publishedOnly = $stmt->fetchAll();
        
        echo "<p><strong>Jumlah artikel yang ditemukan:</strong> " . count($publishedOnly) . "</p>";
        
        if (count($publishedOnly) > 0) {
            echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Judul</th><th>Status</th><th>Published At</th><th>Created At</th></tr>";
            foreach($publishedOnly as $a) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($a['id']) . "</td>";
                echo "<td>" . htmlspecialchars($a['judul']) . "</td>";
                echo "<td>" . htmlspecialchars($a['status']) . "</td>";
                echo "<td>" . ($a['published_at'] ? htmlspecialchars($a['published_at']) : 'NULL') . "</td>";
                echo "<td>" . htmlspecialchars($a['created_at']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Test 4: Cek waktu server
        echo "<h3>4. Waktu Server:</h3>";
        echo "<p>NOW() dari MySQL: ";
        $stmt = $pdo->query("SELECT NOW() as now_time");
        $now = $stmt->fetch();
        echo htmlspecialchars($now['now_time']) . "</p>";
        echo "<p>PHP date('Y-m-d H:i:s'): " . date('Y-m-d H:i:s') . "</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Koneksi database gagal</p>";
}
?>

