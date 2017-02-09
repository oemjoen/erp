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

//include "connection.php"; //Connect to Database

include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_indotgl.php';

$tablename = "kas_kecil_report_all";

//Upload File
if (isset($_POST['submit'])) {
	if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
		echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1>";
		//echo "<h2>Displaying contents:</h2>";
		//readfile($_FILES['filename']['tmp_name']);
	}
	$deleterecords = "TRUNCATE TABLE $tablename"; //empty the table of its current records
	mysql_query($deleterecords);
	//Import uploaded file to Database
	$handle = fopen($_FILES['filename']['tmp_name'], "r");
	//fgetcsv($handle, 1000, ","); 
	while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
			$datakolom0 = str_replace('\'', '"',$data[0]);		
			$datakolom1 = str_replace('\'', '"',$data[1]);
			$datakolom2 = str_replace('\'', '"',tgl_indo_balik($data[2]));
			$datakolom3 = str_replace('\'', '"',$data[3]);
			$datakolom4 = str_replace('\'', '"',$data[4]);
			$datakolom5 = str_replace('\'', '"',$data[5]);
			$datakolom6 = str_replace('\'', '"',$data[6]);
			$datakolom7 = str_replace('\'', '"',$data[7]);
			$datakolom8 = str_replace('\'', '"',$data[8]);
			$datakolom9 = str_replace('\'', '"',$data[9]);
			$datakolom10 = str_replace('\'', '"',$data[10]);
			$datakolom11 = str_replace('\'', '"',$data[11]);
			$datakolom12 = str_replace('\'', '"',$data[12]);
			$datakolom13 = str_replace('\'', '"',$data[13]);
			$datakolom14 = str_replace('\'', '"',$data[14]);
			$datakolom15 = str_replace('\'', '"',$data[15]);
			$datakolom16 = str_replace('\'', '"',$data[16]);
			$datakolom17 = str_replace('\'', '"',$data[17]);
			$datakolom18 = str_replace('\'', '"',$data[18]);
			$datakolom19 = str_replace('\'', '"',$data[19]);
			$datakolom20 = str_replace('\'', '"',$data[20]);
			
		$import="INSERT INTO $tablename
            (Cabang,Jurnal_ID,Tanggal,Transaksi,Kode_Transaksi,Keterangan,Saldo_Awal,Debit,Kredit,Saldo_Akhir,Bulan,Added_User,Added_Time,Jurnal_Acu,Jumlah_Acu,Counter,CR,DR,Jumlah,Ket2,Ket3,Ket4)
				VALUES ('$datakolom0','$datakolom1','$datakolom2','$datakolom3','$datakolom4','$datakolom5','$datakolom6','$datakolom7','$datakolom8','$datakolom9','$datakolom10','$datakolom11','$datakolom12','$datakolom13','$datakolom14','$datakolom15','$datakolom16','$datakolom17','$datakolom18','$datakolom19','$datakolom20')";
		echo $import."</br>";
		mysql_query($import) or die(mysql_error());
		
	}

	fclose($handle);

	$sqldeleteheader = "Delete from $tablename where Cabang = 'Cabang'";
	mysql_query($sqldeleteheader) or die(mysql_error());

	echo "Import done";
	
}else {


	echo "<h2>Upload Kas Kecil</h2><br />\n";
	echo "<form enctype='multipart/form-data' action='media.php?module=upkb' method='post'>";
	echo "Nama File yang di Import:<br />\n";
	echo "<input size='50' type='file' name='filename'><br />\n";
	echo "<input type='submit' name='submit' value='Upload'></form>";

}

echo "<table id='theList' width='100%'>
		<tr>
			<th>No.</th>
			<th>Cabang</th>
			<th>Jurnal_ID</th>
			<th>Tanggal</th>
			<th>Transaksi</th>
			<th>Kode_Transaksi</th>
			<th>Keterangan</th>
			<th>Saldo_Awal</th>
			<th>Debet</th>
			<th>Credit</th>
			<th>Saldo_Akhir</th>
			<th>Bulan</th>
			<th>Jumlah</th>
			<th>Jurnal_Acu</th>
			<th>Jumlah_Acu</th>
		</tr>";
	
		$sql = "SELECT
					  Cabang,Jurnal_ID,Tanggal,Transaksi,Kode_Transaksi,Keterangan,Saldo_Awal,Debit,Kredit,Saldo_Akhir,
					  Bulan,Added_User,Added_Time,Jurnal_Acu,Jumlah_Acu,Counter,CR,DR,Jumlah,Ket2,Ket3,Ket4
					FROM $tablename";				
				
		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1;
		while($r_data=mysql_fetch_array($query)){
			echo "<tr>
					<td align='center'>$no</td>
					<td align='center'>$r_data[Cabang]</td>
					<td align='center'>$r_data[Jurnal_ID]</td>
					<td align='center'>$r_data[Transaksi]</td>
					<td align='center'>$r_data[Tanggal]</td>
					<td align='center'>$r_data[Kode_Transaksi]</td>
					<td align='center'>$r_data[Keterangan]</td>
					<td align='center'>$r_data[Saldo_Awal]</td>
					<td align='center'>$r_data[Debit]</td>
					<td align='center'>$r_data[Kredit]</td>
					<td align='center'>$r_data[Saldo_Akhir]</td>
					<td align='center'>$r_data[Bulan]</td>
					<td align='center'>$r_data[Jumlah]</td>
					<td align='center'>$r_data[Jurnal_Acu]</td>
					<td align='center'>$r_data[Jumlah_Acu]</td>
					</tr>";
			$no++;
		}
	echo "</table>";	

?>


