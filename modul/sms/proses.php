<?php
include "../../inc/inc.koneksi.php"; 
error_reporting(E_ALL ^ E_NOTICE);
$cab = $_GET['cab'];
$ord = $_GET['ord'];

$sqlD = "SELECT * FROM morder WHERE Cabang='$cab' AND `No Order`='$ord'";
$queryD = mysql_query($sqlD);
while($d_data=mysql_fetch_array($queryD)){
	$nTelp = $d_data[Telp];
}
	$sql = "UPDATE morder SET STATUS='Proses' WHERE Cabang='$cab' AND `No Order`='$ord' AND STATUS='Open'";
	$sqlsms = "INSERT INTO outbox(DestinationNumber, TextDecoded, CreatorID) VALUES ('$nTelp', 'Pesanan Anda dengan No Order $ord Telah di Proses dan Akan segera dikirim', 'Gammu')";
	mysql_query($sql);
	mysql_query($sqlsms);
	header("location:../../media.php?module=smsg"); 
 
//echo $sql;
  ?>
