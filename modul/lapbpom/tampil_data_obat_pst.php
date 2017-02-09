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

if($kode2=='1')
{
	$bln = '01';
	$bln1 = '01';
	if($kode1=='2015')
	{
	$bln1 = '0115';
	}
}else if ($kode2=='2')
{
	$bln = '04';
	$bln1 = '04';
	if($kode1=='2015')
	{
	$bln1 = '0415';
	}
}else if ($kode2=='3')
{
	$bln = '07';
	$bln1 = '07';
	if($kode1=='2015')
	{
	$bln1 = '0715';
	}
}else if ($kode2=='4')
{
	$bln = '10';
	$bln1 = '10';
	if($kode1=='2015')
	{
	$bln1 = '1015';
	}
}
else
{
	$bln = '';
	$bln1 = '';
}

	echo "<p align='left'><font size='3'><b>PELAPORAN OBAT PERIODE TRIWULAN : ".$kode2." TAHUN ".$kode1." PBF SAPTASARITAMA<br></b></font></p>";
	echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th rowspan=2>NO</th>
			<th rowspan=2>KODE OBAT</th>
			<th rowspan=2>NAMA OBAT</th>
			<th rowspan=2>STOK AWAL</th>
			<th colspan=2>JUMLAH PEMASUKAN MASUK</th>
			<th colspan=5>JUMLAH PENGELUARAN</th>
			<th rowspan=2>HJD</th>
		</tr>
		<tr>
			<th>MASUK PABRIK</th>
			<th>MASUK PBF</th>
			<th>KELUAR RS</th>
			<th>KELUAR APOTEK</th>
			<th>KELUAR PBF</th>
			<th>KELUAR PEMERINTAH</th>
			<th>KELUAR SWASTA</th>
		</tr>
		";
	$sql1 = "SELECT a.`Produk` AS Kode,b.`Farmalkes`,b.`Produk` AS Nama,0 AS StokAwal,
							SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) AS MasukPabrik, 
							SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) AS KeluarPBF,
							0 AS Akhir,MAX(a.`Hrg Beli Cab`) AS HJD 
						FROM `dbpbdodetail` a, `mproduk` b
						WHERE 
							(a.`Cabang`!='Pusat' AND a.`Cabang`!='Dummy' AND a.`Cabang`!='Dummy22') AND 
							(a.`Status BPB` = 'Open' OR a.`Status BPB` = 'DO' OR a.`Status BPB` = 'BPB Retur' 
								OR a.`Status BPB` = 'BPB Relokasi' OR a.`Status BPB` = 'BPB Koreksi')
							AND (a.`Tgl BPB` BETWEEN DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),
								INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
								AND LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH)))
							AND a.`Produk`=b.`Kode Produk` AND b.`Farmalkes` IS NOT NULL
						GROUP BY b.`Farmalkes`
		";
					 
	 //echo "<br>".$sql1."<br>";die();	
	// echo "<br>".$kode1."-".$cabang."-".$kode2."<br>";	
	

		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[Farmalkes]</td>
					<td align='left'>$r_data[Nama]</td>
					<td align='center'>$r_data[StokAwal]</td>
					<td align='center'>$r_data[MasukPabrik]</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[KeluarPBF]</td>
					<td align='center'>0</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[HJD]</td>
					</tr>
				";
		}
 		echo "</table>";
	echo "</div>";
?>