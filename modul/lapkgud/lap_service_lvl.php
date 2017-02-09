<script type="text/javascript">	
$(document).ready(function() {
	$("#data").load('modul/lapkgud/tampil_data.php?cabang='+$("#textcabang").val());
	
	$("#kode1").datepicker({
	  dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  changeMonth: true,
	  changeYear: true,
	  buttonImage     : "images/calendar.gif",
	  buttonImageOnly : true				
	});
	
	$("#kode2").datepicker({
	  dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  changeMonth: true,
	  changeYear: true,
	  buttonImage     : "images/calendar.gif",
	  buttonImageOnly : true				
	});
	
	
	$("#cari").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode2").val();
		var cabang	= $("#textcabang").val();
		
		if (kode1.length==''){
			alert('Maaf, Kriteria belum lengkap');
			$("#kode1").focus();
			return false;
		}
		if (kode2.length==''){
			alert('Maaf, Kriteria belum lengkap');
			$("#kode2").focus();
			return false;
		}
	   	$.ajax({
			type	: "GET",
			url		: "modul/lapkgud/tampil_data.php",
			data	: "kode1="+kode1+"&kode2="+kode2+'&cabang='+cabang,
			success	: function(data){
				$("#data").html(data);
			}
		});		
	});
	
	$("#refresh").click(function() {
		window.location.reload();
		//alert('tes');
	});
	
	$("#cetak").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode2").val();
		var cabang	= $("#textcabang").val();
		
		window.location.href='modul/laporan/lapservlvlprpo.php?kode1='+kode1+'&kode2='+kode2+'&cabang='+cabang;
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
<?php
echo "<div id='form' align='center'><h3>Kartu Gudang<h3></div>";
echo "<div id='filter' align='center'>
	<fieldset>
		<legend>Filter Data </legend>
		<table width='100%'>
			<tr>
				<td width='10%'>Tanggal</td>
				<td width='2%'>:</td>
				<td ><input type='text' name='kode1' id='kode1'  readonly/></td>
				<td width='15%'> Sampai Dengan </td>
				<td width='10%'>Tanggal PR</td>
				<td width='2%'>:</td>
				<td ><input type='text' name='kode2' id='kode2'  readonly/></td>	
			</tr>
			<tr>
				<td align='center' colspan='7'>
				<button name='cari' id='cari'>CARI DATA</button>
				<button name='refresh' id='refresh'>REFRESH</button>
				</td>
			</tr>
		</table>
	</fieldset>
	</div><br>";
echo "<div id='data' align='center'></div>";
echo "<div id='tombol' align='center'><button name='cetak' id='cetak'>Import To Excel</button></div>";
?>
