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
	
	function editRow(ID){
	   var kode = ID; 
		window.location.href='media.php?module=t_order&no='+kode;

	}
	function cetakRow(ID){
	   var kode = ID; 
		window.location.href='modul/laporan/cetak_pr.php?kode='+kode;

	}
	function lihatRow(ID){
	   	var kode = ID; 
		var cabang	= $("#textcabang").val();
		var apt	= $("#textuser").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/t_order/tampil_data_list_ord_sp.php",
			data	: "kode="+kode+"&cabang="+cabang+"&apt="+apt,
			success	: function(data){
				$("#data").html(data);
			}
		});		
	}	

	function updateExpRow(ID){
	   	var kode = ID; 
		var status	= "Y";
		var cabang	= $("#textcabang").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/t_order/tampil_data_list_ord_sp.php",
			data	: "kode="+kode+"&status="+status+"&cabang="+cabang,
			success	: function(data){
				$("#data").html(data);
			}
		});		
	}
		
</script>
<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';

$kode1	= $_GET['kode'];
$kode2	= $_GET['kode'];
$kode	= $_GET['kode'];
$cabang	= $_GET['cabang'];
$hal	= $_GET['hal'] ? $_GET['hal'] : 0;
$status = $_GET['status'];
$apt	= $_GET['apt'];
$spc	= $_GET['spc'];
$lim	= 50;
$tglsp = date("Y-m-d");
// if($spc=='spcek')
// {
// $textspc	= "SELECT MAX(RIGHT(NoSP,5)) AS no_akhir FROM t_order WHERE cabang='$cabang' AND KodeApotik='$apt'";
// $sqlspc 	= mysql_query($textspc);
// $data=mysql_fetch_array($sqlspc);	
// $no = $data['no_akhir'];
// $no_akhir = (int)$no;
// $no_akhir++;
// $nosp = 'SP/'.$apt.'/'.sprintf("%05s",$no_akhir);
// $sqlspc2 = "Update t_order SET NoSP = '$nosp', TglSP='$tglsp',Status='OpenSP' WHERE cabang='$cabang' AND KodeApotik='$apt' AND Status='Open'";
// echo $sqlspc2."<br>".$textspc;
// mysql_query($sqlspc2);
// }

if (empty($kode)){
	if ($cabang =='KPS')
	{
		$query2 = "SELECT a.NoSP,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,SUM(a.`Value`) AS tot1 FROM t_order a, mpelanggan c, mretail d
						WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
						GROUP BY a.NoSP,a.`TglSP`,a.`cabang`
						ORDER BY RIGHT(a.`NoSP`,5) DESC";//echo "--1--".$query2;
	}
	else
	{
		$query2 = "SELECT a.`NoSP`,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,SUM(a.`Value`) AS tot1 FROM t_order a, mpelanggan c, mretail d
							WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
							AND a.cabang='$cabang'
							GROUP BY a.NoSP,a.`TglSP`,a.`KodeApotik`
							ORDER BY RIGHT(a.`NoSP`,5) DESC";//echo "--2--".$query2;
	}
}else{
	if ($cabang =='KPS')
	{
		$query2 = "SELECT a.`NoSP`,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,SUM(a.`Value`) AS tot1 FROM t_order a, mpelanggan c, mretail d
							WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
							AND a.cabang='$cabang' AND NoSP = '$kode'";//echo "--3--".$query2;
	}
	else
	{
		$query2 = "SELECT a.`NoSP`,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,SUM(a.`Value`) AS tot1 FROM t_order a, mpelanggan c, mretail d
							WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
							NoSP = '$kode' AND cabang='$cabang'";//echo "--4--".$query2;
	}
}
	$data2	= mysql_query($query2);
	$jml	= mysql_num_rows($data2);
	
	$max	= ceil($jml/$lim);

if (empty($kode))
	{
		echo "<div id='info'>
		<table id='theList' width='100%'>
			<tr>
				<th>No.</th>
				<th>Kode SP</th>
				<th>Tanggal SP</th>
				<th>Kode Pelanggan</th>
				<th>Nama Pelanggan</th>
				<th>Status</th>
				<th>Lihat</th>
				<th>Proses</th>
				<th>Cetak</th>
			</tr>";		
	}else {
		echo "<div id='info'>
		<table id='theList' width='100%'>
			<tr>
				<th>No.</th>
				<th>Kode SP</th>
				<th>Tanggal SP</th>
				<th>ACU</th>
				<th>Qty</th>
				<th>Satuan</th>
				<th>Nama Barang</th>
				<th>Kode Barang</th>
			</tr>";
	}


			if (empty($kode)){
				if ($cabang =='KPS'){
						$sql = "SELECT a.NoSP,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,SUM(a.`Value`) AS tot1 FROM t_order a, mpelanggan c, mretail d
						WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
						GROUP BY a.NoSP,a.`TglSP`,a.`cabang`
						ORDER BY RIGHT(a.`NoSP`,5) DESC
									LIMIT $hal,$lim";//echo "--5--".$sql;
								}
					else{
						$sql = "SELECT a.`NoSP`,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,SUM(a.`Value`) AS tot1 
						FROM t_order a, mpelanggan c, mretail d
							WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
							AND a.cabang='$cabang'
							GROUP BY a.NoSP,a.`TglSP`,a.`cabang`
							ORDER BY RIGHT(a.`NoSP`,5) DESC
									LIMIT $hal,$lim";//echo "--6--".$sql;
									}
				}	
		else
			{
				if ($cabang =='KPS'){
						$sql = "SELECT a.`NoSP`,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,a.`Value` AS tot1, a.KodeProduk, b.Produk AS NmProd, b.Satuan, a.Banyak 
						FROM t_order a, mpelanggan c, mretail d, mproduk b
							WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
							AND a.KodeProduk=b.`Kode Produk` AND a.`NoSP`='$kode'
							ORDER BY RIGHT(a.`NoSP`,5) DESC
							LIMIT $hal,$lim";//echo "--7--".$sql;
						}
				else {
					$sql = "SELECT a.`NoSP`,a.`TglSP`,a.`KodeApotik`,c.`Nama Faktur` AS NmFak,a.`Status`,a.`Value` AS tot1, a.KodeProduk, b.Produk AS NmProd, b.Satuan, a.Banyak, a.`KodePelanggan`
					FROM t_order a, mpelanggan c, mretail d, mproduk b
						WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
						AND a.KodeProduk=b.`Kode Produk` AND a.`NoSP`='$kode'
						ORDER BY RIGHT(a.`NoSP`,5) DESC, a.`KodePelanggan`, b.Produk 
						LIMIT $hal,$lim";//echo "--8--".$sql;
					}
			}
		
				
		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1+$hal;
		if (empty($kode)){
		while($r_data=mysql_fetch_array($query)){
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[NoSP]</td>
					<td align='center'>$r_data[TglSP]</td>
					<td>$r_data[KodeApotik]</td>
					<td>$r_data[NmFak]</td>
					<td>$r_data[Status]</td>
					<td align='center'>
						<a href='javascript:lihatRow(\"{$r_data[NoSP]}\")' >
						<img src='icon/magnifier.png' border='0' id='lihatttt' title='Lihat' width='12' height='12' ></a></td>
					<td align='center'>";
						if ($r_data[Status]=='OpenSP')
							{
								echo "<a href='javascript:editRow(\"{$r_data[NoSP]}\")' ><img src='icon/thumb_up.png' border='0' id='edit' title='Edit' width='12' height='12' ></a>";
							}
					echo "</td>
					<td align='center'><a href='javascript:cetakRow(\"{$r_data[NoSP]}\")' ><img src='icon/printer.png' border='0' id='cetakkk' title='Cetak' width='12' height='12' ></a></td>
					</tr>";
			$no++;//echo "--9--";
		}
		}else {
		while($r_data=mysql_fetch_array($query)){		
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[NoSP]</td>
					<td align='center'>$r_data[TglSP]</td>
					<td align='center'>$r_data[KodePelanggan]</td>
					<td align='center'>$r_data[Banyak]</td>
					<td>$r_data[Satuan]</td>
					<td>$r_data[NmProd]</td>
					<td>$r_data[KodeProduk]</td>
					</tr>";//echo "--10--";
			$no++;
		}
		}
	echo "</table>";
	echo "<table width='100%'>
		<tr>
			<td>Jumlah Data : $jml</td>";
		if (empty($kode1) && empty($kode2)){//echo "--11--";
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/t_order/tampil_data_list_ord_sp.php?cabang=<?php echo $cabang;?>&hal=<?php echo $list[$h]; ?>', 
                    null, function(data) {$('#info').html(data);})" <?php echo">".$h."</a> ";
				}
	echo "</td>";
		}else{//echo "--12--";
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/t_order/tampil_data_list_ord_sp.php?cabang=<?php echo $cabang;?>&kode=<?php echo $_GET['kode'];?>
                    &kode=<?php echo $_GET['kode'];?>
                    &hal=<?php echo $list[$h]; ?>', 
                    null, function(data) {$('#info').html(data);})" <?php echo">".$h."</a> ";
				}
	echo "</td>";
		}
	echo "</tr>
		</table>";
	echo "</div>";
?>