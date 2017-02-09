<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode	= $_GET[kode];
	
   		if(empty($kode)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
			$query = "SELECT * FROM purchase_detail_report";
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
		}else{
			$query 	= "SELECT * FROM purchase_detail_report WHERE No_PR='$kode'";
				}	 
	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);

	$namacabang = cari_nama_cabang($kode);
	$total_qty_pr = total_barang_pr($kode);
	$tanggal_pr = tgl_indo(tanggal_pr($kode));
	
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{
	   function FancyTable(){
  	
			$kode	= $_GET[kode];   		
				if(empty($kode)){
							$query = "SELECT * FROM purchase_detail_report";
					}else{
							$query 	= "SELECT * FROM purchase_detail_report WHERE No_PR='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(10,15,15,50,25,65,15);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){
							$popusatket = produk_pusat_po($data['kode_barang']);
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[1],5,number_format($data['Qty_PR']),1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['Satuan'],1,0,'L',$fill);
						  $this->Cell($w[3],5,$data['Produk'],1,0,'L',$fill);
						  $this->Cell($w[4],5,/*$data['kode_barang']*/"",1,0,'L',$fill);
						  $this->Cell($w[6],5,/*$data['diskon']*/"",1,0,'C',$fill);
						  $this->Cell($w[5],5,$data['Keterangan']." ".$popusatket,1,0,'L',$fill);
						  $this->Ln();  
						  $no++;
						}
							$this->Cell(array_sum($w),0,'','T');
		}
	
	function FancyTable2(){
  	
			$kode	= $_GET[kode];   		
				if(empty($kode)){


							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po,  
										COALESCE(((a.`jumlah_beli` + a.`stok` )/a.`averg`),2) AS HRasio 
										FROM pembelian a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po,  
										COALESCE(ROUND(((a.`jumlah_beli` + a.`stok` )/a.`averg`),2),0) AS HRasio 
										FROM pembelian a 
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
							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian a 
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
										FROM pembelian a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po,  
										COALESCE(ROUND(((a.`jumlah_beli` + a.`stok` + a.`outstading_po`)/a.`averg`),4),0) AS HRasio 
										FROM pembelian a 
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
							$diskon = diskontabprod($data['kode_barang']);
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[1],5,$data['kode_barang'],1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[3],5,number_format($data['jumlah_beli']),1,0,'C',$fill);
						  //$this->Cell($w[1],5,$diskon,1,0,'R',$fill);
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
	$A4L[0]=220;
	$A4L[1]=297;
    $pdf=new PDF('P','mm',$A4L);
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetTitle('PR RUTIN');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
	$tgl = $tanggal_pr;
	$pdf->Image('images/logo_sp.png',10,10,100);
	$pdf->Ln(2);
    $pdf->SetFont('arial','B',8);
    $pdf->Cell(190,5,'Kepada Yth: ',0,1,'R');
	$pdf->Cell(190,5,'PT. SAPTASARITAMA PUSAT',0,1,'R');
	$pdf->Cell(190,5,'BANDUNG',0,1,'R');
	$pdf->Ln(4);
    $pdf->SetFont('arial','B',9);
	$pdf->Cell(60,5,'Harap dikirim kecabang :  ',0,0,'L');
    $pdf->SetFont('arial','B',12);
	$pdf->Cell(60,5,$namacabang,0,1,'L');
	$pdf->Cell(60,5,"",0,1,'L');
    $pdf->SetFont('arial','B',9);
    $pdf->Cell(0,5,'SURAT PESANAN',0,1,'C');
	$pdf->SetX(10);
    $pdf->SetFont('arial','B',12);
    $pdf->Cell(100,5,'No. PR        : '.$kode,0,1,'L');
    $pdf->Cell(100,5,'No. PO        : -',0,1,'L');
    $pdf->Cell(0,5,'Tanggal      : '.$tgl,0,1,'L');
	$pdf->Ln(1);
	$pdf->SetFont('arial','B',9);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(10,5,'No.',1,0,'C',true);
	$pdf->Cell(15,5,'Qty',1,0,'C',true);
	$pdf->Cell(15,5,'Satuan',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(25,5,'Kode Barang',1,0,'C',true);
	$pdf->Cell(15,5,'Disc(%)',1,0,'C',true);
	$pdf->Cell(65,5,'Keterangan',1,1,'C',true);
	$pdf->SetFont('arial','',7);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable();	
	$pdf->Ln(1);
	$pdf->SetFont('arial','B',7);	
	$pdf->Cell(30,5,'*Setiap pengiriman di Surat Jalan harap mencantumkan Nomor PO dan PR',0,1,'L',false);
	$pdf->Ln(1);
	$pdf->SetFont('arial','',7);	
	
	$pdf->Cell(50,3,'Penerima Pesanan,',0,0,'C',false);
	$pdf->Cell(50,3,'Pemesan,',0,0,'C',false);
	$pdf->Cell(50,3,'Mengetahui, ',0,0,'C',false);
	$pdf->Cell(50,3,'Mengetahui, ',0,1,'C',false);
	
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'Apoteker',0,0,'C',false);
	$pdf->Cell(50,3,'Business Manager',0,0,'C',false);
	$pdf->Cell(50,3,'Regional Business Manager',0,1,'C',false);

//BANDUNG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='BANDUNG'){$pdf->Image('images/gudbandung.png',28,$pdf->GetY(),15);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='BANDUNG'){$pdf->Image('images/aptbandung.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='BANDUNG'){$pdf->Image('images/bmbandung.png',130,$pdf->GetY(),13);} 
	if(app_rbm($kode)=='Y' && $namacabang=='BANDUNG'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);} 
	if(app_bod($kode)=='Y' && $namacabang=='BANDUNG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//LAMPUNG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='LAMPUNG'){$pdf->Image('images/gudlampung.png',25,$pdf->GetY(),30);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='LAMPUNG'){$pdf->Image('images/aptlampung.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='LAMPUNG'){$pdf->Image('images/bmlampung.png',120,$pdf->GetY()-5,35);}
	//if(app_rbm($kode)=='Y' && $namacabang=='LAMPUNG'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);} 
	if(app_bod($kode)=='Y' && $namacabang=='LAMPUNG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//BOGOR	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='BOGOR'){$pdf->Image('images/gudbogor.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='BOGOR'){$pdf->Image('images/aptbogor.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='BOGOR'){$pdf->Image('images/bmbogor.png',125,$pdf->GetY()-5,20);} 
	if(app_rbm($kode)=='Y' && $namacabang=='BOGOR'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='BOGOR'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//BANJARMASIN	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='BANJARMASIN'){$pdf->Image('images/gudbanjarmasin.png',23,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='BANJARMASIN'){$pdf->Image('images/aptbanjarmasin.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='BANJARMASIN'){$pdf->Image('images/bmbanjarmasin.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='BANJARMASIN'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='BANJARMASIN'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//CIREBON	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='CIREBON'){$pdf->Image('images/gudcirebon.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='CIREBON'){$pdf->Image('images/aptcirebon.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='CIREBON'){$pdf->Image('images/bmcirebon.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='CIREBON'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='CIREBON'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//DENPASAR	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='DENPASAR'){$pdf->Image('images/guddenpasar.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='DENPASAR'){$pdf->Image('images/aptdenpasar.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='DENPASAR'){$pdf->Image('images/bmdenpasar.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='DENPASAR'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='DENPASAR'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//JEMBER	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='JEMBER'){$pdf->Image('images/gudjember.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='JEMBER'){$pdf->Image('images/aptjember.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='JEMBER'){$pdf->Image('images/bmjember.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='JEMBER'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='JEMBER'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//JAKARTA 1	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='JAKARTA 1'){$pdf->Image('images/gudjakarta1.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='JAKARTA 1'){$pdf->Image('images/aptjakarta1.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='JAKARTA 1'){$pdf->Image('images/bmjakarta1.png',120,$pdf->GetY(),20);}
//	if(app_rbm($kode)=='Y' && $namacabang=='JAKARTA 1'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='JAKARTA 1'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//JAKARTA 2	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='JAKARTA 2'){$pdf->Image('images/gudjakarta2.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='JAKARTA 2'){$pdf->Image('images/aptjakarta2.png',70,$pdf->GetY()-3,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='JAKARTA 2'){$pdf->Image('images/bmjakarta2.png',125,$pdf->GetY(),15);}
//	if(app_rbm($kode)=='Y' && $namacabang=='JAKARTA 2'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='JAKARTA 2'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//JAMBI	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='JAMBI'){$pdf->Image('images/gudjambi.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='JAMBI'){$pdf->Image('images/aptjambi.png',70,$pdf->GetY()-3,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='JAMBI'){$pdf->Image('images/bmjambi.png',125,$pdf->GetY(),20);}
//	if(app_rbm($kode)=='Y' && $namacabang=='JAMBI'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='JAMBI'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//KUPANG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='KUPANG'){$pdf->Image('images/gudkupang.png',28,$pdf->GetY(),25);}*/ 
	if(app_apt($kode)=='Y' && $namacabang=='KUPANG'){$pdf->Image('images/aptkupang.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='KUPANG'){$pdf->Image('images/bmkupang.png',123,$pdf->GetY()-3,20);} 
	if(app_rbm($kode)=='Y' && $namacabang=='KUPANG'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='KUPANG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//MEDAN	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='MEDAN'){$pdf->Image('images/gudmedan.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='MEDAN'){$pdf->Image('images/aptmedan.png',75,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='MEDAN'){$pdf->Image('images/bmmedan.png',125,$pdf->GetY(),15);}
//	if(app_rbm($kode)=='Y' && $namacabang=='MEDAN'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='MEDAN'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//MAKASAR	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='MAKASAR'){$pdf->Image('images/gudmakasar.png',28,$pdf->GetY(),15);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='MAKASAR'){$pdf->Image('images/aptmakasar.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='MAKASAR'){$pdf->Image('images/bmmakasar.png',130,$pdf->GetY(),15);} 
	if(app_rbm($kode)=='Y' && $namacabang=='MAKASAR'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='MAKASAR'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//MALANG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='MALANG'){$pdf->Image('images/gudmalang.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='MALANG'){$pdf->Image('images/aptmalang.png',70,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='MALANG'){$pdf->Image('images/bmmalang.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='MALANG'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='MALANG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//MATARAM	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='MATARAM'){$pdf->Image('images/gudmataram.png',28,$pdf->GetY(),35);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='MATARAM'){$pdf->Image('images/aptmataram.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='MATARAM'){$pdf->Image('images/bmmataram.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='MATARAM'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='MATARAM'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//ACEH	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='ACEH'){$pdf->Image('images/gudaceh.png',20,($pdf->GetY())-3,35);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='ACEH'){$pdf->Image('images/aptaceh.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='ACEH'){$pdf->Image('images/bmaceh.png',125,(($pdf->GetY())-5),25);}
//	if(app_rbm($kode)=='Y' && $namacabang=='ACEH'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='ACEH'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//PADANG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='PADANG'){$pdf->Image('images/gudpadang.png',28,$pdf->GetY()-3,20);}	*/
	if(app_apt($kode)=='Y' && $namacabang=='PADANG'){$pdf->Image('images/aptpadang.png',70,$pdf->GetY()-3,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='PADANG'){$pdf->Image('images/bmpadang.png',120,$pdf->GetY(),35);}
//	if(app_rbm($kode)=='Y' && $namacabang=='PADANG'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='PADANG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//PEKANBARU	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='PEKANBARU'){$pdf->Image('images/gudpekanbaru.png',28,$pdf->GetY(),30);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='PEKANBARU'){$pdf->Image('images/aptpekanbaru.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='PEKANBARU'){$pdf->Image('images/bmpekanbaru.png',120,$pdf->GetY()-3,35);}
//	if(app_rbm($kode)=='Y' && $namacabang=='PEKANBARU'){$pdf->Image('images/rbmrbmbarattimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='PEKANBARU'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//PONTIANAK	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='PONTIANAK'){$pdf->Image('images/gudpontianak.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='PONTIANAK'){$pdf->Image('images/aptpontianak.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='PONTIANAK'){$pdf->Image('images/bmpontianak.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='PONTIANAK'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='PONTIANAK'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//PURWOKERTO	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='PURWOKERTO'){$pdf->Image('images/gudpurwokerto.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='PURWOKERTO'){$pdf->Image('images/aptpurwokerto.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='PURWOKERTO'){$pdf->Image('images/bmpurwokerto.png',120,$pdf->GetY(),35);}
	if(app_rbm($kode)=='Y' && $namacabang=='PURWOKERTO'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='PURWOKERTO'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//SURABAYA	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='SURABAYA'){$pdf->Image('images/gudsurabaya.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='SURABAYA'){$pdf->Image('images/aptsurabaya.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SURABAYA'){$pdf->Image('images/bmsurabaya.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='SURABAYA'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='SURABAYA'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//SOLO	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='SOLO'){$pdf->Image('images/gudsolo.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='SOLO'){$pdf->Image('images/aptsolo.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SOLO'){$pdf->Image('images/bmsolo.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='SOLO'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='SOLO'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//SEMARANG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='SEMARANG'){$pdf->Image('images/gudsemarang.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='SEMARANG'){$pdf->Image('images/aptsemarang.png',75,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SEMARANG'){$pdf->Image('images/bmsemarang.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='SEMARANG'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='SEMARANG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//SERANG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='SERANG'){$pdf->Image('images/gudserang.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='SERANG'){$pdf->Image('images/aptserang.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SERANG'){$pdf->Image('images/bmserang.png',120,$pdf->GetY(),20);} 
	if(app_rbm($kode)=='Y' && $namacabang=='SERANG'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='SERANG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//MANADO	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='MANADO'){$pdf->Image('images/gudmanado.png',28,$pdf->GetY(),35);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='MANADO'){$pdf->Image('images/aptmanado.png',70,$pdf->GetY()-5,35);}	
	if(app_bm($kode)=='Y' && $namacabang=='MANADO'){$pdf->Image('images/bmmanado.png',120,$pdf->GetY(),35);} 
	if(app_rbm($kode)=='Y' && $namacabang=='MANADO'){$pdf->Image('images/rbmtengah.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='MANADO'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//PALEMBANG	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='PALEMBANG'){$pdf->Image('images/gudpalembang.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='PALEMBANG'){$pdf->Image('images/aptpalembang.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='PALEMBANG'){$pdf->Image('images/bmpalembang.png',120,$pdf->GetY(),35);}
//	if(app_rbm($kode)=='Y' && $namacabang=='PALEMBANG'){$pdf->Image('images/rbmbarat.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='PALEMBANG'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//SAMARINDA	
	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='SAMARINDA'){$pdf->Image('images/gudsamarinda.png',28,$pdf->GetY(),20);}*/	
	if(app_apt($kode)=='Y' && $namacabang=='SAMARINDA'){$pdf->Image('images/aptsamarinda.png',70,$pdf->GetY(),35);}	
	if(app_bm($kode)=='Y' && $namacabang=='SAMARINDA'){$pdf->Image('images/bmsamarinda.png',120,$pdf->GetY()-3,40);} 
	if(app_rbm($kode)=='Y' && $namacabang=='SAMARINDA'){$pdf->Image('images/rbmtimur.png',170,$pdf->GetY(),30);}
	if(app_bod($kode)=='Y' && $namacabang=='SAMARINDA'){$pdf->Image('images/agussusanto.png',190,$pdf->GetY(),30);}
//PUSAT	
//	/*if(app_kepala_gud($kode)=='Y' && $namacabang=='PUSAT'){$pdf->Image('images/gudpusat.png',28,$pdf->GetY(),20);}*/	if(app_apt($kode)=='Y' && $namacabang=='PUSAT'){$pdf->Image('images/aptpusat.png',70,$pdf->GetY(),35);}	if(app_bm($kode)=='Y' && $namacabang=='PUSAT'){$pdf->Image('images/bmpusat.png',120,$pdf->GetY(),35);}
	
	$pdf->Ln(13);
	
	$pdf->Cell(50,3,'_______________________',0,0,'C',false);
	$pdf->Cell(50,3,'_______________________',0,0,'C',false);
	$pdf->Cell(50,3,'_______________________',0,0,'C',false);
	$pdf->Cell(50,3,'_______________________',0,1,'C',false);
	
	$pdf->Cell(50,3,'Nama/SIK',0,0,'C',false);
	$pdf->Cell(50,3,cari_nama_apoteker($kode),0,0,'C',false);
	$pdf->Cell(50,3,cari_nama_bmanager($kode),0,0,'C',false);
	$pdf->Cell(50,3,cari_nama_rbm($kode),0,1,'C',false);
	
	
	/*if ($namacabang = 'MANADO' || $namacabang = 'SEMARANG' || $namacabang = 'SOLO' || $namacabang = 'PURWOKERTO' || $namacabang = 'PONTIANAK' || $namacabang = 'MAKASAR' || $namacabang = 'CIREBON' || $namacabang = 'BOGOR' || $namacabang = 'BANDUNG') 
		{ 
			$pdf->Cell(50,3,'Hengky Yudhoyono',0,1,'C',false);
		}
	if ($namacabang = 'SAMARINDA' || $namacabang = 'SURABAYA' || $namacabang = 'MATARAM' || $namacabang = 'MALANG' || $namacabang = 'KUPANG' || $namacabang = 'JEMBER' || $namacabang = 'DENPASAR' || $namacabang = 'BANJARMASIN')	
		{ 
			$pdf->Cell(50,3,'Hadi A.K.',0,1,'C',false);
		}
	if ($namacabang = 'PALEMBANG' || $namacabang = 'SERANG' || $namacabang = 'PEKANBARU' || $namacabang = 'PADANG' || $namacabang = 'ACEH' || $namacabang = 'MEDAN' || $namacabang = 'JAMBI' || $namacabang = 'JAKARTA 2' || $namacabang = 'JAKARTA 1' || $namacabang = 'LAMPUNG')	
		{ 
			$pdf->Cell(50,3,'Nama',0,1,'C',false);
		}*/

	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'SIK No: '.cari_sika($kode),0,0,'C',false);
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'',0,1,'C',false);

	$pdf->SetFont('arial','I',5);
	$pdf->Cell(50,3,'*'.tgl_kepala_gud($kode),0,0,'L',false);
	$pdf->Cell(50,3,'*'.tgl_apt($kode),0,0,'L',false);
	$pdf->Cell(50,3,'*'.tgl_bm($kode),0,0,'L',false);
	$pdf->Cell(25,3,'*'.tgl_rbm($kode),0,0,'L',false);
	$pdf->Cell(30,3,'*'.tgl_bod($kode),0,1,'L',false);
		
//keterangan tambahan	
	$pdf->Ln(5);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(10,5,'AVG',1,0,'C',true);
	$pdf->Cell(10,5,'Stok',1,0,'C',true);
	$pdf->Cell(10,5,'Ratio',1,0,'C',true);
	$pdf->Cell(10,5,'PO',1,0,'C',true);
	$pdf->Cell(10,5,'HRatio',1,1,'C',true);
	$pdf->SetFont('arial','',7);
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
	//$pdf->Cell(15,5,'Disc(%)',1,0,'C',true);
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
	$pdf->SetFont('arial','',7);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable3();	
	$pdf->Ln(1);

	
	$pdf->Output('Cetak_PR_'.$kode.'.pdf','D'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
  }
?>