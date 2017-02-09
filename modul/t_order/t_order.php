<script type="text/javascript" src="modul/t_order/ajax.js"></script>
<script type="text/javascript">	
	
	//$("#data").load('modul/t_order/cari_nomor.php?kode='+$("#txt_kode_beli").val());
	
	
	
//fungsi jumlah	
	function hitung() {
      var qty = document.getElementById('txt_jumlah').value;
      var harga = document.getElementById('txt_hargajual').value;
      var bns = document.getElementById('txt_bonus').value;
      var disc1 = document.getElementById('txt_diskon').value;
	  if(qty=="")
	  {
		  qty = 0;
	  }
	  if(harga=="")
	  {
		  harga = 0;
	  }
	  if(bns=="")
	  {
		  bns = 0;
	  }
	  if(disc1=="")
	  {
		  disc1 = 0;
	  }
	  var disc = parseFloat(disc1).toFixed(2);
      var gross = (parseInt(qty) + parseInt(bns)) * parseInt(harga);
      var pot = (parseInt(bns) * parseInt(harga))+(parseInt(qty) * parseInt(harga) * (disc/100));
      var val = gross-pot;
      var ppn = val * 0.1;
      var tot = val + ppn;
      if (!isNaN(gross)) {
         document.getElementById('txt_gross').value = gross;
         document.getElementById('txt_potongan').value = pot;
         document.getElementById('txt_value').value = val;
		 document.getElementById('txt_ppn').value = ppn;
		 document.getElementById('txt_total').value = tot;
      }
}	

	function editRow(ID){
	   var kode = ID; 
	   	$.ajax({
			type	: "POST",
			url	: "modul/t_order/cari.php",
			data	: "kode="+kode,
			dataType: "json",
			success	: function(data){
				$("#txt_id").val(kode);
				$("#txt_id_ord").val(data.NoOrder);
				$("#txt_kode_barang").val(data.KodeProduk);
				$("#txt_kode_barang").focus();
				$("#txt_jumlah").val(data.Qty);
				$("#txt_bonus").val(data.Bonus);
				$("#txt_diskon").val(data.Diskon);
				$("#txt_hargajual").val(data.Harga);
				$("#txt_gross").val(data.Gross);
				$("#txt_potongan").val(data.Potongan);
				$("#txt_value").val(data.Value);
				$("#txt_ppn").val(data.Ppn);
				$("#txt_total").val(data.Total);
			}
		});

	}

	
	function hapus_data(ID) {
		var kode = $("#txt_kode_beli").val(); 
		var id	= ID;
	   var pilih = confirm('Data yang akan dihapus kode = '+id+ '?');
		if (pilih==true) {
			$.ajax({
				type	: "POST",
				url	: "modul/t_order/hapus.php",
				data	: "kode="+kode+"&id="+id,
				success	: function(data){
					$("#info").html(data);
					kosong();
				}
			});
		}
	}

	function tampil_data_retail() {
		var	koder	= $("#txt_kode").val();
		var cabang1 = $("#textcabang").val();
		$.ajax({
			type	: "POST",
			url		: "modul/t_order/tampil_limit.php",
			data	: "kode="+kode+"&cabang1="+cabang1,
			dataType: "json",
			success	: function(data){
				$("#info2").val(data);
				//alert(data.kode_pobeli);
			}
		});		
	}
    
</script>
<style type="text/css">
.readonly{
	background-color:#00FFFF;
}
.detail_readonly{
	background-color:#00FFFF;
}
h3 {
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	text-align:center;
	color:#009;
}
.bg_input {
	background-color:#CCCCCC;
}
</style>
<?php
include 'inc/inc.koneksi.php'; 
?>

	<h3>Order Manual</h3>
	<p align="right">
	  <input type="hidden" name="textcabang" id='textcabang' value = "<?php echo $cabangrr; ?>" readonly/>
	  <input type="hidden" name="kodeedit" id='kodeedit' value = "<?php echo $_GET['no']; ?>" readonly/>
	  <input type="hidden" name="textusername" id='textusername' value = "<?php echo $userrr; ?>" readonly/>
	  <input type="hidden" name="textKodeAP" id='textKodeAP' value = "<?php echo $userrr; ?>" readonly/>
	  <input type="hidden" name="textNamaAP" id='textNamaAP' value = "<?php echo $namarr; ?>" readonly/>
	  <input type="hidden" name="textime" id='textime' value = "<?php echo date("Y-m-d H:i:s"); ?>" readonly/>
	  
	  
    <?php echo $namarr;?> - cabang <?php echo $cabangrr; ?></p>
			<?php 
			echo"<p align=right>Login : $hari_ini, ";
			echo tgl_indo(date("Y m d")); 
			echo " | "; 
			echo date("H:i:s");
			echo " WIB</p>";?>
	<table id='theList' width='100%'>
      <tr>
        <td>Kode SP</td>
        <td>:</td>
        <td><input type='text' name='txt_kode_beli' id='txt_kode_beli'  size='50' lenght='50' class='input' readonly/></td>
      </tr>
      <tr>
        <td>Tanggal SP</td>
        <td>:</td>
        <td><input type='text' name='txt_tgl_beli' id='txt_tgl_beli'  size='12' lenght='12' class='input' value=<?php print(date('Y-m-d')); ?> readonly/></td>
      </tr>
<!--	  <tr>
		<td>Tipe</td>
		<td>:</td>
		<td><label><input type="checkbox" id="cbox1" value="Langsung">Langsung</label></td>
	  </tr>
      <tr>
        <td>Pelanggan</td>
        <td>:</td>
        <td><select name='cbo_retail' id='cbo_retail' class='input' onchange= 'tampil_data_retail()'>
            <option value='' selected>-Pilih-</option>
            <?php		
		$sql	= "SELECT Kode,`Nama Faktur` AS namaretail, `Kode Reps` AS reps, Alamat FROM mretail WHERE cabang='$cabangrr' Order By `Nama Faktur`, Kode";
		$query	= mysql_query($sql);
		while($r=mysql_fetch_array($query)){
			echo "<option value='$r[Kode]'>$r[namaretail] - $r[Kode] - $r[Alamat]</option>";
		}?>
          </select>
        </td>
      </tr>
	  <tr>
		<td>Data</td>
		<td>:</td>
		<td><textarea rows="7" cols="80"  name="info2" id="info2" readonly></textarea ></td>
	  </tr>-->
    </table>
	<div class='bg_input'>
	<table width='100%' border="1">
	<tr>
		<th width="10%">Kode Barang</th>
		<th width="20%">Nama Barang</th>
		<th width="5%">Jumlah</th>
		<th width="5%">Bonus</th>
		<th width="3%">Diskon</th>
		<th width="5%">Satuan</th>
	    <th width="5%">Stock</th>
	    <th width="5%">Harga</th>	    
	    <th width="10%">Gross</th>	    
	    <th width="10%">Potongan</th>	    
	    <th width="10%">Value</th>	    
	    <th width="10%">PPN</th>	    
	    <th width="10%">Total</th>	    
	</tr>
	<tr>
		
		<td><input type='text' name='txt_kode_barang' id='txt_kode_barang'  size='15' class='input_detail'></td>
	  		<input type="hidden" name="txt_prinsipal_prod" id='txt_prinsipal_prod' readonly />
	  		<input type="hidden" name="txt_kategorikhusus" id='txt_kategorikhusus' readonly />
	  		<input type="hidden" name="txt_produk_sp" id='txt_produk_sp' readonly />
	  		<!--btb_outstanding--><input type="hidden" name="txt_btb_outstanding" id='txt_btb_outstanding' readonly  />
	  		<!--pr_outstanding--><input type="hidden" name="txt_pr_outstanding" id='txt_pr_outstanding' readonly  />
	  		<!--pr_outstanding_nopo--><input type="hidden" name="txt_pr_outstanding_nopo" id='txt_pr_outstanding_nopo' readonly  />
	  		<!--pr_outstanding_pr--><input type="hidden" name="txt_pr_outstanding_pr" id='txt_pr_outstanding_pr' readonly  />
	  		<!--PR Outstanding--><input hidden="text" name="txt_pr_outstanding_nopr" id='txt_pr_outstanding_nopr' readonly  />
			<input type='hidden' name="textfield_ketcabang" id="textfield_ketcabang" class='input_detail'>
			<input type='hidden' name='txt_po_outstanding' id='txt_po_outstanding'  size='5' align='center' class='detail_readonly' readonly />
			<input type='hidden' name='txt_ratio' id='txt_ratio'  size='5' align='center' class='detail_readonly' readonly />
			<input type='hidden' name='txt_avg' id='txt_avg'  size='5' align='center' class='detail_readonly' readonly="readonly" />
			<input type='hidden' name="textfield_ketprisipal" id="textfield_ketprisipal" class='input_detail'>
		  <input type='text' name='txt_id' readonly="readonly" class="txt_id" id="txt_id"/></div></td>
		  <input type='text' name='txt_id_ord' readonly="txt_id_ord" class="txt_id_ord" id="txt_id_ord"/></div></td>

		<td><div align="center">
		  <textarea name="txt_nama_barang" readonly="readonly" class="detail_readonly" id="txt_nama_barang"></textarea></div>
		<td><div align="center">
		  <input type='text' name='txt_jumlah' id='txt_jumlah'  size='7'  class='input_detail' onkeyup='hitung()'/>
		</div></td>
	    <td><div align="center">
	      <input type='text' name='txt_bonus' id='txt_bonus'  size='5' align='center'  class='input_detail' onkeyup='hitung()'/>
	    </div></td>		
		<td><div align="center">
		  <input type='number'  step='0.1' min='0' max='100' name='txt_diskon' id='txt_diskon'  size='4'  class='input_detail'   onkeyup='hitung()' />
		</div></td>
		<td><div align="center">
		  <input type='text' name='txt_satuan' id='txt_satuan'  size='5' align='center'  class='detail_readonly' readonly="readonly" />
		</div></td>
	    <td><div align="center">
	      <input type='text' name='txt_stok' id='txt_stok'  size='5' align='center'  class='detail_readonly' readonly />
	    </div></td>
	    <td><div align="center">
	      <input type='text' name='txt_hargajual' id='txt_hargajual'  size='10' align='center' class='input_detail'  onkeyup='hitung()' />
        </div></td>
	    <td><div align="center">
	      <input type='text' name='txt_gross' id='txt_gross'  size='5' align='center'  class='detail_readonly' readonly />
	    </div></td>
	    <td><div align="center">
	      <input type='text' name='txt_potongan' id='txt_potongan'  size='5' align='center'  class='detail_readonly' readonly />
	    </div></td>		
	    <td><div align="center">
	      <input type='text' name='txt_value' id='txt_value'  size='5' align='center'  class='detail_readonly' readonly />
	    </div></td>		
	    <td><div align="center">
	      <input type='text' name='txt_ppn' id='txt_ppn'  size='5' align='center'  class='detail_readonly' readonly />
	    </div></td>		
	    <td><div align="center">
	      <input type='text' name='txt_total' id='txt_total'  size='5' align='center'  class='detail_readonly' readonly />
	    </div></td>		
	</tr>	
	<tr>
		<td colspan='12' align='center'>
		<!--<button name='tambah_detail' id='tambah_detail'>Tambah Barang</button>-->
		<button name='simpan' id='simpan'>Simpan Barang</button>	
		<button name='cetak2' id='cetak2'>Cetak</button>
		</td>
	</tr>
	</table>
	</div>
	<div id='info' align='center'></div>
	<div id='tombol'>
	<table width='100%'>
	<tr>
		<td align='center'>
		<button name='proses' id='proses'>Proses SP</button>
		<button name='keluar' id='keluar'>Keluar</button>
		</td>
	</tr>
	</table></div>


