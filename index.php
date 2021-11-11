<?php
ob_start();
include"bl_kabelutp.php";
if(!isset($_SESSION['bl_user'])){
    session_start();
}
$bl_text = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$bl_panjang = strlen($bl_text);
$bl_hasil =0;
for($bl_i =1;$bl_i<=10;$bl_i++){
$bl_hasil = trim($bl_hasil).substr($bl_text,mt_rand(0,$bl_panjang),1);
    
if(isset($_POST['bl_rusername'])){
    $bl_riduser = strip_tags(trim($_POST['bl_rid_user']));
    $bl_rnama = strip_tags(trim($_POST['bl_rnama']));
    $bl_rusername = strip_tags(trim($_POST['bl_rusername']));
    $bl_rpassword = sha1(strip_tags(trim($_POST['bl_rpassword'])));
    $bl_remail = strip_tags(trim($_POST['bl_remail']));
    $bl_rsex = strip_tags(trim($_POST['bl_rsex']));
    $bl_ralamat = strip_tags(trim($_POST['bl_ralamat']));
    $bl_rtelp = strip_tags(trim($_POST['bl_rtelp']));
    $bl_rngumpul = strip_tags(trim($_POST['bl_rngumpul']));
    $bl_perintahsql = mysql_query("select * from user where username='$bl_rusername'");
    $bl_jmsql = mysql_num_rows($bl_perintahsql);
    if($bl_jmsql > 0){
        ?>
<script>alert('Username <?php echo $bl_rusername; ?> sudah ada');history.back();</script>
<?php
    }else{
        $bl_selamatkan = mysql_query("insert into user set id_user='$bl_riduser', nama_user='$bl_rnama', username='$bl_rusername', password='$bl_rpassword', email='$bl_remail', level='member', jenkel='$bl_rsex', alamat='$bl_ralamat', no_hp='$bl_rtelp', foto='user.png',ngumpul='$bl_rngumpul', hapus_user='ok'");
    }
}
}

if(isset($_POST['bl_log_username'])){
    $bl_log_username = strip_tags(trim($_POST['bl_log_username']));
    $bl_log_password = strip_tags(trim($_POST['bl_log_password']));
    $bl_log_pass2    = sha1($bl_log_password);
    if(isset($_POST['bl_log_remember'])){
        setcookie("blc_username",$bl_log_username,time() + (3600 * 72));
        setcookie("blc_password",$bl_log_password,time() + (3600 * 72));
    }else{
        unset($_COOKIE['blc_username']);
        unset($_COOKIE['blc_password']);
    }
    $bl_log_sql = mysql_query("select * from user where username='$bl_log_username' and password='$bl_log_pass2'");
    $bl_log_jml = mysql_num_rows($bl_log_sql);
    $bl_log_row = mysql_fetch_array($bl_log_sql);
    if($bl_log_jml > 0){
        $_SESSION['bl_id_user']=$bl_log_row['id_user'];
        $_SESSION['bl_nama']=$bl_log_row['nama_user'];
        $_SESSION['bl_user']=$bl_log_row['username'];
        $_SESSION['bl_pass']=$bl_log_row['password'];
        $_SESSION['bl_email']=$bl_log_row['email'];
        $_SESSION['bl_level']=$bl_log_row['level'];
        $_SESSION['bl_jenkel']=$bl_log_row['jenkel'];
        $_SESSION['bl_alamat']=$bl_log_row['alamat'];
        $_SESSION['bl_telp']=$bl_log_row['no_hp'];
        $_SESSION['bl_foto']=$bl_log_row['foto'];
        $_SESSION['bl_ngumpul']=$bl_log_row['ngumpul'];
        if($bl_log_row['level']=="superadmin"){
            header("location:stang/config.php");
        }else if($bl_log_row['level']=="admin"){
            header("location:stang/index.php");
        }else{
            header("location:stang/low_index.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, height=device-height, user-scalable=no, minimum-scale=1, maximum-scale=1">
    <title>Booking</title>
    <link href="csgo/bootstrap.min.css" rel="stylesheet">
    <link href="csgo/bl_default.css" rel="stylesheet">
    <script>
         function cekpass(){
 var pass = document.getElementById('bl_rpassword').value;
    var pass1 = document.getElementById('bl_rpassword').value;
    var pass2 = document.getElementById('bl_rcpassword').value;
    if(pass.length <8 && pass1!=pass2){
        document.getElementById('bl_psnpass').style.color="red";
        document.getElementById('bl_psnpass').innerHTML="Panjang password minimal 8 karakter";
        document.getElementById('bl_rpassword').focus();
        return false;
    }
             else if(pass.length >=8 && pass2.length<=0){
                 document.getElementById('bl_psnpass').style.color="green";
                 document.getElementById('bl_psnpass').innerHTML="Password bagus";
                 document.getElementById('ok-tiga').style.display="block";
             }
             else if(pass.length >=8 && pass1!=pass2){
                 document.getElementById('bl_psnpass').style.color="red";
                 document.getElementById('bl_psnpass').innerHTML="Password tidak sama";
                 document.getElementById('bl_rcpassword').focus();
                 return false;
             }
             else if(pass.length >=8 && pass1==pass2){
                 document.getElementById('bl_psnpass').style.color="blue";
                 document.getElementById('bl_psnpass').innerHTML="Password diterima";
                 document.getElementById('ok-empat').style.display="block";
             }
}
        function ceknama(){
            var nama = document.getElementById('bl_rnama').value;
            if(nama.length > 0){
                document.getElementById('ok-satu').style.display="block";
            }else{
                document.getElementById('ok-satu').style.display="none";
            }
        }
        function cekuser(){
            var nama = document.getElementById('bl_rusername').value;
            if(nama.length > 0){
                document.getElementById('ok-dua').style.display="block";
            }else{
                document.getElementById('ok-dua').style.display="none";
            }
        }
        function cekemail(){
            var nama = document.getElementById('bl_remail').value;
            if(nama.length > 0){
                document.getElementById('ok-lima').style.display="block";
            }else{
                document.getElementById('ok-lima').style.display="none";
            }
        }
        
        function ceksex(){
            var nama = document.getElementById('bl_remail');
            if(nama.select){
                document.getElementById('ok-enam').style.display="block";
            }else{
                document.getElementById('ok-enam').style.display="none";
            }
        }
        
        function cekalamat(){
            var nama = document.getElementById('bl_ralamat').value;
            if(nama.length > 0){
                document.getElementById('ok-tujuh').style.display="block";
            }else{
                document.getElementById('ok-tujuh').style.display="none";
            }
        }
        
        function ceknotel(){
            var nama = document.getElementById('bl_rtelp').value;
            if(nama.length > 0){
                document.getElementById('ok-lapan').style.display="block";
            }else{
                document.getElementById('ok-lapan').style.display="none";
            }
        }
    </script>
</head>

<body>
    <span class="top"><span class="glyphicon glyphicon-arrow-up"></span></span>
    <div class="register">
        
        <div class="content-login">
            <div class="isi-login" id="login-satu"><div class="img-user-regist"></div>
                <button type="button" class="tutup" onclick="location.href='index.php'"><span class="glyphicon glyphicon-remove"></span></button>
            </div>
            <form target="_self" method="post" enctype="multipart/form-data" name="register" id="register">
                <div class="isi-login" id="login-dua">
                    <input type="hidden" value="<?php echo $bl_hasil;?>" name="bl_rid_user" id="bl_rid_user">
                    <input type="hidden" value="<?php echo date('d'.' '.'M'.' '.'Y');?>" name="bl_rngumpul" id="bl_rngumpul">
                    <div class="inpt">
                            <span class="glyphicon glyphicon-pencil" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="bl_rnama" id="bl_rnama" placeholder="Nama Lengkap" required onblur="ceknama()">            
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-satu">
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-user" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="bl_rusername" id="bl_rusername" placeholder="Username" required onblur="cekuser()">            
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-dua">
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-eye-open" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="password" name="bl_rpassword" id="bl_rpassword" placeholder="Password" required onblur="cekpass();">            
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-tiga">
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-eye-close" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="password" name="bl_rcpassword" id="bl_rcpassword" placeholder="Cofirm Password" required onblur="cekpass()"  onfocus="cekpass()">            
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-empat">
                        </div>
                        <div class="inpt" id="inpt-msg">
                            <div id="bl_psnpass">Panjang password minimal 8 karakter</div><br>
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-envelope" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="email" name="bl_remail" id="bl_remail" placeholder="Email (example@email.com)" required onfocus="cekpass()" onblur="cekemail()">            
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-lima">
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-pawn" id="ico-left"></span> &nbsp;&nbsp;
                            <select name="bl_rsex" id="bl_rsex" onblur="ceksex()" onchange="ceksex()" onfocus="ceksex()" onselect="ceksex()">
                                <option disabled selected> - Pilih Jenis Kelamin -</option>
                                <?php
                                $bl_jenkel = array('L','P');
                                for($bl_i=0;$bl_i<=1;$bl_i++){
                                    echo"<option value='$bl_jenkel[$bl_i]'>$bl_jenkel[$bl_i]</option>";
                                }
                                ?>
                        </select>
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-enam">
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-road" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="bl_ralamat" id="bl_ralamat" placeholder="Alamat" required onblur="cekalamat()" onfocus="cekalamat()">            
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-tujuh">
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-phone" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="bl_rtelp" id="bl_rtelp" placeholder="No Telp" required onblur="ceknotel()" onfocus="ceknotel()">            
                            &nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" id="ok-lapan">
                        </div>
                   
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span> &nbsp; Kirim</button> <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-erase"></span> &nbsp; Clear</button>
                    </div>
                </div>
            </form>
        </div>
    <div class="login">
        <div class="content-login">
            <div class="isi-login" id="login-satu"><div class="img-user-regist"></div>
                <button type="button" class="tutup" onclick="location.href='index.php'"><span class="glyphicon glyphicon-remove"></span></button>
            </div>
            <form target="_self" method="post" enctype="multipart/form-data" name="register" id="register">
                <div class="isi-login" id="login-dua">
                    <input type="text" name="bl_log_username" id="bl_log_username" value="<?php echo isset($_COOKIE['blc_username'])?$_COOKIE['blc_username']:''; ?>" placeholder="Username" required>
                    <input type="password" name="bl_log_password" id="bl_log_password" value="<?php echo isset($_COOKIE['blc_password'])?$_COOKIE['blc_username']:''; ?>" placeholder="Password" required><br>
                   <input type="checkbox" name="bl_log_remember"> Remember Me<br>                
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp; Login</button> <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-erase"></span> &nbsp; Clear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Booking</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#">Lapangan</a>
                    </li>
                    <li>
                        <a href="#">Berita</a>
                    </li>

                    <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Join Us <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li class="btn-register"><span class="glyphicon glyphicon-pencil"></span> &nbsp; Register</li>
                    <li class="btn-login"><span class="glyphicon glyphicon-log-in"></span> &nbsp; Login</li>
                  </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">

        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>TEST</h1>
                <p class="lead">Test</p>
                <ul class="list-unstyled">
                    <li>Booking Lapangan</li>
                    <li>Booking Lapangan</li>
                </ul>
            </div>
        </div>
    </div>
    <script src="jackson/jquery.js"></script>
    <script src="jackson/bootstrap.min.js"></script>
    <script src="jackson/bl_default.js"></script>
</body>
</html>
    <?php
    ob_flush();
    ?>
