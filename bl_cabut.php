<?php
ob_start();
if(!isset($_SESSION['bl_user'])){
    session_start();
}
$bl_level = $_SESSION['bl_level'];
unset($_SESSION['bl_id_user']);
unset($_SESSION['bl_nama']);
unset($_SESSION['bl_user']);
unset($_SESSION['bl_pass']);
unset($_SESSION['bl_email']);
unset($_SESSION['bl_level']);
unset($_SESSION['bl_jenkel']);
unset($_SESSION['bl_alamat']);
unset($_SESSION['bl_telp']);
unset($_SESSION['bl_foto']);
unset($_SESSION['bl_ngumpul']);
session_destroy();
header("location:index.php");
ob_flush();
?>
