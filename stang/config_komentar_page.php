<?php
ob_start();
if(!isset($_SESSION['bl_user'])){
    session_start();
}
include"../bl_kabelutp.php";
include"../bl_tembok_config.php";
$blad_id        = $_SESSION['bl_id_user'];
$blad_nama      = $_SESSION['bl_nama'];
$blad_user      = $_SESSION['bl_user'];
$blad_pass      = $_SESSION['bl_pass'];
$blad_email     = $_SESSION['bl_email'];
$blad_level     = $_SESSION['bl_level'];
$blad_jenkel    = $_SESSION['bl_jenkel'];
$blad_alamat    = $_SESSION['bl_alamat'];
$blad_telp      = $_SESSION['bl_telp'];
$blad_foto      = $_SESSION['bl_foto'];
$blad_ngumpul   = $_SESSION['bl_ngumpul'];

$bl_text = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$bl_panjang = strlen($bl_text);
$bl_hasil =0;
for($bl_i =1;$bl_i<=12;$bl_i++){
$bl_hasil = trim($bl_hasil).substr($bl_text,mt_rand(0,$bl_panjang),1);
}

$bl_text_koment = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$bl_panjang_koment = strlen($bl_text);
$bl_hasil_koment =0;
for($bl_i_koment =1;$bl_i_koment<=5;$bl_i_koment++){
$bl_hasil_koment = trim($bl_hasil_koment).substr($bl_text_koment,mt_rand(0,$bl_panjang_koment),1);
}

$blad_limit =100;
if(!isset($_GET['blad_halaman'])){
    $blad_halaman =1;
    $blad_posisi = 0;
}else{
    $blad_halaman = $_GET['blad_halaman'];
    $blad_posisi = ($blad_halaman-1) * $blad_limit;
}

if(isset($_GET['blad_cari'])){
    $blad_keyword = $_GET['blad_cari'];
    $blad_sql_cari = mysql_query("select * from lapangan where id_lapangan like '%$blad_keyword%' or nama_lapangan like '%$blad_keyword%' or alamat_lapangan like '%$blad_keyword%' order by nama_lapangan desc limit $blad_posisi,$blad_limit");
}else{
    $blad_sql_cari = mysql_query("select * from lapangan order by nama_lapangan desc limit $blad_posisi,$blad_limit");
}
$blad_sql= mysql_query("select * from lapangan");
$blad_fetch = mysql_fetch_array($blad_sql);

if(isset($_GET['blad_hps_all'])){
    $blad_hps_all = $_GET['blad_hps_all'];
    $blad_query_get = mysql_query("select * from lapangan where hapus_lapangan='$blad_hps_all'");    
    while($blad_get_file = mysql_fetch_array($blad_query_get)){
		$blad_sql_hps_all = mysql_query("delete from lapangan where id_lapangan='$blad_get_file[id_lapangan]'");
		if($blad_sql_hps_all){
            unlink("../davinci/lapangan/".$blad_get_file['foto_lapangan_1']);
            unlink("../davinci/lapangan/".$blad_get_file['foto_lapangan_2']);
            unlink("../davinci/lapangan/".$blad_get_file['foto_lapangan_3']);
            unlink("../davinci/lapangan/".$blad_get_file['foto_lapangan_4']);
            unlink("../bergerak/".$blad_get_file['video']);
            header("location:index.php");
		}
	}
    
}

if(isset($_GET['blad_id_lap_hps'])){
    $blad_id_lap_hps = $_GET['blad_id_lap_hps'];
    $blad_query_hps = mysql_query("select * from lapangan where id_lapangan='$blad_id_lap_hps'");
    $blad_ambil_gambar = mysql_fetch_array($blad_query_hps);
    $blad_hps_gambar1 = $blad_ambil_gambar['foto_lapangan_1'];
    $blad_hps_gambar2 = $blad_ambil_gambar['foto_lapangan_2'];
    $blad_hps_gambar3 = $blad_ambil_gambar['foto_lapangan_3'];
    $blad_hps_gambar4 = $blad_ambil_gambar['foto_lapangan_4'];
    $blad_hps_video   = $blad_ambil_gambar['video'];
    $blad_sql_id_lap_hps = mysql_query("delete from lapangan where id_lapangan='$blad_id_lap_hps'");
    if($blad_sql_id_lap_hps){
        unlink("../davinci/lapangan/".$blad_hps_gambar1);
        unlink("../davinci/lapangan/".$blad_hps_gambar2);
        unlink("../davinci/lapangan/".$blad_hps_gambar3);
        unlink("../davinci/lapangan/".$blad_hps_gambar4);
        unlink("../bergerak/".$blad_hps_video);
        header("location:index.php");
    }else{
        ?>
<script>alert('Kesalahan saat menghapus data');</script>
<?php
    }
}

if(isset($_POST['blad_id_lapangan'])){
    $blad_id_lapangan       = strip_tags(trim($_POST['blad_id_lapangan']));
    $blad_kode_komentar     = strip_tags(trim($_POST['blad_kode_komentar']));
    $blad_nama_lapangan     = strip_tags(trim($_POST['blad_nama_lapangan']));
    $blad_alamat_lapangan   = strip_tags(trim($_POST['blad_alamat_lapangan']));
    $blad_kota              = strip_tags(trim($_POST['blad_kota']));
    $blad_kecamatan         = strip_tags(trim($_POST['blad_kecamatan']));
    $blad_kelurahan         = strip_tags(trim($_POST['blad_kelurahan']));
    $blad_deskripsi         = strip_tags(trim($_POST['blad_deskripsi']));
    $blad_kode_komentar     = strip_tags(trim($_POST['blad_kode_komentar']));
    $blad_id_user           = strip_tags(trim($blad_id));
    $blad_harga             = strip_tags(trim($_POST['blad_harga']));
    $blad_telp_lapangan     = strip_tags(trim($_POST['blad_telp_lapangan']));
    $blad_foto_lapangan1    = $_FILES['blad_foto_lapangan1']['name']?$_FILES['blad_foto_lapangan1']['name']:"kosong";
    $blad_foto_lap1_type    = pathinfo($blad_foto_lapangan1, PATHINFO_EXTENSION);
    $blad_foto_lap1_folder  = "davinci1";
    $blad_foto_lap1_new     = $blad_foto_lap1_folder."_".$bl_hasil_koment.".".$blad_foto_lap1_type;
    $blad_foto_lapangan2    = $_FILES['blad_foto_lapangan2']['name']?$_FILES['blad_foto_lapangan2']['name']:"kosong";
    $blad_foto_lap2_type    = pathinfo($blad_foto_lapangan2, PATHINFO_EXTENSION);
    $blad_foto_lap2_folder  = "davinci2";
    $blad_foto_lap2_new     = $blad_foto_lap2_folder."_".$bl_hasil_koment.".".$blad_foto_lap2_type;
    $blad_foto_lapangan3    = $_FILES['blad_foto_lapangan3']['name']?$_FILES['blad_foto_lapangan3']['name']:"kosong";
    $blad_foto_lap3_type    = pathinfo($blad_foto_lapangan3, PATHINFO_EXTENSION);
    $blad_foto_lap3_folder  = "davinci3";
    $blad_foto_lap3_new     = $blad_foto_lap3_folder."_".$bl_hasil_koment.".".$blad_foto_lap3_type;
    $blad_foto_lapangan4    = $_FILES['blad_foto_lapangan4']['name']?$_FILES['blad_foto_lapangan4']['name']:"kosong";
    $blad_foto_lap4_type    = pathinfo($blad_foto_lapangan4, PATHINFO_EXTENSION);
    $blad_foto_lap4_folder  = "davinci4";
    $blad_foto_lap4_new     = $blad_foto_lap4_folder."_".$bl_hasil_koment.".".$blad_foto_lap4_type;
    $blad_video_lapangan    = $_FILES['blad_video_lapangan']['name']?$_FILES['blad_video_lapangan']['name']:"kosong";
    $blad_video_size        = $_FILES['blad_video_lapangan']['size'];
    $blad_video_type        = pathinfo($blad_video_lapangan, PATHINFO_EXTENSION);
    $blad_nama_folder       = "bergerak";
    $blad_nama_file_baru    = $blad_nama_folder."_".$bl_hasil_koment.".".$blad_video_type;
    $blad_sql_cek           = mysql_query("select * from lapangan where nama_lapangan='$blad_nama_lapangan'");
    $blad_jml_cek = mysql_num_rows($blad_sql_cek);
    if($blad_jml_cek > 0){
        ?>
<script>alert('Nama Lapangan <?php echo $blad_nama_lapangan; ?> sudah ada ! Periksa kembali data lapangan, atau hapus yang sudah ada');history.back();</script>
<?php
    }else{
        if($blad_video_size > 209715200){
            ?>
<script>alert('Ukuran Video Terlalu besar');history.back();</script>
<?php
        }else{
        $blad_sql_insert = mysql_query("insert into lapangan set id_lapangan='$blad_id_lapangan', nama_lapangan='$blad_nama_lapangan', alamat_lapangan='$blad_alamat_lapangan', kota='$blad_kota', kecamatan='$blad_kecamatan', kelurahan='$blad_kelurahan', deskripsi='$blad_deskripsi', kode_komentar='$blad_kode_komentar',id_user='$blad_id_user',harga='$blad_harga',telp_lapangan='$blad_telp_lapangan', foto_lapangan_1='$blad_foto_lap1_new', foto_lapangan_2='$blad_foto_lap2_new', foto_lapangan_3='$blad_foto_lap3_new', foto_lapangan_4='$blad_foto_lap4_new', video='$blad_nama_file_baru', hapus_lapangan='ok'") or die ('erro query');
        if($blad_sql_insert && isset($blad_foto_lapangan1) && isset($blad_foto_lapangan2) && isset($blad_foto_lapangan3) && isset($blad_foto_lapangan4) && isset($blad_video_lapangan)){
            move_uploaded_file($_FILES['blad_foto_lapangan1']['tmp_name'],"../davinci/lapangan/".$blad_foto_lap1_new); 
            move_uploaded_file($_FILES['blad_foto_lapangan2']['tmp_name'],"../davinci/lapangan/".$blad_foto_lap2_new); 
            move_uploaded_file($_FILES['blad_foto_lapangan3']['tmp_name'],"../davinci/lapangan/".$blad_foto_lap3_new); 
            move_uploaded_file($_FILES['blad_foto_lapangan4']['tmp_name'],"../davinci/lapangan/".$blad_foto_lap4_new); 
            move_uploaded_file($_FILES['blad_video_lapangan']['tmp_name'],"../bergerak/".$blad_nama_file_baru); 
            
        }
            header("location:index.php");
    }
    }
        
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | <?php echo $_SESSION['bl_nama']; ?> | Comment Page</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
    <link href="../csgo/bl_stang.css" rel="stylesheet">
    <script type="text/javascript">
        function blad_cek_foto(){
            var file_foto1  = document.getElementById('blad_foto_lapangan1');
            var info1       = file_foto1.files[0];
            var size1       = info1.size;
            var mbsize1     = Math.round(size1 / 1048576);
            var file_foto2  = document.getElementById('blad_foto_lapangan2');
            var info2       = file_foto2.files[0];
            var size2       = info2.size;
            var mbsize3     = Math.round(size2 / 1048576);
            var file_foto3  = document.getElementById('blad_foto_lapangan3');
            var info3       = file_foto3.files[0];
            var size3       = info3.size;
            var mbsize3     = Math.round(size3 / 1048576);
            var file_foto4  = document.getElementById('blad_foto_lapangan4');
            var info4       = file_foto4.files[0];
            var size4       = info4.size;
            var mbsize4     = Math.round(size4 / 1048576);
            if(size1 > 5242880 || size2 > 5242880 || size3 > 5242880 || size4 > 5242880){
                document.getElementById('btn_submt').style.display='none';
                document.getElementById('btn_submt2').style.display='block';
            }else{
                document.getElementById('btn_submt').style.display='block';
                document.getElementById('btn_submt2').style.display='none';
            }            
            
        }
        
        function blad_cek_video(){
            var file_video = document.getElementById('blad_video_lapangan');
            var info_video = file_video.files[0];
            var size_video = info_video.size;
            var mbvideo    = Math.round(size_video/1048576);
            if(size_video > 209715200){
                document.getElementById('btn_submt').style.display='none';
                document.getElementById('btn_submt2').style.display='block';
            }else{
                document.getElementById('btn_submt').style.display='block';
                document.getElementById('btn_submt2').style.display='none';
            }   
        }
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="register">
        <div class="content-login">
            <div class="isi-login" id="login-satu">
            <div class="in-login" id="in-satu">
                INPUT DATA LAPANGAN<br><i> Masukkan Data Lapangan Dengan Benar</i>
                </div>    
            <div class="in-login" id="in-dua">
                <button type="button" class="tutup" onclick="location.href='index.php'"><span class="glyphicon glyphicon-remove"></span></button>
                </div>    
            </div>
            <form target="_self" method="post" enctype="multipart/form-data" name="register" id="register">
                <div class="isi-login" id="login-dua">
                    <input type="hidden" value="LP_<?php echo $bl_hasil; ?>" name="blad_id_lapangan" id="blad_id_lapangan">
                    <input type="hidden" value="KM_<?php echo $bl_hasil_koment; ?>" name="blad_kode_komentar" id="blad_kode_komentar">
                    <div class="inpt">
                            <span class="glyphicon glyphicon-pencil" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_nama_lapangan" id="blad_nama_lapangan" placeholder="Nama Lapangan" required onblur="ceknama()">            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-road" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_alamat_lapangan" id="blad_alamat_lapangan" placeholder="Alamat Lapangan" required onblur="cekuser()">            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-map-marker" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_kota" id="blad_kota" placeholder="Kota" required onblur="cekpass();">            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-map-marker" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_kecamatan" id="blad_kecamatan" placeholder="Kecamatan" required onblur="cekpass()"  onfocus="cekpass()">            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-map-marker" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_kelurahan" id="blad_kelurahan" placeholder="Kelurahan" required onfocus="cekpass()" onblur="cekemail()">            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-usd" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="number" min="10000" name="blad_harga" id="blad_harga" placeholder="Harga" required onblur="cekalamat()" onfocus="cekalamat()">
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-phone" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_telp_lapangan" id="blad_telp_lapangan" placeholder="No Telp Lapangan" required onblur="ceknotel()" onfocus="ceknotel()">      
                        </div>
                    <div class="inpt" id="inpt-msg">
                            <div id="bl_psnpass">Masukkan Gambar Lapangan Maksimal 4 </div>
                        </div>
                    <div class="inpt" id="inpt-msg">
                            <div id="bl_psnpass">Ukuran maksimal 5 mb </div>
                        </div><br>
                    <div class="inpt">
                            <input type="file" name="blad_foto_lapangan1" id="blad_foto_lapangan1" required onblur="blad_cek_foto()" onfocus="blad_cek_foto()" onchange="blad_cek_foto();">            
                        </div>
                    <div id="msgfoto1"></div>
                    <div class="inpt">
                            <input type="file" name="blad_foto_lapangan2" id="blad_foto_lapangan2" required onblur="blad_cek_foto()" onfocus="blad_cek_foto()" onchange="blad_cek_foto();">            
                        </div>
                    <div id="msgfoto2"></div>
                    <div class="inpt">
                            <input type="file" name="blad_foto_lapangan3" id="blad_foto_lapangan3" required onblur="blad_cek_foto()" onfocus="blad_cek_foto()" onchange="blad_cek_foto();">            
                        </div>
                    <div id="msgfoto3"></div>
                    <div class="inpt">
                            <input type="file" name="blad_foto_lapangan4" id="blad_foto_lapangan4" required onblur="blad_cek_foto()" onfocus="blad_cek_foto()" onchange="blad_cek_foto();">            
                        </div>
                    <div id="msgfoto4"></div>
                    <div class="inpt">
                            <textarea name="blad_deskripsi" id="blad_deskripsi" placeholder="Deskripsi Lapangan" required></textarea>           
                        </div>
                    <div class="inpt" id="inpt-msg">
                            <div id="bl_psnpass">Masukkan Video Lapangan </div>
                        </div>
                    <div class="inpt" id="inpt-msg">
                            <div id="bl_psnpass">Ukuran maksimal 200 mb </div>
                        </div><br>
                    <div class="inpt">
                            <input type="file" name="blad_video_lapangan" id="blad_video_lapangan" required onblur="blad_cek_video()" onfocus="blad_cek_video()" onchange="blad_cek_video()">            
                        </div>
                    <button type="submit" class="btn btn-primary" id="btn_submt" onfocus="blad_cek_foto(); blad_cek_video();" onblur="blad_cek_foto();blad_cek_video();"><span class="glyphicon glyphicon-send"></span> &nbsp; Kirim</button>
                    <button type="submit" class="btn btn-primary" id="btn_submt2" onfocus="blad_cek_foto();blad_cek_video();" onblur="blad_cek_foto();blad_cek_video();" disabled><span class="glyphicon glyphicon-send"></span> &nbsp; Kirim</button>
                    <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-erase"></span> &nbsp; Clear</button>
                    </div>
                </div>
            </form>
        </div>
<div class="wrapper">  
  <header class="main-header">
    <a href="index.php" class="logo">
      <span class="logo-mini"><b>B</b>King</span>
      
      <span class="logo-lg"><b>Booking</b></span>
    </a>

    
    <nav class="navbar navbar-static-top" role="navigation">
      
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              
              <img src="../davinci/admin/<?php echo $blad_foto; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $blad_nama;  ?></span>
            </a>
            <ul class="dropdown-menu">
              
              <li class="user-header">
                <img src="../davinci/admin/<?php echo $blad_foto; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $blad_nama; ?> - <?php echo $blad_level; ?>
                  <small><?php echo $blad_level; ?> since <?php echo $blad_ngumpul;  ?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="../bl_cabut.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../davinci/admin/<?php echo $blad_foto; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $blad_nama; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <form method="get" class="sidebar-form" enctype="multipart/form-data" target="_self" name="blad_sch_cari" id="blad_sch_cari">
        <div class="input-group">
          <input type="text" name="blad_cari" class="form-control" placeholder="ID, Nama Lap, Alamat">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-flat"><i class="glyphicon glyphicon-search"></i>
                </button>
              </span>
        </div>
      </form>
      <ul class="sidebar-menu">
        <li class="header">MENU ADMIN</li>
          <li><a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> <span>Data Lapangan <span class="badge">
              <?php
              $blad_sql_dlap = mysql_query("select * from lapangan");
              $blad_jml_dlap = mysql_num_rows($blad_sql_dlap);
              echo $blad_jml_dlap;
              ?>
              </span></span></a></li>
        <li class="treeview ">
          <a href="#"><span class="glyphicon glyphicon-user"></span> <span>Pengguna
              <span class="badge">
                  <?php
                  $blad_sql_user = mysql_query("select * from user where level='admin' or level='lowadmin' or level='member'");
                  echo $blad_jml_user = mysql_num_rows($blad_sql_user);
                  ?>
              </span>
              </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="blad_admin_page.php">Admin <span class="badge">
                    <?php
                    $blad_sql_admn = mysql_query("select * from user where level='admin' or level='lowadmin'");
                    echo $blad_jml_admn = mysql_num_rows($blad_sql_admn);
                    ?>
                </span></a>
                
              </li>
            <li><a href="blad_member_page.php">Member
                <span class="badge">
                    <?php
                    $blad_sql_member = mysql_query("select * from user where level='member'");
                    echo $blad_jml_member = mysql_num_rows($blad_sql_member);
                    ?>
                </span>
                </a></li>
          </ul>
        </li>
          
        <li><a href="blad_data_berita.php"><span class="glyphicon glyphicon-globe"></span> <span>Data Berita 
            <span class="badge">
                <?php
                $blad_sql_dberita = mysql_query("select * from berita");
                echo $blad_jml_dberita = mysql_num_rows($blad_sql_dberita);
                ?>
            </span>
            </span></a></li>
          <li class="treeview active">
          <a href="#"><span class="glyphicon glyphicon-comment"></span> <span>Komentar
              <span class="badge">
                <?php
                $blad_sql_koment= mysql_query("select * from komentar");
                echo $blad_jml_komentar = mysql_num_rows($blad_sql_koment);
                ?>
            </span>
              </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="blad_admin_page.php">Komentar Lapangan <span class="badge">
                    <?php
                    $blad_sql_admn = mysql_query("select * from komentar  ");
                    echo $blad_jml_admn = mysql_num_rows($blad_sql_admn);
                    ?>
                </span></a>
                
              </li>
            <li><a href="blad_member_page.php">Komentar Berita
                <span class="badge">
                    <?php
                    $blad_sql_member = mysql_query("select * from user where level='member'");
                    echo $blad_jml_member = mysql_num_rows($blad_sql_member);
                    ?>
                </span>
                </a></li>
          </ul>
        </li>
          <li><a href="blad_activity_page.php"><span class="glyphicon glyphicon-list"></span> <span>Aktifitas
            <span class="badge">
                <?php
                $blad_sql_koment= mysql_query("select * from kelakuan");
                echo $blad_jml_komentar = mysql_num_rows($blad_sql_koment);
                ?>
            </span>
            </span></a></li>
      </ul>
    </section>
  </aside>
  <div class="content-wrapper">
    <section class="content-header">
            <a href="index.php?blad_hps_all=<?php echo $blad_fetch['hapus_lapangan']; ?>"><button type="button" class="hapus-semua" onclick="return confirm('Apa anda yakin ingin menghapus semua data ini ? Data yang terhapus tidak dapat dikembalikan')"><span class="glyphicon glyphicon-trash"></span> Delete All</button></a>
            <button type="button" class="tambah" id="tmb_lapangan"><span class="glyphicon glyphicon-plus"></span> Tambah Data</button>
        
        <ol class="breadcrumb">
        <li><span class="glyphicon glyphicon-dashboard"></span> &nbsp; DATA KOMENTAR</li>
      </ol>
    </section>
    <section class="content">
        <table>
            <tr>
                <th>No</th>
                <th>Id Lapangan</th>
                <th>Nama Lapangan</th>
                <th>Alamat</th>
                <th class="kota">Kota</th>
                <th class="kecamatan">Kecamatan</th>
                <th class="kelurahan">Kelurahan</th>
                <th>Harga</th>
                <th>Telpon</th>
                <th>Opsi</th>
            </tr>
            <?php
            $blad_jml_record = mysql_num_rows($blad_sql_cari);
            if($blad_jml_record > 0){
                $blad_no=1;
                while($blad_rows = mysql_fetch_array($blad_sql_cari)){
                    if($blad_no%2==0){
                        $blad_backcol ="#eee";
                    }else{
                        $blad_backcol ="white";
                    }
                
            ?>
            <tr bgcolor="<?php echo $blad_backcol; ?>">
                <td align="center"><?php echo $blad_no; ?></td>
                <td><?php echo substr($blad_rows['id_lapangan'],0,5),"..."; ?></td>
                <td><?php echo $blad_rows['nama_lapangan'];?></td>
                <td><?php echo $blad_rows['alamat_lapangan'];?></td>
                <td class="kota" align="center"><?php echo $blad_rows['kota']; ?></td>
                <td class="kecamatan"><?php echo $blad_rows['kecamatan']; ?></td>
                <td class="kelurahan"><?php echo $blad_rows['kelurahan']; ?></td>
                <td align="right"><?php echo"Rp ".number_format($blad_rows['harga'],0,',','.'); ?> / Jam</td>
                <td align="center"><?php echo substr($blad_rows['telp_lapangan'],0,4)." - ".substr($blad_rows['telp_lapangan'],4,4)." - ".substr($blad_rows['telp_lapangan'],8,11); ?></td>
                <td align="center">
                    <a href="blad_detail.php?blad_id_lap=<?php echo $blad_rows['id_lapangan']; ?>" class="detail"><span class="glyphicon glyphicon-folder-open"></span></a> &nbsp;
                    <a href="index.php?blad_id_lap_hps=<?php echo $blad_rows['id_lapangan']; ?>" class="hapus"><span class="glyphicon glyphicon-remove"></span></a> &nbsp;
                    <a href="blad_edt.php?blad_id_lap_edt=<?php echo $blad_rows['id_lapangan']; ?>" class="edit"><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
            </tr>
            <?php
                        $blad_no++;
                    }
            }else{
                ?>
            <tr>
                <td colspan="10" align="center">
                    <?php
                echo"Data lapangan tidak ditemukan";
                ?>
                </td>
            </tr>
            <?php
                
            }
                    ?>
        </table>
    </section>
  </div>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <ul class="pagination">
         <?php
   $blad_query = mysql_query("select * from lapangan");
   $blad_jml_data = mysql_num_rows($blad_query);
   $blad_hal = ceil($blad_jml_data/$blad_limit);
   if($blad_halaman > 1){
       $blad_prev = $blad_halaman -1;
       echo"<li><a href='$_SERVER[PHP_SELF]?blad_halaman=$blad_prev'><<</a></li>";
   }
   
for ($blad_i=1;$blad_i<=$blad_hal;$blad_i++)
   if($blad_i!=$blad_halaman){
       echo"<li><a href='$_SERVER[PHP_SELF]?blad_halaman=$blad_i'>$blad_i</a><li>";
   }
   
   if($blad_halaman < $blad_hal){
       $blad_next = $blad_halaman+1;
       echo "<li><a href='$_SERVER[PHP_SELF]?blad_halaman=$blad_next'>>></a></li>";
   }
   ?>
            </ul>
    </div>
    <strong>Copyright &copy; 2016 - <?php echo date('Y');?> <a href="index.php">Booking Lapangan</a>.</strong> All rights reserved.
  </footer>
  
</div>
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="dist/js/app.min.js"></script>
    <script src="bootstrap/js/bl_stang.js" type="text/javascript"></script>
</body>
</html>
<?php
mysql_close($bl_koneksi);
ob_flush();
?>