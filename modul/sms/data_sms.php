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
		"<p align='left'><font size='3'><b>PEMBELIAN VIA SMS <br>
	 	Periode : ".date('Y-m-d', strtotime($kode1))."<br></b></font></p>
<div id='info'>
	<table id='theList' width='100%'>
		<tr>
			<th >NO</th>
			<th >CABANG</th>
			<th >PELANGGAN</th>
			<th >NO TELP</th>
			<th >CONTENT</th>
			<th >TIME</th>
			<th >JUMLAH BELI</th>
			<th >KODE</th>
			<th >PEMBELIAN</th>
		</tr>
		";
//	$sql1	= "SELECT * FROM smsc where cabang = '$cabang' order by time desc";	
	$sql1	= "SELECT * FROM smsc order by time desc";	

	//	echo "<br>".$sql1."<br>";
	//die();
//		$dat = "";
		$no=0 ;
		$query = mysql_query($sql1);	
		while($r_data=mysql_fetch_array($query)){
		$no++;
		
		$jcont = strlen($r_data[content]);
		$dat = explode(",",substr($r_data[content],2,$jcont-4));
		$jumdat = sizeof($dat);
		$datTam = "";
		for($i = 0; $i<$jumdat ;$i++)
		{
			$smsprod = substr($dat[$i],0,3);
			$prod = mysql_query("select produk,`kode produk` as kode, satuan from mproduk where `Kode SMS`='$smsprod'");
			//echo "select produk from mproduk where `Kode SMS`=$smsprod";
			while($pDsms = mysql_fetch_array($prod))
			{
				$pSms = "<strong>".$pDsms[kode]."</strong> - <strong>".$pDsms[produk]."</strong>";
				$pSmsStn = $pDsms[satuan];
			}
			$datTam = $datTam.$pSms." Sebanyak <strong>".substr($dat[$i],4,10)." ".$pSmsStn."</strong><br>";
		}
		
		$smspel = mysql_query("select * from mpelanggan where `smsP`='$r_data[number]'");
			while($psmspel = mysql_fetch_array($smspel))
			{
				$pelSMS = $psmspel[Kode]." - ".$psmspel[Pelanggan];					
			}
		
		
		if(empty($r_data[cabang]))
		{
			echo"	<tr>
						<td align='center'><font color='red'>$no</font></td>
						<td align='left'><font color='red'>Nomor Telpon Tidak Terdaftar</font></td>
						<td align='left'><font color='red'>$pelSMS</font></td>
						<td align='left'><font color='red'>$r_data[number]</font></td>
						<td align='left'><font color='red'>$r_data[content]</font></td>
						<td align='left'><font color='red'>$r_data[time]</font></td>
						<td align='left'>$jumdat</td>
						<td align='left'><font color='red'>$r_data[No]</font></td>
						<td align='left'><font color='red'>$datTam</font></td>
					</tr>";
			
		}
			
		else
		{
			echo"	<tr>
						<td align='center'>$no</td>
						<td align='left'>$r_data[cabang]</td>
						<td align='left'>$pelSMS</td>
						<td align='left'>$r_data[number]</td>
						<td align='left'>$r_data[content]</td>
						<td align='left'>$r_data[time]</td>
						<td align='left'>$jumdat</td>
						<td align='left'>$r_data[No]</td>
						<td align='left'>$datTam</td>
					</tr>";
				
			}
			
		}
		
		echo "</table></div>";
			
?>
