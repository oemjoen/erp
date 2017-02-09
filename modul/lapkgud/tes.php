<html>
<head><title>Combobox Bertingkat</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=propinsi>
$("#propinsi").change(function(){
var propinsi = $("#propinsi").val();
$.ajax({
url: "ambilkota.php",
data: "propinsi="+propinsi,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=kota>
$("#kota").html(msg);
}
});
});
$("#kota").change(function(){
var kota = $("#kota").val();
$.ajax({
url: "ambilkecamatan.php",
data: "kota="+kota,
cache: false,
success: function(msg){
$("#kec").html(msg);
}
});
});
});
 
</script>
</head>
<body>
<?php
mysql_connect("localhost","root","");
mysql_select_db("combobox_bertingkat");
?>
 
<form id="" method="post" action="simpan.php">
 
Pilih Provinsi :<br>
<select name="propinsi" id="propinsi">
<option>--Pilih Provinsi--</option>
<?php
//mengambil nama-nama propinsi yang ada di database
$propinsi = mysql_query("SELECT * FROM prov ORDER BY nama_prov");
while($p=mysql_fetch_array($propinsi)){
echo "<option value=\"$p[id_prov]\">$p[nama_prov]</option>\n";
}
?>
</select>
 
<br>Pilih Kabupaten/Kota :<br>
<select name="kota" id="kota">
<option>--Pilih Kabupaten/Kota--</option>
<?php
//mengambil nama-nama propinsi yang ada di database
$kota = mysql_query("SELECT * FROM kabkot ORDER BY nama_kabkot");
while($p=mysql_fetch_array($propinsi)){
echo "<option value=\"$p[id_kabkot]\">$p[nama_kabkot]</option>\n";
}
?>
</select>
 
<br>Pilih Kecamatan :<br>
<select name="kec" id="kec">
<option>--Pilih Kecamatan--</option>
</select><p>
<input id='' type ='submit' value='Simpan' name=''/>
<input type="reset" value="Batal">
</form>
<p>
<table width='500' border='1' cellspacing='1' align='left'>
<?php
mysql_connect("localhost","root","");
mysql_select_db("combobox_bertingkat");
 
$query=mysql_query("
SELECT * FROM data
JOIN prov ON data.id_prov = prov.id_prov
JOIN kabkot ON data.id_kabkot = kabkot.id_kabkot
JOIN kec ON data.id_kec = kec.id_kec") or die (mysql_error());
?>
<tr align='center'><td>No.</td><td>Provinsi</td><td>Kabupaten/Kota</td><td>Kecamatan</td></tr>
<?php
$no="1";
while ($row=mysql_fetch_array($query))
{
$nmprov = $row['nama_prov'];
$nmkab    = $row['nama_kabkot'];
$nmkec  = $row['nama_kec'];
echo "<tr><td width='10'>";
echo "$no";
echo "</td><td>";
echo "$nmprov";
echo "</td><td>";
echo "$nmkab";
echo "</td><td>";
echo "$nmkec";
echo "</td></tr>";
$no++;
}
?>
</table>
</body>
</html>
