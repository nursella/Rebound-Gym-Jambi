<?php
require_once '../config/config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false]); exit(); }

$id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if($role == 'admin') {
    $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM notifikasi WHERE status='belum'"))['total'];
} else {
    $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM notifikasi n JOIN transaksi t ON n.id_transaksi=t.id_transaksi WHERE t.id_member='$id' AND n.status='belum'"))['total'];
}

echo json_encode(['success'=>true, 'count'=>$count]);
?>