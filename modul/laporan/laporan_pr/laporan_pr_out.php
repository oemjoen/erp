<script type="text/javascript">	
$(document).ready(function() {
	$("#data").load('modul/laporan/laporan_pr/tampil_data_list_pr_out.php?cabang='+$("#textcabang").val());


	$("#cari").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode1").val();
		var kode	= $("#kode1").val();
		var cabang	= $("#textcabang").val();
		
/*		if (kode1.length==''){
			alert('Maaf, Kriteria belum lengkap');
			$("#kode1").focus();
			return false;
		}
		if (kode2.length==''){
			alert('Maaf, Kriteria belum lengkap');
			$("#kode1").focus();
			return false;
		}*/
	   	$.ajax({
			type	: "GET",
			url		: "modul/laporan/laporan_pr/tampil_data_list_pr_out.php",
			data	: "kode="+kode+"&cabang="+cabang,
			success	: function(data){
				$("#data").html(data);
			}
		});		
	});
	
	$("#refresh").click(function() {
		window.location.reload();
		//alert('tes');
	});
	
	$("#edit_pr").click(function() {
		var kode = $("#kode1").val();
		
		if (kode.length==''){
			alert('Maaf, Tidak bisa edit karena kode kososng');
			$("#kode").focus();
			return false;
		}
		
	   if (kode.search("PRE")){
		window.location.href='media.php?module=pembelian_pre&no='+kode;
	   }else if (kode.search("PSI")){
		window.location.href='media.php?module=pembelian_psi&no='+kode;
	   }else {
		window.location.href='media.php?module=pembelian&no='+kode;
		}
	});

	$("#importdata").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode2").val();
		var cabang	= $("#textcabang").val();
		window.location.href='modul/laporan/lapservlvlprnopo.php?cabang='+cabang;
	});
		
	$("#cetak").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode1").val();
		var kode	= $("#kode1").val();
		if (kode.length==''){
			alert('Maaf, Tidak bisa cetak karena kode kosong');
			$("#kode1").focus();
			return false;
		}
		
		if (kode.search("PRE")){
		window.location.href='modul/laporan/cetak_pr_pre.php?kode='+kode;
	   }else if (kode.search("PSI")){
		window.location.href='modul/laporan/cetak_pr_psi.php?kode='+kode;
	   }else {
		window.location.href='modul/laporan/cetak_pr.php?kode='+kode;
		} 
	});
});
</script>
<style type="text/css">
#info {
	font-size:12px;
	font-weight:bold;
	color:#F00;
}
</style>
	<p align="right">
	  <input type="hidden" name="textcabang" id='textcabang' value = "<?php echo $cabangrr; ?>"/>
      USER : <?php echo $namarr;?> - CABANG : <?php echo $cabangrr; ?></p>

<div id='form' align='center'><h2>Laporan OUTSTANDING PR</h2></div>
<div id='filter' align='center'>
	<fieldset>
		<legend>Filter Data </legend>
		<table width='100%'>
			<tr>
				<td width='10%'>Kode PR</td>
				<td width='2%'>:</td>
				<td ><select nama='kode1' id='kode1'>
				<option value=''>-Pilih Kode-</option>";
				<?php 
				if ($cabangrr == 'KPS') {
				$query	= "SELECT * FROM (
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
								ORDER BY tgl_pr DESC,cabang,kode_pr";
				} else {
				$query	= "SELECT * FROM (
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
								)servlvlpopr HAVING value_pr <> 0 AND cabang='$cabangrr' AND kode_po=''
								ORDER BY tgl_pr DESC,cabang,kode_pr";
				}
				$sql	= mysql_query($query);
				while($r_data=mysql_fetch_array($sql)){
					echo "<option value='$r_data[kode_beli]'>$r_data[KODE_PR] - $r_data[CABANG] - Tanggal Beli : $r_data[TGL_PR]</option>";
				}?>
				</select> 
				</td>
			
			</tr>
			<tr>
				<td align='center' colspan='7'>
				<button name='cari' id='cari'>CARI DATA</button>
				<button name='refresh' id='refresh'>REFRESH</button>
				</td>
			</tr>
		</table>
	</fieldset>
	</div><br>
<div id='data' align='center'></div>
<div id='tombol' align='center'>
	<button name='cetak' id='cetak'>Cetak</button>
	<button name='edit_pr' id='edit_pr'>Edit PR</button>
	<button name='importdata' id='importdata'>Import Data ke Excel</button>	
</div>

