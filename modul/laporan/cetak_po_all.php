<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  
  date_default_timezone_set('Asia/Jakarta'); 
  
	$tgldata = $_GET[tanggal]; 
  	//$kode	= $_GET[kode];
	
   		if(empty($tgldata)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
			echo "SELECT  kodepo_beli FROM po_pembelian WHERE tglpo_beli='$tgldata' GROUP BY kodepo_beli HAVING SUM(jumlah_beli_valid) <> 0";
		}else{
			$querytgl 	= "SELECT  kodepo_beli FROM po_pembelian WHERE tglpo_beli='$tgldata' GROUP BY kodepo_beli HAVING SUM(jumlah_beli_valid) <> 0 limit 1";
			}	 
	
  	$sqltgl	= mysql_query($querytgl);
	$rowtgl	= mysql_num_rows($sqltgl);	
	echo '1-'.$querytgl.'</br>';
	
	while ($datatgl=mysql_fetch_array($sqltgl))
		{
		echo '2-'.'</br>';
		$kode=$datatgl[kodepo_beli];
		echo '3-'.$kode.'</br>';
			
   		if(empty($kode)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
			$query = "SELECT * FROM po_pembelian WHERE jumlah_beli_valid<>0";
		}else{
			$query 	= "SELECT kodepo_beli FROM po_pembelian
						WHERE kodepo_beli='$kode' AND jumlah_beli_valid<>0";
			}	 
	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);

	$namacabang = cari_nama_cabang2($kode);
	$total_qty_po = total_barang_po($kode);
	$kode_pr = kode_pr_po($kode);
	$namasupp_po =nama_supplier_po($kode);
	$tanggal_po_beli = tgl_indo(tanggal_po($kode));
	
	echo '4-'.$query.'</br>';
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{
	   function FancyTable(){
  	
		$kode	= $_GET[kode];   		
		if(empty($kode)){
			$query = "SELECT a.*,b.`satuan` FROM po_pembelian a
						LEFT JOIN `mstproduk` b ON a.`kode_barang`=b.`kodeproduk` 
					WHERE a.jumlah_beli_valid<>0";
		}else{
			$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,d.`ket_prinsipal`,b.satuan FROM po_pembelian a 
							LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
							LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
							LEFT JOIN pembelian d ON a.`kode_beli`=d.`kode_beli` AND a.`kode_barang`=d.`kode_barang` 
						WHERE a.kodepo_beli='$kode' AND a.jumlah_beli_valid<>0";
		} 
		
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(5,15,15,75,20,65,15);
	    //$w=array(10,22,60,20,20,20,20,20);

		$no=1;
        while($data=mysql_fetch_array($sql)){
			$popusatket = produk_pusat_po($data['kode_barang']);
			if (empty($popusatket)){$popusatket = $data['ket_prinsipal'];}
			
			$diskonitem = produk_diskon_popr($data['kode_beli'],$data['kode_barang']);
			
		  $this->SetFont('arial','',10);
		  $this->Cell($w[0],6,$no,1,0,'C',$fill);
		  $this->Cell($w[1],6,$data['jumlah_beli_valid'],1,0,'C',$fill);
		  $this->Cell($w[2],6,$data['satuan'],1,0,'L',$fill);
		  $this->SetFont('arial','',10);
		  $this->Cell($w[3],6,$data['namaproduk'],1,0,'L',$fill);
		  $this->SetFont('arial','',8);		  
		  $this->Cell($w[4],6,$data['kode_barang'],1,0,'L',$fill);
		  $this->SetFont('arial','',7);
		  $this->Cell($w[6],6,$diskonitem,1,0,'C',$fill);
		  $this->Cell($w[5],6,$popusatket,1,0,'L',$fill);
          $this->Ln();  
		  $no++;
        }
        	$this->Cell(array_sum($w),0,'','T');
      }
	}
	
    //Instanciation of inherited class
	$A4[0]=210;
	$A4[1]=297;
	$Q[0]=216;
	$Q[1]=279;
	$S[0]=230;
	$S[1]=290;
    $pdf=new PDF('P','mm',$S);
//	$pdf->SetMargins(1,1,1,1);
	$pdf->SetTopMargin(1);	
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetTitle('PO RUTIN');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
	$tgl = $tanggal_po_beli;
	$pdf->Image('images/logo_sp.png',11,2,100);
	$pdf->Ln(2);
    $pdf->SetFont('arial','B',8);
    $pdf->Cell(210,5,'Kepada Yth: ',0,1,'R');
    $pdf->SetFont('arial','B',9);
	$pdf->Cell(210,5,$namasupp_po,0,1,'R');
	$pdf->Cell(210,5,'',0,1,'R');
	$pdf->Ln(4);
    $pdf->SetFont('arial','B',9);
	$pdf->Cell(60,5,'Harap dikirim kecabang :  ',0,0,'L');
    $pdf->SetFont('arial','B',12);
	$pdf->Cell(60,5,$namacabang,0,1,'L');
    $pdf->SetFont('arial','B',9);
    $pdf->Cell(0,5,'SURAT PESANAN',0,1,'C');
	$pdf->SetX(10);
    $pdf->SetFont('arial','B',14);
    $pdf->Cell(120,5,'No. PR        : '.$kode_pr,0,0,'L');
    $pdf->Cell(120,5,'No. PO        : '.$kode,0,1,'L');
    $pdf->Cell(0,5,'Tanggal      : '.$tgl,0,1,'L');
	$pdf->Ln(1);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(15,5,'Qty',1,0,'C',true);
	$pdf->Cell(15,5,'Satuan',1,0,'C',true);
	$pdf->Cell(75,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(20,5,'Kode Barang',1,0,'C',true);
	$pdf->Cell(15,5,'Disc(%)',1,0,'C',true);
	$pdf->Cell(65,5,'Keterangan',1,1,'C',true);
	$pdf->SetFont('arial','',12);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable();	
	$pdf->Ln(1);
	$pdf->SetFont('arial','B',7);	
	$pdf->Cell(30,5,'*Setiap pengiriman di Surat Jalan harap mencantumkan Nomor PO dan PR',0,1,'L',false);
	$pdf->Ln(1);
	$pdf->SetFont('arial','',7);	
	$pdf->Cell(60,3,'Penerima Pesanan, ',0,0,'C',false);
	$pdf->Cell(60,3,'Pemesan, ',0,0,'C',false);
	$pdf->Cell(60,3,'Mengetahui, ',0,1,'C',false);
	$pdf->Cell(60,3,'Penanggung Jawab',0,0,'C',false);
	$pdf->Cell(60,3,'Apoteker Penanggung Jawab',0,0,'C',false);
	$pdf->Cell(60,3,'Penanggung Jawab',0,1,'C',false);
	
	if(app_apt_pst($kode)=='Y'){$pdf->Image('images/ttd/nining31.png',85,$pdf->GetY()-4,40);}	
	if(user_buat_po($kode)=='ELLAKPS'){$pdf->Image('images/ttd/ella3.png',145,$pdf->GetY()-4,40);}
	if(user_buat_po($kode)=='ANDIKPS'){$pdf->Image('images/ttd/andi3.png',145,$pdf->GetY()-4,40);}
	if(user_buat_po($kode)=='WATIKPS'){$pdf->Image('images/ttd/wati3.png',145,$pdf->GetY()-4,40);}
	if(user_buat_po($kode)=='WIDIKPS'){$pdf->Image('images/ttd/widi3.png',145,$pdf->GetY()-4,40);}
	if(user_buat_po($kode)=='RYANKPS' || user_buat_po($kode)=='AGUSKPS'){$pdf->Image('images/ttd/agus3.png',145,$pdf->GetY()-4,40);}
	
	$pdf->Ln(10);
	$pdf->Cell(60,3,'_______________________',0,0,'C',false);
	$pdf->Cell(60,3,'_______________________',0,0,'C',false);
	$pdf->Cell(60,3,'_______________________',0,1,'C',false);
	$pdf->Cell(60,3,'Cap & Nama',0,0,'C',false);
	$pdf->Cell(60,3,cari_nama_apoteker_pusat($kode),0,0,'C',false);
	$pdf->Cell(60,3,cari_nama_user_buat($kode),0,1,'C',false);
	$pdf->Cell(60,3,'SIK No: ',0,0,'C',false);
	$pdf->Cell(60,3,'SIK No: '.cari_sika_pusat($kode),0,0,'C',false);
	$pdf->Cell(60,3,'',0,1,'C',false);
	
	$pdf->SetFont('arial','I',5);
	$pdf->Cell(60,3,'*',0,0,'L',false);
	$pdf->Cell(60,3,'*'.tgl_apt_pst($kode),0,0,'L',false);
	$pdf->Cell(60,3,'*'.tgl_buat_po($kode),0,1,'L',false);	
	
	
  } 

$pdf->Output('Cetak_PO_'.$kode.'.pdf','D'); //D=Download, I= ,
}
  
?>