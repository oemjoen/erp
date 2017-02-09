<?php

require_once('auth.php');
require_once('connection.php');


	$kirim=$_POST['kirim'];
	$user = $_POST['user'];
	$new = $_POST['newpass'];
	

$conn = @mysql_connect($mysql_hostname,$mysql_user,$mysql_password);
if(!$conn) {
	echo '<p>Koneksi ke server database gagal untuk saat ini</p>';
	exit();
}


if(!@mysql_select_db($mysql_database)){
	exit('<p> DB tidak ditemukan</p>');
}

	
	if($kirim)
	{
		$sql = mysql_query("select * from member where username='$user'");
		$hasil = mysql_num_rows($sql);
		if($hasil == 1)
		{
			mysql_query("update member set password='$new' where username='$user' ");
			//echo "update user set password='$new' where username='$user' ";
			//lempar ke halaman admin
			echo "<center>password berhasil diganti<br><a href='home.php'>back</a></center>";
		}else{
			//lempar ke ganti pass
			echo "<center>password Gagal diganti!!<br><a href='ganti.php'>back</a></center>";
		}
	}else
	{
		header('Location: home.php');
	}
	
?>