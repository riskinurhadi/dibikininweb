<?php
/**
 * Test Database Connection & Data
 */
require_once __DIR__ . '/../../koneksi.php';

echo "<h2>Test Database Connection</h2>";

if ($pdo) {
    echo "<p style='color: green;'>✓ Koneksi database berhasil</p>";
    
    try {
        // Test query total artikel
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel");
        $row = $stmt->fetch();
        $total = isset($row['total']) ? (int)$row['total'] : 0;
        echo "<p><strong>Total Artikel:</strong> {$total}</p>";
        
        // Test query artikel detail
        $stmt = $pdo->query("SELECT id, judul, status, dilihat FROM artikel LIMIT 10");
        $articles = $stmt->fetchAll();
        
        echo "<p><strong>Artikel yang ditemukan:</strong> " . count($articles) . "</p>";
        
        if (count($articles) > 0) {
            echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Judul</th><th>Status</th><th>Dilihat</th></tr>";
            foreach($articles as $a) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($a['id']) . "</td>";
                echo "<td>" . htmlspecialchars($a['judul']) . "</td>";
                echo "<td>" . htmlspecialchars($a['status']) . "</td>";
                echo "<td>" . htmlspecialchars($a['dilihat'] ?? 0) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠ Tidak ada artikel di database</p>";
        }
        
        // Test query published
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel WHERE status = 'published'");
        $row = $stmt->fetch();
        $published = isset($row['total']) ? (int)$row['total'] : 0;
        echo "<p><strong>Artikel Published:</strong> {$published}</p>";
        
        // Test query draft
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM artikel WHERE status = 'draft'");
        $row = $stmt->fetch();
        $draft = isset($row['total']) ? (int)$row['total'] : 0;
        echo "<p><strong>Artikel Draft:</strong> {$draft}</p>";
        
        // Test query total views
        $stmt = $pdo->query("SELECT COALESCE(SUM(dilihat), 0) as total FROM artikel");
        $row = $stmt->fetch();
        $views = isset($row['total']) ? (int)$row['total'] : 0;
        echo "<p><strong>Total Views:</strong> {$views}</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Koneksi database gagal</p>";
}
?>

