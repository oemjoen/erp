<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  include_once('../../inc/fungsi_rupiah.php');
  
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode			= $_GET[kode];
	$kodecabbtb 	= $_GET[btbcab];
	
   		if(empty($kode)){
			//echo "<h1>Maaf, data tidak ditemukan</h1><br>";
			$query = "SELECT * FROM `trans_nonsalestok` a ";
		}else{
			$query 	= "SELECT * FROM `trans_nonsalestok` a WHERE a.`kode_nsas`='$kode'";
			}	 
	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);

	$namacabang = cari_nama_cabang3	($kode);
	$tanggal_sobb = tgl_indo(tanggal_nsas($kode));
	$totselisihunitsobbxx = totselisihunitsobb($kode);
	$totselisihvaluesobbxx = format_rupiah(totselisihvaluesobb($kode));
	$kategori = cari_kategori_nsas($kode);
	
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{
	
	function allDataTable(){
  	
		$kode	= $_GET[kode]; 
  		
		if(empty($kode)){
			$query = "SELECT a.*,b.`namaproduk`,b.`satuan`,SUBSTRING(c.`namasupplier`,1,20) AS namasupplier
							FROM `trans_nonsalestok` a 
							LEFT JOIN `mstproduk` b ON a.`kode_produk`=b.`kodeproduk`
							LEFT JOIN `mstsupplier2` c ON a.`kode_supp`=c.`kodesupplier`
							WHERE a.`kode_nsas`='$kode'
							ORDER BY c.`namasupplier`,b.`namaproduk`";
		}else{
			$query 	= "SELECT a.*,b.`namaproduk`,b.`satuan`,SUBSTRING(c.`namasupplier`,1,20) AS namasupplier
							FROM `trans_nonsalestok` a 
							LEFT JOIN `mstproduk` b ON a.`kode_produk`=b.`kodeproduk`
							LEFT JOIN `mstsupplier2` c ON a.`kode_supp`=c.`kodesupplier`
							WHERE a.`kode_nsas`='$kode' 
							ORDER BY c.`namasupplier`,b.`namaproduk`";
		} 
			
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(8,12,35,20,10,18,50);

		$no=1;
        while($data=mysql_fetch_array($sql)){
		  //$this->SetLeftMargin(2);
		  $this->SetFont('arial','',7);
		  $this->Cell($w[0],5,$no,1,0,'C',$fill);
		  $this->Cell($w[2],5,$data['namasupplier'],1,0,'L',$fill);
		  $this->Cell($w[6],5,$data['namaproduk'],1,0,'L',$fill);
		  $this->Cell($w[3],5,$data['kode_produk'],1,0,'L',$fill);
		  $this->Cell($w[1],5,$data['satuan'],1,0,'L',$fill);
		  $this->Cell($w[4],5,$data['unit'],1,0,'C',$fill);
		  $this->Cell($w[5],5,$data['batch'],1,0,'L',$fill);
		  $this->Cell($w[5],5,$data['ed'],1,0,'C',$fill);
		  $this->Cell($w[2],5,$data['keterangan'],1,0,'L',$fill);
          $this->Ln();  
		  $no++;
        }
        	$this->Cell(array_sum($w),0,'','T');
      }
  
  
	function Header() 
	{

			if ($this->PageNo() == 1)
			{

				$this->Image('images/logo_sp.png',11,2,100);
				$this->SetFont('arial','',7);
				$this->Cell(0,5,'NON SALEABLE STOCK (NSAS)',0,1,'R');
				$this->$_GET[kode];
				$this->Ln(25);
				$this->SetFont('arial','B',12);
				$this->Cell(0,5,'NON SALEABLE STOCK (NSAS)',0,1,'C');
				$this->Ln(5); 

			}
			

		
		
		
			if ($this->PageNo() > 1)
			{
				//$this->Image('images/logo_sp.png',11,2,100);
				$this->SetFont('arial','',7);
				$this->Cell(0,5,'NON SALEABLE STOCK (NSAS) - Hal '.$this->PageNo().'/{nb}',0,1,'R');
				
				//$this->SetLeftMargin(2);
				$this->SetFont('arial','',8);
				$this->Ln(2); 
				//$this->SetLeftMargin(2);
				$this->SetFont('arial','',8);
				$this->Ln(2);
				$this->SetFont('arial','B',8);
				$this->SetLineWidth(.1);
				$this->SetFillColor(229,229,229);
				  $this->SetFont('arial','',7);
				  $this->Cell($w[0],5,$no,1,0,'C',$fill);
				  $this->Cell($w[2],5,$data['namasupplier'],1,0,'L',$fill);
				  $this->Cell($w[6],5,$data['namaproduk'],1,0,'L',$fill);
				  $this->Cell($w[3],5,$data['kode_produk'],1,0,'L',$fill);
				  $this->Cell($w[1],5,$data['satuan'],1,0,'L',$fill);
				  $this->Cell($w[4],5,$data['unit'],1,0,'C',$fill);
				  $this->Cell($w[5],5,$data['batch'],1,0,'L',$fill);
				  $this->Cell($w[5],5,$data['ed'],1,0,'C',$fill);
				  $this->Cell($w[2],5,$data['keterangan'],1,0,'L',$fill);
				
			}
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
    $pdf->SetTitle('NON SALEABLE STOCK');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
	$tgl = $tanggal_sobb;
    $pdf->SetFont('arial','B',10);


    $pdf->SetFont('arial','',10);
	$pdf->Ln(2); 
    $pdf->Cell(0,5,'Kami Cabang '.$namacabang.' Telah Melaksanakan NON SALEABLE STOCK (NSAS) Entry dengan Nomor '.$kode,0,1,'L');
    $pdf->Cell(0,5,'pada tanggal '.$tanggal_sobb,0,1,'L');
	$pdf->Ln(2); 
	$pdf->Cell(0,5,'Pencatatan Batch Number dan Expired Date (ED) dilakukan dengan sebenar - benarnya dan dapat dipertanggung jawabkan hasilnya.',0,1,'L',false);
	$pdf->Cell(0,5,'Foto terlampir.',0,1,'L',false);
	$pdf->Cell(0,5,'Kategori '.$kategori,0,1,'L',false);

//	$pdf->SetLeftMargin(2);
	$pdf->SetFont('arial','',8);
	$pdf->Ln(2); 
//	$pdf->SetLeftMargin(2);
	$pdf->SetFont('arial','',8);
	$pdf->Ln(2);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(8,5,'No.',1,0,'C',true);
	$pdf->Cell(35,5,'Nama Supplier',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Produk',1,0,'C',true);
	$pdf->Cell(20,5,'KProduk',1,0,'C',true);
	$pdf->Cell(12,5,'Satuan',1,0,'C',true);
	$pdf->Cell(10,5,'Unit',1,0,'C',true);
	$pdf->Cell(18,5,'Batch',1,0,'C',true);
	$pdf->Cell(18,5,'ED',1,0,'C',true);
	$pdf->Cell(35,5,'Keterangan',1,1,'C',true);

	$pdf->SetLineWidth(.1);
//	$pdf->SetLeftMargin(2);
	$pdf->allDataTable();		
	
	$pdf->Ln(5); 
    $pdf->SetFont('arial','',10);
	$pdf->Cell(70,3,'Yang Membuat, ',0,0,'C',false);
	$pdf->Cell(70,3,'Menyetujui,',0,0,'C',false);
	$pdf->Cell(70,3,'Mengetahui, ',0,1,'C',false);
	$pdf->Ln(15);
	$pdf->Cell(70,3,'_______________________',0,0,'C',false);
	$pdf->Cell(70,3,'_______________________',0,0,'C',false);
	$pdf->Cell(70,3,'_______________________',0,1,'C',false);
	$pdf->Ln(2);
	$pdf->Cell(70,3,'Kepala Logistik',0,0,'C',false);
	$pdf->Cell(70,3,'Branch Manager',0,0,'C',false);
	$pdf->Cell(70,3,'Regional Branch Manager',0,1,'C',false);
	
	$pdf->Ln(3);
	$pdf->SetFont('arial','B',7);
	$pdf->Cell(70,3,'*[Nama jelas dan tanda tangan]',0,1,'L',false);	
	
	
	//$pdf->AddPage();//page break	
	//$pdf->SetLineWidth(.1);
	//$pdf->SetLeftMargin(2);
	//$pdf->allDataTable();	
	$pdf->Ln(5);	
	
	
	
	$pdf->Output('Cetak_SOBB_DETAIL_'.$kode.'.pdf','D'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
	echo $query."</br>";
  }
?>