<?php
$bl_koneksi = mysql_connect('localhost','root','satusampe250599') or die(mysql_error());
$bl_db = mysql_select_db('2017_web_native_booking_lapangan',$bl_koneksi) or die(mysql_error());
?>