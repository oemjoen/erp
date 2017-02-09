<?php
$server = "localhost";
$username = "sapta";  
$password = "Sapta254*x"; 
$database = "erp";

/* $server = "athens.phpwebhosting.com";
$username = "sapta";
$password = "saptaas1a";
$database = "saptalog"; */

$konek = mysql_connect($server, $username, $password) or die ("Gagal konek ke server MySQL" .mysql_error());
$bukadb = mysql_select_db($database) or die ("Gagal membuka database $database" .mysql_error());

?>