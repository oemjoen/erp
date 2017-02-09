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
			$query = "SELECT a.*,b.`namasupplier`,c.`namaprinsipal`,d.`namakatkhusus` FROM mstproduk a
					LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`= b.`kodesupplier`
					LEFT JOIN `mstprinsipal` c ON a.`kodeprinsipal` = c.`kodeprinsipal`
					LEFT JOIN `mstkategorikhusus` d ON a.`kategorikhusus` = d.`kodekatkhusus`
					ORDER BY b.namasupplier,a.namaproduk,c.`namaprinsipal`";
		}else{
			$query 	= "SELECT a.*,b.`namasupplier`,c.`namaprinsipal`,d.`namakatkhusus` FROM mstproduk a
					LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`= b.`kodesupplier`
					LEFT JOIN `mstprinsipal` c ON a.`kodeprinsipal` = c.`kodeprinsipal`
					LEFT JOIN `mstkategorikhusus` d ON a.`kategorikhusus` = d.`kodekatkhusus`
					ORDER BY b.namasupplier,a.namaproduk,c.`namaprinsipal`";
			}	 
	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);

	$namacabang = cari_nama_cabang_master($kodecabbtb);
	$tanggal_sobb = tgl_indo(tanggal_sobb($kode));
	$totselisihunitsobbxx = totselisihunitsobb($kode);
	$totselisihvaluesobbxx = format_rupiah(totselisihvaluesobb($kode));
	
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{
	
	




	  
	function allDataTable(){
  	
		$kode	= $_GET[kode]; 
  		
		if(empty($kode)){
			$query = "SELECT a.*,b.`namasupplier`,c.`namaprinsipal`,d.`namakatkhusus` FROM mstproduk a
					LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`= b.`kodesupplier`
					LEFT JOIN `mstprinsipal` c ON a.`kodeprinsipal` = c.`kodeprinsipal`
					LEFT JOIN `mstkategorikhusus` d ON a.`kategorikhusus` = d.`kodekatkhusus`
					ORDER BY b.namasupplier,a.namaproduk,c.`namaprinsipal`";
		}else{
			$query 	= "SELECT a.*,b.`namasupplier`,c.`namaprinsipal`,d.`namakatkhusus` FROM mstproduk a
					LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`= b.`kodesupplier`
					LEFT JOIN `mstprinsipal` c ON a.`kodeprinsipal` = c.`kodeprinsipal`
					LEFT JOIN `mstkategorikhusus` d ON a.`kategorikhusus` = d.`kodekatkhusus`
					ORDER BY b.namasupplier,a.namaproduk,c.`namaprinsipal`";
		} 
			
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(8,12,50,20,10,18);

		$no=1;
        while($data=mysql_fetch_array($sql)){
		  $this->SetLeftMargin(2);
		  $this->SetFont('arial','',7);
		  $this->Cell($w[0],5,$no,1,0,'C',$fill);
		  $this->Cell($w[2],5,$data['namasupplier'],1,0,'L',$fill);
		  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
		  $this->SetFont('arial','',8);
		  $this->Cell($w[3],5,$data['kode_produk'],1,0,'L',$fill);
		  $this->Cell($w[1],5,$data['satuan'],1,0,'L',$fill);
		  $this->Cell($w[4],5,$data['unit_1'],1,0,'C',$fill);
		  $this->Cell($w[5],5,$data['ed_1'],1,0,'L',$fill);
		  $this->Cell($w[4],5,$data['unit_2'],1,0,'C',$fill);
		  $this->Cell($w[5],5,$data['ed_2'],1,0,'L',$fill);
		  $this->Cell($w[4],5,$data['unit_3'],1,0,'C',$fill);
		  $this->Cell($w[5],5,$data['ed_3'],1,0,'L',$fill);
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
				$this->Cell(0,5,'REGISTER PRODUK',0,1,'R');
				$this->$_GET[kode];
				$this->Ln(25);
				$this->SetFont('arial','B',12);
				$this->Cell(0,5,'REGISTER PRODUK',0,1,'C');
				$this->Ln(5); 

			}
			

		
		
		
			if ($this->PageNo() > 1)
			{
				//$this->Image('images/logo_sp.png',11,2,100);
				$this->SetFont('arial','',7);
				$this->Cell(0,5,'REGISTER PRODUK Hal '.$this->PageNo().'/{nb}',0,1,'R');
				
				$this->SetLeftMargin(2);
				$this->SetFont('arial','',8);
				$this->Ln(2); 
				$this->SetLeftMargin(2);
				$this->SetFont('arial','',8);
				$this->Ln(2);
				$this->SetFont('arial','B',8);
				$this->SetLineWidth(.1);
				$this->SetFillColor(229,229,229);
				$this->Cell(8,5,'No.',1,0,'C',true);
				$this->Cell(50,5,'Nama Supplier',1,0,'C',true);
				$this->Cell(50,5,'Nama Produk',1,0,'C',true);
				$this->Cell(20,5,'KProduk',1,0,'C',true);
				$this->Cell(12,5,'Satuan',1,0,'C',true);
				$this->Cell(10,5,'Unit1',1,0,'C',true);
				$this->Cell(18,5,'ED1',1,0,'C',true);
				$this->Cell(10,5,'Unit2',1,0,'C',true);
				$this->Cell(18,5,'ED2',1,0,'C',true);
				$this->Cell(10,5,'Unit3',1,0,'C',true);
				$this->Cell(18,5,'ED3',1,1,'C',true);
				
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
    $pdf->SetTitle('SOBB DETAIL');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
	$tgl = $tanggal_sobb;
    $pdf->SetFont('arial','B',10);


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

	$this->SetLeftMargin(2);
	$this->SetFont('arial','',8);
	$this->Ln(2); 
	$this->SetLeftMargin(2);
	$this->SetFont('arial','',8);
	$this->Ln(2);
	$this->SetFont('arial','B',8);
	$this->SetLineWidth(.1);
	$this->SetFillColor(229,229,229);
	$this->Cell(8,5,'No.',1,0,'C',true);
	$this->Cell(50,5,'Nama Supplier',1,0,'C',true);
	$this->Cell(50,5,'Nama Produk',1,0,'C',true);
	$this->Cell(20,5,'KProduk',1,0,'C',true);
	$this->Cell(12,5,'Satuan',1,0,'C',true);
	$this->Cell(10,5,'Unit1',1,0,'C',true);
	$this->Cell(18,5,'ED1',1,0,'C',true);
	$this->Cell(10,5,'Unit2',1,0,'C',true);
	$this->Cell(18,5,'ED2',1,0,'C',true);
	$this->Cell(10,5,'Unit3',1,0,'C',true);
	$this->Cell(18,5,'ED3',1,1,'C',true);
	
	$pdf->AddPage();//page break	
	$pdf->SetLineWidth(.1);
	$pdf->SetLeftMargin(2);
	$pdf->allDataTable();	
	$pdf->Ln(5);	
	
	
	
	$pdf->Output('Cetak_SOBB_DETAIL_'.$kode.'.pdf','I'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
	echo $query."</br>";
  }
?>