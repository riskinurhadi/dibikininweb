<?php
/**
 * File Koneksi Database
 * Pastikan database sudah dibuat sebelum menggunakan
 */

// Konfigurasi Database
$host = 'localhost';
$dbname = 'dibikininweb'; 
$username = 'dibikininweb';
$password = 'Aloevera21.';

// Inisialisasi $pdo sebagai null
$pdo = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Set $pdo ke null jika koneksi gagal
    $pdo = null;
    // Log error
    error_log("Koneksi database gagal: " . $e->getMessage());
    // Untuk development, uncomment baris di bawah untuk melihat error
    // die("Koneksi database gagal: " . $e->getMessage());
}
?>

