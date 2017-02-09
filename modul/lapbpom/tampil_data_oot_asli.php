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
//error_reporting( E_ALL );
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';
include '../../inc/fungsi_rupiah.php';
date_default_timezone_set('Asia/Jakarta'); 
//Tanggal
$kode1	= jin_date_sql($_GET['kode1']);
//Produk
$kode2	= $_GET['kode2'];
//cabang
$cabang	= $_GET['cabang'];


//cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime($kode1));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));
$yr = date('Y', strtotime($kode1));
$yr1 = date('Y', strtotime($kode1));


	//echo "<p align='left'><font size='3'><b>K A R T U   G U D A N G<br>Produk : <br></b></font></p>";
	echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th>NO</th>
			<th>NAMA PRODUK</th>
			<th>TANGGAL</th>
			<th>SALDO AWAL</th>
			<th>BATCH AWAL</th>
			<th>DOKUMEN MASUK</th>
			<th>SUMBER</th>
			<th>JUMLAH MASUK</th>
			<th>BATCH MASUK</th>
			<th>DOKUMEN KELUAR</th>
			<th>TUJUAN</th>
			<th>JUMLAH KELUAR</th>
			<th>BATCH KELUAR</th>
			<th>SALDO AKHIR</th>
			<th>BATCH AKHIR</th>
			<th>EXPIRED DATE</th>
		</tr>";
	

	$sql1="SELECT * FROM( 
						SELECT 
							a.Produk,b.Produk AS NamaProduk,
							DATE(`Tgl BPB`) AS `Tanggal`, 
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN IFNULL(`Batch No`,'') 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN `No BPB` 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN 'PUSAT' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN `No BPB` 
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN 'PUSAT' 
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))*-1 
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN IFNULL(`Batch No`,'') 
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch,
							IFNULL(DATE(`Exp Date`),'') AS expr, 
							`Time BPB` AS dTime, `Counter BPB` AS counter, `Keterangan` AS Keterangan
						FROM `dbpbdodetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cabang' AND
							b.Kategori IN ('OOT') AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
								OR `Status BPB` = 'BKB Retur' 
								OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
								OR `Status BPB` = 'BKB Relokasi' OR `Status BPB` = 'BKB Koreksi' 
								OR `Status BPB` = 'BPB Koreksi') AND `Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'
					UNION ALL 
						SELECT a.Produk,`namaproduk`,DATE(`Tanggal`) AS `Tanggal`,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN IFNULL(`Batch No`,'')			
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `No Faktur` 			
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `Nama Faktur`			
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0))) * -1			
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `No Faktur`
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `Nama Faktur` 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) ) 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN IFNULL(`Batch No`,'')
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch, 
							IFNULL(DATE(`Exp Date`),'') AS expr,
							`Time` AS dTime,`Counter` AS counter, '' AS Keterangan
						FROM `dsalesdetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cabang' AND
						b.Kategori IN ('OOT') AND(`Status`='Faktur' OR `Status`='Retur') 
						AND Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'  
					)a 
					ORDER BY a.Produk,a.Tanggal,a.Counter,a.dTime
;";
	
	//echo "<br>".$sql1."<br>";die();

		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[NamaProduk]</td>
					<td align='center'>$r_data[Tanggal]</td>
					<td align='center'></td>
					<td align='center'>$r_data[batch]</td>
					<td align='left'>$r_data[NoDokMasuk]</td>
					<td align='left'>$r_data[sumber]</td>
					<td align='center'>$r_data[JumlahMasuk]</td>
					<td align='center'>$r_data[BatchMasuk]</td>
					<td align='left'>$r_data[NoDokKeluar]</td>
					<td align='left'>$r_data[tujuan]</td>
					<td align='center'>$r_data[JumlahKeluar]</td>
					<td align='center'>$r_data[BatchKeluar]</td>
					<td align='center'></td>
					<td align='center'></td>
					<td align='center'>$r_data[expr]</td>
					</tr>";
			
		}
 		echo "</table>";
	echo "</div>";
?>