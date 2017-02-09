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

	$sqlProd = mysql_query("SELECT `Kode Produk` AS kodep, `Produk` AS nama,`Satuan` FROM `mproduk` WHERE `Kode Produk`='$kode2'");
	$hasilProd=mysql_fetch_array($sqlProd); 
	echo 
		"<p align='left'><font size='3'><b>L A P O R A N  P T <br>
	 	Periode : ".$tgl_awal." s/d ".$tgl_akhir."<br></b></font></p>
<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th >NO</th>
			<th >WIL</th>
			<th >NMCABANG</th>
			<th >KODEJUAL</th>
			<th >NODOKJDI</th>
			<th >NOACU</th>
			<th >KODE PELANGGAN</th>
			<th >KODE KOTA</th>
			<th >KODE TYPE</th>
			<th >KODE LANG</th>
			<th >NAMA LANG</th>
			<th >ALAMAT</th>
			<th >JUDUL</th>
			<th >KODEPROD</th>
			<th >NAMAPROD</th>
			<th >UNIT</th>
			<th >PRINS</th>
			<th >BANYAK</th>
			<th >HARGA</th>
			<th >PRSNXTRA</th>
			<th >PRINPXTRA</th>
			<th >TOT1</th>
			<th >NILJU</th>
			<th >PPN</th>
			<th >COGS</th>
			<th >KODESALES</th>
			<th >TGLDOK</th>
			<th >TGLEXP</th>
			<th >BATCH</th>
			<th >AREA</th>
		</tr>
		";
	$sql1	= "SELECT 
					mcabang.`Region 1` AS 'WIL',
					dsalesdetail.Cabang AS 'NM_CABANG',
					IFNULL(dsalesdetail.`Cara Bayar`,'Kredit') AS 'KODE_JUAL',
					`No Faktur` AS 'NODOKJDI',
					IFNULL(`No Acu`,'') AS 'NO_ACU',
					dsalesdetail.`Pelanggan` AS 'KODE_PELANGGAN',
					mPelanggan.Kota AS 'KODE_KOTA',
					mPelanggan.`Tipe Pelanggan` AS 'KODE_TYPE',
					IFNULL(mPelanggan.Kode2,'') AS 'KODE_LANG',
					mPelanggan.Pelanggan AS 'NAMA_LANG',
					mPelanggan.Alamat AS 'ALAMAT',
					Prinsipal AS 'JUDUL',
					Produk AS 'KODEPROD',
					NamaProduk AS 'NAMAPROD',
					IFNULL(Jumlah,0) AS 'UNIT',
					IFNULL(`Bonus Faktur`,0) AS 'PRINS',
					IFNULL(Banyak,0) AS 'BANYAK',
					IFNULL(Harga,0) AS 'HARGA',
					IFNULL(`Dsc Cab`,0) AS 'PRSNXTRA',
					IFNULL(`Dsc Pri`,0) AS 'PRINPXTRA',
					IFNULL(Gross,0) AS 'TOT1',
					IFNULL(`Total Value`,0) AS 'NILJU',
					IFNULL(PPN,0) AS PPN,
					IFNULL(`Total COGS`,0) AS 'COGS',
					Salesman AS 'KODESALES',
					DATE(`Tanggal`) AS 'TGLDOK',
					IFNULL(DATE(`Exp Date`),'') AS 'TGLEXP',
					IFNULL(`Batch No`,'') AS 'BATCH',
					dsalesdetail.`Area String` AS 'Area'
			FROM `dsalesdetail`
			LEFT JOIN (SELECT Kode,Kode2,Kota,`Tipe Pelanggan`,Pelanggan,Alamat FROM mPelanggan) AS mPelanggan 
					ON dsalesdetail.`Pelanggan`=`mPelanggan`.`Kode`
			LEFT JOIN (SELECT `Region 1`,Cabang FROM mcabang) AS mcabang 
					ON `dsalesdetail`.`Cabang`=`mcabang`.Cabang
			WHERE `dsalesdetail`.`Cabang`='$cabang' AND MONTH(`Tanggal`)='$mth' AND YEAR(`Tanggal`)='$yr'
			ORDER BY dsalesdetail.`Tanggal`,dsalesdetail.`Counter`";	

	//	echo "<br>".$sql1."<br>";
	//die();
	
		$no=0 ;
		$query = mysql_query($sql1);	
		while($r_data=mysql_fetch_array($query)){
		$no++;
		echo"	<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[WIL]</td>
					<td align='left'>$r_data[NM_CABANG]</td>
					<td align='left'>$r_data[KODE_JUAL]</td>
					<td align='left'>$r_data[NODOKJDI]</td>
					<td align='left'>$r_data[NO_ACU]</td>
					<td align='left'>$r_data[KODE_PELANGGAN]</td>
					<td align='left'>$r_data[KODE_KOTA]</td>
					<td align='left'>$r_data[KODE_TYPE]</td>
					<td align='left'>$r_data[KODE_LANG]</td>
					<td align='left'>$r_data[NAMA_LANG]</td>
					<td align='left'>$r_data[ALAMAT]</td>
					<td align='left'>$r_data[JUDUL]</td>
					<td align='left'>$r_data[KODEPROD]</td>
					<td align='left'>$r_data[NAMAPROD]</td>
					<td align='left'>".format_rupiah_koma($r_data[UNIT])."</td>
					<td align='left'>$r_data[PRINS]</td>
					<td align='left'>".format_rupiah_koma($r_data[BANYAK])."</td>
					<td align='left'>".format_rupiah_koma($r_data[HARGA])."</td>
					<td align='left'>".format_rupiah_koma($r_data[PRSNXTRA])."</td>
					<td align='left'>".format_rupiah_koma($r_data[PRINPXTRA])."</td>
					<td align='left'>".format_rupiah_koma($r_data[TOT1])."</td>
					<td align='left'>".format_rupiah_koma($r_data[NILJU])."</td>
					<td align='left'>".format_rupiah_koma($r_data[PPN])."</td>
					<td align='left'>".format_rupiah_koma($r_data[COGS])."</td>
					<td align='left'>$r_data[KODESALES]</td>
					<td align='left'>$r_data[TGLDOK]</td>
					<td align='left'>$r_data[TGLEXP]</td>
					<td align='left'>$r_data[BATCH]</td>
					<td align='left'>$r_data[Area]</td>
				</tr>";
			
		}
		
		echo "</table></div>";
			
?>
