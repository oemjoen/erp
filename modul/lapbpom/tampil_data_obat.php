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
	
	
			$sql1 = "SELECT xyz.Cabang,xx.`Kode Produk` AS kode,xx.`Produk`,xx.`Farmalkes`,
							IFNULL(yy.stok,0) AS stk,
							IFNULL(zz.masuk,0) AS masuk,
							IFNULL(xyz.RS,0) AS RS,
							IFNULL(xyz.AP,0) AS AP,
							IFNULL(xyz.PBF,0) AS PBF,
							IFNULL(xyz.PEM,0) AS PEM,
							IFNULL(xyz.SW,0) AS SW,
							xyz.Harga
						FROM `mproduk` xx
								LEFT JOIN(
								SELECT  Cabang,Produk,IFNULL(SUM(SAwal$bln1),0) AS stok FROM `dinventorysummary` WHERE `Cabang`='$cabang' GROUP BY `Cabang`,`Produk`
								) yy ON xx.`Kode Produk`=yy.Produk

								LEFT JOIN(
								SELECT Cabang,`Produk`,SUM((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) AS masuk FROM `dbpbdodetail`
								WHERE `Cabang`='$cabang' 
								AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' OR `Status BPB` = 'BPB Retur' 
										OR `Status BPB` = 'BPB Relokasi' OR `Status BPB` = 'BPB Koreksi')
								AND (`Tgl BPB` BETWEEN DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
									AND LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH)))
								GROUP BY `Cabang`,`Produk`
								)zz ON  xx.`Kode Produk`=zz.Produk AND yy.Produk=zz.Produk AND yy.Cabang=zz.Cabang
								LEFT JOIN (
								SELECT a.Cabang,a.`NamaProduk`, a.`Harga`,a.`Pelanggan`,a.`Produk`,c.`Farmalkes`,
									SUM(CASE WHEN b.`Group Tipe`='Rumah Sakit' THEN (a.`Banyak`) ELSE 0 END) AS RS, 
									SUM(CASE WHEN b.`Group Tipe`='Apotek' THEN (a.`Banyak`) ELSE 0 END) AS AP, 
									SUM(CASE WHEN b.`Group Tipe`='PBF' THEN (a.`Banyak`) ELSE 0 END) AS PBF, 
									SUM(CASE WHEN b.`Group Tipe`='Pemerintah' THEN (a.`Banyak`) ELSE 0 END) AS PEM, 
									SUM(CASE WHEN b.`Group Tipe`='Swasta' THEN (a.`Banyak`) ELSE 0 END) AS SW  
								FROM dsalesdetail a, mpelanggan b, mproduk c
								WHERE a.`Status`='Faktur'
								AND a.`Cabang`='$cabang'
								AND a.`Cabang` = b.`Cabang` AND a.`Pelanggan`=b.`Kode`
								AND a.`Produk` = c.`Kode Produk` 
								AND (a.`Tanggal` BETWEEN DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
									AND LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH))) 
								GROUP BY a.`Cabang`,a.`Produk`)xyz
								ON xx.`Kode Produk`=xyz.Produk AND yy.Produk=xyz.Produk AND zz.Produk=xyz.Produk AND yy.Cabang=xyz.Cabang AND zz.Cabang=xyz.Cabang
								WHERE yy.stok IS NOT NULL
								 AND zz.masuk IS NOT NULL
								 AND xyz.RS IS NOT NULL
								 AND xyz.AP IS NOT NULL
								 AND xyz.PBF IS NOT NULL
								 AND xyz.PEM IS NOT NULL
								 AND xyz.SW IS NOT NULL
								 ORDER BY xx.`Farmalkes` DESC , xx.`Produk` ASC
								";
	
/*	$sql1 = "SELECT xyz.Cabang,xx.`Kode Produk` AS kode,xx.`Produk`,xx.`Farmalkes`,
					IFNULL(yy.stok,0) AS stk,
					IFNULL(zz.masuk,0) AS masuk,
					IFNULL(xyz.RS,0) AS RS,
					IFNULL(xyz.AP,0) AS AP,
					IFNULL(xyz.PBF,0) AS PBF,
					IFNULL(xyz.PEM,0) AS PEM,
					IFNULL(xyz.SW,0) AS SW,
					xyz.Harga
				FROM `mproduk` xx
				LEFT JOIN (
						SELECT Cabang,Produk,IFNULL(SUM(SAwal$bln1),0) AS stok FROM `dinventorysummary` WHERE `Cabang`='$cabang' GROUP BY `Cabang`,`Produk`) yy 
					ON xx.`Kode Produk`=yy.Produk
				LEFT JOIN (
						SELECT Cabang,`Produk`,SUM((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) AS masuk FROM `dbpbdodetail`
							WHERE `Cabang`='$cabang' 
							AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' OR `Status BPB` = 'BPB Retur' 
									OR `Status BPB` = 'BPB Relokasi' OR `Status BPB` = 'BPB Koreksi')
							AND (`Tgl BPB` BETWEEN DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
								AND LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH)))
							GROUP BY `Cabang`,`Produk`)zz
					ON  xx.`Kode Produk`=zz.Produk AND yy.Produk=zz.Produk AND yy.Cabang=zz.Cabang
				LEFT JOIN (
						SELECT a.Cabang,a.`NamaProduk`,a.Produk, a.`Harga`,
							SUM(CASE WHEN b.`Group Tipe`='Rumah Sakit' THEN (a.`Banyak`) ELSE 0 END) AS RS, 
							SUM(CASE WHEN b.`Group Tipe`='Apotek' THEN (a.`Banyak`) ELSE 0 END) AS AP, 
							SUM(CASE WHEN b.`Group Tipe`='PBF' THEN (a.`Banyak`) ELSE 0 END) AS PBF, 
							SUM(CASE WHEN b.`Group Tipe`='Pemerintah' THEN (a.`Banyak`) ELSE 0 END) AS PEM, 
							SUM(CASE WHEN b.`Group Tipe`='Swasta' THEN (a.`Banyak`) ELSE 0 END) AS SW 
						FROM `dsalesdetail` a,  mpelanggan b, mproduk c
						WHERE a.`Status`='Faktur'
							AND a.`Cabang`='$cabang' 
							AND a.`Cabang` = b.`Cabang` AND a.`Pelanggan`=b.`Kode`
							AND a.`Produk` = c.`Kode Produk` 
							AND c.`Farmalkes` IS NOT NULL
							AND (a.`Tanggal` BETWEEN DATE_SUB(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)),INTERVAL DAY(LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 0 MONTH)))-1 DAY) 
								AND LAST_DAY(DATE_ADD('$kode1-$bln-01', INTERVAL 2 MONTH))) 
						GROUP BY a.`Cabang`,a.`Produk`
						)xyz
					ON xx.`Kode Produk`=xyz.Produk AND yy.Produk=xyz.Produk AND zz.Produk=xyz.Produk AND yy.Cabang=xyz.Cabang AND zz.Cabang=xyz.Cabang
				WHERE xx.`Farmalkes` IS NOT NULL
					AND yy.stok IS NOT NULL
					AND zz.masuk IS NOT NULL
					AND xyz.RS IS NOT NULL
					AND xyz.AP IS NOT NULL
					AND xyz.PBF IS NOT NULL
					AND xyz.PEM IS NOT NULL
					AND xyz.SW IS NOT NULL
				ORDER BY xx.`Farmalkes` 
		";*/
					 
	 //echo "<br>".$sql1."<br>";die();	
	// echo "<br>".$kode1."-".$cabang."-".$kode2."<br>";	
	 //echo "<br>".$sql1."<br>";	
	
		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[Farmalkes]</td>
					<td align='left'>$r_data[Produk]</td>
					<td align='center'>$r_data[stk]</td>
					<td align='center'>0</td>
					<td align='center'>$r_data[masuk]</td>
					<td align='center'>$r_data[RS]</td>
					<td align='center'>$r_data[AP]</td>
					<td align='center'>$r_data[PBF]</td>
					<td align='center'>$r_data[PEM]</td>
					<td align='center'>$r_data[SW]</td>
					<td align='center'>$r_data[Harga]</td>
					</tr>
				";
		}
 		echo "</table>";
	echo "</div>";
?>