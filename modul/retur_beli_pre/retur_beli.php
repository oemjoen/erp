<script type="text/javascript" src="modul/retur_beli_pre/ajax.js"></script>
<script type="text/javascript">	
	

	function hapus_data(ID) {
		var kode = $("#txt_kode_beli").val(); 
		var id	= ID;
		var	koderetur	= $("#txt_kode_beli").val();
	   var pilih = confirm('Data yang akan dihapus kode = '+id+ '?');
		if (pilih==true) {
			$.ajax({
				type	: "POST",
				url		: "modul/retur_beli_pre/hapus.php",
				data	: "kode="+kode+"&id="+id+"&koderetur="+koderetur,
				success	: function(data){
					$("#info").html(data);
					kosong();
				}
			});
		}
	}
	
	
	function editRow(ID){
	   var kode = ID; 
	   	$.ajax({
			type	: "POST",
			url		: "modul/retur_beli_pre/cari.php",
			data	: "kode="+kode,
			dataType: "json",
			success	: function(data){
				$("#txt_kode_barang").val(kode);
				$("#txt_kode_barang").focus();
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

	<h3>TRANSAKSI RETUR</h3>
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
        <td>Kode Retur</td>
        <td>:</td>
        <td><input type='text' name='txt_kode_beli' id='txt_kode_beli'  size='50' lenght='50' class='input' readonly/></td>
      </tr>
      <tr>
        <td>Tanggal Retur</td>
        <td>:</td>
        <td><input type='text' name='txt_tgl_beli' id='txt_tgl_beli'  size='12' lenght='12' class='input' readonly/></td>
      </tr>

        <input type='hidden' name='txt_kode_po' id='txt_kode_po'  size='50' lenght='50' class='input' />
        <input type='hidden' name='txt_kode_pr' id='txt_kode_pr'  size='50' lenght='50' class='input' />
        <input type='hidden' name='txt_supplier' id='txt_supplier'  size='50' lenght='50' class='input' />

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
	  
	        <tr>
        <td>Total Koli </td>
        <td>:</td>
        <td><input type='text' name='txt_jumlah_koli' id='txt_jumlah_koli'  size='50' lenght='50' class='angka'/></td>
      </tr>
	  
	</table>
	<div class='bg_input'>
	  <table width='100%' border="1">
        <tr>
          <th width="7%">Kode Barang</th>
          <th width="18%">Nama Barang</th>
          <th width="5%">Satuan</th>
          <th width="5%">QTY</th>
          <th width="5%">Batch</th>
          <th width="10%">ED</th>
          <th width="10%">DO PST</th>
          <th width="10%">Tgl DO PST</th>
          <th width="15%">Alasan</th>
          <th width="15%">Dari</th>
        </tr>
        <tr>
          <td><div align="center">
            <input type='text' name='txt_kode_barang' id='txt_kode_barang'  size='13'  class='input_detail' />
            <input type='hidden' name='txt_kode_po_edit' id='txt_kode_po_edit' />
            <input type='hidden' name='txt_kode_pr_edit' id='txt_kode_pr_edit' />
          </div></td>
          <td><textarea name="txt_nama_barang" cols="30" readonly class="detail_readonly" id="txt_nama_barang"></textarea></td>
          <td><div align="center">
              <input type='text' name='txt_satuan' id='txt_satuan'  size='5' align='center'  class='detail_readonly' readonly />
          </div></td>
          <td><div align="center">
              <input type='text' name='txt_jumlah1' id='txt_jumlah1'  size='9'  class='angka' />
          </div></td>
          <td><div align="center">
              <input type='text' name='txt_batch1' id='txt_batch1'  size='9'  class='input_detail' />
          </div></td>
          <td><div align="center">
              <input type='text' name='txt_ed1' id='txt_ed1'  size='9'  class='tanggal' readonly/>
          </div></td>
          <td><div align="center">
              <textarea name="txt_ref_do_pst" cols="10" class="input_detail" id="txt_ref_do_pst"></textarea>
          </div></td>
          <td><div align="center">
              <input type='text' name='txt_ref_tgl_do_pst' id='txt_ref_tgl_do_pst'  size='9'  class='tanggal' readonly/>
          </div></td>
          <td><div align="center">
            <select name='txt_alasan_retur' id='txt_alasan_retur' class='input' >
            <option value='' selected>-Pilih-</option>
            <?php		
			$sql	= "SELECT * FROM mst_alasan_retur order by kode_alasan";
			$query	= mysql_query($sql);
			while($r=mysql_fetch_array($query)){
				echo "<option value='$r[kode_alasan]'>$r[alasan]</option>";
			}?>
          </select>
          </div></td>		  
          <td><div align="center">
		    <select name='txt_asal_retur' id='txt_asal_retur' class='input' >
            <option value='' selected>-Pilih-</option>
            <?php		
			$sql	= "SELECT * FROM mst_asal_retur order by kode_asal";
			$query	= mysql_query($sql);
			while($r=mysql_fetch_array($query)){
				echo "<option value='$r[kode_asal]'>$r[asal_retur_ket]</option>";
			}?>
          </select>
          </div></td>		  

        </tr>
        <tr>
          <td colspan='13' align='center'><!--<button name='tambah_detail' id='tambah_detail'>Tambah Barang</button>-->
              <button name='simpan' id='simpan'>Simpan Barang</button>
            <button name='cetak2' id='cetak2' >Cetak</button></td>
        </tr>
      </table>
	</div>
	<div id='info' align='center'></div>
	<div id='tombol'>
	<table width='100%'>
	<tr>
		<td align='center'>
		<button name='tambah_beli' id='tambah_beli'>Tambah Retur</button>
		<button name='keluar' id='keluar'>Keluar</button>
		</td>
	</tr>
	</table></div>


