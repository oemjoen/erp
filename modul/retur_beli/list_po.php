<script type="text/javascript">	
$(document).ready(function() {
	$("#data").load('modul/retur_beli/tampil_data_list_po.php?cabang='+$("#textcabang").val());
	
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
			url		: "modul/retur_beli/tampil_data_list_po.php",
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
	
	$("#edit_po").click(function() {
		var kode = $("#kode1").val();
		
		if (kode1.length==''){
			alert('Maaf, Tidak bisa cetak karena kode kososng');
			$("#kode1").focus();
			return false;
		}
		
		window.location.href='media.php?module=edit_po_beli&no='+kode;
	});
	
	$("#cetak").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode1").val();
		var kode	= $("#kode1").val();
		if (kode1.length==''){
			alert('Maaf, Tidak bisa cetak karena kode kosong');
			$("#kode1").focus();
			return false;
		}
		window.location.href='modul/laporan/cetak_po.php?kode='+kode;
		/*
	   	$.ajax({
			type	: "GET",
			url		: "modul/laporan/lap_stok_barang.php",
			data	: "kode1="+kode1+"&kode2="+kode2,
			success	: function(data){
				//$("#data").html(data);
				alert('Data Sukses dicetak');
			}
		});
		*/
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

<div id='form' align='center'><h2>CETAK PO CABANG <h3></div>
<div id='filter' align='center'>
	<fieldset>
		<legend>Filter Data </legend>
		<table width='100%'>
			<tr>
				<td width='10%'>Kode PO</td>
				<td width='2%'>:</td>
				<td ><select nama='kode1' id='kode1'>
				<option value=''>-Pilih Kode-</option>";
				<?php 
				if ($cabangrr == 'KPS') {
				$query	= "SELECT DISTINCT(kodepo_beli) AS kodepo_beli,`tglpo_beli`,cabang FROM po_pembelian 
							ORDER BY `tglpo_beli` DESC,`kodepo_beli` DESC";
				} else {
				$query	= "SELECT DISTINCT(kodepo_beli) AS kodepo_beli,`tglpo_beli`,cabang FROM po_pembelian
							WHERE cabang='$cabangrr'
							ORDER BY `tglpo_beli` DESC,`kodepo_beli` DESC";
				}
				$sql	= mysql_query($query);
				while($r_data=mysql_fetch_array($sql)){
					echo "<option value='$r_data[kodepo_beli]'>$r_data[kodepo_beli] - $r_data[cabang] - Tanggal Beli : $r_data[tglpo_beli]</option>";
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
	<button name='edit_po' id='edit_po'>Edit PO</button>
</div>

