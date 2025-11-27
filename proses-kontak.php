<?php
session_start();

// Konfigurasi email
$to_email = "halo@dibikininweb.com"; // Ganti dengan email Anda
$subject = "Pesan Baru dari Form Kontak dibikininweb";

// Ambil data dari form
$nama = isset($_POST['nama']) ? htmlspecialchars(trim($_POST['nama'])) : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$telepon = isset($_POST['telepon']) ? htmlspecialchars(trim($_POST['telepon'])) : '';
$layanan = isset($_POST['layanan']) ? htmlspecialchars(trim($_POST['layanan'])) : '';
$pesan = isset($_POST['pesan']) ? htmlspecialchars(trim($_POST['pesan'])) : '';

// Validasi
$errors = [];

if (empty($nama)) {
    $errors[] = "Nama harus diisi";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email tidak valid";
}

if (empty($layanan)) {
    $errors[] = "Layanan harus dipilih";
}

// Jika ada error
if (!empty($errors)) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Error!',
        'text' => implode(', ', $errors)
    ];
    header('Location: index.php#Kontak');
    exit;
}

// Format pesan email
$email_message = "Pesan Baru dari Form Kontak dibikininweb\n\n";
$email_message .= "Nama: " . $nama . "\n";
$email_message .= "Email: " . $email . "\n";
$email_message .= "Telepon: " . $telepon . "\n";
$email_message .= "Layanan: " . $layanan . "\n";
$email_message .= "Pesan:\n" . $pesan . "\n";

// Headers email
$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Kirim email
$mail_sent = @mail($to_email, $subject, $email_message, $headers);

// Opsional: Simpan ke database jika diperlukan
// Uncomment kode di bawah jika ingin menyimpan ke database
/*
include 'koneksi.php';
if (isset($pdo)) {
    try {
        $stmt = $pdo->prepare("INSERT INTO kontak (nama, email, telepon, layanan, pesan, tanggal) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$nama, $email, $telepon, $layanan, $pesan]);
    } catch(PDOException $e) {
        error_log("Error menyimpan ke database: " . $e->getMessage());
    }
}
*/

// Set session alert
if ($mail_sent) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil!',
        'text' => 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Gagal!',
        'text' => 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi atau hubungi kami langsung.'
    ];
}

// Redirect kembali ke halaman kontak
header('Location: index.php#Kontak');
exit;
?>

