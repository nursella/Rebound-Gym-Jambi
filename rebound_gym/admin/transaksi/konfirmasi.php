<?php
require_once '../../config/config.php';
checkAdmin();

$id = $_GET['id'];

// Update status pembayaran
mysqli_query($conn, "UPDATE transaksi SET status_pembayaran = 'lunas', tanggal_bayar = CURDATE() WHERE id_transaksi = '$id'");

// Update status member dan membership
$transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id'"));
mysqli_query($conn, "UPDATE member SET status_aktif = 'aktif' WHERE id_member = '{$transaksi['id_member']}'");
mysqli_query($conn, "UPDATE transaksi SET status_membership = 'aktif' WHERE id_transaksi = '$id'");

$_SESSION['success'] = "Pembayaran berhasil dikonfirmasi!";
header("Location: index.php");
exit();
?>