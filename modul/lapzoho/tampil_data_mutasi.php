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
$mth1 = date('m', strtotime($tgl_awal.'+ 1 months'));
$yr = date('Y', strtotime($kode1));
$yr1 = date('Y', strtotime($kode1));

$cektgl = date(m).date(Y);
$cektgl1 = $mth.$yr; 
$cektgl2 = $mth1.$yr1;

	$sqlProd = mysql_query("SELECT `Kode Produk` AS kodep, `Produk` AS nama,`Satuan` FROM `mproduk` WHERE `Kode Produk`='$kode2'");
	$hasilProd=mysql_fetch_array($sqlProd); 
	echo 
		"<p align='left'><font size='3'><b>L A P O R A N  M U T A S I   G U D A N G<br>
	 	Periode : ".$tgl_awal." s/d ".$tgl_akhir."<br></b></font></p>
<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th >NO</th>
			<th >CABANG</th>
			<th >PRINSIPAL</th>
			<th >PRODUK</th>
			<th >NAMA PRODUK</th>
			<th >AWAL</th>
			<th >MASUK</th>
			<th >KELUAR</th>
			<th >AKHIR</th>
		</tr>
		";

	if($cektgl==$cektgl1)
	{
	$sql1	= "SELECT Cabang,b.Prinsipal,a.Produk,b.Produk AS `NamaProduk`,
					SUM(awal) AS awal,SUM(masuk) AS masuk,SUM(keluar) AS keluar, 
					(SUM(awal)+ SUM(masuk)- SUM(keluar)) AS akhir
					
					FROM (
					
					SELECT Cabang,produk,SUM(`SAwal$mth`) AS awal, 0 AS masuk, 0 AS keluar, 0 AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status BPB` IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ABS((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))ELSE 0 END) AS keluar,
						0 AS akhir
					FROM `dbpbdodetail`
					WHERE cabang='$cabang' AND MONTH(`Tgl BPB`)='$mth' AND YEAR(`Tgl BPB`)='$yr'
					GROUP BY Cabang,Produk,`Status BPB`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status`='Retur' THEN ABS(`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status`='Faktur' THEN (`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS keluar,
						0 AS akhir
						FROM `dsalesdetail`
					WHERE cabang='$cabang' AND MONTH(`Tanggal`)='$mth' AND YEAR(`Tanggal`)='$yr'
					GROUP BY Cabang,Produk,`Status`
					
					UNION ALL
					
					SELECT Cabang,produk,0 AS awal, 0 AS masuk, 0 AS keluar, SUM(`SAwal$mth1`) AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					)a
					LEFT JOIN (SELECT `Prinsipal`,`Pabrik`,`Kode Produk`,`Produk` FROM `mproduk`) AS b ON a.Produk=b.`Kode Produk` 
					GROUP BY Cabang,Produk
					ORDER BY Cabang,b.Prinsipal,a.Produk";
	
	}else
	{
	$sql1	= "SELECT Cabang,b.Prinsipal,a.Produk,b.Produk AS `NamaProduk`,
					SUM(awal) AS awal,SUM(masuk) AS masuk,SUM(keluar) AS keluar, 
					SUM(akhir) AS akhir
					
					FROM (
					
					SELECT Cabang,produk,SUM(`SAwal$mth`) AS awal, 0 AS masuk, 0 AS keluar, 0 AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status BPB` IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ABS((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))ELSE 0 END) AS keluar,
						0 AS akhir
					FROM `dbpbdodetail`
					WHERE cabang='$cabang' AND MONTH(`Tgl BPB`)='$mth' AND YEAR(`Tgl BPB`)='$yr'
					GROUP BY Cabang,Produk,`Status BPB`
					
					UNION ALL
					
					SELECT Cabang,Produk,0 AS awal,
						SUM(CASE WHEN `Status`='Retur' THEN ABS(`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS masuk,
						SUM(CASE WHEN `Status`='Faktur' THEN (`Jumlah`+`Bonus Faktur`) ELSE 0 END) AS keluar,
						0 AS akhir
						FROM `dsalesdetail`
					WHERE cabang='$cabang' AND MONTH(`Tanggal`)='$mth' AND YEAR(`Tanggal`)='$yr'
					GROUP BY Cabang,Produk,`Status`
					
					UNION ALL
					
					SELECT Cabang,produk,0 AS awal, 0 AS masuk, 0 AS keluar, SUM(`SAwal$mth1`) AS akhir 
					FROM `dinventorysummary` WHERE cabang='$cabang'
					GROUP BY `cabang`,`produk`
					
					)a
					LEFT JOIN (SELECT `Prinsipal`,`Pabrik`,`Kode Produk`,`Produk` FROM `mproduk`) AS b ON a.Produk=b.`Kode Produk` 
					GROUP BY Cabang,Produk
					ORDER BY Cabang,b.Prinsipal,a.Produk";
	
	}
		

		//echo "<br>".$sql1."<br>";
		//echo "<br> php".date("m")."-".date("Y")."<br>";
		//echo "<br> session".$mth."-".$yr."<br>";
		//echo "<br>".$cektgl."<br>";
		//echo "<br>SAwal".$mth."<br>";
		//echo "<br>SAwal".$mth1."<br>";
		//die();
	
		$no=0 ;
		$query = mysql_query($sql1);	
		while($r_data=mysql_fetch_array($query)){
		
		if($r_data[awal]==0 && $r_data[masuk]==0 && $r_data[keluar]==0 && $r_data[akhir]==0)
		{
		}else
		{
			$no++;
			echo"	<tr>
						<td align='center'>$no</td>
						<td align='left'>$r_data[Cabang]</td>
						<td align='left'>$r_data[Prinsipal]</td>
						<td align='left'>$r_data[Produk]</td>
						<td align='left'>$r_data[NamaProduk]</td>
						<td align='right'>".format_rupiah_koma($r_data[awal])."</td>
						<td align='right'>".format_rupiah_koma($r_data[masuk])."</td>
						<td align='right'>".format_rupiah_koma($r_data[keluar])."</td>
						<td align='right'>".format_rupiah_koma($r_data[akhir])."</td>
					</tr>";
		
		}
		}
		
		echo "</table></div>";
			
?>
