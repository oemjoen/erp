<script type="text/javascript">
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
</script>
<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
include '../../inc/fungsi_tanggal.php';
include '../../inc/fungsi_rupiah.php';
date_default_timezone_set('Asia/Jakarta'); 

	echo "<div id='info'>
	<table id='theList'>
		<tr>
			<th>NO</th>
			<th>CABANG</th>
			<th>KODE PRODUK</th>
		</tr>";
	
	//$sql1 = "SELECT * FROM a_cek";
	$sql1 = "SELECT DISTINCT cabang,`produk`,`nama produk` AS nama FROM `dinventorysummary` WHERE cabang='Padang' ORDER BY `prinsipal`,`nama produk`;";
					 
	 //echo "<br>".$sql1."<br>";	
	//exit(1);
		$no=0 ;
		$query = mysql_query($sql1);
		while($r_data=mysql_fetch_array($query)){
			$no++;
			echo "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[cabang]</td>
					<td align='left'><a href='http://localhost:8080/erp/modul/laporan/kartugudangmod.php?kode1=14-06-2016&kode2=$r_data[produk]&cabang=$r_data[cabang]'  target=_blank'>$r_data[produk] - $r_data[nama]</a></td>
					</tr>
				";
		}
 		echo "</table>";
	echo "</div>";
?>