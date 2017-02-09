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
	echo "<p align='left'><font size='3'><b>K A R T U   G U D A N G<br>Produk : ".$hasilProd['kodep']." - ".$hasilProd['nama']."<br>Satuan : ".$hasilProd['Satuan']."<br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."<br></b></font></p>";
	echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th rowspan=2>NO</th>
			<th rowspan=2>TANGGAL</th>
			<th rowspan=2>TERIMA DARI / KIRIM KEPADA</th>
			<th rowspan=2>NO DOK</th>
			<th rowspan=2>BATCH</th>
			<th rowspan=2>EXP DATE</th>
			<th colspan=2>MUTASI</th>
			<th rowspan=2>SALDO</th>
		</tr>
		<tr>
			<th>MASUK</th>
			<th>KELUAR</th>
		</tr>";
	

	$sql1	= "SELECT *
					FROM(
					SELECT DATE(`Tgl BPB`) AS `Tanggal`, 
						`Supplier` AS Pelanggan,
						`No BPB`AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) AS `Mutasi`, 		
						`Time BPB` AS dTime,
						Produk,`Counter BPB` AS counter, `Keterangan` AS Keterangan, 
						`HPC1` AS cogs,
						`Value BPB` AS val, 
						ROUND((`Value BPB`/((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))),0) AS cogs2
					FROM `dbpbdodetail`
					WHERE Cabang='$cabang' AND Produk='$kode2'
					AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' OR `Status BPB` = 'BKB Retur'
					OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' OR `Status BPB` = 'BKB Relokasi'
					OR `Status BPB` = 'BKB Koreksi' OR `Status BPB` = 'BPB Koreksi')
					UNION ALL
					SELECT DATE(`Tanggal`) AS `Tanggal`,
						`Nama Faktur` AS Pelanggan,
						`No Faktur` AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 ) AS `Mutasi`, 
						`Time` AS dTime,
						Produk,`Counter` AS counter, '' AS Keterangan, 
						ROUND((`Total COGS`/((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 )),0) AS cogs , 
						`Total COGS` AS val, 
						ROUND((`Total COGS`/((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 )),0) AS cogs2
					FROM `dsalesdetail` 
					WHERE Cabang='$cabang' AND Produk='$kode2'
					AND (`Status`='Faktur' OR `Status`='Retur')
					 )a
					 WHERE a.Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
					 ORDER BY a.Tanggal,a.Counter,a.dTime";	
					 
	//echo "<br>".$sql1."<br>";	

		$no=1 ;
		//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
		$sAwal = SaldoAwalStok($kode2,$kode1,$cabang);
		$tglD = $tgl_awal;
		$msk = $sAwal[0];
		$klr = 0;
		$bal = $sAwal[0];
		echo "<tr><td align='center'>".$no."</td><td align='center'>".$tgl_awal."</td><td colspan='6'><b>Saldo Awal ".$hasilProd['kodep']." - ".$hasilProd['nama']."</b></td><td>".format_rupiah($bal)."</td></tr>";

		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$sld= $r_data[Mutasi];
			$vsld = $r_data[cogs];
			$bal= $bal + $sld;
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[Tanggal]</td>";
			if (strpos($r_data[NoDok], "KOR") !== false) 
			{
				echo "<td>$r_data[Keterangan]</td>";
			}
			elseif(strpos($r_data[NoDok], "RLK") !== false)
			{
				 echo "<td>".cabKirimRLK($r_data[NoDok],$cabang)[0]."</td>";
			 }
			else
			{
				echo "<td>$r_data[Pelanggan]</td>";					
			}
			echo"	<td>$r_data[NoDok]</td>
					<td>$r_data[batch]</td>
					<td align='center'>$r_data[expr]</td>";
			if($sld>0)
			{
			echo"	<td align='right'>".format_rupiah($sld)."</td><td align='right'>&nbsp</td>";
			$msk = $msk + $sld;
			}else
			{
			echo"	<td align='right'>&nbsp</td><td align='right'>".format_rupiah($sld*-1)."</td>";
			$klr = $klr + $sld;
			}

			echo"	<td align='right'>".format_rupiah($bal)."</td></tr>";
			
		}
		//saldo mutasi
		echo "<tr><td align='center'>".($no+1)."</td><td align='center'>".$tgl_akhir."</td><td colspan='4'><b>Saldo Akhir Mutasi Produk ".$hasilProd['kodep']." - ".$hasilProd['nama']."</b></td><td>".format_rupiah($msk)."</td><td>".format_rupiah($klr * -1)."</td><td>".format_rupiah($bal)."</td></tr>";

		echo "<tr><td colspan='9'></td>&nbsp</td></tr>";
		echo "<tr><td colspan='9'></td>&nbsp</td></tr>";
		//saldo awal
		echo "<tr><td colspan='7'><b>Saldo Awal ".SaldoAwalStok($kode2,$tglD,$cabang)[2]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'><b>".format_rupiah(SaldoAwalStok($kode2,$tglD,$cabang)[0])."</td><td align='right'><b>".format_rupiah(SaldoAwalStok($kode2,$tglD,$cabang)[1])."</td></tr>";
		$mth = date('m', strtotime($tglD));
		$sqlg	= "SELECT IFNULL((SAwal$mth ),0) AS unit,IFNULL((VAwal$mth),0) AS val, Gudang FROM `dinventorysummary` WHERE `Produk`='$kode2' 
						AND `cabang`='$cabang' ORDER BY Gudang";		
		$queryg = mysql_query($sqlg);
		while($g_data=mysql_fetch_array($queryg)){
		echo "<tr><td colspan='5'>Gudang ".$g_data[Gudang]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'>".format_rupiah($g_data[unit])."</td><td align='right'>".format_rupiah($g_data[val])."</td><td align='right' colspan='2'></td></tr>";		
		}
		$tglcek = date('Y-m-01', strtotime($kode1));
		$cekhariberjalan = date('Y-m-01', strtotime($hari_ini));
		//echo $hari_ini." - ".$tglcek." - ".$cekhariberjalan;
  		if ($cekhariberjalan == $tglcek)
		{
 			//saldo berjalan
			echo "<tr><td colspan='7'><b>Saldo Sekarang ".$hari_ini." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'><b>".format_rupiah(SaldoAwalStok($kode2,$tglD,$cabang)[3])."</td><td align='right'><b>".format_rupiah(SaldoAwalStok($kode2,$tglD,$cabang)[4])."</td></tr>";
			$sqlgs	= "SELECT IFNULL((`Unit Stok`),0) AS unit,IFNULL((`Value Stok`),0) AS val, Gudang FROM `dinventorysummary` WHERE `Produk`='$kode2' 
							AND `cabang`='$cabang' ORDER BY Gudang";		
			$querygs = mysql_query($sqlgs);
			while($gs_data=mysql_fetch_array($querygs)){
			echo "<tr><td colspan='5'>Gudang ".$gs_data[Gudang]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'>".format_rupiah($gs_data[unit])."</td><td align='right'>".format_rupiah($gs_data[val])."</td><td align='right'  colspan='2'></td></tr>";		
			}
 		}
		else
		{
 			//saldo akhir
			echo "<tr><td colspan='7'><b>Saldo Awal ".SaldoAwalStok($kode2,date('Y-m-d', strtotime($tglD. ' + 1 months')),$cabang)[2]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'><b>".format_rupiah(SaldoAwalStok($kode2,date('Y-m-d', strtotime($tglD. ' + 1 months')),$cabang)[0])."</td><td align='right'><b>".format_rupiah(SaldoAwalStok($kode2,date('Y-m-d', strtotime($tglD. ' + 1 months')),$cabang)[1])."</td></tr>";
			$mth1 = date('m', strtotime($tglD.' + 1 months'));
			$sqlg1	= "SELECT IFNULL((SAwal$mth1 ),0) AS unit,IFNULL((VAwal$mth1),0) AS val, Gudang FROM `dinventorysummary` WHERE `Produk`='$kode2' 
							AND `cabang`='$cabang' ORDER BY Gudang";		
			$queryg1 = mysql_query($sqlg1);
			while($g_data1=mysql_fetch_array($queryg1)){
			echo "<tr><td colspan='5'>Gudang ".$g_data1[Gudang]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'>".format_rupiah($g_data1[unit])."</td><td align='right'>".format_rupiah($g_data1[val])."</td><td align='right' colspan='2'></td></tr>";		
			}
 		} 
 		echo "</table>";
	echo "</div>";
?>