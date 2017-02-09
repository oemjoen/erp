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



	echo "<p align='left'><font size='3'><b>L A P O R A N    P E M B E L I A N <br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."<br></b></font></p>";
	echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th>NO</th>
			<th>PRINSIPAL</th>
			<th>CABANG</th>
			<th>KODE PRODUK</th>
			<th>NAMA PRODUK</th>
			<th>NO DOKUMEN</th>
			<th>TGL DOKUMEN</th>
			<th>BATCH NO</th>
			<th>EXP DATE</th>
			<th>JUMLAH</th>
			<th>HARGA BELI</th>
			<th>% DISC</th>
			<th>BONUS</th>
			<th>TOTAL</th>
			<th>NO ACUAN</th>
			<th>_</th>
			<th>STATUS</th>
		</tr>";
	
	$sql1 = "SELECT 
		  a.`Prinsipal`,
		  a.`Cabang`,
		  b.`Kode Produk` AS kode,
		  b.Produk,
		  a.`No BPB` AS bpb,
		  IFNULL(DATE(a.`Tgl BPB`),'') as tgl,
		  IFNULL(a.`Batch No`,'') AS BatchNo,
		  IFNULL(DATE(a.`Exp Date`),'') AS expd,
		  a.`Qty Terima` AS JumlahUnit,
		  a.`Hrg Beli Cab` AS HargaBeli,
		  a.`Disc Cab` AS Disc,
		  a.`Bonus` AS BonusBarang,
		  a.`Value BPB` AS TotalPembelian,
		  a.`No DO` AS NoAcuan,
		  a.`Time BPB` AS Vtime,
		  a.`Status BPB` AS stats
		FROM `dbpbdodetail` a
	LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
	WHERE  a.Cabang = '$cabang' AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
			OR `Status BPB` = 'BKB Retur' 
			OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
			OR `Status BPB` = 'BKB Relokasi' OR `Status BPB` = 'BKB Koreksi' 
			OR `Status BPB` = 'BPB Koreksi') 
		AND (`Tgl BPB` BETWEEN '$kode1' AND '$kode2')
	ORDER BY a.`Tgl BPB`,a.`Counter BPB`, a.`Time BPB`,a.`Prinsipal`,a.`Produk`";
					 
	 //echo "<br>".$sql1."<br>";	
	//exit(1);
		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[Prinsipal]</td>
					<td align='left'>$r_data[Cabang]</td>
					<td align='left'>$r_data[kode]</td>
					<td align='left'>$r_data[Produk]</td>
					<td align='left'>$r_data[bpb]</td>
					<td align='left'>$r_data[tgl]</td>
					<td align='left'>$r_data[BatchNo]</td>
					<td align='left'>$r_data[expd]</td>
					<td align='center'>".format_rupiah_koma($r_data[JumlahUnit])."</td>
					<td align='center'>".format_rupiah_koma($r_data[HargaBeli])."</td>
					<td align='center'>$r_data[Disc]</td>
					<td align='center'>".format_rupiah_koma($r_data[BonusBarang])."</td>
					<td align='center'>".format_rupiah_koma($r_data[TotalPembelian])."</td>
					<td align='left'>$r_data[NoAcuan]</td>
					<td align='left'>$r_data[Vtime]</td>
					<td align='left'>$r_data[stats]</td>
					</tr>
				";
		}
 		echo "</table>";
	echo "</div>";
?>