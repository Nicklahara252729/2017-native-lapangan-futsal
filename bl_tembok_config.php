<?php
ob_start();
if(!isset($_SESSION['bl_user'])){
    session_start();
}
if(!isset($_SESSION['bl_user'])){
    header("location:../bl_error_404.php?level=Superadmin");
}else{
    $bl_level = $_SESSION['bl_level'];
    if(!($bl_level=="superadmin")){
        header("location:../bl_error_404.php?level=Superadmin");
    }
}
ob_flush();
?>