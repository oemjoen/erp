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
	   if (kode.search("PRE") != -1){
		window.location.href='media.php?module=pembelian_pre&no='+kode;
	   }else if (kode.search("PSI") != -1){
		window.location.href='media.php?module=pembelian_psi&no='+kode;
	   }else {
		window.location.href='media.php?module=pembelian&no='+kode;
		}

	}
	function cetakRow(ID){
	   var kode = ID; 
	   if (kode.search("PRE") != -1){
		window.location.href='modul/laporan/cetak_pr_pre.php?kode='+kode;
	   }else if (kode.search("PSI") != -1){
		window.location.href='modul/laporan/cetak_pr_psi.php?kode='+kode;
	   }else {
		window.location.href='modul/laporan/cetak_pr.php?kode='+kode;
		} 
		

	}
	function lihatRow(ID){
	   	var kode = ID; 
		var cabang	= $("#textcabang").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/laporan/laporan_pr/tampil_data_list_pr_out.php",
			data	: "kode="+kode+"&cabang="+cabang,
			success	: function(data){
				$("#data").html(data);
			}
		});		
	

	}	
	
</script>
<?php
include '../../../inc/inc.koneksi.php';
include '../../../inc/fungsi_hdt.php';

$kode1	= $_GET['kode'];
$kode2	= $_GET['kode'];
$kode	= $_GET['kode'];
$cabang	= $_GET['cabang'];
$hal	= $_GET['hal'] ? $_GET['hal'] : 0;
$lim	= 50;

if (empty($kode)){
	if ($cabang =='KPS')
	{
		$query2 = "SELECT * FROM (
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprins`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_pre a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspre`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_psi a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspsi`) po  ON prpo.kode_beli=po.`kode_beli`
								)servlvlpopr HAVING value_pr <> 0 AND kode_po=''
								ORDER BY tgl_pr DESC,cabang,kode_pr";//echo "--1--".$query2."</br>";
	}
	else
	{
		$query2 = "SELECT * FROM (
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprins`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_pre a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspre`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_psi a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspsi`) po  ON prpo.kode_beli=po.`kode_beli`
								)servlvlpopr HAVING value_pr <> 0 AND kode_po='' AND cabang='$cabang'
								ORDER BY tgl_pr DESC,cabang,kode_pr";//echo "--2--".$query2."</br>";
	}
}else{
	if ($cabang =='KPS')
	{
			$table = "pembelian";
			$field = "kode_beli";
		
			if (stripos($kode, "PRE") !== false){ 
				$table = "pembelian_pre";
				$field = "kode_beli";
			}
			if (stripos($kode, "PSI") !== false){ 
				$table = "pembelian_psi";
				$field = "kode_beli";
			}
		$query2 = "SELECT * FROM $table WHERE $field = '$kode'";//echo "--3--".$query2."</br>";
	}
	else
	{
			$table = "pembelian";
			$field = "kode_beli";
		
			if (stripos($kode, "PRE") !== false){ 
				$table = "pembelian_pre";
				$field = "kode_beli";
			}
			if (stripos($kode, "PSI") !== false){ 
				$table = "pembelian_psi";
				$field = "kode_beli";
			}
		$query2 = "SELECT * FROM $table WHERE $field = '$kode' AND cabang='$cabang'";//echo "--4--".$query2."</br>";
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
				<th>Tanggal PO</th>
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
						$sql = "SELECT * FROM (
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprins`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_pre a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspre`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_psi a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspsi`) po  ON prpo.kode_beli=po.`kode_beli`
								)servlvlpopr HAVING value_pr <> 0 AND kode_po='' 
								ORDER BY tgl_pr DESC,cabang,kode_pr
									LIMIT $hal,$lim";//echo "--5--".$sql."</br>";
								}
					else{
						$sql = "SELECT * FROM (
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprins`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_pre a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspre`) po  ON prpo.kode_beli=po.`kode_beli`
								UNION
								SELECT prpo.kode_beli AS `KODE_PR`,prpo.tgl_beli AS `TGL_PR`,prpo.kode_supplier AS `SUPPLIER`,prpo.cabang AS `CABANG`,prpo.total AS `VALUE_PR`,
									IFNULL(po.kodepo_beli,'') AS `KODE_PO`,IFNULL(po.tglpo_beli,'') AS `TGL_PO`,IFNULL(po.namaprinsipal,'') AS `PRINSIPAL` FROM (
								SELECT kode_beli,tgl_beli,kode_supplier,cabang,SUM(subjumlah) AS total FROM (
								SELECT a.kode_beli,a.tgl_beli,a.kode_supplier,a.cabang,a.kode_barang,
									a.`jumlah_beli`,(a.`jumlah_beli` * b.`hargajual`) AS subjumlah FROM pembelian_psi a
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`)sprutin
								GROUP BY kode_beli,tgl_beli,cabang
								)prpo
								LEFT JOIN (SELECT DISTINCT kodepo_beli,kode_beli,tglpo_beli,namaprinsipal FROM `vpoprinspsi`) po  ON prpo.kode_beli=po.`kode_beli`
								)servlvlpopr HAVING value_pr <> 0 AND kode_po='' AND cabang='$cabang'
								ORDER BY tgl_pr DESC,cabang,kode_pr
									LIMIT $hal,$lim";//echo "--6--".$sql."</br>";
									}
				}	
		else
			{
				if ($cabang =='KPS'){
					
						$table = "pembelian";
						$field = "kode_beli";
					
						if (stripos($kode, "PRE") !== false){ 
							$table = "pembelian_pre";
							$field = "kode_beli";
						}
						if (stripos($kode, "PSI") !== false){ 
							$table = "pembelian_psi";
							$field = "kode_beli";
						}
					
						$sql = "SELECT a.`kode_beli`,a.`tgl_beli`,a.`kode_supplier`,b.`namasupplier`,a.`kode_barang`,c.`namaproduk`,c.`satuan`,
							a.`jumlah_beli`,a.`ratio`,a.`stok`,a.`averg`,a.ket_prinsipal,a.ket_cabang
							FROM $table a 
							LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
							LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
							WHERE a.kode_beli='$kode'
							ORDER BY a.kode_beli DESC, a.kode_barang ASC
							LIMIT $hal,$lim";//echo "--7--".$sql."</br>";
						}
					else {
						
						$table = "pembelian";
						$field = "kode_beli";
					
						if (stripos($kode, "PRE") !== false){ 
							$table = "pembelian_pre";
							$field = "kode_beli";
						}
						if (stripos($kode, "PSI") !== false){ 
							$table = "pembelian_psi";
							$field = "kode_beli";
						}
						
						$sql = "SELECT a.`kode_beli`,a.`tgl_beli`,a.`kode_supplier`,b.`namasupplier`,a.`kode_barang`,c.`namaproduk`,c.`satuan`,
							a.`jumlah_beli`,a.`ratio`,a.`stok`,a.`averg`,a.ket_prinsipal,a.ket_cabang
							FROM $table a 
							LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
							LEFT JOIN `mstproduk` c ON a.`kode_barang`=c.`kodeproduk`
							WHERE a.kode_beli='$kode' AND cabang='$cabang'
							ORDER BY a.kode_beli DESC, a.kode_barang ASC
							LIMIT $hal,$lim";//echo "--8--".$sql."</br>";
						}
			}
		
				
		//echo $sql."</br>";
		$query = mysql_query($sql) or  print(mysql_error());
		
		$no=1+$hal;
		if (empty($kode)){
		while($r_data=mysql_fetch_array($query)){
			$suppliername = nama_supplier_saja($r_data[SUPPLIER]);
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[KODE_PR]</td>
					<td align='center'>$r_data[TGL_PR]</td>
					<td>$r_data[SUPPLIER]</td>
					<td>$suppliername</td>
					<td>$r_data[kode_prinsipal]</td>
					<td>$r_data[PRINSIPAL]</td>
					<td>$r_data[KODE_PO]</td>
					<td>$r_data[TGL_PO]</td>
					<td align='center'><a href='javascript:lihatRow(\"{$r_data[KODE_PR]}\")' ><img src='icon/magnifier.png' border='0' id='lihatttt' title='Lihat' width='12' height='12' ></a></td>
					<td align='center'>";if (empty($r_data[KODE_PO])){echo "<a href='javascript:editRow(\"{$r_data[KODE_PR]}\")' ><img src='icon/thumb_up.png' border='0' id='edit' title='Edit' width='12' height='12' ></a>";}echo "</td>
					<td align='center'><a href='javascript:cetakRow(\"{$r_data[KODE_PR]}\")' ><img src='icon/printer.png' border='0' id='cetakkk' title='Cetak' width='12' height='12' ></a></td>
					</tr>";
			$no++;//echo "--9--"."</br>";
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
		if (empty($kode1) && empty($kode2)){//echo "--11--"."</br>";
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/laporan/laporan_pr/tampil_data_list_pr_out.php?cabang=<?php echo $cabang;?>&hal=<?php echo $list[$h]; ?>', 
                    null, function(data) {$('#info').html(data);})" <?php echo">".$h."</a> ";
				}
	echo "</td>";
		}else{//echo "--12--"."</br>";
		echo "<td align='right'>Halaman :";
			for ($h = 1; $h <= $max; $h++) {
					$list[$h] = $lim * $h - $lim;
					echo " <a href='javascript:void(0)' ";?> 
                    onClick="$.get('modul/laporan/laporan_pr/tampil_data_list_pr_out.php?cabang=<?php echo $cabang;?>&kode=<?php echo $_GET['kode'];?>
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