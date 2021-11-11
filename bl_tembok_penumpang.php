<?php
ob_start();
if(!isset($_SESSION['bl_user'])){
    session_start();
}
if(!isset($_SESSION['bl_user'])){
    header("location:../bl_error_404.php?level=Lowadmin");
}else{
    $bl_level = $_SESSION['bl_level'];
    if(!$bl_level=="lowadmin"){
        header("location:../bl_error_404.php?level=Lowadmin");
    }
}
ob_flush();
?>