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

$kode1	= $_GET['kode'];
$kode2	= $_GET['kode'];
$kode	= $_GET['kode'];
$cabang	= $_GET['cabang'];
$hal	= $_GET['hal'] ? $_GET['hal'] : 0;
$lim	= 20;

if (empty($kode)){
	if ($cabang =='KPS')
	{
		$query2 = "SELECT DISTINCT a.`kodepo_beli`,a.`tglpo_beli`,c.`kode_beli`,a.`kode_prinsipal`,d.`namaprinsipal`,c.`kode_supplier`,b.`namasupplier` 
					FROM po_pembelian a
						LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
						LEFT JOIN pembelian c ON a.`kode_beli`=c.`kode_beli` AND a.`kode_barang`=c.`kode_barang`
						LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal` 
						ORDER BY a.`tglpo_beli` DESC,a.`cabang`,a.`kode_beli`";//echo "--1--".$query2;
	}
	else
	{
		$query2 = "SELECT DISTINCT a.`kodepo_beli`,a.`tglpo_beli`,c.`kode_beli`,a.`kode_prinsipal`,d.`namaprinsipal`,c.`kode_supplier`,b.`namasupplier` 
					FROM po_pembelian a
					LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
					LEFT JOIN pembelian c ON a.`kode_beli`=c.`kode_beli` AND a.`kode_barang`=c.`kode_barang`
					LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal` 
					WHERE a.cabang='$cabang'
					ORDER BY a.`tglpo_beli` DESC,a.`cabang`,a.`kode_beli`";//echo "--12--".$query2;
	}
}else{
	if ($cabang =='KPS')
	{
		$query2 = "SELECT * FROM po_pembelian WHERE kode_beli = '$kode'";//echo "--3--".$query2;
	}
	else
	{
		$query2 = "SELECT * FROM po_pembelian WHERE kode_beli = '$kode' AND cabang='$cabang'";//echo "--4--".$query2;
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
				<th>Kode PO</th>
				<th>Tanggal PO</th>
				<th>Kode Prinsipal</th>
				<th>Nama Prinsipal</th>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
				<th>Kode PR</th>
			</tr>";		
	}else {
		echo "<div id='info'>
		<table id='theList' width='100%'>
			<tr>
				<th>No.</th>
				<th>Kode PO</th>
				<th>Qty</th>
				<th>Satuan</th>
				<th>Nama Barang</th>
				<th>Kode Barang</th>
				<th>Keterangan </th>
			</tr>";
	}


		if (empty($kode)){
			if ($cabang =='KPS'){
					$sql = "SELECT DISTINCT a.`kodepo_beli`,a.`tglpo_beli`,c.`kode_beli`,a.`kode_prinsipal`,d.`namaprinsipal`,c.`kode_supplier`,b.`namasupplier` 
							FROM po_pembelian a
								LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
								LEFT JOIN pembelian c ON a.`kode_beli`=c.`kode_beli` AND a.`kode_barang`=c.`kode_barang`
								LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal` 
								ORDER BY a.`tglpo_beli` DESC,a.`cabang`,a.`kode_beli` 
								LIMIT $hal,$lim";//echo "--5--".$sql;
							}
				else{
					$sql = "SELECT DISTINCT a.`kodepo_beli`,a.`tglpo_beli`,c.`kode_beli`,a.`kode_prinsipal`,d.`namaprinsipal`,c.`kode_supplier`,b.`namasupplier` 
							FROM po_pembelian a
								LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
								LEFT JOIN pembelian c ON a.`kode_beli`=c.`kode_beli` AND a.`kode_barang`=c.`kode_barang`
								LEFT JOIN `mstprinsipal` d ON a.`kode_prinsipal`=d.`kodeprinsipal` 
								ORDER BY a.`tglpo_beli` DESC,a.`cabang`,a.`kode_beli` 
								WHERE a.cabang='$cabang'
								ORDER BY a.`tglpo_beli` DESC,a.`cabang`,a.`kode_beli`
								LIMIT $hal,$lim";//echo "--6--".$sql;
								}
			}	
		else
			{
				if ($cabang =='KPS'){
						$sql = "SELECT a.`kodepo_beli`,a.`tglpo_beli`,a.`kode_supplier`,b.`namasupplier`,a.`kode_barang`,c.`namaproduk`,c.`satuan`,
							a.`jumlah_beli_valid`
							FROM `po_pembelian` a 
							LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
							LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
							WHERE a.kodepo_beli='$kode'
							ORDER BY a.kodepo_beli DESC, a.kode_barang ASC
							LIMIT $hal,$lim";//echo "--7--".$sql;
							}
					else {
						$sql = "SELECT a.`kodepo_beli`,a.`tglpo_beli`,a.`kode_supplier`,b.`namasupplier`,a.`kode_barang`,c.`namaproduk`,c.`satuan`,
							a.`jumlah_beli_valid`
							FROM `po_pembelian` a 
							LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
							LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
							WHERE a.kodepo_beli='$kode' AND a.cabang='$cabang'
							ORDER BY a.kodepo_beli DESC, a.kode_barang ASC
							LIMIT $hal,$lim";				//echo "--8--".$sql;	
					}
			}
		
				
		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1+$hal;
		if (empty($kode)){
		while($r_data=mysql_fetch_array($query)){
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[kodepo_beli]</td>
					<td align='center'>$r_data[tgl_beli]</td>
					<td>$r_data[kode_prinsipal]</td>
					<td>$r_data[namaprinsipal]</td>
					<td>$r_data[kode_supplier]</td>
					<td>$r_data[namasupplier]</td>
					<td>$r_data[kode_beli]</td>
					</tr>";
			$no++;//echo "--9--";
		}
		}else {
		while($r_data=mysql_fetch_array($query)){		
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[kodepo_beli]</td>
					<td align='center'>$r_data[jumlah_beli_valid]</td>
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
		if (empty($kode)){
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/pembelian/tampil_data_list_po.php?cabang=<?php echo $cabang;?>&hal=<?php echo $list[$h]; ?>', 
                    null, function(data) {$('#info').html(data);})" <?php echo">".$h."</a> ";
				}
	echo "</td>";
		}else{
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/pembelian/tampil_data_list_po.php?cabang=<?php echo $cabang;?>&kode=<?php echo $_GET['kode'];?>
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