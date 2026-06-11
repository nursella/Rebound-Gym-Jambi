<?php
require_once '../../config/config.php';
checkAdmin();
$id = (int)$_GET['id'];
// Cegah hapus super admin atau diri sendiri
$current_id = $_SESSION['user_id'];
mysqli_query($conn, "DELETE FROM owner WHERE id_owner='$id' AND id_owner != '$current_id' AND role != 'super_admin'");
redirect('admin/', 'Admin berhasil dihapus!', 'success');
?>