<script type="text/javascript">	
$(document).ready(function() {
	$("#data").load('modul/t_order/tampil_data_list_ord_sp.php?cabang='+$("#textcabang").val());

	$("#txt_tgl_beli_list").datepicker({
	  dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  buttonImage     : "images/calendar.gif",
	  buttonImageOnly : true				
	});
	
	
	$("#cari").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode1").val();
		var kode	= $("#kode1").val();
		var cabang	= $("#textcabang").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/t_order/tampil_data_list_ord_sp.php",
			data	: "kode="+kode+"&cabang="+cabang,
			success	: function(data){
				$("#data").html(data);
			}
		});		
	});
		
	$("#spesan").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode1").val();
		var kode	= $("#kode1").val();
		var cabang	= $("#textcabang").val();
		var apt	= $("#textuser").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/t_order/tampil_data_list_ord_sp.php",
			data	: "cabang="+cabang+"&spc=spcek"+"&apt="+apt,
			success	: function(data){
				$("#data").html(data);
			}
		});		
	});
	
	$("#caritgl").click(function() {
		var tgl	= $("#txt_tgl_beli_list").val();
		var cabang	= $("#textcabang").val();
		
	   	$.ajax({
			type	: "GET",
			url		: "modul/t_order/tampil_data_list_pr_bydate.php",
			data	: "tgl="+tgl+"&cabang="+cabang,
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
		window.location.href='media.php?module=t_order&no='+kode;
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
	  <input type="hidden" name="textuser" id='textuser' value = "<?php echo $userrr; ?>"/>
      USER : <?php echo $namarr;?> - CABANG : <?php echo $cabangrr; ?></p>

<div id='form' align='center'><h2>LIST / CETAK SP </h2></div>
<div id='filter' align='center'>
	<fieldset>
		<legend>Filter Data </legend>
		<table width='100%'>
			<tr>
				<td width='10%'>Kode SP</td>
				<td width='1%'>:</td>
				<td width='89%'><select nama='kode1' id='kode1'>
				<option value=''>-Pilih Kode-</option>";
				<?php 
				if ($cabangrr == 'KPS') {
				$query	= "SELECT a.NoSP,a.NoSP AS NoOrder2,a.`TglSP`,a.KodeApotik,SUM(a.`Value`) AS tot1 FROM t_order a, mpelanggan c, mretail d
						WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
						GROUP BY a.NoSP,a.`TglSP`,a.`KodeApotik`
						ORDER BY RIGHT(a.`NoSP`,5) DESC";
				} else {
				$query	= "SELECT a.`NoSP`,RIGHT(a.`NoSP`,5) AS NoOrder2,a.`TglSP`,a.KodeApotik, SUM(a.`Value`) AS tot1 FROM t_order a, mpelanggan c, mretail d
							WHERE a.`KodeApotik`=c.`Kode` AND a.`KodePelanggan`=d.`Kode` AND a.`Status`='OpenSP'
							AND a.Cabang='$cabangrr'
							GROUP BY a.NoSP,a.`TglSP`,a.`KodeApotik`
							ORDER BY RIGHT(a.`NoSP`,5) DESC";
				}
				$sql	= mysql_query($query);
				while($r_data=mysql_fetch_array($sql)){
					echo "<option value='$r_data[NoSP]'>$r_data[NoOrder2] - $r_data[KodeApotik] - Tanggal Beli : $r_data[TglSP]</option>";
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
<!--	<button name='spesan' id='spesan'>Buat SP</button> -->
<!--	<button name='edit_pr' id='edit_pr'>Edit PR</button> -->
</div>
<?php
	//echo $query;
?>