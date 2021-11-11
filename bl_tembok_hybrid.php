<?php
ob_start();
if(!isset($_SESSION['bl_user'])){
    session_start();
}
if(!isset($_SESSION['bl_user'])){
    header("location:../bl_error_404.php?level=Admin");
}else{
    $bl_level = $_SESSION['bl_level'];
    if(!($bl_level=="admin" or $bl_level=="superadmin" or $bl_level=="lowadmin")){
        header("location:../bl_error_404.php?level=admin");
    }
}
ob_flush();
?>