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
		window.location.href='media.php?module=pembelian&no='+kode;

	}
	function cetakRow(ID){
	   var kode = ID; 
		window.location.href='modul/laporan/cetak_pr.php?kode='+kode;

	}
	function lihatRow(ID){
	   	var kode = ID; 
		var cabang	= $("#textcabang").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/pembelian/tampil_data_list_pr.php",
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
		$query2 = "SELECT DISTINCT a.kode_beli FROM pembelian a ORDER BY a.`tgl_beli` DESC,a.`cabang`,a.`kode_beli`";//echo "--1--".$query2;
	}
	else
	{
		$query2 = "SELECT DISTINCT a.kode_beli FROM pembelian a WHERE a.cabang='$cabang' ORDER BY a.`tgl_beli` DESC,a.`cabang`,a.`kode_beli`";//echo "--2--".$query2;
	}
}else{
	if ($cabang =='KPS')
	{
		$query2 = "SELECT * FROM pembelian WHERE kode_beli = '$kode'";//echo "--3--".$query2;
	}
	else
	{
		$query2 = "SELECT * FROM pembelian WHERE kode_beli = '$kode' AND cabang='$cabang'";//echo "--4--".$query2;
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
				<th>Kode PR</th>
				<th>Tanggal Beli</th>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
				<th>Kode Prinsipal</th>
				<th>Nama Prinsipal</th>
				<th>Kode PO</th>
				<th>Lihat</th>
				<th>Edit</th>
				<th>Cetak</th>
			</tr>";		
	}else {
		echo "<div id='info'>
		<table id='theList' width='100%'>
			<tr>
				<th>No.</th>
				<th>Kode PR</th>
				<th>Qty</th>
				<th>Satuan</th>
				<th>Nama Barang</th>
				<th>Kode Barang</th>
				<th>Keterangan </th>
			</tr>";
	}


			if (empty($kode)){
				if ($cabang =='KPS'){
						$sql = "SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									ORDER BY a.`tgl_beli` DESC,a.`cabang`,a.`kode_beli`
									LIMIT $hal,$lim";//echo "--5--".$sql;
								}
					else{
						$sql = "SELECT DISTINCT a.kode_beli,a.tgl_beli,a.kode_supplier,
									b.`namasupplier`,
									IFNULL(gab.`kode_prinsipal`,'') AS kode_prinsipal,
									IFNULL(gab.`namaprinsipal`,'') AS namaprinsipal,
									IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli 
									FROM pembelian a
									LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
									LEFT JOIN (
											SELECT DISTINCT kodepo_beli,kode_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian po
											LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
										) gab ON a.`kode_beli`= gab.kode_beli
									WHERE a.cabang='$cabang'
									ORDER BY a.`tgl_beli` DESC,a.`cabang`,a.`kode_beli`
									LIMIT $hal,$lim";//echo "--6--".$sql;
									}
				}	
		else
			{
				if ($cabang =='KPS'){
						$sql = "SELECT a.`kode_beli`,a.`tgl_beli`,a.`kode_supplier`,b.`namasupplier`,a.`kode_barang`,c.`namaproduk`,c.`satuan`,
							a.`jumlah_beli`,a.`ratio`,a.`stok`,a.`averg`,a.ket_prinsipal,a.ket_cabang
							FROM `pembelian` a 
							LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
							LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
							WHERE a.kode_beli='$kode'
							ORDER BY a.kode_beli DESC, a.kode_barang ASC
							LIMIT $hal,$lim";//echo "--7--".$sql;
						}
					else {
						$sql = "SELECT a.`kode_beli`,a.`tgl_beli`,a.`kode_supplier`,b.`namasupplier`,a.`kode_barang`,c.`namaproduk`,c.`satuan`,
							a.`jumlah_beli`,a.`ratio`,a.`stok`,a.`averg`,a.ket_prinsipal,a.ket_cabang
							FROM `pembelian` a 
							LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
							LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
							WHERE a.kode_beli='$kode' AND cabang='$cabang'
							ORDER BY a.kode_beli DESC, a.kode_barang ASC
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
					<td align='center'>$r_data[kode_beli]</td>
					<td align='center'>$r_data[tgl_beli]</td>
					<td>$r_data[kode_supplier]</td>
					<td>$r_data[namasupplier]</td>
					<td>$r_data[kode_prinsipal]</td>
					<td>$r_data[namaprinsipal]</td>
					<td>$r_data[kodepo_beli]</td>
					<td align='center'><a href='javascript:lihatRow(\"{$r_data[kode_beli]}\")' ><img src='icon/magnifier.png' border='0' id='lihatttt' title='Lihat' width='12' height='12' ></a></td>
					<td align='center'>";if (empty($r_data[kodepo_beli])){echo "<a href='javascript:editRow(\"{$r_data[kode_beli]}\")' ><img src='icon/thumb_up.png' border='0' id='edit' title='Edit' width='12' height='12' ></a>";}echo "</td>
					<td align='center'><a href='javascript:cetakRow(\"{$r_data[kode_beli]}\")' ><img src='icon/printer.png' border='0' id='cetakkk' title='Cetak' width='12' height='12' ></a></td>
					</tr>";
			$no++;//echo "--9--";
		}
		}else {
		while($r_data=mysql_fetch_array($query)){		
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[kode_beli]</td>
					<td align='center'>$r_data[jumlah_beli]</td>
					<td>$r_data[satuan]</td>
					<td>$r_data[namaproduk]</td>
					<td>$r_data[kode_barang]</td>
					<td>$r_data[ket_prinsipal]</td>
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
                    onClick="$.get('modul/pembelian/tampil_data_list_pr.php?cabang=<?php echo $cabang;?>&hal=<?php echo $list[$h]; ?>', 
                    null, function(data) {$('#info').html(data);})" <?php echo">".$h."</a> ";
				}
	echo "</td>";
		}else{//echo "--12--";
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/pembelian/tampil_data_list_pr.php?cabang=<?php echo $cabang;?>&kode=<?php echo $_GET['kode'];?>
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