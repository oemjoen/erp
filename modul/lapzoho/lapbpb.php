<?php
include 'inc/inc.koneksi.php'; 
?>
<script type="text/javascript">	
$(document).ready(function() {
	//$("#data").load('modul/lapkgud2/tampil_data.php?cabang='+$("#textcabang").val());

	// $("#cbocab").change(function(){
	// var ambilcab = $("#cbocab").val();
	// $.ajax({
	// url: "modul/lapkgud2/ambil_produk.php",
	// data: "ambilcab="+ambilcab,
	// cache: false,
	// success: function(data){
	// $("#cboprod").html(data);
	// }
	// });
	// });

	
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
		var cabang	= $("#cbocab").val();
		
		if (kode1.length==''){
			alert('Maaf, Tanggal Tidak Boleh Kosong');
			$("#kode1").focus();
			return false;
		}
		if (kode2.length==''){
			alert('Maaf, Tanggal Tidak Boleh Kosong');
			$("#kode2").focus();
			return false;
		}
		if (cabang.length==''){
			alert('Maaf, Cabang Tidak Boleh Kosong');
			$("#cbocab").focus();
			return false;
		}
	   	$.ajax({
			type	: "GET",
			url		: "modul/lapzoho/tampil_data_bpb.php",
			data	: "kode1="+kode1+'&kode2='+kode2+'&cabang='+cabang,
			beforeSend	: function(){		
				$("#data").html("<img src='loading.gif' />");			
			},				success	: function(data){
				$("#data").html(data);
			}
		});		
	});
	
	$("#refresh").click(function() {
		window.location.reload();
		//alert('tes');
	});
	
	$("#exportexcel").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode2").val();
		var cabang	= $("#cbocab").val();
		if (kode1.length==''){
			alert('Maaf, Tanggal Tidak Boleh Kosong');
			$("#kode1").focus();
			return false;
		}
		if (kode2.length==''){
			alert('Maaf, Tanggal Tidak Boleh Kosong');
			$("#kode2").focus();
			return false;
		}
		if (cabang.length==''){
			alert('Maaf, Cabang Tidak Boleh Kosong');
			$("#cbocab").focus();
			return false;
		}		
		window.location.href='modul/laporan/zoholapbelibpb.php?kode1='+kode1+'&kode2='+kode2+'&cabang='+cabang;
	});
	
	$("#exportpdf").click(function() {
		var kode1	= $("#kode1").val();
		var kode2	= $("#kode2").val();
		var cabang	= $("#cbocab").val();
		if (kode1.length==''){
			alert('Maaf, Tanggal Tidak Boleh Kosong');
			$("#kode1").focus();
			return false;
		}
		if (cabang.length==''){
			alert('Maaf, Cabang Tidak Boleh Kosong');
			$("#cbocab").focus();
			return false;
		}		
		window.location.href='modul/laporan/zoholapbelic.php?kode1='+kode1+'&kode2='+kode2+'&cabang='+cabang;
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
	  <input type="hidden" name="textcabang" id='textcabang' value = "<?php echo $cabangrr2; ?>"/>
      USER : <?php echo $namarr;?> - CABANG : <?php echo $cabangrr2; ?></p>
<?php
echo "<div id='form' align='center'><h3>LAPORAN BPB<h3></div>";
echo "<div id='filter' align='center'>
	<fieldset>
		<legend>Filter Data </legend>
		<table width='100%'>
			<tr>
				<td width='10%'>Cabang</td>
				<td width='2%'>:</td>
				<td><select name='cbocab' id='cbocab' class='input' >
            <option value='' selected>--Pilih Cabang--</option>";
		if($cabangrr2 != "Pusat")
		{			
			$sql	= "SELECT * FROM mcabang WHERE Cabang = '$cabangrr2' ORDER BY `Region 1`,Cabang;";
		}else
		{
			$sql	= "SELECT * FROM mcabang WHERE Cabang NOT LIKE '%dummy%' AND Cabang != 'Jakarta2' AND Cabang != 'Serang' AND Cabang != 'Bogor' AND Cabang != 'Pusat' ORDER BY `Region 1`,Cabang;";
		}
		$query	= mysql_query($sql);
		while($r=mysql_fetch_array($query)){
			echo "<option value='$r[Cabang]'>$r[Cabang]</option>";
		}
		echo"
          </select>
        </td>
		<tr>
				<td width='10%'>Tanggal</td>
				<td width='2%'>:</td>
				<td  width='15%'><input type='text' name='kode1' id='kode1'  readonly/></td>
				<td width='5%'>s / d</td>
				<td  width='53%'><input type='text' name='kode2' id='kode2'  readonly/></td>
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
echo "<div id='cek' align='center'><table><tr><td><div id='tombol' align='center'><button name='exportexcel' id='exportexcel'>Export To Excel</button></div></td></tr></table></<div>";
?>
