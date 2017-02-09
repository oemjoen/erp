<script type="text/javascript" src="modul/pembelian/ajax.js"></script>
<script type="text/javascript">	
	
	//$("#data").load('modul/pembelian/cari_nomor.php?kode='+$("#txt_kode_beli").val());
	


	
/*	function cari_nomor() {
		var no		= 1;
		var cabang1	= $("#textcabang").val();
		var tglbeli = $("#txt_tgl_beli").val();
		
		$.ajax({
			type	: "POST",
			url		: "modul/pembelian/cari_nomor.php",
			data	: "cabang1="+cabang+"&tglbeli="+tglbeli,
			dataType : "json",
			success	: function(data){
				$("#txt_kode_beli").val(data.nomor);
				tampil_data();
			}
		});		
	}*/
	
	function editRow(ID){
	   var kode = ID; 
	   	$.ajax({
			type	: "POST",
			url		: "modul/pembelian/cari.php",
			data	: "kode="+kode,
			dataType: "json",
			success	: function(data){
				$("#txt_kode_barang").val(kode);
				$("#txt_kode_barang").focus();
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
				url		: "modul/pembelian/hapus.php",
				data	: "kode="+kode+"&id="+id,
				success	: function(data){
					$("#info").html(data);
					kosong();
				}
			});
		}
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

	<h3>TRANSAKSI PR RUTIN</h3>
	<p align="right">
	  <input type="hidden" name="textcabang" id='textcabang' value = "<?php echo $cabangrr; ?>"/>
	  <input type="hidden" name="kodeedit" id='kodeedit' value = "<?php echo $_GET['no']; ?>"/>
	  <input type="hidden" name="textusername" id='textusername' value = "<?php echo $userrr; ?>"/>
	  
	  
    <?php echo $namarr;?> - cabang <?php echo $cabangrr; ?></p>
			<?php 
			echo"<p align=right>Login : $hari_ini, ";
			echo tgl_indo(date("Y m d")); 
			echo " | "; 
			echo date("H:i:s");
			echo " WIB</p>";?>
	<table id='theList' width='100%'>
      <tr>
        <td>Kode PR</td>
        <td>:</td>
        <td><input type='text' name='txt_kode_beli' id='txt_kode_beli'  size='50' lenght='50' class='input' readonly/></td>
      </tr>
      <tr>
        <td>Tanggal PR</td>
        <td>:</td>
        <td><input type='text' name='txt_tgl_beli' id='txt_tgl_beli'  size='12' lenght='12' class='input' readonly/></td>
      </tr>
      <tr>
        <td>Supplier</td>
        <td>:</td>
        <td><select name='cbo_supplie' id='cbo_supplier' class='input' >
            <option value='' selected>-Pilih-</option>
            <?php		
		$sql	= "SELECT * FROM mstsupplier2 order by namasupplier";
		$query	= mysql_query($sql);
		while($r=mysql_fetch_array($query)){
			echo "<option value='$r[kodesupplier]'>$r[namasupplier]</option>";
		}?>
          </select>
        </td>
      </tr>
    </table>
	<div class='bg_input'>
	<table width='100%' border="1">
	<tr>
		<th width="10%">Kode Barang</th>
		<th width="30%">Nama Barang</th>
		<th width="5%">Jumlah</th>
		<th width="5%">Satuan</th>
		<th width="15%">Keterangan ke Prinsipal </th>
		<th width="5%">AVG</th>
	    <th width="5%">Stock</th>
	    <th width="5%">Ratio</th>
	    <th width="5%">PO</th>
	    <th width="15%">Penjelasan Cabang </th>
	</tr>
	<tr>
		<td><input type='text' name='txt_kode_barang' id='txt_kode_barang'  size='15' lenght='10' class='input_detail'></td>
		<td><input type='text' name='txt_nama_barang' id='txt_nama_barang'  size='60'  class='detail_readonly' readonly></td>
		<td><div align="center">
		  <input type='text' name='txt_jumlah' id='txt_jumlah'  size='9'  class='input_detail' />
		</div></td>
		<td><div align="center">
		  <input type='text' name='txt_satuan' id='txt_satuan'  size='5' align='center'  class='detail_readonly' readonly="readonly" />
		</div></td>
		<td><div align="center">
		  <textarea name="textfield_ketprisipal" id="textfield_ketprisipal" class='input_detail'></textarea>
		</div></td>
		<td><div align="center">
		  <input type='text' name='txt_avg' id='txt_avg'  size='5' align='center' class='detail_readonly' readonly="readonly" />
		</div></td>
	    <td><div align="center">
	      <input type='text' name='txt_stok' id='txt_stok'  size='5' align='center'  class='detail_readonly' readonly />
	    </div></td>
	    <td><div align="center">
	      <input type='text' name='txt_ratio' id='txt_ratio'  size='5' align='center' class='detail_readonly' readonly />
        </div></td>
	    <td><div align="center">
	      <input type='text' name='txt_po_outstanding' id='txt_po_outstanding'  size='5' align='center' class='detail_readonly' readonly />
        </div></td>
	    <td><textarea name="textfield_ketcabang" id="textfield_ketcabang" class='input_detail'></textarea></td>
	</tr>	
	<tr>
		<td colspan='10' align='center'>
		<button name='tambah_detail' id='tambah_detail'>Tambah Barang</button>
		<button name='simpan' id='simpan'>Simpan Barang</button>	
		<button name='cetak2' id='cetak2' onclick="cetak_pr()">Cetak</button>
		</td>
	</tr>
	</table>
	</div>
	<div id='info' align='center'></div>
	<div id='tombol'>
	<table width='100%'>
	<tr>
		<td align='center'>
		<button name='tambah_beli' id='tambah_beli'>Tambah Pembelian</button>
		<button name='keluar' id='keluar'>Keluar</button>
		</td>
	</tr>
	</table></div>


