<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";

$kode	= $_POST['kode'];
$kode_btb	= $_POST['kode_btb'];
$cabang	= $_POST['cabang'];

$text	= "SELECT a.*,b.`namasupplier` FROM `mstproduk` a 
			LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`
						WHERE a.kodeproduk='$kode'";
			
$sql 	= mysql_query($text) or die(mysql_error());
$row	= mysql_num_rows($sql);

//echo $text;

if ($row>0){
	while ($r=mysql_fetch_array($sql)){	

		$data['namaproduk']			= $r[namaproduk].' >> '.$r[namasupplier];
		$data['satuan']				= $r[satuan];
		
		echo json_encode($data);
	}
}else{
		
				$data['namaproduk']			= ' - ';
				$data['satuan']				= ' - ';
				
				echo json_encode($data);
			}

?>