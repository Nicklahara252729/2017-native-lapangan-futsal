<?php
ob_start();
if(!isset($_SESSION['bl_user'])){
    session_start();
}
include"../bl_kabelutp.php";
include"../bl_tembok_penumpang.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Member</title>
    </head>
    <body>
        member <a href="../bl_cabut.php">LOGOUT</a>
    </body>
</html>
<?php
mysql_close($bl_koneksi);
ob_flush();
?>