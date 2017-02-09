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
$tglD = date('Y-m-t', strtotime($kode1));
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
		"<p align='left'><font size='3'><b>ORDER VIA SMS <br>
	 	Periode : ".$hari_ini."<br></b></font></p>
<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th >NO</th>
			<th >ORDER</th>
			<th >CABANG</th>
			<th >PELANGGAN</th>
			<th >ACU</th>
			<th >NO TELP</th>
			<th >CONTENT</th>
			<th >TIME</th>
			<th >STATUS</th>
			<th >PROSES</th>
		</tr>
		";
if($cabang=="Pusat")
{
	$sql1	= "SELECT DISTINCT Cabang,`No Order` AS NoOrder, `Kode Pelanggan` AS KPel,
				`Nama Pelanggan` AS NPel, Telp, DATE(Tanggal) AS Tanggal, Status, `Kode Panel` AS pnl, `Nama Panel` AS Npnl
				FROM morder ORDER BY Tanggal DESC,`No Order` DESC";	
}else
{
	$sql1	= "SELECT DISTINCT Cabang,`No Order` AS NoOrder, `Kode Pelanggan` AS KPel,
				`Nama Pelanggan` AS NPel, Telp, DATE(Tanggal) AS Tanggal, Status, `Kode Panel` AS pnl, `Nama Panel` AS Npnl
				FROM morder WHERE Cabang='$cabang'  ORDER BY `No Order` DESC";	
}				
				
	//	echo "<br>".$sql1."<br>";
	//die();
//		$dat = "";
		$no=0 ;
		$query = mysql_query($sql1);	
		while($r_data=mysql_fetch_array($query)){
		$no++;
		
		if($cabang=="Pusat")
		{
		$sqlD = "SELECT Cabang,`No Order` AS NoOrder, `Kode Pelanggan` AS KPel, `Nama Pelanggan` AS NPel, 
					Telp, `Kode Produk` AS kProd, `Nama Produk` AS nProd,
					`Qty Produk` AS qtyProd, Status, `Kode Panel` AS pnl, `Nama Panel` AS Npnl
					FROM morder WHERE `No Order`='$r_data[NoOrder]' AND Cabang='$r_data[Cabang]'";
		}else
		{
		$sqlD = "SELECT Cabang,`No Order` AS NoOrder, `Kode Pelanggan` AS KPel, `Nama Pelanggan` AS NPel, 
					Telp, `Kode Produk` AS kProd, `Nama Produk` AS nProd,
					`Qty Produk` AS qtyProd, Status, `Kode Panel` AS pnl, `Nama Panel` AS Npnl
					FROM morder WHERE `No Order`='$r_data[NoOrder]' AND Cabang='$cabang'";		
		}
		
/* 					if($cabang=="Pusat")
					{
						echo $sqlD; 
					} */
		$queryD = mysql_query($sqlD);
		$detData = "";
			while($d_data=mysql_fetch_array($queryD)){
				$prod = mysql_query("select produk,`kode produk` as kode, satuan from mproduk 
										where `Kode Produk`='$d_data[kProd]'");
				while($pD = mysql_fetch_array($prod))
				$detData = $detData.$d_data[kProd]." - ".$d_data[nProd]." Sebanyak : ".$d_data[qtyProd]." ".$pD[satuan]."<br>";
			}
		$pelang = $r_data[KPel]." - ".$r_data[NPel];
		$pane = $r_data[pnl]." - ".$r_data[Npnl];
		echo"	<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[NoOrder]</td>
					<td align='left'>$r_data[Cabang]</td>
					<td align='left'>$pane</td>
					<td align='left'>$r_data[KPel]</td>
					<td align='left'>$r_data[Telp]</td>
					<td align='left'>$detData</td>
					<td align='left'>$r_data[Tanggal]</td>
					<td align='left'>$r_data[Status]</td>";
					if($r_data[Status]=="Open")
					{
					echo "
					<td align='left'> <form action = 'modul\sms\proses.php' method = 'GET'>
					<input type = 'hidden' name = 'cab' value = '".$r_data['Cabang']."'>
					<input type = 'hidden' name = 'ord' value = '".$r_data['NoOrder']."'>
					<input type = 'submit' name = 'tombolproses' value = 'Proses' class = 'Proses'></form></td>";
					}elseif ($r_data[Status]=="Proses")
					{
					echo "
					<td align='left'><font color='green'><Strong>Produk Sudah di Proses<br>Harap Cek Pengiriman</Strong></font></td>";	
					}else
					{
					echo "
					<td align='left'><font color='Red'><Strong>Cek Status</Strong></font></td>";
					}
				echo "</tr>";
		
		}
	
		
		echo "</table></div>";
	
?>
