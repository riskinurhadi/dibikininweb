<?php
// File koneksi database
// Sesuaikan dengan konfigurasi database Anda

$host = 'localhost';
$dbname = 'dibikininweb_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Jika koneksi gagal, tetap lanjutkan tanpa database
    // Hapus atau sesuaikan sesuai kebutuhan
    error_log("Koneksi database gagal: " . $e->getMessage());
}
?>

