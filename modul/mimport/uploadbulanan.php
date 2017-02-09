<h2>Upload File Rata - Rata Stok Per 3 Bulan</h2>
Ukuran File Maximal: 10mb.
<form name="form" enctype="multipart/form-data" action="modul/mimport/prosesbulanan.php" method="POST">
Pilih File Excel*: <input name="fileexcel" type="file"> <input name="upload" type="submit" value="Import">
</form>
* file yang bisa di import adalah .xls (Excel 2003-2007). - <a href="modul/mimport/files/formatstokbulanan.xls">Download File Format Stok Rata - Rata </a> 
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
			<th>Region</th>
			<th>Cabang</th>
			<th>Jumlah Data</th>
		</tr>";
	
		$sql = "SELECT region,cabang,COUNT(cabang) AS `jumlahdata` 
				FROM mstlogmonth GROUP BY region,cabang
				ORDER BY region,cabang";

		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1;
		while($r_data=mysql_fetch_array($query)){
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[region]</td>
					<td align='center'>$r_data[cabang]</td>
					<td align='center'>$r_data[jumlahdata]</td>
					</tr>";
			$no++;
		}
	echo "</table>";


?>