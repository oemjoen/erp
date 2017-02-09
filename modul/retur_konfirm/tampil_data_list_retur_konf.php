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
		window.location.href='media.php?module=edit_retur_konfirm&no='+kode;

	}
	function cetakRow(ID){
	   var kode = ID; 
		window.location.href='modul/laporan/cetak_retur_konfirm.php?kode='+kode;

	}
	function lihatRow(ID){
	   	var kode = ID; 
		var cabang	= $("#textcabang").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/retur_konfirm/tampil_data_list_retur_konf.php",
			data	: "kode="+kode+"&cabang="+cabang,
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
$lim	= 50;

if (empty($kode)){
	if ($cabang =='KPS')
	{
		$query2 = "SELECT DISTINCT a.kodekonf_retur FROM konfirm_retur_beli a ORDER BY a.`tglkonf_retur` DESC,a.`cabang`,a.`kode_retur`";//echo "--1--".$query2;
	}
	else
	{
		$query2 = "SELECT DISTINCT a.kodekonf_retur FROM konfirm_retur_beli a WHERE a.cabang='$cabang' ORDER BY a.`tglkonf_retur` DESC,a.`cabang`,a.`kode_retur`";//echo "--2--".$query2;
	}
}else{
	if ($cabang =='KPS')
	{
		$query2 = "SELECT * FROM konfirm_retur_beli WHERE kodekonf_retur = '$kode'";//echo "--3--".$query2;
	}
	else
	{
		$query2 = "SELECT * FROM konfirm_retur_beli WHERE kodekonf_retur = '$kode' AND cabang='$cabang'";//echo "--4--".$query2;
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
				<th>Kode Retur Konfirm</th>
				<th>Tanggal Retur Konfirm</th>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
				<th>Kode Prinsipal</th>
				<th>Nama Prinsipal</th>
				<th>Kode Retur</th>
				<th>Tanggal Retur</th>
				<th>Lihat</th>
				<th>Edit</th>
				<th>Cetak</th>
			</tr>";		
	}else {
		echo "<div id='info'>
		<table id='theList' width='100%'>
			<tr>
				<th>No.</th>
				<th>Kode Retur</th>
				<th>Supp</th>
				<th>Nama Barang</th>
				<th>Kode</br>Barang</th>
				<th>Satuan</th>
				<th>Qty Retur</th>
				<th>Batch</th>
				<th>Expired </br> Date</th>
				<th>Ref DO</br>Pusat</th>
				<th>Tgl Ref DO</br>Pusat</th>
				<th>Alasan</th>
				<th>Dari</th>
			</tr>";
	}


			if (empty($kode)){
				if ($cabang =='KPS'){
						$sql = "SELECT DISTINCT a.kodekonf_retur,a.tglkonf_retur,a.kode_supplier,a.kode_prinsipal,
									b.`namasupplier`,d.`namaprinsipal`,e.tgl_retur,a.kode_retur 
									FROM `konfirm_retur_beli` a 
									LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal`
									LEFT JOIN retur_beli e ON a.`kode_retur`=e.`kode_retur` AND a.`kode_barang`=e.`kode_barang`									
									ORDER BY tglkonf_retur DESC,kodekonf_retur, a.kode_barang
									LIMIT $hal,$lim";//echo "--5--".$sql;
								}
					else{
						$sql = "SELECT DISTINCT a.kodekonf_retur,a.tglkonf_retur,a.kode_supplier,a.kode_prinsipal,
									b.`namasupplier`,d.`namaprinsipal`,e.tgl_retur,a.kode_retur 
									FROM `konfirm_retur_beli` a 
									LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal`
									LEFT JOIN retur_beli e ON a.`kode_retur`=e.`kode_retur` AND a.`kode_barang`=e.`kode_barang`
									WHERE a.cabang='$cabang'
									ORDER BY a.`tglkonf_retur` DESC,a.`cabang`,a.`kodekonf_retur`
									LIMIT $hal,$lim";//echo "--6--".$sql;
									}
				}	
		else
			{
				if ($cabang =='KPS'){
						$sql = "SELECT a.*,b.`namasupplier`,c.`namaproduk`,c.`satuan`,d.`namaprinsipal`
								,e.`batch`,e.`alasan_retur`,e.`asal_retur`,e.`ed`,e.tgl_retur,e.`do_pusat`,e.`tgl_do_pusat`  
									FROM `konfirm_retur_beli` a 
									LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
									LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal`
									LEFT JOIN retur_beli e ON a.`kode_retur`=e.`kode_retur` AND a.`kode_barang`=e.`kode_barang`
									WHERE a.kodekonf_retur='$kode'
									ORDER BY `tglkonf_retur` DESC,kodekonf_retur, a.kode_barang
							LIMIT $hal,$lim";//echo "--7--".$sql;
						}
					else {
						$sql = "SELECT a.*,b.`namasupplier`,c.`namaproduk`,c.`satuan`,d.`namaprinsipal` 
								,e.`batch`,e.`alasan_retur`,e.`asal_retur`,e.`ed`,e.tgl_retur,e.`do_pusat`,e.`tgl_do_pusat`  
									FROM `konfirm_retur_beli` a 
									LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
									LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal`
									LEFT JOIN retur_beli e ON a.`kode_retur`=e.`kode_retur` AND a.`kode_barang`=e.`kode_barang`
									WHERE a.cabang='$cabang' AND a.kodekonf_retur='$kode'
									ORDER BY a.`tglkonf_retur` DESC,a.`cabang`,a.`kodekonf_retur` ASC
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
					<td align='center'>$r_data[kodekonf_retur]</td>
					<td align='center'>$r_data[tglkonf_retur]</td>
					<td>$r_data[kode_supplier]</td>
					<td>$r_data[namasupplier]</td>
					<td>$r_data[kode_prinsipal]</td>
					<td>$r_data[namaprinsipal]</td>
					<td>$r_data[kode_retur]</td>
					<td>$r_data[tgl_retur]</td>
					<td align='center'><a href='javascript:lihatRow(\"{$r_data[kodekonf_retur]}\")' ><img src='icon/magnifier.png' border='0' id='lihatttt' title='Lihat' width='12' height='12' ></a></td>
					<td align='center'><a href='javascript:editRow(\"{$r_data[kodekonf_retur]}\")' ><img src='icon/thumb_up.png' border='0' id='editttt' title='Edit' width='12' height='12' ></a></td>
					<td align='center'><a href='javascript:cetakRow(\"{$r_data[kodekonf_retur]}\")' ><img src='icon/printer.png' border='0' id='cetakkk' title='Cetak' width='12' height='12' ></a></td>
					</tr>";
			$no++;//echo "--9--";
		}
		}else {
		while($r_data=mysql_fetch_array($query)){		
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[kode_retur]</td>
					<td>$r_data[namasupplier]</td>
					<td>$r_data[namaproduk]</td>
					<td>$r_data[kode_barang]</td>
					<td>$r_data[satuan]</td>
					<td>$r_data[jumlah_retur]</td>
					<td>$r_data[batch]</td>
					<td>$r_data[ed]</td>
					<td>$r_data[do_pusat]</td>
					<td>$r_data[tgl_do_pusat]</td>
					<td>$r_data[alasan_retur]</td>
					<td>$r_data[asal_retur]</td>
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
                    onClick="$.get('modul/retur_konfirm/tampil_data_list_retur_konf.php?cabang=<?php echo $cabang;?>&hal=<?php echo $list[$h]; ?>', 
                    null, function(data) {$('#info').html(data);})" <?php echo">".$h."</a> ";
				}
	echo "</td>";
		}else{//echo "--12--";
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/retur_konfirm/tampil_data_list_retur_konf.php?cabang=<?php echo $cabang;?>&kode=<?php echo $_GET['kode'];?>
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