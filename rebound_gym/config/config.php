<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rebound_gym_jambi');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, 'utf8mb4');

// Base URL - Sesuaikan dengan folder project Anda
define('BASE_URL', 'http://localhost/rebound_gym/');

// Function untuk format rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Function untuk format tanggal Indonesia
function formatTanggal($tanggal) {
    if (empty($tanggal) || $tanggal == '0000-00-00') return '-';
    
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Function untuk escape string (PENTING!)
function escape($str) {
    global $conn;
    return mysqli_real_escape_string($conn, $str);
}

// Function untuk cek login member
function checkLogin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member') {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

// Function untuk cek login admin
function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

// Function untuk redirect dengan pesan
function redirect($url, $message = '', $type = 'success') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: " . BASE_URL . $url);
    exit();
}

// Function untuk get flash message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// Function generate notifikasi otomatis
function generateNotifikasiOtomatis() {
    global $conn;
    $today = date('Y-m-d');
    
    // Notifikasi H-7
    $query_h7 = "SELECT t.*, m.nama, m.nomor_whatsapp, m.email, p.nama_paket 
                 FROM transaksi t
                 JOIN member m ON t.id_member = m.id_member
                 JOIN paket p ON t.id_paket = p.id_paket
                 WHERE t.status_membership = 'aktif' 
                 AND DATEDIFF(t.tanggal_expired, '$today') = 7";
    $result_h7 = mysqli_query($conn, $query_h7);
    
    while ($row = mysqli_fetch_assoc($result_h7)) {
        $pesan = "Halo {$row['nama']}! 🏋️‍♂️\n\n" .
                 "Membership {$row['nama_paket']} Anda akan berakhir pada " . 
                 formatTanggal($row['tanggal_expired']) . " (7 hari lagi).\n\n" .
                 "Jangan lewatkan kesempatan untuk tetap konsisten!\n\n" .
                 "Cara Perpanjangan:\n" .
                 "1. Login ke akun member Anda\n" .
                 "2. Klik tombol 'Perpanjang Sekarang'\n" .
                 "3. Pilih paket yang diinginkan\n" .
                 "4. Lakukan pembayaran\n\n" .
                 "Terima kasih telah menjadi bagian dari Rebound Gym Jambi! 💪";
        
        $cek = mysqli_query($conn, "SELECT * FROM notifikasi WHERE id_transaksi = '{$row['id_transaksi']}' AND jenis = 'h-7'");
        if (mysqli_num_rows($cek) == 0) {
            $query_insert = "INSERT INTO notifikasi (id_transaksi, jenis, pesan, tanggal_kirim, status) 
                          VALUES ('{$row['id_transaksi']}', 'h-7', '" . escape($pesan) . "', NOW(), 'terkirim')";
            mysqli_query($conn, $query_insert);
        }
    }
    
    // Notifikasi H-3
    $query_h3 = "SELECT t.*, m.nama, m.nomor_whatsapp, m.email, p.nama_paket 
                 FROM transaksi t
                 JOIN member m ON t.id_member = m.id_member
                 JOIN paket p ON t.id_paket = p.id_paket
                 WHERE t.status_membership = 'aktif' 
                 AND DATEDIFF(t.tanggal_expired, '$today') = 3";
    $result_h3 = mysqli_query($conn, $query_h3);
    
    while ($row = mysqli_fetch_assoc($result_h3)) {
        $pesan = "URGENT! ⚠️ {$row['nama']}\n\n" .
                 "Membership {$row['nama_paket']} Anda akan berakhir dalam 3 hari lagi!\n" .
                 "Tanggal Expired: " . formatTanggal($row['tanggal_expired']) . "\n\n" .
                 "Segera perpanjang agar tidak kehilangan akses ke fasilitas gym.\n\n" .
                 "Perpanjang Sekarang:\n" .
                 "- Login ke website\n" .
                 "- Klik menu Riwayat Membership\n" .
                 "- Pilih 'Perpanjang Membership'\n\n" .
                 "Jangan sampai terlewat! 🔥";
        
        $cek = mysqli_query($conn, "SELECT * FROM notifikasi WHERE id_transaksi = '{$row['id_transaksi']}' AND jenis = 'h-3'");
        if (mysqli_num_rows($cek) == 0) {
            $query_insert = "INSERT INTO notifikasi (id_transaksi, jenis, pesan, tanggal_kirim, status) 
                          VALUES ('{$row['id_transaksi']}', 'h-3', '" . escape($pesan) . "', NOW(), 'terkirim')";
            mysqli_query($conn, $query_insert);
        }
    }
    
    // Update status expired
    mysqli_query($conn, "UPDATE transaksi SET status_membership = 'expired' WHERE status_membership = 'aktif' AND tanggal_expired < '$today'");
    mysqli_query($conn, "UPDATE member m JOIN transaksi t ON m.id_member = t.id_member SET m.status_aktif = 'expired' WHERE t.status_membership = 'expired' AND m.status_aktif = 'aktif'");
}

// Jalankan generate notifikasi
generateNotifikasiOtomatis();
?>