<h2>Upload File Stok Pusat</h2>
Ukuran File Maximal: 10mb.
<form name="form" enctype="multipart/form-data" action="modul/mimport/prosesstokpusat.php" method="POST">
Pilih File Excel*: <input name="fileexcel" type="file"> <input name="upload" type="submit" value="Import">
</form>
* file yang bisa di import adalah .xls (Excel 2003-2007). - <a href="modul/mimport/files/formatstokpusat.xls">Download File Format Stok Pusat</a> 
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
</script>

<?php
include '../../inc/inc.koneksi.php';

echo "<table id='theList' width='100%'>
		<tr>
			<th>No.</th>
			<th>Prinsipal</th>
			<th>Supplier</th>
			<th>Nama Barang</th>
			<th>Kode Barang</th>
			<th>Jumlah</th>
			<th>ED</th>
		</tr>";
	
		$sql = "SELECT a.*,b.`namaproduk`,b.`kodeprinsipal`,b.`kodesupplier` ,
					c.`namaprinsipal`,d.`namasupplier`
				FROM `mst_stok_pusat` a
				LEFT JOIN `mstproduk` b ON a.`kodeproduk`=b.`kodeproduk`
				LEFT JOIN mstprinsipal c ON b.`kodeprinsipal`=c.`kodeprinsipal`
				LEFT JOIN `mstsupplier2` d ON b.`kodesupplier`=d.`kodesupplier`
				ORDER BY b.`kodeprinsipal`,b.`kodesupplier`,b.`kodeproduk`";

		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1;
		while($r_data=mysql_fetch_array($query)){
			echo "<tr>
					<td align='center'>$no</td>
					<td >$r_data[namaprinsipal]</td>
					<td >$r_data[namasupplier]</td>
					<td >$r_data[namaproduk]</td>
					<td align='center'>$r_data[kodeproduk]</td>
					<td align='center'>$r_data[stok]</td>
					<td align='center'>$r_data[ed]</td>
					</tr>";
			$no++;
		}
	echo "</table>";


?>