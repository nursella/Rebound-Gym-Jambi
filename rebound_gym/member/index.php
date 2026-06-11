<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member') {
    header("Location: ../login.php");
    exit;
}

// Redirect ke dashboard
header("Location: dashboard.php");
exit;
?>