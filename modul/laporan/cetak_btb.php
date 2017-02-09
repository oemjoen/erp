<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode	= $_GET[kode];
	
   		if(empty($kode)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
			$query = "SELECT * FROM trans_btb";
		}else{
			$query 	= "SELECT * FROM trans_btb a WHERE a.kodebtb='$kode'";
			}	 
	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);

	$namacabang = cari_nama_cabang2($kode);
	$total_qty_po = total_barang_po($kode);
	$kode_pr = kode_pr_btb($kode);
	$kode_po = kode_po_btb($kode);
	$nama_supplier_btb =nama_supplier_btb($kode);
	$tanggal_btb = tgl_indo(tanggal_btb($kode));
	$kode_resi_btb = kode_resi_btb($kode);
	$ekspedisi_btb = ekspedisi_btb($kode);
	$suratjalan_btb = suratjalan_btb($kode);
	
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{


	function FancyTable(){
  	
		$kode	= $_GET[kode];   		
		if(empty($kode)){
			echo "data tidak ada";
		}else{
			$query 	= "SELECT a.*,b.`namaproduk`,b.`satuan` FROM trans_btb a 
							LEFT JOIN `mstproduk` b ON a.`kode_barang`=b.`kodeproduk`
 			WHERE a.kodebtb='$kode' AND (a.`jumlah_terima` + a.`jumlah_terima_a` + a.`jumlah_terima_b`) > 0";
		} 
			
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(5,15,12,60,20,0,10);
	    //$w=array(10,22,60,20,20,20,20,20);

		$no=1;
        while($data=mysql_fetch_array($sql)){
			$totalterima = $data['jumlah_terima'] + $data['jumlah_terima_a'] +$data['jumlah_terima_b'];
		  $this->SetFont('arial','',8);
		  $this->Cell($w[0],4,$no,1,0,'C',$fill);
		  $this->Cell($w[3],4,$data['namaproduk'],1,0,'L',$fill);
		  $this->Cell($w[4],4,$data['kode_barang'],1,0,'L',$fill);
		  $this->Cell($w[2],4,$data['satuan'],1,0,'L',$fill);
		  $this->Cell($w[6],4,$data['jumlah_po'],1,0,'L',$fill);
		  $this->Cell($w[1],4,$totalterima,1,0,'L',$fill);
          $this->Ln();  
		  $no++;
        }
        	$this->Cell(array_sum($w),0,'','T');
      }
	  
	   function FancyTable2(){
  	
		$kode	= $_GET[kode];   		
		if(empty($kode)){
			echo "data tidak ada";
		}else{
			$query 	= "SELECT a.*,b.`namaproduk`,b.`satuan` FROM trans_btb a 
							LEFT JOIN `mstproduk` b ON a.`kode_barang`=b.`kodeproduk`
 			WHERE a.kodebtb='$kode' AND (a.`jumlah_terima` + a.`jumlah_terima_a` + a.`jumlah_terima_b`) > 0";
		} 
		
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(5,0,0,0,20,0,10);
	    //$w=array(10,22,60,20,20,20,20,20);

		$no=1;
        while($data=mysql_fetch_array($sql)){
			
		  $this->SetFont('arial','',8);
		  $this->Cell($w[0],4,$no,1,0,'C',$fill);
		  $this->Cell($w[4],4,$data['kode_barang'],1,0,'L',$fill);
		  $this->Cell($w[6],4,$data['jumlah_terima'],1,0,'L',$fill);
		  $this->Cell($w[4],4,$data['batch_number'],1,0,'L',$fill);
		  $this->Cell($w[4],4,$data['ed'],1,0,'L',$fill);
		  $this->Cell($w[6],4,$data['jumlah_terima_a'],1,0,'L',$fill);
		  $this->Cell($w[4],4,$data['batch_number_a'],1,0,'L',$fill);
		  $this->Cell($w[4],4,$data['ed_a'],1,0,'L',$fill);
		  $this->Cell($w[6],4,$data['jumlah_terima_b'],1,0,'L',$fill);
		  $this->Cell($w[4],4,$data['batch_number_b'],1,0,'L',$fill);
		  $this->Cell($w[4],4,$data['ed_b'],1,0,'L',$fill);
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
	$S[0]=235;
	$S[1]=290;
    $pdf=new PDF('P','mm',$S);
//	$pdf->SetMargins(1,1,1,1);
	$pdf->SetTopMargin(1);	
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetTitle('BUKTI TERIMA BARANG');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
	$tgl = $tanggal_btb;
	$pdf->Image('images/logo_sp.png',11,2,100);
	$pdf->Ln(25);
    $pdf->SetFont('arial','B',12);
    $pdf->Cell(0,5,'BUKTI TERIMA BARANG (BTB)',0,1,'C');
	$pdf->Ln(2);
	$pdf->SetX(10);
    $pdf->SetFont('arial','B',8);
	
    $pdf->Cell(30,4,'Tanggal',0,0,'L');
    $pdf->Cell(30,4,': '.$tgl,0,1,'L');
    $pdf->Cell(30,4,'No. BTB',0,0,'L');
    $pdf->Cell(30,4,': '.$kode,0,1,'L');
	$pdf->Ln(2);
	
    $pdf->Cell(30,4,'No. PR',0,0,'L');
    $pdf->Cell(30,4,': '.$kode_pr,0,1,'L');
    $pdf->Cell(30,4,'No. PO',0,0,'L');
    $pdf->Cell(30,4,': '.$kode_po,0,1,'L');
 
 
    $pdf->Cell(30,4,'Ekspedisi',0,0,'L');
    $pdf->Cell(30,4,': '.$ekspedisi_btb,0,1,'L');
    $pdf->Cell(30,4,'Resi',0,0,'L');
    $pdf->Cell(30,4,': '.$kode_resi_btb,0,1,'L');
    $pdf->Cell(30,4,'Supplier',0,0,'L');
    $pdf->Cell(30,4,': '.$nama_supplier_btb,0,1,'L');
	$pdf->Ln(2);
	
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(60,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(20,5,'Kode Barang',1,0,'C',true);
	$pdf->Cell(12,5,'Satuan',1,0,'C',true);
	$pdf->Cell(10,5,'PO',1,0,'C',true);
	$pdf->Cell(15,5,'Total Trm',1,1,'C',true);

	$pdf->SetLineWidth(.1);
	$pdf->FancyTable();	
	$pdf->Ln(5);
	
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);	
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(20,5,'Kode Barang',1,0,'C',true);
	$pdf->Cell(10,5,'Trm 1',1,0,'C',true);
	$pdf->Cell(20,5,'BN 1',1,0,'C',true);
	$pdf->Cell(20,5,'ED 1',1,0,'C',true);
	$pdf->Cell(10,5,'Trm 2',1,0,'C',true);
	$pdf->Cell(20,5,'BN 2',1,0,'C',true);
	$pdf->Cell(20,5,'ED 2',1,0,'C',true);
	$pdf->Cell(10,5,'Trm 3',1,0,'C',true);
	$pdf->Cell(20,5,'BN 3',1,0,'C',true);
	$pdf->Cell(20,5,'ED 3',1,1,'C',true);
	
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable2();	
	$pdf->Ln(5);
	
	$pdf->SetFont('arial','B',7);	
	$pdf->Cell(30,5,'*DENGAN ADANYA BUKTI TERIMA BARANG INI, ADALAH BENAR BAHWA CABANG KAMI MENERIMA FISIK BARANG SESUAI DENGAN YANG TERTERA DIATAS.',0,1,'L',false);
	$pdf->Ln(1);
	$pdf->SetFont('arial','',8);	
	$pdf->Cell(60,3,'Penerima, ',0,0,'C',false);
	$pdf->Cell(60,3,'Penanggung Jawab,',0,0,'C',false);
	$pdf->Cell(60,3,'Menyetujui, ',0,1,'C',false);
	$pdf->Ln(10);
	$pdf->Cell(60,3,'_______________________',0,0,'C',false);
	$pdf->Cell(60,3,'_______________________',0,0,'C',false);
	$pdf->Cell(60,3,'_______________________',0,1,'C',false);
	$pdf->Cell(60,3,'Petugas Gudang',0,0,'C',false);
	$pdf->Cell(60,3,'K. Logistik',0,0,'C',false);
	$pdf->Cell(60,3,'Branch Manager',0,1,'C',false);
	
	$pdf->Ln(3);
	$pdf->SetFont('arial','B',7);
	$pdf->Cell(70,3,'*[Nama jelas dan tanda tangan]',0,1,'L',false);
	
	$pdf->Output('Cetak_BTB_'.$kode.'.pdf','D'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
  }
?>