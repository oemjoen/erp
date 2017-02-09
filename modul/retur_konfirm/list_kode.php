<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";

$tgl	= jin_date_sql($_POST["tgl"]);
$cab	= $_POST["cabang1"];
$prins	= $_POST["prins"];

//ambil bulan saja
$mth = date('m', strtotime($tgl));
$yr = date('Y', strtotime($tgl));

$q = "SELECT DISTINCT `No BPB` AS No,`cabang`,a.`Prinsipal`,`Tgl BPB`,`Counter BPB`,a.`Supplier`,
									b.`Kode`
							FROM `dbpbdodetail` a, `mprinsipal` b
							WHERE cabang = '$cab'  AND `Status BPB` = 'DO' AND MONTH(`Tgl BPB`)='$mth' AND YEAR(`Tgl BPB`)='$yr'
									AND a.`Prinsipal`=b.`Prinsipal` AND b.Kode = $prins
							ORDER BY `Tgl BPB`,`Counter BPB`";

$sql	= mysql_query($q);
							
echo $q;

while($r=mysql_fetch_array($sql)){
	$kode = $r[No];
	$cabang = $r[cabang];
	$sup = $r[Supplier];
	echo "<option value='$kode'>$kode - $cabang - $sup</option>";
}
?>