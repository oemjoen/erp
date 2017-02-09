<?php
function backhome($data){
	header('location:media.php?module=home');
}

function sukses_masuk($username,$pass){
	// Apabila username dan password ditemukan
	$login=mysql_query("SELECT * FROM admins WHERE username='$username' AND password='$pass' AND blokir='N'");
	$ketemu=mysql_num_rows($login);
	$r=mysql_fetch_array($login);
	if ($ketemu > 0){
	  session_start();
	  include "timeout.php";
	
		$_SESSION['namauser']     = $r['username'];
		$_SESSION['namalengkap']  = $r['nama_lengkap'];
		$_SESSION['passuser']     = $r['password'];
		$_SESSION['leveluser']    = $r['level'];
		$_SESSION['cabang']    	  = $r['cabang2'];
		$_SESSION['cabang2']      = $r['cabang2'];
	
		// session timeout
		$_SESSION[login] = 1;
		timer();
		
		$ipaddress = empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
	
		$sql	= "UPDATE admins SET lastlogin=now(),ipaddress='$ipaddress'  WHERE username='$username' AND password='$pass'";
		mysql_query($sql);
	
		header('location:media.php?module=home');
	}
	return false;
}

function msg(){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, silahkan cek kembali <b>Username</b> dan <b>Password</b> Anda<br><br>Kesalahan $_SESSION[salah]";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";
  return false;
}

function salah_blokir($username){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, Username <b>$username</b> telah <b>TERBLOKIR</b>, silahkan hubungi Administrator.";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";
  return false;
}
function salah_username($username){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, Username <b>$username</b> tidak dikenal.";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";	
  return false;
}

function salah_password(){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, silahkan cek kembali <b>Password</b> Anda<br><br>Kesalahan $_SESSION[salah]";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";
   return false;
}

function blokir($username){
	$ipaddress = empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
	$sql	= "UPDATE admins SET lastlogin=now(),ipaddress='$ipaddress',blokir='Y'  WHERE username='$username'";
	mysql_query($sql);		
	session_start();
	session_destroy();
	header('location:index.php');
	 return false;
}


function terbilang($angka) {
    // pastikan kita hanya berususan dengan tipe data numeric
    $angka = (float)$angka;
     
    // array bilangan
    // sepuluh dan sebelas merupakan special karena awalan 'se'
    $bilangan = array(
            '',
            'Satu',
            'Dua',
            'Tiga',
            'Empat',
            'Lima',
            'Enam',
            'Tujuh',
            'Delapan',
            'Sembilan',
            'Sepuluh',
            'Sebelas'
    );
     
    // pencocokan dimulai dari satuan angka terkecil
    if ($angka < 12) {
        // mapping angka ke index array $bilangan
        return $bilangan[$angka];
    } else if ($angka < 20) {
        // bilangan 'belasan'
        // misal 18 maka 18 - 10 = 8
        return $bilangan[$angka - 10] . ' Belas';
    } else if ($angka < 100) {
        // bilangan 'puluhan'
        // misal 27 maka 27 / 10 = 2.7 (integer => 2) 'dua'
        // untuk mendapatkan sisa bagi gunakan modulus
        // 27 mod 10 = 7 'tujuh'
        $hasil_bagi = (int)($angka / 10);
        $hasil_mod = $angka % 10;
        return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
    } else if ($angka < 200) {
        // bilangan 'seratusan' (itulah indonesia knp tidak satu ratus saja? :))
        // misal 151 maka 151 = 100 = 51 (hasil berupa 'puluhan')
        // daripada menulis ulang rutin kode puluhan maka gunakan
        // saja fungsi rekursif dengan memanggil fungsi terbilang(51)
        return sprintf('Seratus %s', terbilang($angka - 100));
    } else if ($angka < 1000) {
        // bilangan 'ratusan'
        // misal 467 maka 467 / 100 = 4,67 (integer => 4) 'empat'
        // sisanya 467 mod 100 = 67 (berupa puluhan jadi gunakan rekursif terbilang(67))
        $hasil_bagi = (int)($angka / 100);
        $hasil_mod = $angka % 100;
        return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], terbilang($hasil_mod)));
    } else if ($angka < 2000) {
        // bilangan 'seribuan'
        // misal 1250 maka 1250 - 1000 = 250 (ratusan)
        // gunakan rekursif terbilang(250)
        return trim(sprintf('Seribu %s', terbilang($angka - 1000)));
    } else if ($angka < 1000000) {
        // bilangan 'ribuan' (sampai ratusan ribu
        $hasil_bagi = (int)($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
        $hasil_mod = $angka % 1000;
        return sprintf('%s Ribu %s', terbilang($hasil_bagi), terbilang($hasil_mod));
    } else if ($angka < 1000000000) {
        // bilangan 'jutaan' (sampai ratusan juta)
        // 'satu puluh' => SALAH
        // 'satu ratus' => SALAH
        // 'satu juta' => BENAR
        // @#$%^ WT*
         
        // hasil bagi bisa satuan, belasan, ratusan jadi langsung kita gunakan rekursif
        $hasil_bagi = (int)($angka / 1000000);
        $hasil_mod = $angka % 1000000;
        return trim(sprintf('%s Juta %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000) {
        // bilangan 'milyaran'
        $hasil_bagi = (int)($angka / 1000000000);
        // karena batas maksimum integer untuk 32bit sistem adalah 2147483647
        // maka kita gunakan fmod agar dapat menghandle angka yang lebih besar
        $hasil_mod = fmod($angka, 1000000000);
        return trim(sprintf('%s Milyar %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000000) {
        // bilangan 'triliun'
        $hasil_bagi = $angka / 1000000000000;
        $hasil_mod = fmod($angka, 1000000000000);
        return trim(sprintf('%s Triliun %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else {
        return 'Wow...';
    }
}

function cari_stok_awal($kode) {
	$sql	= "SELECT kode_barang,stok_awal as jml FROM barang WHERE kode_barang='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_jml_beli($kode){
	$sql	= "SELECT kode_barang,sum(jumlah_beli) as jml FROM pembelian WHERE kode_barang='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}
function cari_jml_jual($kode){
	$sql	= "SELECT kode_barang,sum(jumlah_jual) as jml FROM penjualan WHERE kode_barang='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_jml_relokasi($kode){
	$sql	= "SELECT SUM(relokasi_jumlah_beli_valid) AS rel FROM po_pembelian WHERE kodepo_beli='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[rel];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_jml_relokasidua($kode){
	$sql	= "SELECT SUM(relokasi_jumlah_beli_valid1) AS rel FROM po_pembelian WHERE kodepo_beli='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[rel];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_jml_relokasitiga($kode){
	$sql	= "SELECT SUM(relokasi_jumlah_beli_valid2) AS rel FROM po_pembelian WHERE kodepo_beli='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[rel];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_kategori($kode){
	$sql	= "SELECT UPPER(LEFT(IFNULL(`namakatkhusus`,'Rutin'),3)) AS namakategori FROM `mstproduk` a LEFT JOIN `mstkategorikhusus` b ON a.`kategorikhusus`=b.`kodekatkhusus` WHERE kodeproduk='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[namakategori];
	}else{
		$hasil = '';
	}
	return $hasil;
}

function cari_stok_akhir($kode){
	$stok_awal	= cari_stok_awal($kode);
	$jml_beli = cari_jml_beli($kode);
	$jml_jual = cari_jml_jual($kode);
	
	$hasil	= ($stok_awal+$jml_beli)-$jml_jual;
	return $hasil;
}

function cari_pembelian_total($kode1){

	$sql	= "SELECT SUM(jumlah_beli) as jml FROM pembelian WHERE kode_beli='$kode1'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_nama_cabang($kode){
	$table = "pembelian";
	$field = "a.kode_beli";

	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
		$field = "a.kode_beli";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
		$field = "a.kode_beli";
	}

	if (stripos($kode, "RR") !== false){ 
		$table = "retur_beli";
		$field = "a.kode_retur";
	}

	if (stripos($kode, "RR/PRE") !== false){ 
		$table = "retur_beli_pre";
		$field = "a.kode_retur";
	}	

	if (stripos($kode, "RR/PSI") !== false){ 
		$table = "retur_beli_psi";
		$field = "a.kode_retur";
	}

	if (stripos($kode, "RK") !== false){ 
		$table = "konfirm_retur_beli";
		$field = "kodekonf_retur";
	}

	if (stripos($kode, "RK/PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
		$field = "kodekonf_retur";
	}

	if (stripos($kode, "RK/PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
		$field = "kodekonf_retur";
	}	

	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
		$field = "kode_beli";
	}	

	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
		$field = "kode_beli";
	}	

	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
		$field = "kode_beli";
	}	

	
	
	$sql	= "SELECT cabang FROM purchase_detail_report WHERE No_PR='$kode' Limit 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}

function cari_nama_cabang_po($kode){
	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	$sql	= "SELECT c.NmCabang FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = $table;
	}
	return $hasil;
}

function cari_nama_cabang_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = '';
	}
	return $hasil;
}


function cari_produk_btb_outstanding($cabang,$kode){

	$sql	= "SELECT a.`cabang`,a.`kode_barang` AS barang,b.`kode_barang` FROM `pembelian` a
					LEFT JOIN vpobtbjum b ON a.`kode_beli`=b.`kode_beli` AND a.`kode_barang`=b.`kode_barang` AND b.`jumbtb` > 0
					WHERE a.`cabang`='$cabang' AND a.`kode_barang`='$kode' AND a.`tgl_beli` BETWEEN (NOW() - INTERVAL '1' MONTH) AND NOW() 
					HAVING b.`kode_barang` IS NULL";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[barang];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function cari_produk_pr_outstanding($cabang,$kode,$kodepr){
	
	$table = "pembelian";
	if (stripos($kodepr, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kodepr, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sqla	= "SELECT DISTINCT(kode_barang) AS kode_barang  
				FROM $table WHERE tgl_beli > (NOW() - INTERVAL '1' MONTH) 
					AND cabang='$cabang' AND LEFT(kode_barang,2) NOT IN ('EK') AND kode_barang='$kode'";
					
	$querya	= mysql_query($sqla);
	$rowa	= mysql_num_rows($querya);
	
	if ($rowa>0){
		$dataa	= mysql_fetch_array($querya);
		$hasila	= $dataa[kode_barang];
	}else{
		$hasila = "";
	}
	return $hasila;
}

function cari_produk_pr_outstanding_adapr($cabang,$kode,$kodepr){

	$table = "pembelian";
	if (stripos($kodepr, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kodepr, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	
	$sqlab	= "SELECT DISTINCT(kode_barang) AS kode_barang  
					FROM $table WHERE tgl_beli > (NOW() - INTERVAL '1' MONTH) 
						AND cabang='$cabang' AND LEFT(kode_barang,2) NOT IN ('EK') 
						AND kode_barang='$kode' AND kode_beli='$kodepr'";
					
	$queryab	= mysql_query($sqlab);
	$rowab	= mysql_num_rows($queryab);
	
	if ($rowab>0){
		$dataab	= mysql_fetch_array($queryab);
		$hasilab	= $dataab[kode_barang];
	}else{
		$hasilab = "";
	}
	return $hasilab;
}

function cari_produk_pr_outstanding_adapo($cabang,$kode,$kodepr){

	$table = "po_pembelian";
	$tablepr = "pembelian";
	if (stripos($kodepr, "PRE") !== false){ 
		$table = "po_pembelian_pre";
		$tablepr = "pembelian_pre";
	}
	if (stripos($kodepr, "PSI") !== false){ 
		$table = "po_pembelian_psi";
		$tablepr = "pembelian_psi";
	}
	
	$sqlabc	= "SELECT kode_barang FROM $table WHERE kode_beli IN (		
		SELECT kode_beli  
		FROM $tablepr WHERE tgl_beli > (NOW() - INTERVAL '1' MONTH) 
			AND cabang='$cabang' AND LEFT(kode_barang,2) NOT IN ('EK') 
			AND kode_barang='$kode')
	AND kode_barang='$kode' AND jumlah_beli_valid=0";
					
	$queryabc	= mysql_query($sqlabc);
	$rowabc	= mysql_num_rows($queryabc);
	
	if ($rowabc>0){
		$dataabc	= mysql_fetch_array($queryabc);
		$hasilabc	= $dataabc[kode_barang];
	}else{
		$hasilabc = "";
	}
	return $hasilabc;
}

function cari_produk_pr_outstanding_nopr($cabang,$kode,$kodepr){

	$table = "pembelian";
	if (stripos($kodepr, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kodepr, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT DISTINCT(kode_beli) as kode_beli FROM $table WHERE tgl_beli > (NOW() - INTERVAL '1' MONTH) AND cabang='$cabang' AND LEFT(kode_barang,2) NOT IN ('EK') AND kode_barang='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$result	= $data[kode_beli];
			$hasil = $hasil.$result.", ";	
		}
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function cari_ijin_pbf($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[izin];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_ijin_pbf_po($kode){

	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[izin];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_ijin_pbf_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[izin];
	}else{
		$hasil = '';
	}
	return $hasil;
}



function cari_alamat_pbf($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_alamat_pbf_po($kode){

	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_alamat_pbf_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat];
	}else{
		$hasil = '';
	}
	return $hasil;
}



function cari_telp_pbf($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`telpon`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= 'Telp. : '. $data[telpon];
	}else{
		$hasil = '-';
	}
	return $hasil;
}


function cari_telp_pbf_po($kode){

	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`telpon`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= 'Telp. : '. $data[telpon];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function cari_telp_pbf_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= 'Telp. : '. $data[telpon];
	}else{
		$hasil = '';
	}
	return $hasil;
}

function cari_nama_apoteker($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`telpon`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[apoteker];
	}else{
		$hasil = '-';
	}
	return $hasil;
}


function cari_nama_apoteker_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[apoteker];
	}else{
		$hasil = $table;
	}
	return $hasil;
}

function cari_fax_pbf($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= 'Fax : '.$data[fax];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function cari_fax_pbf_po($kode){

	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= 'Fax : '.$data[fax];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function cari_fax_pbf_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= 'Fax : '.$data[fax];
	}else{
		$hasil = '';
	}
	return $hasil;
}


function cari_sika($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika` FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[sika];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function cari_sika_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[sika];
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function cari_alamat_apt_pusat($kode){

	$sql	= " SELECT * FROM mastercabang WHERE `kdCabang`='KPS'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat_apt];
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function cari_alamat_apt($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika`,c.alamat_apt FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat_apt];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function cari_kota_pr($kode){

	$table = "pembelian";
	$field = "a.kode_beli";

	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
		$field = "a.kode_beli";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
		$field = "a.kode_beli";
	}

	if (stripos($kode, "RR") !== false){ 
		$table = "retur_beli";
		$field = "a.kode_retur";
	}

	if (stripos($kode, "RR/PRE") !== false){ 
		$table = "retur_beli_pre";
		$field = "a.kode_retur";
	}

	if (stripos($kode, "RR/PSI") !== false){ 
		$table = "retur_beli_psi";
		$field = "a.kode_retur";
	}

	if (stripos($kode, "RK") !== false){ 
		$table = "konfirm_retur_beli";
		$field = "a.kodekonf_retur";
	}

	if (stripos($kode, "RK/PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
		$field = "a.kodekonf_retur";
	}

	if (stripos($kode, "RK/PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
		$field = "a.kodekonf_retur";
	}	
	
	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika`,c.kota FROM $table a 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE $field='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kota];
	}else{
		$hasil = '';
	}
	return $hasil;
}

function cari_nama_cabang2($kode){

	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT c.NmCabang FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_nama_cabang_relokasi($kode){


	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	
	$sql	= "SELECT c.NmCabang FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`relokasi_cabang`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_nama_cabang_relokasisatu($kode){


	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	
	$sql	= "SELECT c.NmCabang FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`relokasi_cabang1`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_nama_cabang_relokasidua($kode){


	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	
	$sql	= "SELECT c.NmCabang FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`relokasi_cabang2`=c.`KdCabang` 
					WHERE a.kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_nama_user_buat($kode){


	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	
	
	$sql	= "SELECT nama_lengkap FROM $table a
				LEFT JOIN admins b ON a.`user_buat`=b.`username`
				WHERE kodepo_beli='$kode' limit 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[nama_lengkap];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_nama_cabang3($kode){

	$sql	= "SELECT c.NmCabang FROM trans_nonsalestok a 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_nsas='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_kategori_nsas($kode){

	$sql	= "SELECT c.nsas FROM trans_nonsalestok a 
				LEFT JOIN `mstkatnsas` c ON a.`kategori_nsas`=c.`kode_kategori`  
					WHERE a.kode_nsas='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[nsas];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_nama_cabang_master($kodecab){

	$sql	= "SELECT * FROM mastercabang WHERE KdCabang='$kodecab'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[NmCabang];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function cari_nama_cabang_upload_data($kodecab){

	$sql	= "SELECT * FROM mastercabang WHERE NmCabang='$kodecab'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[KdCabang];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}



function total_barang_pr($kode){

	$sql	= "SELECT SUM(jumlah_beli) as jml FROM pembelian WHERE kode_beli='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function total_barang_po($kode){

	$sql	= "SELECT SUM(jumlah_beli_valid) as jml FROM po_pembelian WHERE kodepo_beli='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function total_btb_sisa($kodebarang,$kodecab,$kodepo){

	$sql	= "SELECT cabang,kode_barang,
				(SUM(jumlah_terima) + SUM(`jumlah_terima_a`) + SUM(`jumlah_terima_b`)) AS jumterimabtb
				FROM trans_btb 
				WHERE kode_barang='$kodebarang' AND cabang='$kodecab' AND kodepo_beli='$kodepo'				
				GROUP BY cabang";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jumterimabtb];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function kode_pr_btb($kode){


	$sql	= "SELECT DISTINCT(kode_beli) as kode_beli FROM trans_btb WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$result	= $data[kode_beli];
			$hasil = $hasil.$result.", ";	
		}
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function kode_resi_btb($kode){

	$sql	= "SELECT DISTINCT(resi) as resi FROM trans_btb WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[resi];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function ekspedisi_btb($kode){

	$sql	= "SELECT DISTINCT(ekspedisi) as ekspedisi FROM trans_btb WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[ekspedisi];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function suratjalan_btb($kode){

	$sql	= "SELECT DISTINCT(sj_faktur) as sj_faktur FROM trans_btb WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[sj_faktur];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function kode_po_btb($kode){

	$sql	= "SELECT DISTINCT(kodepo_beli) as kode_beli FROM trans_btb WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$result	= $data[kode_beli];
			$hasil = $hasil.$result.", ";	
		}
		
		
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function nama_supplier_saja($kode){

	$sql	= "SELECT `namasupplier` FROM `mstsupplier2` WHERE kodesupplier='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[namasupplier];
		//$hasil	= $sql;
	}else{
		$hasil = "";
	}
	return $hasil;
}



function nama_supplier_btb($kode){

	$sql	= "SELECT b.`namasupplier` 
					FROM trans_btb a
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier` WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[namasupplier];
		//$hasil	= $sql;
	}else{
		$hasil = "";
	}
	return $hasil;
}

function nama_supplier2_btb($kode){

	$sql	= "SELECT DISTINCT b.`namasupplier` 
					FROM trans_btb a
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier` WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$result	= $data[namasupplier];
			$hasil = $hasil.$result.", ";	
		}
		
		
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function kode_supplier2_btb($kode){

	$sql	= "SELECT DISTINCT b.`kodesupplier` 
					FROM trans_btb a
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier` WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$result	= $data[kodesupplier];
			$hasil = $hasil.$result.", ";	
		}
		
		
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function kode_btb_from_pr($kode,$barang){

	$sql	= "SELECT DISTINCT `kodebtb` 
					FROM trans_btb WHERE kode_beli='$kode' and kode_barang='$barang' AND (`jumlah_terima`+`jumlah_terima_a`+`jumlah_terima_b`)>0 ";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$result	= $data[kodebtb];
			$hasil = $hasil.$result.", ";	
		}
		
		
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function namabarangmaster($barang){

	$sql	= "SELECT namaproduk` 
					FROM mstproduk WHERE kodeproduk='$barang'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$hasil	= $data[namaproduk];	
		}
		
		
	}else{
		$hasil = "-";
	}
	return $hasil;
}


function kode_pr_po($kode){

	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT DISTINCT(kode_beli) as kode_beli FROM $table WHERE kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kode_beli];
	}else{
		$hasil = '';
	}
	return $hasil;
}



function nama_supplier_po($kode){
	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	$sql	= "SELECT DISTINCT(a.`kode_supplier`) AS kode_supplier_po, b.`namasupplier` 
					FROM $table a
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier` WHERE kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[namasupplier];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function produk_pusat_po($kode){
	$sql	= "SELECT kodeproduk,stok FROM mst_stok_pusat WHERE kodeproduk='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= "Stok Pst, jml: ".$data[stok];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function produk_diskon_popr($kodepr,$kodebarang){
	$sql	= "SELECT diskon FROM pembelian WHERE kode_beli='$kodepr' AND kode_barang='$kodebarang'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[diskon];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function produk_harga($kode){
	$sql	= "SELECT hargajual FROM mstproduk WHERE kodeproduk='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[hargajual];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function produk_black_list($kode){
	$sql	= "SELECT kodebarang FROM mstproduk_blacklist WHERE kodebarang='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= "Syarat!";
	}else{
		$hasil = "";
	}
	return $hasil;
}

function kode_produk_black_list($kode_barang){
	$sql	= "SELECT kodebarang FROM mstproduk_blacklist WHERE kodebarang='$kode_barang' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= "SYARAT";
	}else{
		$hasil = "";
	}
	return $hasil;
}

function ket_produk_black_list($statprod,$kode){
	
	if ($statprod == "SYARAT"){
		$hasil	= "Produk ".$kode." ber-syarat";
	}else{
		$hasil = "";
	}
	return $hasil;
}

function produk_boleh_jual($kode_barang,$cabang){
	$sql	= "SELECT cabang,kode_barang FROM `mstproduk_sp_cetak` WHERE kode_barang='$kode_barang' AND cabang='$cabang' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= "";
	}else{
		$hasil = "PRODUK TIDAK ADA PENJUALAN...!!!";
	}
	return $hasil;
}

function produk_sp($cabang,$kode_barang){
	$sql	= "SELECT cabang,kode_barang FROM `mstproduk_sp` WHERE kode_barang='$kode_barang' AND cabang='$cabang' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kode_barang];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function alamat_supplier_po($kode){
	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	$sql	= "SELECT DISTINCT(a.`kode_supplier`) AS kode_supplier_po, b.`namasupplier`,b.alamat1 
					FROM $table a
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier` WHERE kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat1];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function alamat_supplier_po_data($kode){
	$table = "po_pembelian";
	$tabel2= "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
		$tabel2= "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
		$tabel2= "pembelian_psi";
	}
	$sql	= "SELECT b.`alamat_supplier`,b.`telp_supplier` FROM $table a 
					LEFT JOIN $tabel2 b ON a.`kode_beli`=b.kode_beli AND a.`kode_barang`=b.kode_barang
					WHERE a.`kodepo_beli`='$kode' limit 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat_supplier];
			if (empty($data[alamat_supplier]))
				{
					$hasil = alamat_supplier_po($kode);
				}
			else
				{
					$hasil = $data[alamat_supplier];
				}
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function alamat_supplier_pr_data($kode){
	$tabel2= "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$tabel2= "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$tabel2= "pembelian_psi";
	}
	$sql	= "SELECT `alamat_supplier`,`telp_supplier` FROM $tabel2 WHERE `kode_beli`='$kode' limit 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[alamat_supplier];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function telpon_supplier_po($kode){
	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	$sql	= "SELECT DISTINCT(a.`kode_supplier`) AS kode_supplier_po, b.`namasupplier`,b.alamat1,b.telp 
					FROM $table a
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier` WHERE kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[telp];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function telp_supplier_po_data($kode){
	$table = "po_pembelian";
	$tabel2= "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
		$tabel2= "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
		$tabel2= "pembelian_psi";
	}
	$sql	= "SELECT DISTINCT b.`alamat_supplier`,b.`telp_supplier` FROM $table a 
					LEFT JOIN $tabel2 b ON a.`kode_beli`=b.kode_beli AND a.`kode_barang`=b.kode_barang
					WHERE a.`kodepo_beli`='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[telp_supplier];
			if (empty($data[telp_supplier]))
				{
					$hasil = telpon_supplier_po($kode);
				}
			else
				{
					$hasil = $data[telp_supplier];
				}
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function telp_supplier_pr_data($kode){
	$tabel2= "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$tabel2= "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$tabel2= "pembelian_psi";
	}
	$sql	= "SELECT `alamat_supplier`,`telp_supplier` FROM $tabel2 WHERE `kode_beli`='$kode' limit 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[telp_supplier];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}

function tanggal_po($kode){

	$table = "po_pembelian";
	$field = "kodepo_beli";
	$field2 = "tglpo_beli";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "po_pembelian_pre";
		$field = "kodepo_beli";
		$field2 = "tglpo_beli";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "po_pembelian_psi";
		$field = "kodepo_beli";
		$field2 = "tglpo_beli";
	}
	if (stripos($kode, "RK") !== false){ 
		$table = "konfirm_retur_beli";
		$field = "kodekonf_retur";
		$field2 = "tglkonf_retur";
	}
	if (stripos($kode, "RK/PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
		$field = "kodekonf_retur";
		$field2 = "tglkonf_retur";
	}
	if (stripos($kode, "RK/PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
		$field = "kodekonf_retur";
		$field2 = "tglkonf_retur";
	}
	
	$sql	= "SELECT DISTINCT ($field2) as tanggal_po_beli FROM $table WHERE $field='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_po_beli];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function tanggal_sobb($kode){

	$sql	= "SELECT DISTINCT (`tgl_sobb`) AS tgl_sobb FROM trans_sobb WHERE kode_sobb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tgl_sobb];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function tanggal_sobh($kode){

	$sql	= "SELECT DISTINCT (`tgl_sobh`) AS tgl_sobh FROM trans_sobh WHERE kode_sobh='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tgl_sobh];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function tanggal_btb($kode){

	$sql	= "SELECT DISTINCT (`tglbtb`) AS tglbtb FROM trans_btb WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tglbtb];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function tanggal_pr($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP/") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}

	$sql	= "SELECT DISTINCT (Tgl_PR) as tanggal_pr_beli FROM purchase_detail_report WHERE No_PR='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_pr_beli];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function tanggal_pr_pre($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT DISTINCT (tgl_beli) as tanggal_pr_beli FROM $table WHERE kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_pr_beli];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function keperluan_pr_pre($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT MAX(keperluan) AS tanggal_pr_beli FROM $table WHERE kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_pr_beli];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function keper_nama_pr_pre($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT MAX(keperluan_nama) AS tanggal_pr_beli FROM $table WHERE kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_pr_beli];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function keper_alm_pr_pre($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	= "SELECT MAX(keperluan_alamat) AS tanggal_pr_beli FROM $table WHERE kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_pr_beli];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function tanggal_po_pre($kode){

	$table = "po_pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT DISTINCT (tglpo_beli) as tanggal_pr_beli FROM $table WHERE kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_pr_beli];
	}else{
		$hasil = '';
	}
	return $hasil;
}


function tanggal_nsas($kode){

	$sql	= "SELECT DISTINCT (tgl_nsas) as tanggal_pr_beli FROM trans_nonsalestok WHERE kode_nsas='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tanggal_pr_beli];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function ref_cab_nsas($kode){

	$sql	= "SELECT DISTINCT (ref_cab) as ref_cab FROM trans_nonsalestok WHERE kode_nsas='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[ref_cab];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function ref_pus_nsas($kode){

	$sql	= "SELECT DISTINCT (ref_pus) as ref_pus FROM trans_nonsalestok WHERE kode_nsas='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[ref_pus];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function combo_supplier_pr_edit($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP/") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}

	$sql	= "SELECT DISTINCT(a.`kode_supplier`) AS kode_supplier_po, b.`namasupplier` 
	FROM $table a
	LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier` WHERE kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kode_supplier_po];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function aproval_pr_number($kode){

	$table = "pembelian_usulan";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}

	$sql	= "SELECT `kode_pr_approv` FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kode_pr_approv];
	}else{
		$hasil = 'error';
	}
	return $hasil;
}

function combo_kategori_nsas_edit($kode){

	$sql	= "SELECT DISTINCT(a.`kode_nsas`) AS kode_nsas,a.kategori_nsas 
	FROM trans_nonsalestok a
	LEFT JOIN `mstkatnsas` b ON a.`kategori_nsas`=b.`kode_kategori` WHERE kode_nsas='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kategori_nsas];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function combo_prinsipal_po_edit($kode){

	$table = "po_pembelian";
	$field = "kodepo_beli";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "po_pembelian_pre";
		$field = "kodepo_beli";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "po_pembelian_psi";
		$field = "kodepo_beli";
	}
	if (stripos($kode, "RK") !== false){ 
		$table = "konfirm_retur_beli";
		$field = "kodekonf_retur";
	}
	if (stripos($kode, "RK/PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
		$field = "kodekonf_retur";
	}
	if (stripos($kode, "RK/PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
		$field = "kodekonf_retur";
	}

	$sql	= "SELECT DISTINCT(a.`kode_prinsipal`) AS kode_prinsipal_po, b.`namaprinsipal` 
	FROM $table a
	LEFT JOIN `mstprinsipal` b ON a.`kode_prinsipal`=b.`kodeprinsipal` WHERE $field='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kode_prinsipal_po];
	}else{
		$hasil = " ";
	}
	return $hasil;
}

function supplier_btb_edit($kode){


	$sql	= "SELECT DISTINCT a.kode_supplier,b.`namasupplier` FROM trans_btb a 
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
					WHERE kodebtb='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[namasupplier];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cekoutstading_pr($kodepr,$kodebarang,$kodecabang){

	$table = "pembelian";
	$table2 = "po_pembelian";
	
	if (stripos($kodepr, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
		$table2 = "po_pembelian_pre";
	}

	if (stripos($kodepr, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
		$table2 = "po_pembelian_psi";
	}

	$sql1	= "SELECT kode_beli FROM $table WHERE kode_barang='$kodebarang' 
				AND cabang='$kodecabang' AND CONCAT(YEAR(tgl_beli),MONTH(tgl_beli))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND jumlah_beli>0
				AND kode_beli<>'$kodepr'";
				
	$sql2	= "SELECT kode_beli FROM $table2 WHERE kode_barang='$kodebarang' AND jumlah_beli_valid <> 0 
				AND kode_beli IN (SELECT kode_beli FROM $table WHERE kode_barang=',$kodebarang' 
				AND cabang='$kodecabang' AND CONCAT(YEAR(tgl_beli),MONTH(tgl_beli))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND jumlah_beli>0
				AND kode_beli<>'$kodepr')";

	$sql3	= "SELECT kode_beli FROM trans_btb WHERE kode_barang='$kodebarang'  
				AND kode_beli IN (SELECT kode_beli FROM $table WHERE kode_barang=',$kodebarang' 
				AND cabang='$kodecabang' AND CONCAT(YEAR(tgl_beli),MONTH(tgl_beli))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND jumlah_beli>0
				AND kode_beli<>'$kodepr')";
					
	$query1	= mysql_query($sql1);
	$row1	= mysql_num_rows($query1);
	
	$query2	= mysql_query($sql2);
	$row2	= mysql_num_rows($query2);

	$query3	= mysql_query($sql3);
	$row3	= mysql_num_rows($query3);
	
	if ($row1>0 || $row2>0 || $row3>0){
		$hasil	= "PR-Out";
	}else{
		$hasil = "";
	}
	return $hasil;
}

function cabang1($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang1 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang1 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}


function cabang2($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang2 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang2 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}




function cabang3($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang3 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang3 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}


function cabang4($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang4 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang4 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}


function cabang5($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang5 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang5 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}

function cabang6($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang6 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang6 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}

function cabang7($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang7 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang7 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}

function cabang8($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang8 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang8 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}

function cabang9($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang9 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang9 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}

function cabang10($cabang,$kodeproduk){

	$sql	= "SELECT COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`),0) AS text_avg, 
						COALESCE(a.`unit`,0) AS text_stok,
						ROUND(COALESCE((a.`unit`/COALESCE(GREATEST(b.`m1`,b.`m2`,b.`m3`))),0),2) AS text_ratio
				FROM mstlogday a
				LEFT JOIN mstlogmonth b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk` AND a.`region`=b.`region`
				WHERE a.kodeproduk='$kodeproduk' AND a.kodestatus='B'
				AND a.kodecabang IN (SELECT cabang10 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang')";
	
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$sqlrel 	= "SELECT kode_sobb,tgl_sobb,kode_produk FROM trans_sobb WHERE kode_produk='$kodeproduk' AND kodecabang IN (SELECT cabang10 FROM `mstcabang_terdekat` WHERE `cabang_utama`='$cabang') AND CONCAT(YEAR(tgl_sobb),MONTH(tgl_sobb))=CONCAT(YEAR(NOW()),MONTH(NOW())) AND (relokasi_1='Y' OR relokasi_2='Y' OR relokasi_3 = 'Y')";
		$queryrel	= mysql_query($sqlrel);
		$rowrel		= mysql_num_rows($queryrel);

		if ($rowrel>0){
			$datarelokasi = "-M";
		}else
		{
			$datarelokasi = "";		
		}
		
		$data	= mysql_fetch_array($query);
		if ($data[text_ratio]>=3) { $ratio = number_format($data[text_ratio])."-R";}
		else{$ratio = number_format($data[text_ratio]);}
		
		$hasil	= number_format($data[text_avg])."/".number_format($data[text_stok])."/".$ratio.$datarelokasi;
	}else{
		$hasil = "0/0/0";
	}
	return $hasil;
}


function namacabang1($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang1 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang1];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang2($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}

	$sql	= "SELECT cabang2 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang2];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang3($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang3 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang3];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang4($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang4 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang4];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang5($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang5 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang5];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang6($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang6 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang6];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang7($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang7 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang7];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang8($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang8 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang8];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function namacabang9($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	$sql	= "SELECT cabang9 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang9];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function namacabang10($kode){

	$table = "pembelian";
	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	
	
	$sql	= "SELECT cabang10 FROM `mstcabang_terdekat` WHERE `cabang_utama` IN (SELECT cabang FROM $table WHERE kode_beli='$kode')";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[cabang10];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function totselisihunitsobb($kode){

	$sql	= "SELECT SUM(totalunitselisih) AS totselisihunit FROM (SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((COALESCE(a.`hpc`,0) * COALESCE(a.qty_komp,0)),0),0) AS valuekomp,
									a.`unit_1`,a.`ed_1`,a.`unit_2`,a.`ed_2`,a.`unit_3`,a.`ed_3`,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobb a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobb='$kode' )sobb
					WHERE 	totalunitselisih <>0";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[totselisihunit];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}

function totselisihunitsobh($kode){

	$sql	= "SELECT SUM(totalunitselisih) AS totselisihunit FROM (SELECT a.`kodecabang`,a.`kode_sobh`,a.`tgl_sobh`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((COALESCE(a.`hpc`,0) * COALESCE(a.qty_komp,0)),0),0) AS valuekomp,
									a.`unit_1`,a.`ed_1`,a.`unit_2`,a.`ed_2`,a.`unit_3`,a.`ed_3`,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobh a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobh='$kode' )sobh
					WHERE 	totalunitselisih <>0";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[totselisihunit];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}


function totselisihvaluesobb($kode){

	$sql	= "SELECT SUM(totalvalueselisih) AS totalvalueselisih FROM (SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((COALESCE(a.`hpc`,0) * COALESCE(a.qty_komp,0)),0),0) AS valuekomp,
									a.`unit_1`,a.`ed_1`,a.`unit_2`,a.`ed_2`,a.`unit_3`,a.`ed_3`,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobb a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobb='$kode' )sobb
					WHERE 	totalunitselisih <>0";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[totalvalueselisih];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}

function totselisihvaluesobh($kode){

	$sql	= "SELECT SUM(totalvalueselisih) AS totalvalueselisih FROM (SELECT a.`kodecabang`,a.`kode_sobh`,a.`tgl_sobh`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((COALESCE(a.`hpc`,0) * COALESCE(a.qty_komp,0)),0),0) AS valuekomp,
									a.`unit_1`,a.`ed_1`,a.`unit_2`,a.`ed_2`,a.`unit_3`,a.`ed_3`,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobh a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobh='$kode' )sobh
					WHERE 	totalunitselisih <>0";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[totalvalueselisih];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}


function kode_btb_regpo($kode){


	$sql	= "SELECT DISTINCT(kodebtb) as kode_beli FROM trans_btb WHERE kodepo_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		while($data=mysql_fetch_array($query)){
			$result	= $data[kode_beli];
			$hasil = $hasil.$result.", ";	
		}
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function supplier_retur_edit($kode){

	$table = "retur_beli";
	if (stripos($kode, "PRE") !== false){ 
		$table = "retur_beli_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "retur_beli_psi";
	}


	$sql	= "SELECT DISTINCT a.kode_supplier,b.`namasupplier` FROM $table a 
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
					WHERE kode_retur='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kode_supplier];
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function nama_retur_edit($kode){

	if (stripos($kode, "RR") !== false){ 
		$table = "retur_beli";
		$field = "kode_retur";
	}
	
	if (stripos($kode, "RR/PRE") !== false){ 
		$table = "retur_beli_pre";
		$field = "kode_retur";
	}
	if (stripos($kode, "RR/PSI") !== false){ 
		$table = "retur_beli_psi";
		$field = "kode_retur";
	}
	
	if (stripos($kode, "RK") !== false){ 
		$table = "konfirm_retur_beli";
		$field = "kodekonf_retur";
	}

	if (stripos($kode, "RK/PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
		$field = "kodekonf_retur";
	}

	if (stripos($kode, "RK/PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
		$field = "kodekonf_retur";
	}
	
	$sql	= "SELECT DISTINCT a.kode_supplier,b.`namasupplier` FROM $table a 
					LEFT JOIN `mstsupplier2` b ON a.`kode_supplier`=b.`kodesupplier`
					WHERE $field='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[namasupplier];
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function tanggal_retur_edit($kode){

	if (stripos($kode, "RR") !== false){ 
		$table = "retur_beli";
		$field = "kode_retur";
		$field2 = "tgl_retur";
	}
	
	if (stripos($kode, "RR/PRE") !== false){ 
		$table = "retur_beli_pre";
		$field = "kode_retur";
		$field2 = "tgl_retur";
	}
	if (stripos($kode, "RR/PSI") !== false){ 
		$table = "retur_beli_psi";
		$field = "kode_retur";
		$field2 = "tgl_retur";
	}
	
	if (stripos($kode, "RK") !== false){ 
		$table = "konfirm_retur_beli";
		$field = "kodekonf_retur";
		$field2 = "tglkonf_retur";
	}

	if (stripos($kode, "RK/PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
		$field = "kodekonf_retur";
		$field2 = "tglkonf_retur";
	}

	if (stripos($kode, "RK/PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
		$field = "kodekonf_retur";
		$field2 = "tglkonf_retur";
	}

	$sql	= "SELECT DISTINCT $field2 AS tglbtb FROM $table WHERE $field='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tglbtb];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}

function koli_retur_edit($kode){

	$table = "retur_beli";
	if (stripos($kode, "PRE") !== false){ 
		$table = "retur_beli_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "retur_beli_psi";
	}

	$sql	= "SELECT DISTINCT (`jum_koli`) AS tglbtb FROM $table WHERE kode_retur='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tglbtb];
	}else{
		$hasil = "-";
	}
	return $hasil;
}

function koli_retur_cetak($kode){

	$table = "konfirm_retur_beli";
	$table2 = "retur_beli";
	
	if (stripos($kode, "PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
		$table2 = "retur_beli_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
		$table2 = "retur_beli_psi";
	}

	$sql	= "SELECT DISTINCT (`jum_koli`) AS tglbtb FROM $table 
				LEFT JOIN $table2 ON $table2.kode_retur=$table.kode_retur
				WHERE kodekonf_retur='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tglbtb];
	}else{
		$hasil = $sql;
	}
	return $hasil;
}

function kode_retur_konfirm($kode){

	$table = "konfirm_retur_beli";
	if (stripos($kode, "PRE") !== false){ 
		$table = "konfirm_retur_beli_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "konfirm_retur_beli_psi";
	}

	$sql	= "SELECT DISTINCT (`kode_retur`) AS tglbtb FROM $table WHERE kodekonf_retur='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tglbtb];
	}else{
		$hasil = "-";
	}
	return $hasil;
}


function kategori_khusus($kode){

	$sql	= " SELECT a.`kodeproduk`,a.`namaproduk`,a.`kategorikhusus`,b.`namakatkhusus` FROM `mstproduk` a
					LEFT JOIN `mstkategorikhusus` b ON a.`kategorikhusus`=b.`kodekatkhusus`
					WHERE kodeproduk='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[namakatkhusus];
	}else{
		$hasil = "-";
	}
	return $hasil;
}


function cek_status_moving($kode,$cabang){

	$sql	= "SELECT a.`kodecabang`,a.`kodeproduk`,b.`unit`,
					a.`m1`,ROUND(IFNULL((a.m1/b.`unit`),0),2) AS satu,CASE WHEN (IFNULL((a.m1/b.`unit`),0) < 0.25) THEN CONCAT('SM1= ',ROUND(IFNULL((a.m1/b.`unit`),0)*100,2) ,'%') END AS ket1slow,CASE WHEN a.m1 <= 0 THEN '-NM1' END AS ket1non ,
					a.`m2`,ROUND(IFNULL((a.m2/b.`unit`),0),2) AS dua,CASE WHEN (IFNULL((a.m2/b.`unit`),0) < 0.25) THEN CONCAT('-SM2= ',ROUND(IFNULL((a.m2/b.`unit`),0)*100,2) ,'%') END AS ket2slow,CASE WHEN a.m2 <= 0 THEN '-NM2' END AS ket2non ,
					a.`m3`,ROUND(IFNULL((a.m3/b.`unit`),0),2) AS tiga,CASE WHEN (IFNULL((a.m3/b.`unit`),0) < 0.25) THEN CONCAT('-SM3= ',ROUND(IFNULL((a.m3/b.`unit`),0)*100,2) ,'%') END AS ket3slow,CASE WHEN a.m3 <= 0 THEN '-NM3' END AS ket3non 
					FROM `mstlogmonth` a 
				LEFT JOIN `mstlogday` b ON a.`kodecabang`=b.`kodecabang` AND a.`kodeproduk`=b.`kodeproduk`
				WHERE a.kodecabang='$cabang' AND b.kodestatus='B' AND a.kodeproduk='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[ket1slow].$data[ket1non].$data[ket2slow].$data[ket2non].$data[ket3slow].$data[ket3non];
	}else{
		$hasil = "NM";
	}
	return $hasil;
}

function cek_status_moving_dua($kode,$cabang){

	$sql	= "SELECT a.`cabang`,a.prinsipal,a.`kodeproduk`,
							ROUND(IFNULL(a.v1,0),0) AS v1,
							b.av1,
							CASE WHEN IFNULL(a.`v1`/b.av1,0) >= 0.7 THEN CONCAT('FM1=',ROUND(IFNULL(a.`v1`/b.av1,0),2)) 
								WHEN IFNULL(a.`v1`/b.av1,0) BETWEEN 0.3 AND 0.7  THEN CONCAT('NR1=',ROUND(IFNULL(a.`v1`/b.av1,0),2)) 
								WHEN IFNULL(a.`v1`/b.av1,0) BETWEEN 0 AND 0.3 THEN CONCAT('SM1=',ROUND(IFNULL(a.`v1`/b.av1,0),2)) 
								WHEN IFNULL(a.`v1`/b.av1,0) <= 0 THEN CONCAT('Non1=',ROUND(IFNULL(a.`v1`/b.av1,0),2)) END AS kettotv1,
							ROUND(IFNULL(a.v2,0),2) AS v2,
							b.av2,
							CASE WHEN IFNULL(a.`v2`/b.av2,0) >= 0.7 THEN CONCAT('FM2=',ROUND(IFNULL(a.`v2`/b.av2,0),2)) 
								WHEN IFNULL(a.`v2`/b.av2,0) BETWEEN 0.3 AND 0.7  THEN CONCAT('NR2=',ROUND(IFNULL(a.`v2`/b.av2,0),2)) 
								WHEN IFNULL(a.`v2`/b.av2,0) BETWEEN 0 AND 0.3 THEN CONCAT('SM2=',ROUND(IFNULL(a.`v2`/b.av2,0),2)) 
								WHEN IFNULL(a.`v2`/b.av2,0) <= 0 THEN CONCAT('Non2=',ROUND(IFNULL(a.`v2`/b.av2,0),2)) END AS kettotv2,
							ROUND(IFNULL(a.v3,0),2) AS v3,
							b.av3,
							CASE WHEN IFNULL(a.`v3`/b.av3,0) >= 0.7 THEN CONCAT('FM3=',ROUND(IFNULL(a.`v3`/b.av3,0),2)) 
								WHEN IFNULL(a.`v3`/b.av3,0) BETWEEN 0.3 AND 0.7  THEN CONCAT('NR3=',ROUND(IFNULL(a.`v3`/b.av3,0),2)) 
								WHEN IFNULL(a.`v3`/b.av3,0) BETWEEN 0 AND 0.3 THEN CONCAT('SM3=',ROUND(IFNULL(a.`v3`/b.av3,0),2)) 
								WHEN IFNULL(a.`v3`/b.av3,0) <= 0 THEN CONCAT('Non3=',ROUND(IFNULL(a.`v3`/b.av3,0),2)) END AS kettotv3
						FROM mstlogmonth a 
					LEFT JOIN (
						SELECT prinsipal,IFNULL(SUM(v1),0) AS av1,IFNULL(SUM(v2),0) AS av2,IFNULL(SUM(v3),0) AS av3
							FROM mstlogmonth WHERE prinsipal IN (
										SELECT prinsipal FROM mstlogmonth WHERE kodecabang='$cabang')
										AND kodecabang='$cabang'
						GROUP BY prinsipal		
					)b ON a.`prinsipal`=b.prinsipal
					WHERE a.kodecabang='$cabang' AND a.kodeproduk='$kode'
					ORDER BY a.`prinsipal`,a.`produk`";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kettotv1].$data[kettotv2].$data[kettotv3];
	}else{
		$hasil = "NM";
	}
	return $hasil;
}



function cek_prinsipal_nonsp($kode,$kodebarang){
	$table = "pembelian_usulan";

	if (stripos($kode, "PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}


	$sql	= "SELECT a.`kode_beli`,a.`kode_barang`,b.`kodeprinsipal`,c.`kodeprinsipal`,d.namaprinsipal FROM $table a
					LEFT JOIN `mstproduk` b ON a.`kode_barang`=b.`kodeproduk`
					LEFT JOIN `mstprinsipal_sp` c ON b.`kodeprinsipal`=c.`kodeprinsipal` AND a.`cabang`=c.`cabang`
					LEFT JOIN `mstprinsipal` d ON b.`kodeprinsipal`=d.`kodeprinsipal`
					WHERE kode_beli='$kode' AND a.`kode_barang` = '$kodebarang' HAVING c.`kodeprinsipal` IS NOT NULL";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= "Prins".$data[namaprinsipal]."NoBuy";
	}else{
		$hasil = "";
	}
	return $hasil;
}


function app_kepala_gud($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT status_apv_gud FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[status_apv_gud];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function app_apt($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT status_apv_apt FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[status_apv_apt];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function app_apt_pst($kode){

	$table = "po_pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "po_pembelian_psi";
	}

	$sql	= "SELECT status_apv_apt FROM $table WHERE kodepo_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[status_apv_apt];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function app_bm($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT status_apv_bm FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[status_apv_bm];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function app_rbm($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	
	$sql	= "SELECT status_apv_rbm FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[status_apv_rbm];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function app_bod($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	
	$sql	= "SELECT status_apv_bod FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[status_apv_bod];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function cari_nama_kgudang($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika`,c.kepalagudang,c.bmanager FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[kepalagudang];
	}else{
		$hasil = '-';
	}
	return $hasil;
}

function cari_nama_bmanager($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}

	$sql	= "SELECT c.NmCabang,c.izin,c.alamat,c.`fax`,c.`apoteker`,c.`sika`,c.kepalagudang,c.bmanager FROM $table a 
					LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
					LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
					WHERE a.kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[bmanager];
	}else{
		$hasil = '-';
	}
	return $hasil;
}



function cari_nama_rbm($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}

	$sql	= "SELECT user_apv_rbm,nama_lengkap FROM $table 
				LEFT JOIN admins ON user_apv_rbm=username 
				WHERE kode_beli='$kode'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[nama_lengkap];
	}else{
		$hasil = '-';
	}
	return $hasil;
}


function tgl_kepala_gud($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT date_apv_gud FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[date_apv_gud];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function tgl_apt($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT date_apv_apt FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[date_apv_apt];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function tgl_apt_pst($kode){

	$table = "po_pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	$sql	= "SELECT date_apv_apt FROM $table WHERE kodepo_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[date_apv_apt];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function tgl_bm($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT date_apv_bm FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[date_apv_bm];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function tgl_rbm($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	
	$sql	= "SELECT date_apv_rbm FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[date_apv_rbm];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function tgl_bod($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	
	$sql	= "SELECT date_apv_bod FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[date_apv_bod];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function tgl_buat_po($kode){

	$table = "po_pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	
	$sql	= "SELECT tgl_buat FROM $table WHERE kodepo_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[tgl_buat];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function user_buat_po($kode){

	$table = "po_pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "po_pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "po_pembelian_psi";
	}
	
	$sql	= "SELECT user_buat FROM $table WHERE kodepo_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[user_buat];
	}else{
		$hasil = "";
	}
	return $hasil;
}

function fsm($kodebarang,$kodecabang){

	
	$sql	= "SELECT fsm FROM mstproduk_fsm WHERE cabang='$kodecabang' AND kode_barang='$kodebarang' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		if ($data[fsm]='F'){
			$hasil	= "FMov";
		}
		if ($data[fsm]='S'){
			$hasil	= "SMov";
		}
		
	}else{
		$hasil = "";
	}
	return $hasil;
}

function maxratio($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT MAX(ratio) AS ratio FROM $table WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[ratio];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cek_hratio($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$sql	="SELECT COALESCE(ROUND(((`jumlah_beli` + `stok` )/`averg`),2),0) AS HRasio FROM $table WHERE kode_beli='$kode'";
												
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[HRasio];
	}else{
		$hasil = 0;
	}
	return $hasil;
}	

function maksratiocab($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}
	if (stripos($kode, "UP") !== false){ 
		$table = "pembelian_usulan";
	}
	if (stripos($kode, "UP/PRE") !== false){ 
		$table = "pembelian_usulan_pre";
	}
	if (stripos($kode, "UP/PSI") !== false){ 
		$table = "pembelian_usulan_psi";
	}
	$sql	= "SELECT ratiomaks FROM $table 
					LEFT JOIN mastercabang ON cabang=kdcabang 
					WHERE kode_beli='$kode' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[ratiomaks];
	}else{
		$hasil = "";
	}
	return $hasil;
}


function detail_pr($kode){

	$table = "pembelian";
	if (stripos($kode, "SP/PRE") !== false){ 
		$table = "pembelian_pre";
	}
	if (stripos($kode, "SP/PSI") !== false){ 
		$table = "pembelian_psi";
	}

	$q = mysql_query("SELECT A.kode_beli AS `KODEBELI`,TGL_BELI AS `TGLBELI`,B.namasupplier AS `NAMASUPPLIER`,
						C.namaproduk AS `NAMAPRODUK` ,DISKON,jumlah_beli AS `JUMLAHBELI`,RATIO,STOK,AVERG,A.SATUAN,
						ket_prinsipal AS `KETPRINSIPAL` FROM $table A
						LEFT JOIN mstsupplier2 B ON A.kode_supplier=B.kodesupplier
						LEFT JOIN MSTPRODUK C ON A.kode_barang=C.kodeproduk WHERE kode_beli='$kode'");
	$v = '{"info" : [';
		while($r=mysql_fetch_array($q))
		{
			$ob = array("<br>","<b>","</b>");
			if(strlen($v)<12)
			{
				$v .= '{"A" : "'.$r['NAMASUPPLIER'].'","B" : "'.$r['NAMAPRODUK'].'", "C" : "'.$r['DISKON'].'",
						"D" : "'.$r['JUMLAHBELI'].'", "E" : "'.$r['RATIO'].'","F" : "'.$r['STOK'].'", 
						"G" : "'.$r['AVERG'].'", "H" : "'.$r['SATUAN'].'","I" : "'.$r['KETPRINSIPAL'].'"}';
			}
			else
			{
				$v .= ',{"A" : "'.$r['NAMASUPPLIER'].'","B" : "'.$r['NAMAPRODUK'].'", "C" : "'.$r['DISKON'].'",
						"D" : "'.$r['JUMLAHBELI'].'", "E" : "'.$r['RATIO'].'","F" : "'.$r['STOK'].'", 
						"G" : "'.$r['AVERG'].'", "H" : "'.$r['SATUAN'].'","I" : "'.$r['KETPRINSIPAL'].'"}';
			}
			
		}
	$v .= ']}';
	
	 /* $v="SELECT A.kode_beli AS `KODEBELI`,TGL_BELI AS `TGLBELI`,B.namasupplier AS `NAMASUPPLIER`,
						C.namaproduk AS `NAMAPRODUK` ,DISKON,jumlah_beli AS `JUMLAHBELI`,RATIO,STOK,AVERG,A.SATUAN,
						ket_prinsipal AS `KETPRINSIPAL` FROM $table A
						LEFT JOIN mstsupplier2 B ON A.kode_supplier=B.kodesupplier
						LEFT JOIN MSTPRODUK C ON A.kode_barang=C.kodeproduk WHERE kode_beli='$kode'";  */
	return $v;
	}										

	
function hargatabprod($kodebarang){

	
	$sql	= "SELECT KODEPROD,NAMAPROD,HRGBELI,HRGJUAL FROM tabprod WHERE kodeprod ='$kodebarang' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
			$hasil	= "Hrg Rp. ".$data[HRGBELI]." ";
	}else{
		$hasil = "";
	}
	return $hasil;
}	

function diskontabprod($kodebarang){

	
	$sql	= "SELECT KODEPROD,NAMAPROD,HRGBELI,HRGJUAL,CASE WHEN KODEPROD LIKE 'EK%' THEN PRSNXTRA ELSE 0 END PRSNXTRA
				FROM tabprod WHERE kodeprod ='$kodebarang' LIMIT 1";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
			$hasil	= $data[PRSNXTRA];
	}else{
		$hasil = "";
	}
	return $hasil;
}	

function SaldoAwalStok($kode,$tgl,$cab)
{
	$mth = date('m', strtotime($tgl));
	$sql	= "SELECT SUM(SAwal$mth) AS unit,SUM(VAwal$mth) AS val,SUM(`Unit Stok`) as units, SUM(`Value Stok`) AS vals FROM `dinventorysummary` WHERE `Produk`='$kode' 
				AND `cabang`='$cab' GROUP BY `cabang`,`Produk`";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	$cekS = 'SAwal'.$mth ;
	if ($row>0){
		$data	= mysql_fetch_array($query);
		return array ($data[unit],$data[val],$cekS,$data[units],$data[vals],$sql);
	}else{
		return array (0,0,'',0,0);
	}
    //return $hasil;
}

function SaldoAwalStokGudang($kode,$tgl,$cab)
{
	$mth = date('m', strtotime($tgl));
	$sql	= "SELECT (SAwal$mth ) AS unit,(VAwal$mth) AS val, Gudang,`Unit Stok` as units, `Value Stok` AS vals FROM `dinventorysummary` WHERE `Produk`='$kode' 
				AND `cabang`='$cab'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	$cekS = 'SAwal'.$mth ;
	if ($row>0){
		$data	= mysql_fetch_array($query);
		return array ($data[unit],$data[val],$data[Gudang],$cekS,$data[units],$data[vals]);
	}else{
		return array (0,0,'','',0,0);
	}
    return $hasil;
}


function kgJual($kode,$tgl,$cab)
{
	$sql	= "SELECT Pelanggan,`Nama Faktur` as NF,`No Faktur` AS NoDok,DATE(Tanggal) AS Tanggal,IFNULL((Banyak),0) AS jum,
					`Batch No` AS batch,DATE(`Exp Date`) AS expr FROM `dsalesdetail` 
					WHERE Cabang='$cab' AND Produk='$kode' AND `Tanggal`='$tgl' AND (`Status` = 'Faktur' OR `Status`='Retur')
					ORDER BY `Tanggal`,`Added Time`";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	
	if ($row>0){
		$data	= mysql_fetch_array($query);
		return array($data[Pelanggan],$data[NF],$data[Tanggal],$data[NoDok],$data[batch],$data[expr],$data[jum]);
		}else
		{
		return "";
		}
}


function kgBeli($kode,$tgl,$cab)
{
	$sql1	= "SELECT `Supplier` AS sup,`Produk` AS prod, DATE(`Tgl BPB`) AS Tanggal,`No BPB` AS NoDok,IFNULL(`Batch No`,'') AS batch,
					DATE(`Exp Date`) AS expd, (`Bonus`+`Qty Terima`) AS jum
				FROM dbpbdodetail WHERE Cabang='$cab' AND Produk='$kode' AND `Tgl BPB`='$tgl' 
				AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' OR `Status BPB` = 'BKB Retur'
				OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' OR `Status BPB` = 'BKB Relokasi'
				OR `Status BPB` = 'BKB Koreksi' OR `Status BPB` = 'BPB Koreksi')";
	 				
	$query1	= mysql_query($sql1);
	$row1	= mysql_num_rows($query1);
	
	if ($row1>0){
		$data1	= mysql_fetch_array($query1);
		return array($data1[sup],$data1[prod],$data1[Tanggal],$data1[NoDok],$data1[batch],$data1[expr],$data1[jum]);
		}else
		{
		return "";
		} 
}

function kgudang($kode,$tgl,$cab)
{
$tgl_awal = date('Y-m-01', strtotime($tgl));
$tgl_akhir = date('Y-m-t', strtotime($tgl));
	$sql1	= "SELECT *
					FROM(
					SELECT DATE(`Tgl BPB`) AS `Tanggal`, 
						`Supplier` AS Pelanggan,
						`No BPB`AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) AS `Mutasi`, 		
						`Time BPB` AS dTime,
						Produk
					FROM `dbpbdodetail`
					WHERE Cabang='$cab' AND Produk='$kode'
					AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' OR `Status BPB` = 'BKB Retur'
					OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' OR `Status BPB` = 'BKB Relokasi'
					OR `Status BPB` = 'BKB Koreksi' OR `Status BPB` = 'BPB Koreksi')
					UNION
					SELECT DATE(`Tanggal`) AS `Tanggal`,
						`Nama Faktur` AS Pelanggan,
						`No Faktur` AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 ) AS `Mutasi`, 
						`Time` AS dTime,
						Produk
					FROM `dsalesdetail` 
					WHERE Cabang='$cab' AND Produk='$kode'
					AND (`Status`='Faktur' OR `Status`='Retur')
					 )a
					 WHERE a.Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
					 ORDER BY a.Tanggal,a.dTime";
	 				
	$query1	= mysql_query($sql1);
	$row1	= mysql_num_rows($query1);
	
	if ($row1>0){
		$data1	= mysql_fetch_array($query1);
		return array($data1[Pelanggan],$data1[Produk],$data1[Tanggal],$data1[NoDok],$data1[batch],$data1[expr],$data1[Mutasi]);
		}else
		{
		return "";
		} 
}

function cabKirimRLK($kode,$cab)
{
	$sql1	= "SELECT Cabang,`No BPB` as bpb FROM `dbpbdodetail` WHERE `No PO String`='$kode'";
	 				
	$query1	= mysql_query($sql1);
	$row1	= mysql_num_rows($query1);
	
	if ($row1>0){
		$data1	= mysql_fetch_array($query1);
		return array ("Pengirim ".$data1[Cabang]." NoDok. ".$data1[bpb]);
		}else
		{
		return array("",$sql1);
		} 
}

function cabDataPsiPre($kode,$cab,$tgl_awal,$tgl_akhir,$batch,$expr)
	{	

	$sql1="SELECT * FROM( 
						SELECT 
							a.Produk,b.Produk AS NamaProduk,
							DATE(`Tgl BPB`) AS `Tanggal`, 
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN IFNULL(`Batch No`,'') 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN `No BPB` 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN 'PUSAT' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN `No BPB` 
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN 'PUSAT' 
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))*-1 
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN IFNULL(`Batch No`,'') 
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch,
							IFNULL(DATE(`Exp Date`),'') AS expr, 
							`Time BPB` AS dTime, `Counter BPB` AS counter, `Keterangan` AS Keterangan
						FROM `dbpbdodetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cab' AND a.Produk='$kode' AND a.`Batch No`= '$batch' AND a.`Exp Date`='$expr' AND
							b.Kategori IN ('Psikotropika','Precursor') AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
								OR `Status BPB` = 'BKB Retur' 
								OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
								OR `Status BPB` = 'BKB Relokasi' OR `Status BPB` = 'BKB Koreksi' 
								OR `Status BPB` = 'BPB Koreksi') AND `Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'
					UNION ALL 
						SELECT a.Produk,`namaproduk`,DATE(`Tanggal`) AS `Tanggal`,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN IFNULL(`Batch No`,'')			
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `No Faktur` 			
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `Nama Faktur`			
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0))) * -1			
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `No Faktur`
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `Nama Faktur` 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) ) 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN IFNULL(`Batch No`,'')
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch, 
							IFNULL(DATE(`Exp Date`),'') AS expr,
							`Time` AS dTime,`Counter` AS counter, '' AS Keterangan
						FROM `dsalesdetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cab' AND a.Produk='$kode' AND a.`Batch No`= '$batch' AND a.`Exp Date`='$expr' AND
						b.Kategori IN ('Psikotropika','Precursor') AND(`Status`='Faktur' OR `Status`='Retur') 
						AND Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'  
					)a 
					ORDER BY a.Produk,a.Tanggal,a.Counter,a.dTime;";

		// return "<br>".$sql1."<br>";

		$no=0 ;
		$query = mysql_query($sql1);
		$hasil = "";
		$tot = 0;
		while($r_data=mysql_fetch_array($query)){
			$no++;
			$tot = $tot + $r_data[JumlahMasuk] - $r_data[JumlahKeluar];
			$hasil .= "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[NamaProduk]</td>
					<td align='center'>$r_data[Tanggal]</td>
					<td align='center'></td>
					<td align='center'>$r_data[batch]</td>
					<td align='left'>$r_data[NoDokMasuk]</td>
					<td align='left'>$r_data[sumber]</td>
					<td align='center'>$r_data[JumlahMasuk]</td>
					<td align='center'>$r_data[BatchMasuk]</td>
					<td align='left'>$r_data[NoDokKeluar]</td>
					<td align='left'>$r_data[tujuan]</td>
					<td align='center'>$r_data[JumlahKeluar]</td>
					<td align='center'>$r_data[BatchKeluar]</td>
					<td align='center'></td>
					<td align='center'></td>
					<td align='center'>$r_data[expr]</td>
					</tr>";
		}
		
//		return $hasil;
		return array ($hasil,$tot,"<br>".$sql1."<br>");

	}	
	
function cabDataOOT($kode,$cab,$tgl_awal,$tgl_akhir,$batch,$expr)
	{	

	$sql1="SELECT * FROM( 
						SELECT 
							a.Produk,b.Produk AS NamaProduk,
							DATE(`Tgl BPB`) AS `Tanggal`, 
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN IFNULL(`Batch No`,'') 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN `No BPB` 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN 'PUSAT' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN '' 
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN `No BPB` 
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN 'PUSAT' 
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN ((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))*-1 
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status BPB` IN ('Open','DO','BPB Retur','BPB Relokasi','BPB Koreksi') THEN '' 
								WHEN `Status BPB`  IN ('BKB Retur','BKB Relokasi','BKB Koreksi') THEN IFNULL(`Batch No`,'') 
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch,
							IFNULL(DATE(`Exp Date`),'') AS expr, 
							`Time BPB` AS dTime, `Counter BPB` AS counter, `Keterangan` AS Keterangan
						FROM `dbpbdodetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cab' AND a.Produk='$kode' AND a.`Batch No`= '$batch' AND a.`Exp Date`='$expr' AND
							b.Kategori IN ('OOT') AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' 
								OR `Status BPB` = 'BKB Retur' 
								OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' 
								OR `Status BPB` = 'BKB Relokasi' OR `Status BPB` = 'BKB Koreksi' 
								OR `Status BPB` = 'BPB Koreksi') AND `Tgl BPB` BETWEEN '$tgl_awal' AND '$tgl_akhir'
					UNION ALL 
						SELECT a.Produk,`namaproduk`,DATE(`Tanggal`) AS `Tanggal`,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN IFNULL(`Batch No`,'')			
								ELSE '' END) AS BatchMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `No Faktur` 			
								ELSE '' END) AS NoDokMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN `Nama Faktur`			
								ELSE '' END) AS sumber,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN '' 
								WHEN `Status` IN ('Retur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0))) * -1			
								ELSE '' END) AS JumlahMasuk,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `No Faktur`
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS NoDokKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN `Nama Faktur` 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS tujuan,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN ((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) ) 
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS JumlahKeluar,
							(CASE 
								WHEN `Status` IN ('Faktur') THEN IFNULL(`Batch No`,'')
								WHEN `Status` IN ('Retur') THEN ''			
								ELSE '' END) AS BatchKeluar,
							IFNULL(`Batch No`,'') AS batch, 
							IFNULL(DATE(`Exp Date`),'') AS expr,
							`Time` AS dTime,`Counter` AS counter, '' AS Keterangan
						FROM `dsalesdetail` a
						LEFT JOIN (SELECT `Kode Produk`,`Produk`,`Kategori` FROM `mproduk`) b ON a.Produk=b.`Kode Produk`
						WHERE  a.Cabang = '$cab' AND a.Produk='$kode' AND a.`Batch No`= '$batch' AND a.`Exp Date`='$expr' AND
						b.Kategori IN ('OOT') AND(`Status`='Faktur' OR `Status`='Retur') 
						AND Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'  
					)a 
					ORDER BY a.Produk,a.Tanggal,a.Counter,a.dTime;";

		// return "<br>".$sql1."<br>";die;

		$no=0 ;
		$query = mysql_query($sql1);
		$hasil = "";
		$tot = 0;
		while($r_data=mysql_fetch_array($query)){
			$no++;
			$tot = $tot + $r_data[JumlahMasuk] - $r_data[JumlahKeluar];
			$hasil .= "<tr>
					<td align='center'>$no</td>
					<td align='left'>$r_data[NamaProduk]</td>
					<td align='center'>$r_data[Tanggal]</td>
					<td align='center'></td>
					<td align='center'>$r_data[batch]</td>
					<td align='left'>$r_data[NoDokMasuk]</td>
					<td align='left'>$r_data[sumber]</td>
					<td align='center'>$r_data[JumlahMasuk]</td>
					<td align='center'>$r_data[BatchMasuk]</td>
					<td align='left'>$r_data[NoDokKeluar]</td>
					<td align='left'>$r_data[tujuan]</td>
					<td align='center'>$r_data[JumlahKeluar]</td>
					<td align='center'>$r_data[BatchKeluar]</td>
					<td align='center'></td>
					<td align='center'></td>
					<td align='center'>$r_data[expr]</td>
					</tr>";
		}
		
//		return $hasil;
		return array ($hasil,$tot,"<br>".$sql1."<br>");

	}	
	
function cekRetBeli($cab,$kode,$prod,$batch)
{
	$sql	= "SELECT SUM(`qty_retur`+`qty_bns_retur`) AS jum  FROM `i_usulanreturbeli` WHERE `no_acu`='$kode' AND `kode_produk`='$prod'
					AND `batch`='$batch' AND `cabang`='$cab'";
					
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	$cekS = 'SAwal'.$mth ;
	if ($row>0){
		$data	= mysql_fetch_array($query);
		return array ($data[jum],$sql);
	}else{
		return array (0,$sql);
	}
}
?>
