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
	  dateFormat      : "yy",        
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
		var error = false;
		
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
		if (kode2.length==''){
			alert('Periode Triwulan Harus di Isi');
			$("#kode2").focus();
			return false;
		}
		
		if(error == false){
	   	$.ajax({
			type	: "GET",
			url		: "modul/lapbpom/tampil_data_obat.php",
			data	: "kode1="+kode1+'&cabang='+cabang+'&kode2='+kode2,
			beforeSend	: function(){		
				$("#data").html("<img src='loading.gif' />");			
			},	
			success	: function(data){
				$("#data").html(data);
			}
		});
		}	
		return false; 		
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
			alert('Periode Triwulan Harus di Isi');
			$("#kode2").focus();
			return false;
		}
		if (cabang.length==''){
			alert('Maaf, Cabang Tidak Boleh Kosong');
			$("#cbocab").focus();
			return false;
		}		
		//C:\wamp\www\erp\modul\laporan\zoholapbpomalkes.php
		window.location.href='modul/laporan/zoholapbpomobat.php?kode1='+kode1+'&cabang='+cabang+'&kode2='+kode2;
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
echo "<div id='form' align='center'><h3>PELAPORAN OBAT<h3></div>";
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
			$sql	= "SELECT * FROM mcabang WHERE Cabang NOT LIKE '%dummy%'  AND Cabang != 'Serang' AND Cabang != 'Jakarta2' AND Cabang != 'Pusat' ORDER BY `Region 1`,Cabang;";
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
				<td ><input type='text' name='kode1' id='kode1'  readonly/></td>
		</tr>
		<tr>
				<td width='10%'>Triwulan</td>
				<td width='2%'>:</td>
				<td><select id='kode2' name='kode2' class='input'>
						<option value='' selected>--Pilih Triwulan--</option>
						<option value='1'>Triwulan 1</option>					
						<option value='2'>Triwulan 2</option>					
						<option value='3'>Triwulan 3</option>					
						<option value='4'>Triwulan 4</option>					
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
	</div><br>";
echo "<div id='info' align='center'></div>";
echo "<div id='data' align='center'></div>";
echo "<div id='cek' align='center'><table><tr><td><div id='tombol' align='center'><button name='exportexcel' id='exportexcel'>Export To Excel</button></div></td></tr></table></<div>";
?>
