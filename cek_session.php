<?php
session_start();
error_reporting(0);
include "timeout.php";

$userrr = $_SESSION['namauser'];
$namarr = $_SESSION['namalengkap'];
$cabangrr = $_SESSION['cabang2'];
$levelrr = $_SESSION['leveluser'];
$cabangrr2 = $_SESSION['cabang2'];

if($_SESSION[login]==1){
	if(!cek_login()){
		$_SESSION[login] = 0;
	}
}
if($_SESSION[login]==0){
  header('location:logout.php');
}
if (empty($_SESSION['username']) AND empty($_SESSION['passuser']) AND $_SESSION['login']==0){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=index.php><b>LOGIN</b></a></center>";
}
?>