<h2>Upload File Stok Harian</h2>
Ukuran File Maximal: 10mb.
<form name="form" enctype="multipart/form-data" action="modul/mimport/prosesharian.php" method="POST">
Pilih File Excel*: <input name="fileexcel" type="file"> <input name="upload" type="submit" value="Import">
</form>
* file yang bisa di import adalah .xls (Excel 2003-2007). - <a href="modul/mimport/files/formatstokharian.xls">Download File Format Stok Harian </a> 
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
			<th>Tgl Stok</th>
			<th>Region</th>
			<th>Cabang</th>
			<th>Kode Status</th>
			<th>Jumlah Data</th>
		</tr>";
	
		$sql = "SELECT tglstok,region,cabang,kodestatus,COUNT(kodestatus) AS `jumlahdata` 
				FROM mstlogday GROUP BY region,cabang,tglstok,kodestatus
				ORDER BY cabang,tglstok DESC,region,kodestatus";

		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1;
		while($r_data=mysql_fetch_array($query)){
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[tglstok]</td>
					<td align='center'>$r_data[region]</td>
					<td align='center'>$r_data[cabang]</td>
					<td align='center'>$r_data[kodestatus]</td>
					<td align='center'>$r_data[jumlahdata]</td>
					</tr>";
			$no++;
		}
	echo "</table>";


?>