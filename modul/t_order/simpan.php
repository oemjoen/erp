<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";
date_default_timezone_set('Asia/Jakarta'); 


$table		="t_order";
$tableh		="t_orderh";
		
$kode			=$_POST[kode];
$kodeO			=$_POST[kodeO];
$tgl			=jin_date_sql($_POST[tgl]);
$reta			=$_POST[reta];
$kode_barang	=$_POST[kode_barang];
$satuan			=$_POST[satuan];
$jumlah			=$_POST[jumlah];
$bonus			=$_POST[bonus];
$diskonitem		=$_POST[diskonitem];
$hrg			=$_POST[hrg];
$gross			=$_POST[gross];
$pot			=$_POST[pot];
$value			=$_POST[value];
$ppn			=$_POST[ppn];
$total			=$_POST[total];
$cabang			=$_POST[cabang];
$username		=$_POST[username];
$tglbuat		= date("Y-m-d H:i:s");
$banyak = $jumlah + $bonus;

$sql = mysql_query("SELECT * FROM $table WHERE NoOrder='$kodeO' AND KodeProduk='$kode_barang' AND Cabang='$cabang'");

$row	= mysql_num_rows($sql);
if ($row>0){
 	$input	= "UPDATE $table
	SET `Qty` = $jumlah,`Bonus` = '$bonus',`Banyak` = '$banyak',
		`Harga` = '$hrg',`Diskon` = '$diskonitem',`Gross` = '$gross',`Potongan` = '$pot',`Value` = '$value',
		`Ppn` = '$ppn',`Total` = '$total'
	WHERE `Cabang` = '$cabang' AND `NoOrder` = '$kodeO' AND `KodeProduk` = '$kode_barang'";
					
	mysql_query($input) or die(mysql_error());								
	echo "<label id='info'><b>Data Sukses diubah</b></label>";
}else{
 	$input = "INSERT INTO $table
       (`Cabang`,`KodeApotik`,`KodePelanggan`,`NoOrder`,`TglOrder`,`TimeOrder`,
			`KodeProduk`,`Qty`,`Bonus`,`Banyak`,`Harga`,`Diskon`,`Gross`,`Potongan`,`Value`,
			`Ppn`,`Total`,`Status`)
		VALUES ('$cabang','$username','$reta','$kode',
		'$tgl','$tglbuat','$kode_barang',$jumlah,'$bonus','$banyak','$hrg','$diskonitem',
		'$gross','$pot','$value','$ppn','$total','Open')";
	//mysql_query($input) or die(mysql_error());
	echo "<label id='info'><b>Data sukses disimpan</b></label>";
}	
//echo "<br/>".$input."<br/>";
include "tampil_data.php";



/* $sql = mysql_query("INSERT INTO `erp`.`t_orderh`
       (`Cabang`,`KodeApotik`,`NamaApotik`,`KodePelanggan`,`NamaPelanggan`,`NoOrder`,`TglOrder`,
			`TimeOrder`,`Gross`,`Potongan`,`Value`,`Ppn`,`Total`,`Status`,`Tipe`,`counter`)
		VALUES ('Cabang','KodeApotik','NamaApotik','KodePelanggan','NamaPelanggan','NoOrder','TglOrder',
			'TimeOrder','Gross','Potongan','Value','Ppn','Total','Status','Tipe','counter')";
        
        
$sqlh = mysql_query("INSERT INTO `erp`.`t_order`
       (`Cabang`,`KodeApotik`,`NamaApotik`,`KodePelanggan`,`NamaPelanggan`,`NoOrder`,`TglOrder`,`TimeOrder`,
	   `KodeProduk`,`NamaProduk`,`Qty`,`Bonus`,`Banyak`,`Harga`,`Diskon`,`Gross`,`Potongan`,`Value`,
	   `Ppn`,`Total`,`Status`,`Tipe`,`counter`)
		VALUES ('Cabang','KodeApotik','NamaApotik','KodePelanggan','NamaPelanggan','NoOrder','TglOrder','TimeOrder',
		'KodeProduk','NamaProduk','Qty','Bonus','Banyak','Harga','Diskon','Gross','Potongan','Value',
		'Ppn','Total','Status','Tipe','counter')");
 */
?>