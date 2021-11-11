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

$blad_limit =250;
if(!isset($_GET['blad_halaman'])){
    $blad_halaman =1;
    $blad_posisi = 0;
}else{
    $blad_halaman = $_GET['blad_halaman'];
    $blad_posisi = ($blad_halaman-1) * $blad_limit;
}

if(isset($_GET['blad_cari'])){
    $blad_keyword = $_GET['blad_cari'];
    $blad_sql_cari = mysql_query("select * from kelakuan join user on(kelakuan.id_user=user.id_user) where nama_user like '%$blad_keyword%' or tgl_tindakan like '%$blad_keyword%' or tindakan like '%$blad_keyword%' order by tgl_tindakan desc limit $blad_posisi,$blad_limit");
}else{
    $blad_sql_cari = mysql_query("select * from kelakuan join user on (kelakuan.id_user = user.id_user) order by tgl_tindakan desc limit $blad_posisi,$blad_limit");
}
$blad_sql= mysql_query("select * from kelakuan");
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
            header("location:config_activity_page.php");
		}
	}
    
}

if(isset($_GET['blad_id_lap_hps'])){
    $blad_id_lap_hps = $_GET['blad_id_lap_hps'];
    $blad_sql_id_lap_hps = mysql_query("delete from kelakuan where id_kelakuan='$blad_id_lap_hps'");
    header("location:config_activity_page.php");
    
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Superadmin | <?php echo $_SESSION['bl_nama']; ?> | Activity Page</title>
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
          <input type="text" name="blad_cari" class="form-control" placeholder="User, Tanggal, Tindakan">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-flat"><i class="glyphicon glyphicon-search"></i>
                </button>
              </span>
        </div>
      </form>
      <ul class="sidebar-menu">
        <li class="header">MENU ADMIN</li>
          <li><a href="config.php"><span class="glyphicon glyphicon-dashboard"></span> <span>Data Lapangan <span class="badge">
              <?php
              $blad_sql_dlap = mysql_query("select * from lapangan");
              $blad_jml_dlap = mysql_num_rows($blad_sql_dlap);
              echo $blad_jml_dlap;
              ?>
              </span></span></a></li>
        <li>
          <a href="config_admin_page.php"><span class="glyphicon glyphicon-user"></span> <span>Pengguna
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
        </li>
          
        <li><a href="config_data_berita.php"><span class="glyphicon glyphicon-globe"></span> <span>Data Berita 
            <span class="badge">
                <?php
                $blad_sql_dberita = mysql_query("select * from berita");
                echo $blad_jml_dberita = mysql_num_rows($blad_sql_dberita);
                ?>
            </span>
            </span></a></li>
          <li class="treeview">
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
          <li class="active"><a href="config_activity_page.php"><span class="glyphicon glyphicon-list"></span> <span>Aktifitas
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
        <a href="config_activity_page.php?blad_hps_all=<?php echo $blad_fetch['hapus_lapangan']; ?>"><button type="button" class="hapus-semua" onclick="return confirm('Apa anda yakin ingin menghapus semua data ini ? Data yang terhapus tidak dapat dikembalikan')"><span class="glyphicon glyphicon-trash"></span> Delete All</button></a>
        <ol class="breadcrumb">
        <li><span class="glyphicon glyphicon-dashboard"></span> &nbsp; AKTIFITAS PENGGUNA</li>
      </ol>
    </section>
    <section class="content">
        <table>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Tanggal</th>
                <th>Aktifitas</th>
                <th>Tindakan</th>
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
                <td ><?php echo $blad_rows['nama_user']; ?></td>
                <td align="center" style="width:100px;"><?php echo $blad_rows['tgl_tindakan'];?></td>
                <td><?php echo $blad_rows['tindakan'];?></td>
                <td align="center">
                    <a href="config_activity_page.php?blad_id_lap_hps=<?php echo $blad_rows['id_kelakuan']; ?>" class="hapus"><span class="glyphicon glyphicon-remove"></span></a> &nbsp;
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