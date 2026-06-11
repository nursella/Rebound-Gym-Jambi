<?php
session_start();
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$nomor_pesanan = $_POST['nomor_pesanan'] ?? '';
$jumlah = $_POST['jumlah'] ?? 0;
$paket_id = $_POST['paket_id'] ?? 0;
$member_id = $_SESSION['user_id'];
$metode = isset($_POST['metode']) ? $_POST['metode'] : 'transfer_bank';

if (empty($nomor_pesanan) || $jumlah <= 0 || $paket_id <= 0) {
    $_SESSION['error'] = "Data pembayaran tidak valid!";
    header("Location: index.php");
    exit;
}

try {
    mysqli_begin_transaction($conn);

    // 1. Ambil data paket
    $query_paket = "SELECT * FROM paket WHERE id_paket = ?";
    $stmt_paket = mysqli_prepare($conn, $query_paket);
    mysqli_stmt_bind_param($stmt_paket, "i", $paket_id);
    mysqli_stmt_execute($stmt_paket);
    $result_paket = mysqli_stmt_get_result($stmt_paket);
    $paket = mysqli_fetch_assoc($result_paket);

    if (!$paket) {
        throw new Exception("Paket tidak ditemukan!");
    }

    // 2. Hitung tanggal
    $tanggal_mulai = date('Y-m-d');
    $durasi = $paket['durasi_hari'];
    $tanggal_expired = date('Y-m-d', strtotime("+$durasi days"));

    // 3. Insert transaksi
    $query_transaksi = "INSERT INTO transaksi (
        id_member, 
        id_paket, 
        tanggal_mulai, 
        tanggal_expired, 
        total_harga, 
        tanggal_bayar, 
        metode_pembayaran, 
        status_pembayaran, 
        status_membership
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'lunas', 'aktif')";

    $stmt_transaksi = mysqli_prepare($conn, $query_transaksi);
    mysqli_stmt_bind_param(
        $stmt_transaksi, 
        "iissdss", 
        $member_id, 
        $paket_id, 
        $tanggal_mulai, 
        $tanggal_expired, 
        $jumlah, 
        $tanggal_mulai, 
        $metode
    );
    
    if (!mysqli_stmt_execute($stmt_transaksi)) {
        throw new Exception("Gagal menyimpan transaksi: " . mysqli_error($conn));
    }

    // 4. Update status member
    $query_update_member = "UPDATE member SET status_aktif = 'aktif' WHERE id_member = ?";
    $stmt_member = mysqli_prepare($conn, $query_update_member);
    mysqli_stmt_bind_param($stmt_member, "i", $member_id);
    mysqli_stmt_execute($stmt_member);

    // Commit
    mysqli_commit($conn);

    // Hapus session
    unset($_SESSION['pembayaran']);

    // Redirect
    header("Location: sukses.php?order=" . urlencode($nomor_pesanan));
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['error'] = "Pembayaran gagal: " . $e->getMessage();
    header("Location: index.php");
    exit;
}
?>