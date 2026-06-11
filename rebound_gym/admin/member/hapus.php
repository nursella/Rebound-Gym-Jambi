<?php
require_once '../../config/config.php';
checkAdmin();
$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM member WHERE id_member='$id'");
redirect('member/', 'Member berhasil dihapus!', 'success');
?>