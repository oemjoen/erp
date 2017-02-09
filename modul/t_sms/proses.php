<?php
include 'inc/inc.koneksi.php'; 


//UPDATE morder SET STATUS='Proses' WHERE Cabang='' AND `No Order`='' AND STATUS='Open'
// $sql = "UPDATE morder set Status='Proses' WHERE `name` = '".$_REQUEST['name']."'";
	$sql = "UPDATE morder SET STATUS='Proses' WHERE Cabang='".$_REQUEST['cab']."' AND `No Order`='".$_REQUEST['ord']."' AND STATUS='Open'";
   if(mysql_query($sql)){
     return "success!";
   }
   else {
    return "failed!";
  }
  echo $sql;
  ?>
  header("location:media.php?module=smsg"); 
