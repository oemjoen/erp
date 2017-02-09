<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode	= $_GET[kode];
	
   		if(empty($kode)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
		}else{
			$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_usulan a 
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
								LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
								WHERE a.kode_beli='$kode'";
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
							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_usulan a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_usulan a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kode_beli='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(10,15,15,50,25,65,15);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){

						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[1],5,number_format($data['jumlah_beli']),1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['satuan'],1,0,'L',$fill);
						  $this->Cell($w[3],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[4],5,$data['kode_barang'],1,0,'L',$fill);
						  $this->Cell($w[6],5,$data['diskon'],1,0,'C',$fill);
						  $this->Cell($w[5],5,$data['ket_cabang'],1,0,'L',$fill);
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
										FROM pembelian_usulan a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po,  
										COALESCE(ROUND(((a.`jumlah_beli` + a.`stok` )/a.`averg`),2),0) AS HRasio 
										FROM pembelian_usulan a 
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
							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_usulan a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM pembelian_usulan a 
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
										FROM pembelian_usulan a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po,  
										COALESCE(ROUND(((a.`jumlah_beli` + a.`stok` + a.`outstading_po`)/a.`averg`),4),0) AS HRasio 
										FROM pembelian_usulan a 
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
	$pdf->Cell(60,5,'USULAN PEMBELIAN CABANG :  ',0,0,'L');
    $pdf->SetFont('arial','B',12);
	$pdf->Cell(60,5,$namacabang,0,1,'L');
    $pdf->SetFont('arial','B',12);
    $pdf->Cell(0,5,'SURAT USULAN PEMBELIAN',0,1,'C');
	$pdf->SetX(10);
    $pdf->SetFont('arial','B',12);
    $pdf->Cell(100,5,'No. USULAN    : '.$kode,0,0,'L');
    $pdf->Cell(100,5,'Tanggal      : '.$tgl,0,1,'L');
	$pdf->Ln(1);
	$pdf->SetFont('arial','B',9);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(10,5,'No.',1,0,'C',true);
	$pdf->Cell(15,5,'Qty',1,0,'C',true);
	$pdf->Cell(15,5,'Satuan',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(25,5,'Kode Barang',1,0,'C',true);
	$pdf->Cell(15,5,'Diskon',1,0,'C',true);
	$pdf->Cell(65,5,'Keterangan',1,1,'C',true);
	$pdf->SetFont('arial','',7);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable();	
	$pdf->Ln(1);
	$pdf->SetFont('arial','B',7);	
	$pdf->Cell(30,5,'*Setiap pengiriman di Surat Jalan harap mencantumkan Nomor PO dan PR',0,1,'L',false);
	$pdf->Ln(1);
	$pdf->SetFont('arial','',7);	
	$pdf->Cell(50,3,'Yang Mengusulkan, ',0,0,'C',false);
	$pdf->Cell(50,3,'Menyetujui, ',0,0,'C',false);
	$pdf->Cell(50,3,'Mengetahui, ',0,0,'C',false);
	$pdf->Cell(50,3,'Mengetahui, ',0,1,'C',false);
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'',0,1,'C',false);
	$pdf->Ln(10);
	$pdf->Cell(50,3,'_______________________',0,0,'C',false);
	$pdf->Cell(50,3,'_______________________',0,0,'C',false);
	$pdf->Cell(50,3,'_______________________',0,0,'C',false);
	$pdf->Cell(50,3,'_______________________',0,1,'C',false);
	$pdf->Cell(50,3,'Cap & Nama',0,0,'C',false);
	$pdf->Cell(50,3,'Apoteker ',0,0,'C',false);
	$pdf->Cell(50,3,'Business Manager',0,0,'C',false);
	$pdf->Cell(50,3,'Regional Business Manager',0,1,'C',false);
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'',0,0,'C',false);
	$pdf->Cell(50,3,'',0,1,'C',false);
	
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