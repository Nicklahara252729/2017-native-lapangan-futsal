<?php
ob_start();
if(!isset($_SESSION['bl_user'])){
    session_start();
}
include"../bl_kabelutp.php";
include"../bl_tembok_hybrid.php";
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

if(isset($_GET['blad_id_lap_edt'])){
    $blad_get_edt_id = $_GET['blad_id_lap_edt'];
    $blad_sql_show = mysql_query("select * from user where id_user='$blad_get_edt_id'");
    $blad_fetching = mysql_fetch_array($blad_sql_show);
}

if(isset($_GET['blad_cari'])){
    $blad_keyword = $_GET['blad_cari'];
    $blad_sql_cari = mysql_query("select * from user where  id_user like '%$blad_keyword%' or nama_user like '%$blad_keyword%' or username like '%$blad_keyword%' order by nama_user desc limit $blad_posisi,$blad_limit");
}else{
    $blad_sql_cari = mysql_query("select * from user where level='member'order by nama_user desc limit $blad_posisi,$blad_limit");
}
$blad_sql= mysql_query("select * from user where level='member' order by nama_user desc");
$blad_fetch = mysql_fetch_array($blad_sql);

if(isset($_POST['blad_id_user'])){
    $blad_id_user      = strip_tags(trim($_POST['blad_id_user']));
    $blad_nama_user    = strip_tags(trim($_POST['blad_nama_user']));
    $blad_username     = strip_tags(trim($_POST['blad_username']));
    $blad_password     = sha1(strip_tags(trim($_POST['blad_password'])));
    $blad_email_user   = strip_tags(trim($_POST['blad_email']));
    $blad_level_user   = strip_tags(trim($_POST['blad_select_level']));
    $blad_jenkel_user  = strip_tags(trim($_POST['blad_jenkel']));
    $blad_alamat_user  = strip_tags(trim($_POST['blad_alamat']));
    $blad_no_hp_user   = strip_tags(trim($_POST['blad_no_hp']));
    $blad_foto_user    = $_FILES['blad_foto_user']['name']?$_FILES['blad_foto_user']['name']:"kosong";
    $blad_foto_type    = pathinfo($blad_foto_user, PATHINFO_EXTENSION);
    $blad_foto_folder  = "davinci";
    $blad_foto_new     = $blad_foto_folder."_".$bl_hasil_koment.".".$blad_foto_type;
    $blad_sql_cek           = mysql_query("select * from user where id_user='$blad_id_user'");
    $blad_jml_cek = mysql_num_rows($blad_sql_cek);
    if($blad_jml_cek <=0){
        ?>
<script>alert('Username <?php echo $blad_username; ?> Data yang akan diubah tidak ditemukan ! Periksa kembali');history.back();</script>
<?php
    }else{
        if(!empty($_FILES['blad_foto_user']['name'])){
        $blad_sql_insert = mysql_query("update user set nama_user='$blad_nama_user', username='$blad_username', password='$blad_password', email='$blad_email_user', level='$blad_level_user', jenkel='$blad_jenkel_user', alamat='$blad_alamat_user',no_hp='$blad_no_hp_user',foto='$blad_foto_new' where id_user='$blad_id_user'");
        $blad_insert_act = mysql_query("insert into kelakuan set id_user='$blad_id', tgl_tindakan='$blad_date', tindakan='Ubah pengguna : $blad_nama_user', hapus_tindakan='ok'");
        if($blad_sql_insert  && $blad_insert_act && isset($blad_foto)){
            move_uploaded_file($_FILES['blad_foto_user']['tmp_name'],"../davinci/admin/".$blad_foto_new); 
            unlink("../davinci/admin/".$blad_fetching['foto']);
        }
        }else{
            $blad_sql_insert = mysql_query("update user set nama_user='$blad_nama_user', username='$blad_username', password='$blad_password', email='$blad_email_user', level='$blad_level_user', jenkel='$blad_jenkel_user', alamat='$blad_alamat_user',no_hp='$blad_no_hp_user' where id_user='$blad_id_user'");
        $blad_insert_act = mysql_query("insert into kelakuan set id_user='$blad_id', tgl_tindakan='$blad_date', tindakan='Ubah pengguna : $blad_nama_user', hapus_tindakan='ok'");
        }
            header("location:blad_admin_page.php");
    }
        
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $blad_level; ?> | <?php echo $_SESSION['bl_nama'];?> | <?php echo $blad_fetching['nama_user']; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
    <link href="../csgo/bl_stang.css" rel="stylesheet">
    <script type="text/javascript">
        function blad_cek_pass(){
            var pass  = document.getElementById('blad_password').value;
            var pass1 = document.getElementById('blad_password').value;
            var pass2 = document.getElementById('blad_confirm').value;
            if(pass.length < 8 && pass1!=pass2){
                document.getElementById('msgpass').style.color="red";
                document.getElementById('msgpass').innerHTML="Panjang karakter minimal 8";
                document.getElementById('blad_password').focus();
            }else if(pass.length >=8 && pass2.length <=1){
                document.getElementById('msgpass').style.color="green";
                document.getElementById('msgpass').innerHTML="Password bagus";
            }else if(pass.length >=8 && pass1!=pass2){
                document.getElementById('msgpass').style.color="red";
                document.getElementById('msgpass').innerHTML="Password tidak sesuai";
                document.getElementById('blad_confirm').focus();
            }else if(pass.length >=8 && pass1==pass2){
                document.getElementById('msgpass').style.color="blue";
                document.getElementById('msgpass').innerHTML="Password sesuai";
            }
        }
        
        function blad_cek_foto(){
            var file_foto = document.getElementById('blad_foto_user');
            var info_foto = file_foto.files[0];
            var size_foto = info_foto.size;
            var mbvideo    = Math.round(size_foto/1048576);
            if(size_foto > 2097152){
                document.getElementById('msgfoto').style.color="red";
                document.getElementById('msgfoto').innerHTML="Ukuran foto maksimal 2 Mb";
                document.getElementById('btn_submt').style.display='none';
                document.getElementById('btn_submt2').style.display='block';
            }else{
                document.getElementById('msgfoto').innerHTML="";
                document.getElementById('btn_submt').style.display='block';
                document.getElementById('btn_submt2').style.display='none';
            }   
        }
        
        
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="detail-page">
        <div class="content-login">
            <div class="isi-login" id="login-satu">
            <div class="in-login" id="in-satu">
                EDIT PENGGUNA : <?php ?><br><i> Masukkan Data Pengguna Dengan Benar</i>
                </div>    
            <div class="in-login" id="in-dua">
                <button type="button" class="tutup" onclick="location.href='blad_admin_page.php'"><span class="glyphicon glyphicon-remove"></span></button>
                </div>    
            </div>
            <form target="_self" method="post" enctype="multipart/form-data" name="register" id="register">
                <div class="isi-login" id="login-dua">
                    <input type="hidden" value="<?php echo $blad_fetching['id_user']; ?>" name="blad_id_user" id="blad_id_user">
                    <div class="inpt">
                            <span class="glyphicon glyphicon-pencil" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_nama_user" id="blad_nama_user" placeholder="Nama User" required value="<?php echo $blad_fetching['nama_user']; ?>">            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-user" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_username" id="blad_username" placeholder="Username" required value="<?php echo $blad_fetching['username']; ?>">            
                        </div>
                   <div class="inpt">
                            <span class="glyphicon glyphicon-wrench" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="password" name="blad_password" id="blad_password" placeholder="Password Baru / Password Lama" required onblur="blad_cek_pass();" >            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-wrench" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="password" name="blad_confirm" id="blad_confirm" placeholder="Confirm Password" required onfocus="blad_cek_pass();" onblur="blad_cek_pass();">            
                        </div>
                    <div id="msgpass"></div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-envelope" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_email" id="blad_email" placeholder="Email" required onfocus="blad_cek_pass();" onblur="blad_cek_pass();" value="<?php echo $blad_fetching['email']; ?>">            
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-credit-card" id="ico-left"></span> &nbsp;&nbsp;
                            <select name="blad_select_level" id="blad_select_level" onfocus="blad_cek_pass();" onblur="blad_cek_pass();">
                                <option selected disabled>- Ubah Hak Akses -</option>
                                <option value="member" <?php echo $blad_fetching['level']=='member'?'selected':''; ?>>Member</option>
                                <option value="lowadmin"  <?php echo $blad_fetching['level']=='lowadmin'?'selected':''; ?>>lowadmin</option>
                                <option value="admin"  <?php echo $blad_fetching['level']=='admin'?'selected':''; ?>>admin</option>
                        </select>
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-king" id="ico-left"></span> &nbsp;&nbsp;
                            <select name="blad_jenkel" id="blad_jenkel" onfocus="blad_cek_pass();" onblur="blad_cek_pass();">
                                <option selected disabled>- Ubah Jenis Kelamin -</option>
                                <option value="L" <?php echo $blad_fetching['jenkel']=='L'?'selected':''; ?>>L</option>
                                <option value="P" <?php echo $blad_fetching['jenkel']=='P'?'selected':''; ?>>P</option>
                        </select>
                        </div>
                    <div class="inpt">
                            <span class="glyphicon glyphicon-road" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_alamat" id="blad_alamat" placeholder="Alamat" required onfocus="blad_cek_pass();" onblur="blad_cek_pass();" value="<?php echo $blad_fetching['alamat']; ?>">      
                        </div>
                    
                    <div class="inpt">
                        <span class="glyphicon glyphicon-phone" id="ico-left"></span> &nbsp;&nbsp;
                            <input type="text" name="blad_no_hp" id="blad_no_hp" required  placeholder="No HP" onfocus="blad_cek_pass();" onblur="blad_cek_pass();" value="<?php echo $blad_fetching['no_hp']; ?>">            
                        </div>
                    <div class="img-user-edt">
                    <img src="../davinci/admin/<?php echo $blad_fetching['foto']; ?>" class="img-edit-user"></div>
                    Foto Maksimal 2 mb
                    <div class="inpt">
                            <input type="file" name="blad_foto_user" id="blad_foto_user"  onblur="blad_cek_foto()" onfocus="blad_cek_foto()" onchange="blad_cek_foto();">            
                        </div>
                    <div id="msgfoto"></div>
                            <input type="hidden" name="blad_ngumpul" id="blad_ngumpul" value="<?php echo date('d'." ".'M'." ".'Y'); ?>">            
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
          <input type="text" name="blad_cari" class="form-control" placeholder="ID, Nama User, Username">
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
        <li class="treeview active">
          <a href=""><span class="glyphicon glyphicon-user"></span> <span>Pengguna
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
          <ul class="treeview-menu">
            <li><a href="blad_admin_page.php">Admin <span class="badge">
                    <?php
                    $blad_sql_admn = mysql_query("select * from user where level='admin' or level='lowadmin'");
                    echo $blad_jml_admn = mysql_num_rows($blad_sql_admn);
                    ?>
                </span></a>
                
              </li>
            <li class="active"><a href="blad_member_page.php">Member
                <span class="badge">
                    <?php
                    $blad_sql_member = mysql_query("select * from user where level='member'");
                    echo $blad_jml_member = mysql_num_rows($blad_sql_member);
                    ?>
                </span>
                </a></li>
          </ul>
        </li>
            </a>
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
            <a href="blad_member_page.php ?blad_hps_all=ok"><button type="button" class="hapus-semua" onclick="return confirm('Apa anda yakin ingin menghapus semua data ini ? Data yang terhapus tidak dapat dikembalikan');"><span class="glyphicon glyphicon-trash"></span> Delete All</button></a>
            <button type="button" class="tambah" id="tmb_lapangan"><span class="glyphicon glyphicon-plus"></span> Tambah Member</button>
        
        <ol class="breadcrumb">
        <li><span class="glyphicon glyphicon-user"></span> &nbsp; DATA MEMBER</li>
      </ol>
    </section>
    <section class="content">
        <table>
            <tr>
                <th>No</th>
                <th>Id Admin</th>
                <th>Nama Admin</th>
                <th>Alamat</th>
                <th class="kota">Username</th>
                <th class="kecamatan">Password</th>
                <th class="kelurahan">Email</th>
                <th>Status</th>
                <th>No Hp</th>
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
                if($blad_rows['level']=="member"){
            ?>
            <tr bgcolor="<?php echo $blad_backcol; ?>">
                <td align="center"><?php echo $blad_no; ?></td>
                <td><?php echo substr($blad_rows['id_user'],0,5),"..."; ?></td>
                <td><?php echo $blad_rows['nama_user'];?></td>
                <td><?php echo $blad_rows['alamat'];?></td>
                <td class="kota" align="center"><?php echo $blad_rows['username']; ?></td>
                <td class="kecamatan"><?php echo substr($blad_rows['password'],0,7)."..."; ?></td>
                <td class="kelurahan"><?php echo $blad_rows['email']; ?></td>
                <td><?php echo $blad_rows['level']; ?></td>
                <td align="center"><?php echo substr($blad_rows['no_hp'],0,4)." - ".substr($blad_rows['no_hp'],4,4)." - ".substr($blad_rows['no_hp'],8,11); ?></td>
                <td align="center">
                    <a href="blad_detail_user.php?blad_id_lap=<?php echo $blad_rows['id_user']; ?>" class="detail"><span class="glyphicon glyphicon-folder-open"></span></a> &nbsp;
                    <a href="blad_member_page.php?blad_id_lap_hps=<?php echo $blad_rows['id_user']; ?>" class="hapus" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?');"><span class="glyphicon glyphicon-remove"></span></a> &nbsp;
                    <a href="blad_edt_admin.php?blad_id_lap_edt=<?php echo $blad_rows['id_user']; ?>" class="edit"><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
            </tr>
            <?php
                }else{
                    ?>
            <tr>
                <td colspan="10" align="center">
                    <?php
                echo"Data pengguna tidak ditemukan";
                ?>
                </td>
            </tr>
            <?php
                }
                        $blad_no++;
                    }
            }else{
                ?>
            <tr>
                <td colspan="10" align="center">
                    <?php
                echo"Data pengguna tidak ditemukan";
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