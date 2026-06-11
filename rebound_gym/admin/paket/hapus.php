<?php
require_once '../../config/config.php';
checkAdmin();

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM paket WHERE id_paket = '$id'");

header("Location: index.php");
exit();
?>