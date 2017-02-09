<script type="text/javascript">	
$(document).ready(function() {
	$("#data").load('modul/pembelian/tampil_data_list_pr.php?cabang='+$("#textcabang").val());


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
			url		: "modul/pembelian/tampil_data_list_pr.php",
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
		
		if (kode1.length==''){
			alert('Maaf, Tidak bisa edit karena kode kososng');
			$("#kode1").focus();
			return false;
		}
		
		window.location.href='media.php?module=pembelian&no='+kode;
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
		window.location.href='modul/laporan/cetak_pr.php?kode='+kode;
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

<div id='form' align='center'><h2>LIST / CETAK PR RUTIN <h3></div>
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
				$query	= "SELECT DISTINCT a.kode_beli,IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli,a.cabang,a.tgl_beli 
										FROM pembelian a
										LEFT JOIN (
												SELECT DISTINCT kodepo_beli,kode_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian po
												LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
											) gab ON a.`kode_beli`= gab.kode_beli
										HAVING kodepo_beli=''
										ORDER BY a.`tgl_beli` DESC,a.`cabang`,a.`kode_beli`";
				} else {
				$query	= "SELECT DISTINCT a.kode_beli,IFNULL(gab.`kodepo_beli`,'') AS kodepo_beli,a.cabang,a.tgl_beli 
										FROM pembelian a
										LEFT JOIN mstsupplier2 b ON a.`kode_supplier`=b.`kodesupplier`
										LEFT JOIN (
												SELECT DISTINCT kodepo_beli,kode_beli,kode_prinsipal,`namaprinsipal` FROM po_pembelian po
												LEFT JOIN mstprinsipal prin ON po.`kode_prinsipal`=prin.`kodeprinsipal`
											) gab ON a.`kode_beli`= gab.kode_beli
										WHERE cabang='$cabangrr' HAVING kodepo_beli=''
										ORDER BY a.`tgl_beli` DESC,a.`cabang`,a.`kode_beli`";
				}
				$sql	= mysql_query($query);
				while($r_data=mysql_fetch_array($sql)){
					echo "<option value='$r_data[kode_beli]'>$r_data[kode_beli] - $r_data[cabang] - Tanggal Beli : $r_data[tgl_beli]</option>";
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
</div>

