<script type="text/javascript">
    $(function() {
        $("#theList tr:even").addClass("stripe1");
        $("#theList tr:odd").addClass("stripe2");

        $("#theList tr").hover(
            function() {
                $(this).toggleClass("highlight");
            },
            function() {
                $(this).toggleClass("highlight");
            }
        );
    });
</script>
<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';
include '../../inc/fungsi_rupiah.php';
date_default_timezone_set('Asia/Jakarta'); 
//Tanggal
//$kode1	= jin_date_sql($_GET['kode1']);
$kode1	= ($_GET['kode1']);
//Triwulan
$kode2	= $_GET['kode2'];
//cabang
$cabang	= $_GET['cabang'];

/* //cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime($kode1));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));
$yr = date('Y', strtotime($kode1));
$yr1 = date('Y', strtotime($kode1.'+ 1 months')); */

if($kode2=='1')
{
	$bln = '01';
}else if ($kode2=='2')
{
	$bln = '04';
}else if ($kode2=='3')
{
	$bln = '07';
}else if ($kode2=='4')
{
	$bln = '10';
}
else
{
	$bln = '';
}

	echo "<p align='left'><font size='3'><b>PELAPORAN RETUR OBAT PERIODE TRIWULAN : ".$kode2." TAHUN ".$kode1." PBF SAPTASARITAMA<br></b></font></p>";
	echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th rowspan=2>NO</th>
			<th rowspan=2>KODE OBAT</th>
			<th rowspan=2>NAMA OBAT</th>
			<th colspan=5>RETUR MASUK</th>
			<th colspan=3>RETUR KELUAR</th>
		</tr>
		<tr>
			<th>DARI RS</th>
			<th>DARI APOTEK</th>
			<th>DARI PBF</th>
			<th>DARI PEMERINTAH</th>
			<th>DARI SWASTA</th>
			<th>PEMUSNAHAN</th>
			<th>KEMBALI KE PABRIK</th>
			<th>KEMBALI KE PBF</th>
		</tr>
		";
	
				$sql1 = "SELECT a.`Produk` AS Kode,b.`Farmalkes`,b.`Produk` AS Nama,
								SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) * -1 AS ReturMasukPBF, 
								SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) * -1 AS ReturKeluarPabrik,
								MAX(a.`Hrg Beli Cab`) AS HJD 
							FROM `dbpbdodetail` a, `mproduk` b
							WHERE 
								(a.`Cabang`!='Pusat' AND a.`Cabang`!='Dummy' AND a.`Cabang`!='Dummy22') AND 
								(a.`Status BPB` = 'BKB Retur')
								AND (a.`Tgl BPB` BETWEEN DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),
									INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
									AND LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH)))
								AND a.`Produk`=b.`Kode Produk` AND b.`Farmalkes` IS NOT NULL
							GROUP BY b.`Farmalkes`
		;";
					 
	// echo "<br>".$sql1."<br>";	
	// echo "<br>".$kode1."-".$cabang."-".$kode2."<br>";	
	

		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[Farmalkes]</td>
					<td align='left'>$r_data[Nama]</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[ReturMasukPBF]</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[ReturKeluarPabrik]</td>
					<td align='center'>0</td>
					</tr>
				";
		}
 		echo "</table>";
	echo "</div>";
?>