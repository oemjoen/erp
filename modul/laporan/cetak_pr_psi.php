<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode	= $_GET[kode];
	
   		if(empty($kode)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
			$query = "SELECT * FROM pembelian_psi";
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
		}else{
			$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_psi a 
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
								LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
								WHERE a.kode_beli='$kode'";
				}	 
	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);

	$namacabang = cari_nama_cabang($kode);
	$ijinpbf = cari_ijin_pbf($kode);
	$alamatpbf = cari_alamat_pbf($kode);
	$telppbf = cari_telp_pbf($kode);
	$faxpbf = cari_fax_pbf($kode);
	$total_qty_pr = total_barang_pr($kode);
	$tanggal_pr = tgl_indo(tanggal_pr_pre($kode));
	
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{
	
	function Header() 
	{
	$kode	= $_GET[kode];
	$ijinpbf = cari_ijin_pbf($kode);
	$alamatpbf = cari_alamat_pbf($kode);
	$telppbf = cari_telp_pbf($kode);
	$faxpbf = cari_fax_pbf($kode);

	$this->Image('images/logo.jpg',10,10,17);
	$this->Ln(2);
	$this->SetLeftMargin(30);
    $this->SetFont('arial','B',15);
 	$this->Cell(0,5,'PT. SAPTA SARI TAMA',0,1,'L');
    $this->SetFont('arial','B',9);
	$this->Cell(0,5,'PEDAGANG BESAR FARMASI Izin PBF No.'.$ijinpbf,0,1,'L');
	$this->Cell(0,5,$alamatpbf,0,1,'L');
	$this->Cell(0,5,$telppbf.' - '.$faxpbf,0,1,'L');
	$this->SetLineWidth(1);
	$this->Line(10,37,220,37);
			

	}
	
	//Page footer
	function Footer()
	{
			$kode = $_GET[kode];
			//Position at 1.5 cm from bottom
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Page number
			$this->Cell(0,10,'Hal '.$this->PageNo().'/{nb}'.' - '.$kode,0,0,'C');
			//$this->$kode;
	}  	
	
	
	
	   function FancyTable(){
  	
			$kode	= $_GET[kode];   		
				if(empty($kode)){
							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang,b.kandungan,b.sediaan FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,b.kandungan,b.sediaan FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kode_beli='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,15,30,70,25,20,10,50);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){
							$popusatket = produk_pusat_po($data['kode_barang']);
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[5],5,$data['kode_barang'],1,0,'L',$fill);
						  $this->Cell($w[7],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[2],5,$data['kandungan'],1,0,'L',$fill);
						  $this->Cell($w[1],5,$data['sediaan'],1,0,'C',$fill);
						  $this->Cell($w[6],5,$data['satuan'],1,0,'L',$fill);
						  $this->Cell($w[6],5,number_format($data['jumlah_beli']),1,0,'C',$fill);
						  $this->Cell($w[3],5,terbilang($data['jumlah_beli']),1,0,'L',$fill);
						  $this->Cell($w[5],5,$data['ket_prinsipal']." ".$popusatket,1,0,'L',$fill);
						  $this->Ln();  
						  $no++;
						}
							$this->Cell(array_sum($w),0,'','T');
		}
	
	function FancyTable2(){
  	
			$kode	= $_GET[kode];   		
				if(empty($kode)){


							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po, a.averg, a.stok,a.ratio,  
										COALESCE(((a.`jumlah_beli` + a.`stok` )/a.`averg`),0) AS HRasio 
										FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po, a.averg, a.stok,a.ratio,
										COALESCE(ROUND(((a.`jumlah_beli` + a.`stok` )/a.`averg`),4),0) AS HRasio 
										FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kode_beli='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,15,50,10);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){
							$xxx = cek_status_moving_dua($data['kode_barang'],$data['cabang']);
							$xxy = cekoutstading_pr($data['kode_beli'],$data['kode_barang'],$data['cabang']);
							$xxz = produk_black_list($data['kode_barang'],$data['cabang']);
							$xxw = cek_prinsipal_nonsp($data['kode_beli'],$data['kode_barang']);
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[3],5,number_format($data['averg']),1,0,'L',$fill);
						  $this->Cell($w[3],5,number_format($data['stok']),1,0,'L',$fill);
						  $this->Cell($w[3],5,$data['ratio'],1,0,'L',$fill);
						  //$this->Cell($w[3],5,number_format($data['outstading_po']),1,0,'L',$fill);
						  $this->Cell($w[3],5,'',1,0,'L',$fill);
						  $this->Cell($w[3],5,$data['HRasio'],1,0,'L',$fill);
						  if ($data['HRasio'] > 3){$this->Cell($w[3],5,'OverSt',0,0,'L',$fill);}
						  if(!empty($xxy)){$this->Cell($w[3],5,cekoutstading_pr($data['kode_beli'],$data['kode_barang'],$data['cabang']),0,0,'L',$fill);}						  
						  if(!empty($xxz)){$this->Cell($w[3],5,produk_black_list($data['kode_barang'],$data['cabang']),0,0,'L',$fill);}
						  if(!empty($xxw)){$this->Cell(22,5,cek_prinsipal_nonsp($data['kode_beli'],$data['kode_barang']),0,0,'L',$fill);}						  
						  if(!empty($xxx)){$this->Cell($w[3],5,fsm($data['kode_barang'],$data['cabang']),0,0,'L',$fill);}
						  $this->Ln();  
						  $no++;
						}
							$this->Cell(array_sum($w),0,'','T');
		}


		function FancyTable3(){
  	
			$kode	= $_GET[kode];   		
				if(empty($kode)){
							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kode_beli='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,15,18);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){
						  $this->SetFont('arial','',7);
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[1],5,$data['kode_barang'],1,0,'C',$fill);
						  $this->SetFont('arial','',6);
						  $this->Cell($w[2],5,cabang1($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang2($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang3($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang4($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang5($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang6($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang7($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang8($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang9($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang10($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Ln();  
						  $no++;
						}
							$this->Cell(array_sum($w),0,'','T');
		}

		function FancyTable4(){
			//echo "fancy4";
			$kode	= $_GET[kode];   		
				if(empty($kode)){


							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po,  
										COALESCE(((a.`jumlah_beli` + a.`stok` + a.`outstading_po`)/a.`averg`),0) AS HRasio 
										FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po,  
										COALESCE(ROUND(((a.`jumlah_beli` + a.`stok` + a.`outstading_po`)/a.`averg`),4),0) AS HRasio 
										FROM pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kode_beli='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,15,50,10,30);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){
							$subtotal = (produk_harga($data['kode_barang']) * $data['jumlah_beli']);
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[1],5,$data['kode_barang'],1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[3],5,number_format($data['jumlah_beli']),1,0,'C',$fill);
						  $this->Cell($w[1],5,number_format(produk_harga($data['kode_barang'])),1,0,'R',$fill);
						  $this->Cell($w[4],5,number_format(produk_harga($data['kode_barang']) * $data['jumlah_beli']),1,0,'R',$fill);
						  if (($data['kodeprinsipal']=='5'))
						  {
							$this->Cell($w[4],5,produk_boleh_jual($data['kode_barang'],$data['cabang']).$data['kodeprinsipal'],0,0,'L',$fill);
						  }
						  if (($data['kodeprinsipal']=='6'))
						  {
							$this->Cell($w[4],5,produk_boleh_jual($data['kode_barang'],$data['cabang']).$data['kodeprinsipal'],0,0,'L',$fill);
						  }
						  if (($data['kodeprinsipal']=='7'))
						  {
							$this->Cell($w[4],5,produk_boleh_jual($data['kode_barang'],$data['cabang']).$data['kodeprinsipal'],0,0,'L',$fill);
						  }
						  if (($data['kodeprinsipal']=='8'))
						  {
							$this->Cell($w[4],5,produk_boleh_jual($data['kode_barang'],$data['cabang']).$data['kodeprinsipal'],0,0,'L',$fill);
						  }
						  $this->Ln();  
						  $no++;
						  $totalharga = $totalharga + $subtotal;
						}
						  $this->Cell(135,5,"Total  = Rp. ".number_format($totalharga),0,1,'R',$fill);
						  //$this->Cell(array_sum($w),0,'','T');
		}
		
	}
	
    //Instanciation of inherited class
	$A4[0]=210;
	$A4[1]=297;
	$Q[0]=216;
	$Q[1]=279;
	$A[0]=235;
	$A[1]=300;
    $pdf=new PDF('P','mm',$A);
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetTitle('PR PSIKOTROPIKA');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
	$tgl = $tanggal_pr;
	$pdf->Ln(10);
	$pdf->SetLeftMargin(10);
    $pdf->SetFont('arial','BU',10);
    $pdf->Cell(0,5,'SURAT PESANAN PSIKOTROPIKA',0,1,'C');
    $pdf->SetFont('arial','B',10);
	
    $pdf->Cell(110,5,'No. PR  : '.$kode,0,0,'R');
    $pdf->Cell(110,5,'No. PO  : - ',0,1,'L');
	
	$pdf->Ln(5);
	
    $pdf->SetFont('arial','',9);
    $pdf->Cell(0,5,'Yang bertanda tangan dibawah ini :',0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(30,5,'Nama',0,0,'L');
    $pdf->Cell(30,5,': '.cari_nama_apoteker($kode),0,1,'L');
    $pdf->Cell(30,5,'Alamat',0,0,'L');
    $pdf->Cell(30,5,': '.cari_alamat_apt($kode),0,1,'L');    
	$pdf->Cell(30,5,'Jabatan',0,0,'L');
    $pdf->Cell(30,5,': Apoteker Penanggung Jawab PT. Sapta Sari Tama Cabang '.cari_nama_cabang($kode),0,1,'L');
    $pdf->Cell(30,5,'SIKA',0,0,'L');
    $pdf->Cell(30,5,': '.cari_sika($kode),0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(0,5,'Mengajukan permohonan, kepada :',0,1,'L');
    $pdf->Cell(30,5,'Nama',0,0,'L');
    $pdf->Cell(30,5,': PT. Sapta Sari Tama Pusat',0,1,'L');
    $pdf->Cell(30,5,'Alamat',0,0,'L');
    $pdf->Cell(30,5,': Jln. Caringin No. 254 A Bandung',0,1,'L');
    $pdf->Cell(30,5,'Telp',0,0,'L');
    $pdf->Cell(30,5,': 022-6026306 / 022-6026310',0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(0,5,'Jenis obat yang mengandung Psikotropika yang dipesan adalah :',0,1,'L');
	

	$pdf->Ln(2);
	$pdf->SetLeftMargin(2);
	$pdf->SetFont('arial','B',7);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->SetX(2);	
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(20,5,'Kode Barang',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(30,5,'Zat Aktif Psikotropika',1,0,'C',true);
	$pdf->Cell(15,5,'Bentuk',1,0,'C',true);
	$pdf->Cell(10,5,'Satuan',1,0,'C',true);
	$pdf->Cell(10,5,'Qty',1,0,'C',true);
	$pdf->Cell(70,5,'Terbilang',1,0,'C',true);
	$pdf->Cell(20,5,'Keterangan',1,1,'C',true);
	
	$pdf->SetFont('arial','B',7);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable();	
	$pdf->Ln(1);


	$pdf->SetLeftMargin(10);
	$pdf->SetFont('arial','B',6);	
	$pdf->Cell(30,5,'*Setiap pengiriman di Surat Jalan harap mencantumkan Nomor PO dan PR',0,1,'L',false);
	
	$pdf->Ln(2);
	$pdf->SetFont('arial','',9);
    $pdf->Cell(0,5,'Obat mengandung Psikotropika tersebut akan digunakan untuk memenuhi kebutuhan :',0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(30,5,'Nama PBF',0,0,'L');
    $pdf->Cell(30,5,': PT. Sapta Sari Tama Cabang '.cari_nama_cabang($kode),0,1,'L');
    $pdf->Cell(30,5,'Alamat PBF',0,0,'L');
    $pdf->Cell(30,5,': '.$alamatpbf,0,1,'L');
    $pdf->Cell(30,5,'Nomor Izin PBF',0,0,'L');
    $pdf->Cell(30,5,': '.$ijinpbf,0,1,'L');

	$pdf->Ln(2);
	$pdf->SetFont('arial','',9);
    $pdf->Cell(0,5,'Untuk Keperluan '.keperluan_pr_pre($kode),0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(30,5,'Nama',0,0,'L');
    $pdf->Cell(30,5,': '.keper_nama_pr_pre($kode),0,1,'L');
    $pdf->Cell(30,5,'Alamat',0,0,'L');
    $pdf->Cell(30,5,': '.keper_alm_pr_pre($kode),0,1,'L');
	
	
	$pdf->Ln(5);
	$pdf->SetFont('arial','',8);	
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,cari_kota_pr($kode).", ".$tanggal_pr,0,1,'C',false);
	$pdf->Ln(2);
	$pdf->Cell(73,3,'Mengetahui, ',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'Pemesan, ',0,1,'C',false);
	$pdf->Cell(73,3,'Branch Manager',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'Apoteker Penanggung Jawab',0,1,'C',false);

//BANDUNG	
	if(app_apt($kode)=='Y' && $namacabang=='BANDUNG'){$pdf->Image('images/aptbandung.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='BANDUNG'){$pdf->Image('images/bmbandung.png',30,$pdf->GetY()-3,35);}
//LAMPUNG	
	if(app_apt($kode)=='Y' && $namacabang=='LAMPUNG'){$pdf->Image('images/aptlampung.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='LAMPUNG'){$pdf->Image('images/bmlampung.png',30,$pdf->GetY()-3,35);}
//BOGOR	
	if(app_apt($kode)=='Y' && $namacabang=='BOGOR'){$pdf->Image('images/aptbogor.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='BOGOR'){$pdf->Image('images/bmbogor.png',30,$pdf->GetY()-3,35);}
//BANJARMASIN	
		if(app_apt($kode)=='Y' && $namacabang=='BANJARMASIN'){$pdf->Image('images/aptbanjarmasin.png',175,$pdf->GetY()-5,35);}		
		if(app_bm($kode)=='Y' && $namacabang=='BANJARMASIN'){$pdf->Image('images/bmbanjarmasin.png',30,$pdf->GetY()-3,35);}
//CIREBON	
	if(app_apt($kode)=='Y' && $namacabang=='CIREBON'){$pdf->Image('images/aptcirebon.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='CIREBON'){$pdf->Image('images/bmcirebon.png',30,$pdf->GetY()-3,35);}
//DENPASAR	
	if(app_apt($kode)=='Y' && $namacabang=='DENPASAR'){$pdf->Image('images/aptdenpasar.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='DENPASAR'){$pdf->Image('images/bmdenpasar.png',30,$pdf->GetY()-3,35);}
//JEMBER	
	if(app_apt($kode)=='Y' && $namacabang=='JEMBER'){$pdf->Image('images/aptjember.png',175,$pdf->GetY()-3,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='JEMBER'){$pdf->Image('images/bmjember.png',30,$pdf->GetY()-3,35);}
//JAKARTA 1	
	if(app_apt($kode)=='Y' && $namacabang=='JAKARTA 1'){$pdf->Image('images/aptjakarta1.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='JAKARTA 1'){$pdf->Image('images/bmjakarta1.png',30,$pdf->GetY()-3,35);}
//JAKARTA 2	
	if(app_apt($kode)=='Y' && $namacabang=='JAKARTA 2'){$pdf->Image('images/aptjakarta2.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='JAKARTA 2'){$pdf->Image('images/bmjakarta2.png',30,$pdf->GetY()-3,35);}
//JAMBI	
	if(app_apt($kode)=='Y' && $namacabang=='JAMBI'){$pdf->Image('images/aptjambi.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='JAMBI'){$pdf->Image('images/bmjambi.png',30,$pdf->GetY()-3,35);}
//KUPANG	
	if(app_apt($kode)=='Y' && $namacabang=='KUPANG'){$pdf->Image('images/aptkupang.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='KUPANG'){$pdf->Image('images/bmkupang.png',30,$pdf->GetY()-3,35);}
//MEDAN	
	if(app_apt($kode)=='Y' && $namacabang=='MEDAN'){$pdf->Image('images/aptmedan.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='MEDAN'){$pdf->Image('images/bmmedan.png',30,$pdf->GetY()-3,35);}
//MAKASAR	
	if(app_apt($kode)=='Y' && $namacabang=='MAKASAR'){$pdf->Image('images/aptmakasar.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='MAKASAR'){$pdf->Image('images/bmmakasar.png',30,$pdf->GetY()-3,35);}
//MALANG	
	if(app_apt($kode)=='Y' && $namacabang=='MALANG'){$pdf->Image('images/aptmalang.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='MALANG'){$pdf->Image('images/bmmalang.png',30,$pdf->GetY()-3,35);}
//MATARAM	
	if(app_apt($kode)=='Y' && $namacabang=='MATARAM'){$pdf->Image('images/aptmataram.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='MATARAM'){$pdf->Image('images/bmmataram.png',30,$pdf->GetY()-3,35);}
//ACEH	
	if(app_apt($kode)=='Y' && $namacabang=='ACEH'){$pdf->Image('images/aptaceh.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='ACEH'){$pdf->Image('images/bmaceh.png',30,$pdf->GetY()-3,35);}
//PADANG	
	if(app_apt($kode)=='Y' && $namacabang=='PADANG'){$pdf->Image('images/aptpadang.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='PADANG'){$pdf->Image('images/bmpadang.png',30,$pdf->GetY()-3,35);}
//PEKANBARU	
	if(app_apt($kode)=='Y' && $namacabang=='PEKANBARU'){$pdf->Image('images/aptpekanbaru.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='PEKANBARU'){$pdf->Image('images/bmpekanbaru.png',30,$pdf->GetY()-3,35);}
//PONTIANAK	
	if(app_apt($kode)=='Y' && $namacabang=='PONTIANAK'){$pdf->Image('images/aptpontianak.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='PONTIANAK'){$pdf->Image('images/bmpontianak.png',30,$pdf->GetY()-3,35);}
//PURWOKERTO	
	if(app_apt($kode)=='Y' && $namacabang=='PURWOKERTO'){$pdf->Image('images/aptpurwokerto.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='PURWOKERTO'){$pdf->Image('images/bmpurwokerto.png',30,$pdf->GetY()-3,35);}
//SURABAYA	
	if(app_apt($kode)=='Y' && $namacabang=='SURABAYA'){$pdf->Image('images/aptsurabaya.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SURABAYA'){$pdf->Image('images/bmsurabaya.png',30,$pdf->GetY()-3,35);}
//SOLO	
	if(app_apt($kode)=='Y' && $namacabang=='SOLO'){$pdf->Image('images/aptsolo.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='SOLO'){$pdf->Image('images/bmsolo.png',30,$pdf->GetY()-3,35);}
//SEMARANG	
	if(app_apt($kode)=='Y' && $namacabang=='SEMARANG'){$pdf->Image('images/aptsemarang.png',175,$pdf->GetY()-5,35);}		
	if(app_bm($kode)=='Y' && $namacabang=='SEMARANG'){$pdf->Image('images/bmsemarang.png',30,$pdf->GetY()-3,35);}
//SERANG	
	if(app_apt($kode)=='Y' && $namacabang=='SERANG'){$pdf->Image('images/aptserang.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SERANG'){$pdf->Image('images/bmserang.png',30,$pdf->GetY()-3,35);}
//MANADO	
	if(app_apt($kode)=='Y' && $namacabang=='MANADO'){$pdf->Image('images/aptmanado.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='MANADO'){$pdf->Image('images/bmmanado.png',30,$pdf->GetY()-3,35);}
//PALEMBANG	
	if(app_apt($kode)=='Y' && $namacabang=='PALEMBANG'){$pdf->Image('images/aptpalembang.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='PALEMBANG'){$pdf->Image('images/bmpalembang.png',30,$pdf->GetY()-3,35);}
//SAMARINDA	
	if(app_apt($kode)=='Y' && $namacabang=='SAMARINDA'){$pdf->Image('images/aptsamarinda.png',175,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SAMARINDA'){$pdf->Image('images/bmsamarinda.png',30,$pdf->GetY()-3,35);}
//PUSAT	
//	if(app_kepala_gud($kode)=='Y' && $namacabang=='PUSAT'){$pdf->Image('images/gudpusat.png',28,$pdf->GetY(),20);}	if(app_apt($kode)=='Y' && $namacabang=='PUSAT'){$pdf->Image('images/aptpusat.png',70,$pdf->GetY(),35);}	if(app_bm($kode)=='Y' && $namacabang=='PUSAT'){$pdf->Image('images/bmpusat.png',120,$pdf->GetY(),35);}


	$pdf->Ln(10);
	$pdf->SetFont('arial','BU',8);
	$pdf->Cell(73,3,cari_nama_bmanager($kode),0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,cari_nama_apoteker($kode),0,1,'C',false);
	$pdf->SetFont('arial','',8);
	$pdf->Cell(73,3,'Cap & Nama',0,0,'C',false);
	$pdf->Cell(73,3,' ',0,0,'C',false);
	$pdf->Cell(73,3,'SIK No : '.cari_sika($kode),0,1,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'Cap & Nama',0,1,'C',false);
	
	$pdf->SetFont('arial','I',5);
	$pdf->Cell(73,3,'*'.tgl_bm($kode),0,0,'L',false);
	$pdf->Cell(73,3,'',0,0,'L',false);
	$pdf->Cell(73,3,'*'.tgl_bm($kode),0,1,'L',false);

	
//keterangan tambahan	
	
	$pdf->AddPage();//page break
	$pdf->SetLeftMargin(10);
	$pdf->Ln(10);
	$pdf->SetFont('arial','B',7);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(10,5,'AVG',1,0,'C',true);
	$pdf->Cell(10,5,'Stok',1,0,'C',true);
	$pdf->Cell(10,5,'Ratio',1,0,'C',true);
	$pdf->Cell(10,5,'PO',1,0,'C',true);
	$pdf->Cell(10,5,'HRatio',1,1,'C',true);
	
	$pdf->SetFont('arial','',6);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable2();	
	$pdf->Ln(1);
	
	$pdf->Ln(5);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(15,5,'BKode',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(10,5,'Qty',1,0,'C',true);
	$pdf->Cell(15,5,'Harga',1,0,'C',true);
	$pdf->Cell(30,5,'Sub Total',1,1,'C',true);
	$pdf->SetFont('arial','',7);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable4();	
	$pdf->Ln(1);	

//keterangan tambahan	
	$pdf->Ln(3);
	$pdf->SetFont('arial','B',7);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(15,5,'BKode',1,0,'C',true);
	$pdf->Cell(18,5,namacabang1($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang2($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang3($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang4($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang5($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang6($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang7($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang8($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang9($kode),1,0,'C',true);
	$pdf->Cell(18,5,namacabang10($kode),1,1,'C',true);
	$pdf->SetFont('arial','',6);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable3();	
	$pdf->Ln(1);

	
	$pdf->Output('Cetak_PR_'.$kode.'.pdf','D'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
  }
?>