<h2>Upload Purchase Detail</h2>


Upload purchase_detail_report<br />
<form enctype='multipart/form-data' action='media.php?module=uppurch' method='post'>
Nama File yang di Import:<br />
<input size='50' type='file' name='filename'><br />
<input type='submit' name='submit' value='Upload'></form><br />


<form name="form" enctype="multipart/form-data" action="modul/mimport/proses_purc_det.php" method="POST">
Pilih File Excel*: <input name="fileexcel" type="file"> <input name="submit" type="submit" value="Import">
</form>
* file yang bisa di import adalah .csv 
<script type="text/javascript">
$(document).ready(function() {
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
});

	function cetakRow(ID){
	   var kode = ID; 
		window.location.href='modul/laporan/cetak_pr.php?kode='+kode;

	}

</script>

<?php
include '../../inc/inc.koneksi.php';

echo "<table id='theList' width='100%'>
		<tr>
			<th>No.</th>
			<th>Cabang</th>
			<th>Prinsipal</th>
			<th>Supplier</th>
			<th>Produk</th>
			<th>No_PR</th>
			<th>Tgl_PR</th>
			<th>No_PO</th>
			<th>Tgl_PO</th>
			<th>No_BPB</th>
			<th>Tgl_BPB</th>
			<th>No_Invoice</th>
			<th>Tgl_Invoice</th>
		</tr>";
	
		$sql = "SELECT
				  Cabang,Prinsipal,Supplier,Produk,No_PR,Tgl_PR,Time_PR,Status_PR,Qty_PR,Satuan,Keterangan,Penjelasan,Qty_REC,AVG,Indeks,Stok,GIT,HPC,Value_PR,User_PR,
				  No_PO,Tgl_PO,Time_PO,Qty_PO,Status_PO,HPP,Value_PO,User_PO,
				  No_BPB,Tgl_BPB,Time_BPB,No_SJ,No_BEX,Batch_No,Exp_Date,Qty_BPB,HPC1,Value_BPB,User_BPB,Status_BPB,
				  No_Invoice,Tgl_Invoice,Time_Invoice,HPP1,Value_Invoice,Status_Invoice,User_Invoice,
				  Counter_PR,Counter_PO,Counter_BPB,Counter_Invoice
				FROM purchase_detail_report 
				ORDER BY cabang,Tgl_PR,no_pr,produk";

		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1;
		while($r_data=mysql_fetch_array($query)){
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[Cabang]</td>
					<td align='center'>$r_data[Prinsipal]</td>
					<td align='center'>$r_data[Supplier]</td>
					<td align='center'>$r_data[Produk]</td>
					<td align='center'>$r_data[No_PR]";if(!empty($r_data[No_PR])){; echo"<a href='javascript:cetakRow(\"{$r_data[No_PR]}\")' ><img src='icon/printer.png' border='0' id='cetakkk' title='Cetak' width='12' height='12' ></a>";}echo "</td>
					<td align='center'>$r_data[Tgl_PR]</td>
					<td align='center'>$r_data[No_PO]</td>
					<td align='center'>$r_data[Tgl_PO]</td>
					<td align='center'>$r_data[No_BPB]</td>
					<td align='center'>$r_data[Tgl_BPB]</td>
					<td align='center'>$r_data[No_Invoice]</td>
					<td align='center'>$r_data[Tgl_Invoice]</td>
					</tr>";
			$no++;
		}
	echo "</table>";


?>