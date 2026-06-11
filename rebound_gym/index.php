<?php
require_once 'config/config.php';
if(isset($_SESSION['user_id'])){
    if($_SESSION['role']=='admin') header("Location: admin/dashboard.php");
    else header("Location: member/dashboard.php");
} else {
    header("Location: login.php");
}
exit();
?>