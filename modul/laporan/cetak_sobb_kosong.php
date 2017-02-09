<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  include_once('../../inc/fungsi_rupiah.php');
  
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode			= $_GET[kode];
	$kodecabbtb 	= $_GET[btbcab];
	
	$namacabang = cari_nama_cabang_master($kodecabbtb);
	$tanggal_sobb = tgl_indo(tanggal_sobb($kode));
	//$totselisihunitsobbxx = totselisihunitsobb($kode);
	//$totselisihvaluesobbxx = format_rupiah(totselisihvaluesobb($kode));
	
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{
 
		function Header() 
		{
			if ($this->PageNo() == 1)
			{

				$this->Image('images/logo_sp.png',11,2,100);
				$this->SetFont('arial','',7);
				$this->Cell(0,5,'STOK OPNAME BARANG BULANAN (SOBB)',0,1,'R');

			}
			
			
			if ($this->PageNo() > 1)
			{
				//$this->Image('images/logo_sp.png',11,2,100);
				$this->SetFont('arial','',7);
				$this->Cell(0,5,'STOK OPNAME BARANG BULANAN (SOBB) - Hal '.$this->PageNo().'/{nb}',0,1,'R');
				
				$this->SetLeftMargin(2);
				$this->SetFont('arial','',8);
				$this->Ln(2);
				$this->SetFont('arial','B',8);
				$this->SetLineWidth(.1);
				$this->SetFillColor(229,229,229);
				$this->Cell(8,5,'No.',1,0,'C',true);
				$this->Cell(33,5,'Nama Supplier',1,0,'C',true);
				$this->Cell(48,5,'Nama Produk',1,0,'C',true);
				$this->Cell(20,5,'KProduk',1,0,'C',true);
				$this->Cell(12,5,'Satuan',1,0,'C',true);
				$this->Cell(10,5,'Unit1',1,0,'C',true);
				$this->Cell(15,5,'ED1',1,0,'C',true);
				$this->Cell(10,5,'Unit2',1,0,'C',true);
				$this->Cell(15,5,'ED2',1,0,'C',true);
				$this->Cell(10,5,'Unit3',1,0,'C',true);
				$this->Cell(15,5,'ED3',1,0,'C',true);	
				$this->Cell(15,5,'Pelaksana',1,0,'C',true);
				$this->Cell(15,5,'Checker',1,1,'C',true);
				
			}
		}

		
		function allDataTable(){
		
			$kode			= $_GET[kode];
			$kodecabbtb 	= $_GET[btbcab];			

			$query 	= "SELECT SUBSTRING(c.`namasupplier`,1,20) AS namasupplier,b.`namaproduk`,
							a.`kodeproduk`,b.`satuan` FROM `mstlogday` a
						LEFT JOIN `mstproduk` b ON a.`kodeproduk`=b.`kodeproduk`
						LEFT JOIN `mstsupplier2` c ON a.`supplier`=c.`kodesupplier`
						WHERE a.`kodecabang`='$kodecabbtb' AND a.kodestatus='B'
						ORDER BY c.`namasupplier`,b.`namaproduk`,a.`kodeproduk`";
			
			$sql 	= mysql_query($query);
			$row	= mysql_num_rows($sql);

			
			$w=array(8,12,48,20,10,15,33);

			$no=1;
			while($data=mysql_fetch_array($sql)){
			  $this->SetFont('arial','',7);
			  $this->Cell($w[0],5,$no,1,0,'C',$fill);
			  $this->Cell($w[6],5,$data['namasupplier'],1,0,'L',$fill);
			  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
			  $this->SetFont('arial','',8);
			  $this->Cell($w[3],5,$data['kodeproduk'],1,0,'L',$fill);
			  $this->Cell($w[1],5,$data['satuan'],1,0,'L',$fill);
			  $this->Cell($w[4],5,'',1,0,'C',$fill);
			  $this->Cell($w[5],5,'',1,0,'L',$fill);
			  $this->Cell($w[4],5,'',1,0,'C',$fill);
			  $this->Cell($w[5],5,'',1,0,'L',$fill);
			  $this->Cell($w[4],5,'',1,0,'C',$fill);
			  $this->Cell($w[5],5,'',1,0,'L',$fill);
			  $this->Cell($w[5],5,'',1,0,'C',$fill);
			  $this->Cell($w[5],5,'',1,0,'L',$fill);
			  $this->Ln();  
			  $no++;
			}
				$this->Cell(array_sum($w),0,'','T');
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
			$this->$kode;
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
	//$pdf->SetMargins(1,1,1,1);
	$pdf->SetTopMargin(1);	
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetTitle('SOBB KOSONG');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
	$tglhari = date('d M Y');
	$tglhari2 = tgl_indo($tglhari);
	$tgl = $tanggal_sobb;
	$pdf->SetX(10);
	
	$pdf->Ln(25); 
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(0,5,'LAPORAN STOK OPNAME BARANG BULANAN (SOBB)',0,1,'C');
	$pdf->Ln(5); 

    $pdf->SetFont('arial','',10);
	$pdf->Ln(2); 
    $pdf->Cell(0,5,'Kami Cabang '.$namacabang.' Telah Melaksanakan SOBB dengan Nomor '.$kode.' pada tanggal '.$tglhari,0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(0,5,'Yang Melakukan SOBB adalah sebagai Berikut :',0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(0,5,'Checker :',0,1,'L');
    $pdf->Cell(0,5,'1. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'2. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(0,5,'Data Entry :',0,1,'L');
    $pdf->Cell(0,5,'1. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'2. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(0,5,'Pelaksana :',0,1,'L');
    $pdf->Cell(0,5,'1. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'2. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'3. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'4. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'5. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'6. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'7. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'8. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'9. Nama  :________________________________________Paraf  :__________________________',0,1,'L');
    $pdf->Cell(0,5,'10.Nama  :________________________________________Paraf  :__________________________',0,1,'L');

	$pdf->Ln(2); 
	$pdf->Cell(0,5,'Stock Opname Barang Bulanan (SOBB) ini dilakukukan dengan sebenar - benarnya. Dengan ini melaporkan hasilnya',0,1,'L',false);

	$pdf->Ln(5); 
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
	
	$pdf->AddPage();//page break
	$pdf->SetLineWidth(.1);
	$pdf->allDataTable();	
	$pdf->Ln(5);	
	
	//$pdf->SetAutoPageBreak(0,1);
	
	$pdf->Output('Cetak_SOBB_KOSONG_'.$kode.'.pdf','D'); //D=Download, I= ,

?>