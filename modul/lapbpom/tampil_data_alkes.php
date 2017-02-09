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
	
	$sql1 = "SELECT 'Pusat' AS Asal,Cabang,`Nama Faktur` as salur ,det.`Produk`,`NamaProduk`, ifnull((SUM(`Jumlah`) + SUM(`Bonus Faktur`)),0) AS  `Jumlah`
					FROM `dsalesdetail` det
					LEFT JOIN (SELECT `Kode Produk`,Prinsipal FROM `mproduk` WHERE `Prinsipal` IN ('ALKES','ALKES-LAIN','KARINDO','AXION')) prod
						ON det.`Produk`=prod.`Kode Produk`
					WHERE prod.`Kode Produk` IS NOT NULL AND (`Tanggal` BETWEEN '$tgl_awal' AND '$tgl_akhir') AND Cabang ='$cabang'
					GROUP BY det.`Cabang`,det.`Produk`,det.`Pelanggan`
					HAVING (SUM(`Jumlah`) + SUM(`Bonus Faktur`)) != 0
					ORDER BY det.`Cabang`,prod.`Prinsipal`,det.`Produk`,det.`Pelanggan`";
					 
	//echo "<br>".$sql1."<br>";	

		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[NamaProduk]</td>
					<td align='center'>$r_data[Jumlah]</td>
					<td align='left'>$r_data[Asal]</td>
					<td align='left'>$r_data[salur]</td>
					<td align='center'></td>
					</tr>
				";
		}
 		echo "</table>";
	echo "</div>";
?>