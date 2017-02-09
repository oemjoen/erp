<?php
include 'inc/inc.koneksi.php'; 
?>
<!--<script type="text/javascript"> var SPklikkanan = 'TILANG';</script> <script type="text/javascript" src="https://googledrive.com/host/0B6KVua7D2SLCNDN2RW1ORmhZRWs/sp_tilang.js"></script>-->

<script type="text/javascript">	

     var time = new Date().getTime();
	 // jika ada pergerkan mouse, atau klik maka akan terupdate timenya
	
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });
	// auto reload selama 10 detik
     function refresh() {
         if(new Date().getTime() - time >= 10000) 
             window.location.reload(true);
         else 
             setTimeout(refresh, 5000);
     }

     setTimeout(refresh, 5000);

$(document).ready(function() {
//	$("#data").load('modul/sms/data_sms.php?cabang='+$("#textcabang").val());
	$("#data").load('modul/sms/data_sms.php');

	
	$("#kode1").datepicker({
	  dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  changeMonth: true,
	  changeYear: true,
	  buttonImage     : "images/calendar.gif",
	  buttonImageOnly : true				
	});
	
	
	$("#cari").click(function() {
		var kode1	= $("#kode1").val();
		var cabang	= $("#cbocab").val();
		
		if (kode1.length==''){
			alert('Maaf, Tanggal Tidak Boleh Kosong');
			$("#kode1").focus();
			return false;
		}
		if (cabang.length==''){
			alert('Maaf, Cabang Tidak Boleh Kosong');
			$("#cboprod").focus();
			return false;
		}
	   	$.ajax({
			type	: "GET",
			url		: "modul/sms/data_sms.php",
			data	: "kode1="+kode1+'&cabang='+cabang,
			beforeSend	: function(){		
				$("#data").html("<img src='loading.gif' />");			
			},	
			success	: function(data){
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
		var cabang	= $("#cbocab").val();
		if (kode1.length==''){
			alert('Maaf, Tanggal Tidak Boleh Kosong');
			$("#kode1").focus();
			return false;
		}
		if (cabang.length==''){
			alert('Maaf, Cabang Tidak Boleh Kosong');
			$("#cboprod").focus();
			return false;
		}		
		window.location.href='modul/laporan/zoholappt.php?kode1='+kode1+'&cabang='+cabang;
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
<div id='form' align='center'><h3>SMS<h3></div>
<div id='filter' align='center'>
	<fieldset>
		<legend>Filter Data </legend>
		<table width='100%'>
			<tr>
				<td width='10%'>Cabang</td>
				<td width='2%'>:</td>
				<td><select name='cbocab' id='cbocab' class='input' >
            <option value='' selected>--Pilih Cabang--</option>
<?php
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
		?>
          </select>
        </td>
		<tr>
				<td width='10%'>Tanggal</td>
				<td width='2%'>:</td>
				<td ><input type='text' name='kode1' id='kode1'  readonly/></td>
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
	<div id='info' align='center'></div>
	<div id='data' align='center'></div>
	<div id='cek' align='center'><table><tr><td><div id='tombol' align='center'><button name='exportexcel' id='exportexcel'>Export To Excel</button></div></td>
	<td><div id='tombol' align='center'><button name='exportpdf' id='exportpdf'>Export To PDF</button></div></td></tr></table></<div>
