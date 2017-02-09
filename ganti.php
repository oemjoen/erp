<?php
require_once('auth.php');
require_once('connection.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Halaman Ganti Password</title>

<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	font-size: 18px;
	font-weight: bold;
}
body {
	background-color: #00CCFF;
}
.style3 {color: #FF0000}
-->
</style>
</head>

<body>
<form id="gantipass" name="gantipass" method="post" action="proses_ganti.php">
  <label></label>
  <p>
    <label></label>
  </p>
  <table width="300" border="1" align="center">
    <tr>
      <th scope="row"><label>
        <div align="left">Username anda </div>
      </label></th>
      <td><input type="text" name="user"  id="user" value="<?=$_SESSION["SESS_FIRST_NAME"];?>" readonly=""/></td>
    </tr>
    <tr>
      <th scope="row"><label>
        <div align="left">Password baru </div>
      </label></th>
      <td><input name="newpass" type="password" id="newpass" maxlength="10" required/> <span class="style3">*</span></td>
    </tr>
    <tr>
      <th colspan="2" scope="row"><input name="kirim" type="submit" id="kirim" value="Simpan Ganti Password" /></th>
    </tr>
  </table>
</form>
</body>
</html>
<center>[<a href="home.php">Kembali</a>]</center>
