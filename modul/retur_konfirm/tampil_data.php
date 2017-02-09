<script type="text/javascript">
$(document).ready(function() {

    var inpA = "input[rel=Ajumlah]";
    var inpB = "input[rel=Bjumlah]";
	
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
	
	$(".jmlvalid").keypress(function(data){
		if (data.which!=8 && data.which!=0 && (data.which<48 || data.which>57)) 
		{
			return false;
		}
	});
		
	function h_cek(){
		var cek 	= $(".cek:checked").length;
		$("#tot_cek").html('Jumlah Ceklist : '+cek);
	}

  $("#theList.retur tr")
  	.filter(":has(:checkbox:checked)")
	.addClass("klik")
	.end()
	.click(function(event) {		
		$(this).toggleClass("klik");
		if(event.target.type !== "checkbox") {
			$(":checkbox",this).attr("checked",function(){															
				return !this.checked;
			});
			h_cek();
		}
		
	});
	

	$("#simpan_konfretur").click(function(){
		
		var cekxx 			= $(".cek:checked").length;
		var cek 			= $(".cek").length;
		var cek2 			= $(".cek").length;
		var prins			= $("#cbo_prinsipal").val();
		//nomor usulan retur
		var kodekonf_retur 	= $("#txt_kode").val();
		var counterr 	= $("#counterr").val();
		var	tgl				= $("#txt_tgl_beli").val();
		//no acu bpb
		var	kode			= $("#cbo_beli").val();
		var kodeuser 		= $("#kode_user").val();
		
		if(prins.length == 0){
           alert("Maaf, Prinsipal tidak boleh kosong");
		   $("#cbo_prinsipal").focus();
		   return false;
         }
		 
		//buat_kode();	
		

		if(kodekonf_retur.length == 0){
           alert("Maaf, Kode Retur tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return false;
         }
		 
		if(tgl.length == 0){
           alert("Maaf, Tanggal Retur tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return false;
         }
		if(kode.length == 0){
           alert("Maaf, Kode Retur tidak boleh kosong");
		   $("#cbo_beli").focus();
		   return false;
         }	
		
		if (cekxx ==0){
			alert('Maaf, Anda belum memilik/cek data');
			return false;
		}
		
		for (var j = 1; j <= cek2 ; ++j) {
			
			
			var cekdata = $("#cek"+j+":checked").val();
			
			if(cekdata != null)
			{
				var jmlvalidx = $("#jmlvalid"+j).val();
				var jmlvalidbnsx = $("#jmlvalidbns"+j).val();
				var qtycekx =$("#qtyreturawal"+j).val();

				var qtybpb =$("#qtybpb"+j).val();
				var Bonus =$("#Bonus"+j).val();
				
				var kode_brgx = $("#kode_brg"+j).val();				

				if ((jmlvalidx + jmlvalidbnsx + qtycekx) > (qtybpb + Bonus))
				{
					alert("Maaf, Jumlah Retur Produk "+kode_brgx+" melebihi total beli");
					$("#jmlvalid"+j).focus();
					return false;
				}				

			}
			
		}
		
		
		for (var i = 1; i <= cek ; ++i) {
			//var id = $("#cek"+i).val();

			var cekdata = $("#cek"+i+":checked").val();
			
			if(cekdata != null)
			{
				//alert(cekdata);
				var cabang = $("#cabang"+i).val();
				var kode_supplier = $("#kode_supplier"+i).val();
				var tglacu =$("#tglacu"+i).val();
				var kode_brg = $("#kode_brg"+i).val();
				var kodep =$("#kodep"+i).val();
				var namap =$("#namap"+i).val();
				var qtybpb =$("#qtybpb"+i).val();
				var Bonus =$("#Bonus"+i).val();
				var batch = $("#batch"+i).val();
				var expd =$("#expd"+i).val();
				var Satuan =$("#Satuan"+i).val();

				//input manual
				var jmlvalid = $("#jmlvalid"+i).val();
				var jmlvalidbns = $("#jmlvalidbns"+i).val();
				var ket_pusat = $("#ket_pusat"+i).val();
				
				//Cabang
				var hpc =$("#hpc"+i).val();
				var disc =$("#disc"+i).val();
				var harga =$("#harga"+i).val();
				var grosb =$("#grosb"+i).val();
				var pot =$("#pot"+i).val();
				var val =$("#val"+i).val();
				var ppn =$("#ppn"+i).val();
				var nilju =$("#nilju"+i).val();
				
				//Pusat
				var hpp =$("#hpp"+i).val();
				var discp =$("#discp"+i).val();
				var hrgp =$("#hrgp"+i).val();
				var grBPP =$("#grBPP"+i).val();
				var valp =$("#valp"+i).val();
				var potp =$("#potp"+i).val();
				var niljup =$("#niljup"+i).val();
				
				
				if ((jmlvalid + jmlvalidbns) == 0)
				{
					alert("Maaf, Jumlah Retur Produk "+kode_brg+" tidak boleh kosong");
					$("#jmlvalid"+i).focus();
					return false;
				}				
				
				if (ket_pusat.length == 0)
				{
					alert("Maaf, Keterangan Retur Produk "+kode_brg+" tidak boleh kosong");
					$("#ket_pusat"+i).focus();
					return false;
				}

				//buat_kode();
				$.ajax({
				type	: "POST",
				url		: "modul/retur_konfirm/simpan.php",
				data	: 	"prins="+prins+
							"&kode_supplier="+kode_supplier+
							"&cabang="+cabang+
							"&kodekonf_retur="+kodekonf_retur+
							"&counterr="+counterr+
							"&tgl="+tgl+
							"&kode="+kode+
							"&tglacu="+tglacu+
							"&kodep="+kodep+
							"&qtybpb="+qtybpb+
							"&Bonus="+Bonus+
							"&batch="+batch+
							"&expd="+expd+
							"&Satuan="+Satuan+
							"&jmlvalid="+jmlvalid+
							"&jmlvalidbns="+jmlvalidbns+
							"&ket_pusat="+ket_pusat+
							"&hpc="+hpc+
							"&disc="+disc+
							"&harga="+harga+
							"&grosb="+grosb+
							"&pot="+pot+
							"&val="+val+
							"&ppn="+ppn+
							"&nilju="+nilju+
							"&hpp="+hpp+
							"&discp="+discp+
							"&hrgp="+hrgp+
							"&grBPP="+grBPP+
							"&valp="+valp+
							"&potp="+potp+
							"&niljup="+niljup+
							"&kodeuser="+kodeuser,
				success	: function(data){
					$("#ket").html(data);
					//alert('OK');
					document.location='?module=retur_konfirm';
				}
				});
			
			} 
		}
		
	});

});
</script>
<style type="text/css">
.klik {  
     background:#090; 
}     
</style>

<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_rupiah.php';
include '../../inc/fungsi_hdt.php';
$kode	= $_POST[kode];
$cabang	= $_POST[cabang1];

echo "<table id='theList' class='retur' width='100%'>
		<tr>
			<th width='2%'>No.</th>
			<th width='2%'>Cek</th>
			<th>Kode<br>Barang</th>
			<th>Nama<br>Barang</th>
			<th>Qty<br>BPB</th>
			<th>Qty<br>Bonus</th>
			<th>Qty<br>ReturX</th>
			<th>Satuan</th>
			<th>Batch</th>
			<th>ED</th>
			<th>Qty<br>Retur</th>
			<th>Qty<br>Bonus<br>Retur</th>
			<th>Alasan</th>
			<th>Disc</th>
			<th>Harga</th>
			<th>Gross</th>
			<th>Potongan</th>
			<th>Value</th>
			<th>PPN</th>
			<th>NilDok</th>
		</tr>";
				
		$sql = "SELECT a.cabang,a.`Produk` AS kodep, b.`Produk` AS namap, 
					a.`Supplier`,a.`Qty Terima` AS qtybpb,
					a.`Bonus`, b.`Satuan`,`Batch No` AS batch, 
					DATE(`Exp Date`) AS expd, `No DO` AS nodo,`Tgl DO` AS tgldo, 
					`Hrg Beli Cab` AS hrgc,`Disc Cab` AS discc, 
					a.`Gross BPB` AS grBPB,a.`Gross DO` AS grDO,
					`Pot BPB` AS pot,`Value BPB` AS val,a.`PPN DO` AS ppn,`Value PPN BPB` AS nilju,
					`HPC1` AS unitcogs, (`HPC1` * a.`Qty Terima`) AS totcogshit,
					hpc1,hpp1,
					a.`Hrg Beli Pst` AS hrgp,`Disc Pst` AS discp,
					`Value BPP` AS valp, `Pot BPP` AS potp, `Value PPN BPP` AS niljup,
					`Gross BPP` AS grBPP	
				FROM `dbpbdodetail` a, `mproduk` b
				WHERE cabang='$cabang' AND `Status BPB` = 'DO' AND `No BPB`='$kode'
					AND a.`Produk`=b.`Kode Produk`
				ORDER BY `Tgl BPB`,`Counter BPB`, a.`Produk`";		
		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1;
		while($r_data=mysql_fetch_array($query)){
			$total	= $r_data[jumlah_retur]*$r_data[harga_beli];
			$crb = cekRetBeli($r_data[cabang],$kode,$r_data[kodep],$r_data[batch])[0];
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>
					<input type='checkbox' value='$no' class='cek' id='cek$no'>
					<input type='hidden' name='kode_brg$no'  id='kode_brg$no' value='$r_data[kodep]' >
					<input type='hidden' name='kode_supplier$no'  id='kode_supplier$no' value='$r_data[Supplier]' >
					<input type='hidden' name='cabang$no'  id='cabang$no' value='$r_data[cabang]' >
					<input type='hidden' name='tglacu$no'  id='tglacu$no' value='$r_data[tgldo]' >
					<input type='hidden' name='hpc$no'  id='hpc$no' value='$r_data[hpc1]' >
					<input type='hidden' name='hpp$no'  id='hpp$no' value='$r_data[hpp1]' >
					<input type='hidden' name='hrgp$no'  id='hrgp$no' value='$r_data[hrgp]' >
					<input type='hidden' name='discp$no'  id='discp$no' value='$r_data[discp]' >
					<input type='hidden' name='valp$no'  id='valp$no' value='$r_data[valp]' >
					<input type='hidden' name='potp$no'  id='potp$no' value='$r_data[potp]' >
					<input type='hidden' name='niljup$no'  id='niljup$no' value='$r_data[niljup]' >
					<input type='hidden' name='grBPP$no'  id='grBPP$no' value='$r_data[grBPP]' >
					</td>
					<td><input type='hidden' name='kodep$no'  id='kodep$no' value='$r_data[kodep]' >$r_data[kodep]</td>
					<td><input type='hidden' name='namap$no'  id='namap$no' value='$r_data[namap]' >$r_data[namap]</td>
					<td align='center'><input type='hidden' name='qtybpb$no'  id='qtybpb$no' value='$r_data[qtybpb]' >$r_data[qtybpb]</td>
					<td align='center'><input type='hidden' name='Bonus$no'  id='Bonus$no' value='$r_data[Bonus]' >$r_data[Bonus]</td>
					<td align='center'><input type='hidden' name='qtyreturawal$no'  id='qtyreturawal$no' value='$crb' >$crb</td>
					<td align='center'><input type='hidden' name='Satuan$no'  id='Satuan$no' value='$r_data[Satuan]' >$r_data[Satuan]</td>
					<td align='center'><input type='hidden' name='batch$no'  id='batch$no' value='$r_data[batch]' >$r_data[batch]</td>
					<td align='center'><input type='hidden' name='expd$no'  id='expd$no' value='$r_data[expd]' >$r_data[expd]</td>
					<td align='center'><input type='text' class='jmlvalid' id='jmlvalid$no' size='5' ></td>
					<td align='center'><input type='text'  class='jmlvalid' id='jmlvalidbns$no' size='5'></td>
					<td align='center'><input type='text'  id='ket_pusat$no'  size='10'></td>
					<td align='center'><input type='hidden' class='jmlvalid' id='disc$no' size='10' value=$r_data[discc] >".format_rupiah_koma($r_data[discc])."</td>
					<td align='center'><input type='hidden' class='jmlvalid' id='harga$no' size='10' value=$r_data[hrgc] >".format_rupiah_koma($r_data[hrgc])."</td>
					<td align='center'><input type='hidden' class='jmlvalid' id='grosb$no' size='10' value=$r_data[grBPB] >".format_rupiah_koma($r_data[grBPB])."</td>
					<td align='center'><input type='hidden' class='jmlvalid' id='pot$no' size='10' value=$r_data[pot] >".format_rupiah_koma($r_data[pot])."</td>
					<td align='center'><input type='hidden' class='jmlvalid' id='val$no' size='10' value=$r_data[val] >".format_rupiah_koma($r_data[val])."</td>
					<td align='center'><input type='hidden' class='jmlvalid' id='ppn$no' size='10' value=$r_data[ppn] >".format_rupiah_koma($r_data[ppn])."</td>
					<td align='center'><input type='hidden' class='jmlvalid' id='nilju$no' size='10' value=$r_data[nilju] >".format_rupiah_koma($r_data[nilju])."</td>
					</tr>";
			$no++;
			$g_total = $g_total+$total;
		} ?>
	</table>
	<table width='100%'>
		<tr>
			<td><div id='tot_cek'></div></td>
		</tr>
	</table>
	
	<table width='100%'>
		<tr>
		  <td align='center'><button name='simpan_konfretur' id='simpan_konfretur'>Simpan Usulan Retur</button>

			</td>
		</tr>
		</table>
