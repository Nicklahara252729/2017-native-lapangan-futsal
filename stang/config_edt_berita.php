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
$blad_date      = date('d/m/y');

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


if(isset($_GET['blad_id_lap_det'])){
    $blad_get_id_lap = $_GET['blad_id_lap_det'];
    $blad_sql_show = mysql_query("select * from berita where id_berita='$blad_get_id_lap'");
    $blad_fetching = mysql_fetch_array($blad_sql_show);
}

if(isset($_POST['blad_id_berita'])){
    $blad_id_berita         = strip_tags(trim($_POST['blad_id_berita']));
    $blad_judul_berita      = strip_tags(trim($_POST['blad_judul_berita']));
    $blad_isi_berita         = strip_tags(trim($_POST['blad_isi_berita']));
    $blad_foto_lapangan1    = $_FILES['blad_foto_berita']['name']?$_FILES['blad_foto_berita']['name']:"kosong";
    $blad_foto_lap1_type    = pathinfo($blad_foto_lapangan1, PATHINFO_EXTENSION);
    $blad_foto_lap1_folder  = "berita";
    $blad_foto_lap1_new     = $blad_foto_lap1_folder."_".$bl_hasil_koment.".".$blad_foto_lap1_type;
    $blad_video_lapangan    = $_FILES['blad_video_berita']['name']?$_FILES['blad_video_berita']['name']:"kosong";
    $blad_video_size        = $_FILES['blad_video_berita']['size'];
    $blad_video_type        = pathinfo($blad_video_lapangan, PATHINFO_EXTENSION);
    $blad_nama_folder       = "berita";
    $blad_nama_file_baru    = $blad_nama_folder."_".$bl_hasil_koment.".".$blad_video_type;
    $blad_sql_cek           = mysql_query("select * from berita where id_berita='$blad_id_berita'");
    $blad_jml_cek = mysql_num_rows($blad_sql_cek);
    if($blad_jml_cek <= 0){
        ?>
<script>alert('Berita <?php echo $blad_nama_lapangan; ?> tidak ada ! Periksa kembali berita');history.back();</script>
<?php
    }else{
        if($blad_video_size > 209715200){
            ?>
<script>alert('Ukuran Video Terlalu besar');history.back();</script>
<?php
        }else{
            if(!empty($_FILES['blad_foto_berita']['name']) and ($_FILES['blad_video_berita']['name'])){
        $blad_sql_insert = mysql_query("update berita set judul_berita='$blad_judul_berita', isi_berita='$blad_isi_berita',foto_berita='$blad_foto_lap1_new',video='$blad_nama_file_baru' where id_berita='$blad_id_berita'");
             $blad_insert_act = mysql_query("insert into kelakuan set id_user='$blad_id', tgl_tindakan='$blad_date', tindakan='Ubah berita : $blad_judul_berita', hapus_tindakan='ok'");
        if($blad_sql_insert && isset($blad_foto_lapangan1) && isset($blad_video_lapangan)){
            move_uploaded_file($_FILES['blad_foto_berita']['tmp_name'],"../davinci/berita/".$blad_foto_lap1_new); 
            move_uploaded_file($_FILES['blad_video_berita']['tmp_name'],"../bergerak/".$blad_nama_file_baru); 
            unlink("../davinci/berita/".$blad_fetching['foto_berita']);
            unlink("../bergerak/".$blad_fetching['video']);
            
        }
            } else if(!empty($_FILES['blad_foto_berita']['name']) and empty($_FILES['blad_video_berita']['name'])){
                $blad_sql_insert = mysql_query("update berita set judul_berita='$blad_judul_berita', isi_berita='$blad_isi_berita',foto_berita='$blad_foto_lap1_new' where id_berita='$blad_id_berita'");
             $blad_insert_act = mysql_query("insert into kelakuan set id_user='$blad_id', tgl_tindakan='$blad_date', tindakan='Ubah berita : $blad_judul_berita', hapus_tindakan='ok'");
        if($blad_sql_insert && isset($blad_foto_lapangan1) ){
            move_uploaded_file($_FILES['blad_foto_berita']['tmp_name'],"../davinci/berita/".$blad_foto_lap1_new); 
            unlink("../davinci/berita/".$blad_fetching['foto_berita']);
        }
            }
            
            else if(empty($_FILES['blad_foto_berita']['name']) and !empty($_FILES['blad_video_berita']['name'])){
                $blad_sql_insert = mysql_query("update berita set judul_berita='$blad_judul_berita', isi_berita='$blad_isi_berita', video='$blad_nama_file_baru' where id_berita='$blad_id_berita'");
             $blad_insert_act = mysql_query("insert into kelakuan set id_user='$blad_id', tgl_tindakan='$blad_date', tindakan='Ubah berita : $blad_judul_berita', hapus_tindakan='ok'");
        if($blad_sql_insert && isset($blad_video_lapangan)){
            move_uploaded_file($_FILES['blad_video_berita']['tmp_name'],"../bergerak/".$blad_nama_file_baru);
            unlink("../bergerak/".$blad_fetching['video']);
            
        }
            }
            
            else {
                $blad_sql_insert = mysql_query("update berita set judul_berita='$blad_judul_berita', isi_berita='$blad_isi_berita' where id_berita='$blad_id_berita'");
             $blad_insert_act = mysql_query("insert into kelakuan set id_user='$blad_id', tgl_tindakan='$blad_date', tindakan='Ubah berita : $blad_judul_berita', hapus_tindakan='ok'");
            }
            header("location:config_data_berita.php");
    }
    }
        
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Superadmin - <?php echo $_SESSION['bl_nama']; ?> | <?php echo $blad_fetching['judul_berita']; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
    <link href="../csgo/bl_stang.css" rel="stylesheet">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="detail-page">
    
    <div class="container" id="container">
        <div class="rows">
            <div class="col-md-4">
                <div class="rows">
                    <div class="col-md-12">
                        <video src="../bergerak/<?php echo $blad_fetching['video']; ?>" class="vid-det-lap" controls></video>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-md-12">
                        <img src="../davinci/berita/<?php echo $blad_fetching['foto_berita']; ?>" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="rows">
                    <div class="col-md-12" id="title-edt">
                        Ubah Data Berita : <b><?php echo $blad_fetching['judul_berita']; ?></b>
                        <button type="button" class="tutup" onclick="location.href='config_data_berita.php'">X</button>
                    </div>
                </div>
                <form target="_self" name="blad_edt_berita" id="blad_edt_berita" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="blad_id_berita" id="blad_id_berita" value="<?php echo $blad_fetching['id_berita']; ?>">
                <div class="rows">
                    <div class="col-md-12" id="red">
                        <input type="text" placeholder="Masukkan judul baru" name="blad_judul_berita" id="blad_judul_berita" value="<?php echo $blad_fetching['judul_berita']; ?>" required>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-md-12">
                      <span class="glyphicon glyphicon-picture"></span>  <span class="caption">Pilih gambar jika ingin diubah. Max 2 mb</span>
                    </div>
                    <div class="col-md-12" id="red">
                        <input type="file" name="blad_foto_berita" id="blad_foto_berita">
                    </div>
                    </div>
                    <div class="rows">
                    <div class="col-md-12">
                      <span class="glyphicon glyphicon-facetime-video"></span>  <span class="caption">Pilih video jika ingin diubah. Max 200 mb</span>
                    </div>
                    <div class="col-md-12" id="red">
                        <input type="file" name="blad_video_berita" id="blad_video_berita">
                    </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="area">
                            <textarea placeholder="Isi Berita" name="blad_isi_berita" id="blad_isi_berita" required><?php echo $blad_fetching['isi_berita']; ?></textarea>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                                       <button type="submit" class="btn btn-primary" id="but-on">Save</button>
                            <button type="reset" class="btn btn-danger" id="but-on">Cancel</button>
                 
                        </div>
                    </div>
                    </form>
            </div>
        </div>
    </div>
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
          <li class="active"><a href="#"><span class="glyphicon glyphicon-dashboard"></span> <span>Data Lapangan <span class="badge">
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
          <li><a href="blad_activity_page.php"><span class="glyphicon glyphicon-list"></span> <span>Aktifitas
            <span class="badge">
                <?php
                $blad_sql_koment= mysql_query("select * from kelakuan");
                echo $blad_jml_komentar = mysql_num_rows($blad_sql_koment);
                ?>
            </span>
            </span></a>
          </li>
      </ul>
    </section>
  </aside>
  <div class="content-wrapper">
    <section class="content-header">
            <a href="index.php?blad_hps_all=<?php echo $blad_fetch['hapus_lapangan']; ?>"><button type="button" class="hapus-semua" onclick="return confirm('Apa anda yakin ingin menghapus semua data ini ? Data yang terhapus tidak dapat dikembalikan')"><span class="glyphicon glyphicon-trash"></span> Delete All</button></a>
            <button type="button" class="tambah" id="tmb_lapangan"><span class="glyphicon glyphicon-plus"></span> Tambah Data</button>
        
        <ol class="breadcrumb">
        <li><span class="glyphicon glyphicon-dashboard"></span> &nbsp; DATA Ber</li>
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
                    <a href="blad_detail_lapangan.php?blad_id_lap=<?php echo $blad_rows['id_lapangan']; ?>" class="detail"><span class="glyphicon glyphicon-folder-open"></span></a> &nbsp;
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