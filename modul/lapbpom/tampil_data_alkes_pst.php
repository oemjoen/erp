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
$kode1	= jin_date_sql($_GET['kode1']);
//Produk
$kode2	= jin_date_sql($_GET['kode2']);
//cabang
$cabang	= $_GET['cabang'];


//cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime($kode2));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));
$yr = date('Y', strtotime($kode1));
$yr1 = date('Y', strtotime($kode1.'+ 1 months'));


	echo "<p align='left'><font size='3'><b>L A P O R A N    A L K E S <br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."<br></b></font></p>";
	echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th>NO</th>
			<th>NAMA PRODUK</th>
			<th>JUMLAH</th>
			<th>ASAL PRODUK</th>
			<th>DISALURKAN KEPADA</th>
			<th>KETERANGAN</th>
		</tr>";
					
	$sql1 = "SELECT IFNULL(xx.Asal,yy.`Supplier`) AS Asal,xx.salur,xx.Kode,xx.Nama,xx.Jumlah,yy.`Supplier` FROM(
					SELECT a.`Supplier` AS Asal,a.Cabang AS salur,a.`Produk` AS Kode,b.`Produk` AS Nama,
						SUM((IFNULL(a.`Qty Terima`,0) + IFNULL(a.`Bonus`,0))) AS Jumlah 
					FROM `dbpbdodetail` a, `mproduk` b
					WHERE 
						(a.`Cabang`!='Pusat' AND a.`Cabang`!='Dummy' AND a.`Cabang`!='Dummy22') 
						AND (a.`Status BPB` = 'Open' OR a.`Status BPB` = 'DO' OR a.`Status BPB` = 'BPB Retur' 
							OR a.`Status BPB` = 'BPB Relokasi' OR a.`Status BPB` = 'BPB Koreksi')
						AND ((a.`Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'))
						AND b.`Prinsipal` IN ('ALKES','ALKES-LAIN','KARINDO','AXION')
						AND a.`Produk`=b.`Kode Produk` 
					GROUP BY a.`Cabang`,a.`Produk`
					ORDER BY a.`Cabang`,a.`Supplier`,a.`Produk`)xx,(SELECT DISTINCT Produk,`Supplier` FROM `dbpbdodetail` WHERE `supplier` IS NOT NULL) yy
					WHERE xx.Kode = yy.`Produk`
					ORDER BY xx.`Nama`,xx.`Asal`,xx.`salur`
";
					 
	//echo "<br>".$sql1."<br>";	die();

		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[Nama]</td>
					<td align='center'>$r_data[Jumlah]</td>
					<td align='left'>$r_data[Asal]</td>
					<td align='left'>".strtoupper($r_data[salur]."</td>
					<td align='center'></td>
					</tr>
				";
		}
 		echo "</table>";
	echo "</div>";
?>