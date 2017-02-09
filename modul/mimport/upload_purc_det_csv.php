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
$tablename = "purchase_detail_report";

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
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$datakolom0 = str_replace('\'', '"',$data[0]);		
			$datakolom1 = str_replace('\'', '"',$data[1]);
			$datakolom2 = str_replace('\'', '"',$data[2]);
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
			$datakolom21 = str_replace('\'', '"',$data[21]);
			$datakolom22 = str_replace('\'', '"',$data[22]);
			$datakolom23 = str_replace('\'', '"',$data[23]);
			$datakolom24 = str_replace('\'', '"',$data[24]);
			$datakolom25 = str_replace('\'', '"',$data[25]);
			$datakolom26 = str_replace('\'', '"',$data[26]);
			$datakolom27 = str_replace('\'', '"',$data[27]);
			$datakolom28 = str_replace('\'', '"',$data[28]);
			$datakolom29 = str_replace('\'', '"',$data[29]);
			$datakolom30 = str_replace('\'', '"',$data[30]);
			$datakolom31 = str_replace('\'', '"',$data[31]);
			$datakolom32 = str_replace('\'', '"',$data[32]);
			$datakolom33 = str_replace('\'', '"',$data[33]);
			$datakolom34 = str_replace('\'', '"',$data[34]);
			$datakolom35 = str_replace('\'', '"',$data[35]);
			$datakolom36 = str_replace('\'', '"',$data[36]);
			$datakolom37 = str_replace('\'', '"',$data[37]);
			$datakolom38 = str_replace('\'', '"',$data[38]);
			$datakolom39 = str_replace('\'', '"',$data[39]);
			$datakolom40 = str_replace('\'', '"',$data[40]);
			$datakolom41 = str_replace('\'', '"',$data[41]);
			$datakolom42 = str_replace('\'', '"',$data[42]);
			$datakolom43 = str_replace('\'', '"',$data[43]);
			$datakolom44 = str_replace('\'', '"',$data[44]);
			$datakolom45 = str_replace('\'', '"',$data[45]);
			$datakolom46 = str_replace('\'', '"',$data[46]);
			$datakolom47 = str_replace('\'', '"',$data[47]);
			$datakolom48 = str_replace('\'', '"',$data[48]);
			$datakolom49 = str_replace('\'', '"',$data[49]);
			$datakolom50 = str_replace('\'', '"',$data[50]);
			
		$import="INSERT INTO $tablename
            (Cabang,Prinsipal,Supplier,Produk,No_PR,Tgl_PR,Time_PR,Status_PR,Qty_PR,Satuan,Keterangan,Penjelasan,Qty_REC,Avg,Indeks,Stok,GIT,HPC,Value_PR,User_PR,No_PO,Tgl_PO,Time_PO,Qty_PO,Status_PO,HPP,Value_PO,User_PO,No_BPB,Tgl_BPB,Time_BPB,No_SJ,No_BEX,Batch_No,Exp_Date,Qty_BPB,HPC1,Value_BPB,User_BPB,Status_BPB,No_Invoice,Tgl_Invoice,Time_Invoice,HPP1,Value_Invoice,Status_Invoice,User_Invoice,Counter_PR,Counter_PO,Counter_BPB,Counter_Invoice)
				VALUES ('$datakolom0','$datakolom1','$datakolom2','$datakolom3','$datakolom4','$datakolom5','$datakolom6','$datakolom7','$datakolom8','$datakolom9','$datakolom10','$datakolom11','$datakolom12','$datakolom13','$datakolom14','$datakolom15','$datakolom16','$datakolom17','$datakolom18','$datakolom19','$datakolom20','$datakolom21','$datakolom22','$datakolom23','$datakolom24','$datakolom25','$datakolom26','$datakolom27','$datakolom28','$datakolom29','$datakolom30','$datakolom31','$datakolom32','$datakolom33','$datakolom34','$datakolom35','$datakolom36','$datakolom37','$datakolom38','$datakolom39','$datakolom40','$datakolom41','$datakolom42','$datakolom43','$datakolom44','$datakolom45','$datakolom46','$datakolom47','$datakolom48','$datakolom49','$datakolom50')";

		mysql_query($import) or die(mysql_error());
		
	}

	fclose($handle);

	echo "Import done";

	$sqldeleteheader = "Delete from $tablename where Cabang = 'Cabang'";
	mysql_query($sqldeleteheader) or die(mysql_error());

	
}else {


	echo "<h2>Upload Purchase Detail</h2><br />\n";
	echo "<form enctype='multipart/form-data' action='media.php?module=uppurch' method='post'>";
	echo "Nama File yang di Import:<br />\n";
	echo "<input size='50' type='file' name='filename'><br />\n";
	echo "<input type='submit' name='submit' value='Upload'></form>";

}

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
	
		$sqlasli = "SELECT
				  Cabang,Prinsipal,Supplier,Produk,No_PR,Tgl_PR,Time_PR,Status_PR,Qty_PR,Satuan,Keterangan,Penjelasan,Qty_REC,AVG,Indeks,Stok,GIT,HPC,Value_PR,User_PR,
				  No_PO,Tgl_PO,Time_PO,Qty_PO,Status_PO,HPP,Value_PO,User_PO,
				  No_BPB,Tgl_BPB,Time_BPB,No_SJ,No_BEX,Batch_No,Exp_Date,Qty_BPB,HPC1,Value_BPB,User_BPB,Status_BPB,
				  No_Invoice,Tgl_Invoice,Time_Invoice,HPP1,Value_Invoice,Status_Invoice,User_Invoice,
				  Counter_PR,Counter_PO,Counter_BPB,Counter_Invoice
				FROM purchase_detail_report 
				ORDER BY cabang,Tgl_PR,no_pr,produk";

		$sql = "SELECT distinct
				  Cabang,Prinsipal,Supplier,No_PR,
				  No_PO,Tgl_PO,
				  No_BPB,Tgl_BPB,
				  No_Invoice,Tgl_Invoice
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


