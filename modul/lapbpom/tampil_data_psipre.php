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
// error_reporting( E_ALL );
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
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
	
	$sqlprod = "SELECT DISTINCT * FROM( 
					SELECT 
						a.Produk,b.Produk AS NamaProduk, 
						`Batch No` AS batch,
						(`Exp Date`) AS expr
					FROM `dbpbdodetail` a
					LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
					WHERE  a.Cabang = '$cabang' AND 
						b.Kategori IN ('Psikotropika','Precursor') AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
							OR `Status BPB` = 'BKB Retur' 
							OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
							OR `Status BPB` = 'BKB Relokasi' OR `Status BPB` = 'BKB Koreksi' 
							OR `Status BPB` = 'BPB Koreksi') AND `Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'
					UNION ALL 
					SELECT a.Produk,`namaproduk`,
						`Batch No` AS batch, 
						(`Exp Date`) AS expr
					FROM `dsalesdetail` a
					LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
					WHERE  a.Cabang = '$cabang' AND 
					b.Kategori IN ('Psikotropika','Precursor') AND(`Status`='Faktur' OR `Status`='Retur') 
					AND a.Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'  
					)a 
					ORDER BY a.Produk";
					
					
	
	
//	echo "<br>".$sql1."<br>";	
//	echo "<br>".$sqlprod."<br>";	
//die();
	$queryprod = mysql_query($sqlprod);
	while($p_data=mysql_fetch_array($queryprod))
	{
		echo "<tr>
		<td align='center'>Awal</td>
		<td align='left'>$p_data[NamaProduk]</td>
		<td align='center'>$tgl_awal</td>
		<td align='center'>0</td>
		<td align='center'>$p_data[batch]</td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'>$p_data[expr]</td>
		</tr>";

	echo cabDataPsiPre($p_data['Produk'],$cabang,$tgl_awal,$tgl_akhir,$p_data[batch],$p_data[expr])[0];
	//echo cabDataPsiPre($p_data['Produk'],$cabang,$tgl_awal,$tgl_akhir,$p_data[batch],$p_data[expr])[2];
	$saldoAkhir = cabDataPsiPre($p_data['Produk'],$cabang,$tgl_awal,$tgl_akhir,$p_data[batch],$p_data[expr])[1];

		echo "<tr>
		<td align='center'>Akhir</td>
		<td align='left'>$p_data[NamaProduk]</td>
		<td align='center'>$tgl_akhir</td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='center'></td>
		<td align='center'></td>
		<td align='center'>$saldoAkhir</td>
		<td align='center'>$p_data[batch]</td>
		<td align='center'>$p_data[expr]</td>
		</tr>";



	}
	
 		echo "</table>";
	echo "</div>";
?>