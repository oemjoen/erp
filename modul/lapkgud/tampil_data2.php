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



//$kode1	= "2014-06-16";
//$kode2	= "2014-06-24";

//$hal	= $_GET['hal'] ? $_GET['hal'] : 0;
//$lim	= 500;

/* if ($cabang == 'KPS'){$where2="";}
else{$where2="cabang='$cabang' AND ";}

if (empty($kode1) && empty($kode2)){
	$query2	= "SELECT `Tanggal`,`Terima Dari/Kirim Dari` AS Pelanggan,
					`No Dok` as Dokumen,`Batch No` AS BATCH ,`Exp Date` as EXPD ,`Mutasi`,`Saldo` FROM (
							SELECT '2016-03-01' AS Tanggal,  
											'Saldo Awal' AS `Terima Dari/Kirim Dari`,
											'' AS `No Dok`,
											'' AS `Batch No`,
											'' AS `Exp Date`,
											SUM(`SAwal03`) AS `Mutasi`,
											'' AS `Saldo`
			FROM `dinventorysummary` WHERE `Cabang`='Bandung' AND produk='MCLSR1' 
			UNION
			(SELECT DATE(`Tanggal`) AS `Tanggal`,
							`Nama Faktur` AS `Terima Dari/Kirim Dari`,
							`No Faktur` AS `No Dok`,IFNULL(`Batch No`,'') AS `Batch No`,
							IFNULL(DATE(`Exp Date`),'') AS `Exp Date`,
							((`Jumlah` + `Bonus Faktur`) * -1 ) AS `Mutasi`,'' AS `Saldo`
			FROM `dsalesdetail` WHERE Cabang='Bandung' AND Produk='MCLSR1' 
			AND (`Status`='Faktur' OR `Status`='Retur')
			ORDER BY `Tanggal`,`Added Time`)
			)AS a ORDER BY Tanggal";
}else{
	$query2	= "";
}
		//echo $query2."</br>";

	$data2	= mysql_query($query2);
	$jml	= mysql_num_rows($data2);
		//echo $jml."-".$lim;
	$max	= ceil($jml/$lim); */

/* echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th>NO</th>
			<th>TANGGAL</th>
			<th>TERIMA DARI / KIRIM KEPADA</th>
			<th>NO DOK</th>
			<th>BATCH</th>
			<th>EXP DATE</th>
			<th>MUTASI</th>
			<th>SALDO</th>
		</tr>";
		if (empty($kode1) && empty($kode2)){
		$sql = "SELECT `Tanggal`,`Terima Dari/Kirim Dari` AS Pelanggan,`No Dok` AS Dokumen,`Batch No` AS BATCH,`Exp Date` as EXPD,`Mutasi`,
				`Saldo` FROM (
							SELECT '2016-03-01' AS Tanggal,  
											'Saldo Awal' AS `Terima Dari/Kirim Dari`,
											'' AS `No Dok`,
											'' AS `Batch No`,
											'' AS `Exp Date`,
											SUM(`SAwal03`) AS `Mutasi`,
											'' AS `Saldo`
			FROM `dinventorysummary` WHERE `Cabang`='Bandung' AND produk='MCLSR1' 
			UNION
			(SELECT DATE(`Tanggal`) AS `Tanggal`,`Nama Faktur` AS `Terima Dari/Kirim Dari`,
							`No Faktur` AS `No Dok`,IFNULL(`Batch No`,'') AS `Batch No`,IFNULL(DATE(`Exp Date`),'') AS `Exp Date`,
							((`Jumlah` + `Bonus Faktur`) * -1 ) AS `Mutasi`,'' AS `Saldo`
			FROM `dsalesdetail` WHERE Cabang='Bandung' AND Produk='MCLSR1' 
			AND (`Status`='Faktur' OR `Status`='Retur')
			ORDER BY `Tanggal`,`Added Time`)
			)AS a ORDER BY Tanggal";
		}else{
		$sql = "";
		}
		
		//echo $where2." - ".$cabang."</br>";
		//echo $sql."</br>";
		$query = mysql_query($sql);
		
		$no=1+$hal;
		$bal = 0;
		while($r_data=mysql_fetch_array($query)){
				$sld= $r_data[Mutasi];
				$bal= $bal + $sld;
 			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[Tanggal]</td>
					<td>$r_data[Pelanggan]</td>
					<td>$r_data[Dokumen]</td>
					<td>$r_data[BATCH]</td>
					<td>$r_data[EXPD]</td>
					<td align='right'>$sld</td>
					<td>$bal</td>
					</tr>";
			$no++;
		}
		
	echo "</table>"; */

	$sqlProd = mysql_query("SELECT `Kode Produk` AS kodep, `Produk` AS nama FROM `mproduk` WHERE `Kode Produk`='$kode2'");
	$hasilProd=mysql_fetch_array($sqlProd);
	echo "<font size='3'><b>K A R T U   G U D A N G<br>Produk : ".$hasilProd['kodep']." - ".$hasilProd['nama']."<br> Periode : ".$tgl_awal." s/d ".$tgl_akhir."<br></b></font>";
//	echo "Sekarang : ".$hari_ini.", Awal Bulan : ".$tgl_awal.", Akhir Bulan : ".$tgl_akhir.", Bulan=$mth.$mth1 <br>" ;
//	<div id='progress' style='width:500px;border:1px solid #ccc;'></div>
//	<div id='information' style='width'></div>
	echo "<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th>NO</th>
			<th>TANGGAL</th>
			<th>TERIMA DARI / KIRIM KEPADA</th>
			<th>NO DOK</th>
			<th>BATCH</th>
			<th>EXP DATE</th>
			<th>MUTASI</th>
			<th>SALDO</th>
		</tr>";
		//$noloop = 0;
		//$no=1 ;
		//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
		//$sAwal = SaldoAwalStok($kode2,$kode1,$cabang);
		//$tglD = $tgl_awal;
		//$bal = $sAwal;
		//echo "<tr><td>".$no."</td><td>".$tgl_awal."</td><td colspan='5'><b>Saldo Awal ".$hasilProd['kodep']." - ".$hasilProd['nama']."</b></td><td>".$bal."</td>";

/*		for ($loop == 0; $loop < 31; $loop++)
		{
 			//$percent = intval($loop/31 * 100)."%";
			//echo '<script language="javascript">
			//		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
			//		document.getElementById("information").innerHTML="'.$i.' row(s) processed.";
			//		</script>'; 
			//echo str_repeat(' ',1024*64); 
			//flush(); 
			//echo '<script language="javascript">document.getElementById("information").innerHTML="Process completed"</script>'; 
			
			//$noloop++;
			if($tglD <= $tgl_akhir)
			{
				if(kgBeli($kode2,$tglD,$cabang)[6] != 0)
				{
				$no++;
				$bal = $bal+kgBeli($kode2,$tglD,$cabang)[6];
				echo "<tr><td>".$no."</td><td>".$tglD."</td><td>".kgBeli($kode2,$tglD,$cabang)[0]."</td><td>".kgBeli($kode2,$tglD,$cabang)[3]."</td><td>".kgBeli($kode2,$tglD,$cabang)[4]."</td><td>".kgBeli($kode2,$tglD,$cabang)[5]."</td><td>".(kgBeli($kode2,$tglD,$cabang)[6])."</td><td>".$bal."</td>";
				}
				if(kgJual($kode2,$tglD,$cabang)[6] != 0)
				{
				$no++;
				$bal = $bal-kgJual($kode2,$tglD,$cabang)[6];
				echo "<tr><td>".$no."</td><td>".$tglD."</td><td>".kgJual($kode2,$tglD,$cabang)[0]." - ".kgJual($kode2,$tglD,$cabang)[1]."</td><td>".kgJual($kode2,$tglD,$cabang)[3]."</td><td>".kgJual($kode2,$tglD,$cabang)[4]."</td><td>".kgJual($kode2,$tglD,$cabang)[5]."</td><td>".(kgJual($kode2,$tglD,$cabang)[6] *-1)."</td><td>".$bal."</td>";
				}
			}
			//date('Y-m-d', strtotime($Date. ' + 2 days'))
			$tglD = date('Y-m-d', strtotime($tglD. ' + 1 days'));
		}*/
		
	$sql1	= "SELECT *
					FROM(
					SELECT DATE(`Tgl BPB`) AS `Tanggal`, 
						`Supplier` AS Pelanggan,
						`No BPB`AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) AS `Mutasi`, 		
						`Time BPB` AS dTime,
						Produk
					FROM `dbpbdodetail`
					WHERE Cabang='$cabang' AND Produk='$kode2'
					AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' OR `Status BPB` = 'BKB Retur'
					OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' OR `Status BPB` = 'BKB Relokasi'
					OR `Status BPB` = 'BKB Koreksi' OR `Status BPB` = 'BPB Koreksi')
					UNION
					SELECT DATE(`Tanggal`) AS `Tanggal`,
						`Nama Faktur` AS Pelanggan,
						`No Faktur` AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 ) AS `Mutasi`, 
						`Time` AS dTime,
						Produk
					FROM `dsalesdetail` 
					WHERE Cabang='$cabang' AND Produk='$kode2'
					AND (`Status`='Faktur' OR `Status`='Retur')
					 )a
					 WHERE a.Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
					 ORDER BY a.Tanggal,a.dTime";		
	echo "<br>".$sql1."<br>";	

		$no=1 ;
		//fungsi saldo Awal SaldoAwalStok($kode,$tgl,$cab)
		$sAwal = SaldoAwalStok($kode2,$kode1,$cabang);
		$tglD = $tgl_awal;
		$bal = $sAwal;
		echo "<tr><td align='center'>".$no."</td><td align='center'>".$tgl_awal."</td><td colspan='5'><b>Saldo Awal ".$hasilProd['kodep']." - ".$hasilProd['nama']."</b></td><td>".$bal."</td>";

		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
				$sld= $r_data[Mutasi];
				$bal= $bal + $sld;
 			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[Tanggal]</td>
					<td>$r_data[Pelanggan]</td>
					<td>$r_data[NoDok]</td>
					<td>$r_data[batch]</td>
					<td>$r_data[expr]</td>
					<td align='right'>$sld</td>
					<td>$bal</td>
					</tr>";
			
		}
		echo "<tr><td align='center'>".($no+1)."</td><td align='center'>".$tgl_akhir."</td><td colspan='5'><b>Saldo Akhir Mutasi Produk ".$hasilProd['kodep']." - ".$hasilProd['nama']."</b></td><td>".$bal."</td>";
		//echo "<tr><td>".$no++."</td><td>".$tgl_akhir."</td><td colspan='5'>Saldo Akhir ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td>".SaldoAwalStok($kode2,date('Y-m-d', strtotime($tglD. ' + 1 months')),$cabang)."</td>";
	echo "</table>";
	echo "</div>";
?>