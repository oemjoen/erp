<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode	= $_GET[kode];
//echo '--1--'."</br>";	
   		if(empty($kode)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
			$query = "SELECT * FROM retur_beli_psi";
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
		}else{
			$query 	= "SELECT * FROM retur_beli_psi WHERE kode_retur='$kode'";
				}	 
//echo '--2--'.$query."</br>";	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);
//echo '--3--'.$query."</br>";	
	$namacabang = cari_nama_cabang($kode);
	$ijinpbf = cari_ijin_pbf($kode);
	$alamatpbf = cari_alamat_pbf($kode);
	$telppbf = cari_telp_pbf($kode);
	$faxpbf = cari_fax_pbf($kode);
	$total_qty_pr = total_barang_pr($kode);
	$tanggal_pr = tgl_indo(tanggal_retur_edit($kode));

//echo '--4--'.$query.">>".$row."</br>";	
	
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');
//echo '--5--'.$query."</br>";
    class PDF extends FPDF{
	
	function Header() 
	{
		//echo '--6--'.$query."</br>";
		$kode	= $_GET[kode];
		//echo '--7--'.$kode."</br>";
		$ijinpbf = cari_ijin_pbf_pusat($kode);
		$alamatpbf = cari_alamat_pbf_pusat($kode);
		$telppbf = cari_telp_pbf_pusat($kode);
		$faxpbf = cari_fax_pbf_pusat($kode);
		//echo '--8--'.$kode.">>".$ijinpbf.">>".$alamatpbf.">>".$telppbf.">>".$faxpbf."</br>";
		$this->Image('images/logo.jpg',10,2,18);
		$this->Ln(2);
		$this->SetLeftMargin(30);
		$this->SetFont('arial','B',15);
		$this->Cell(0,5,'PT. SAPTA SARI TAMA',0,1,'L');
		$this->SetFont('arial','B',9);
		$this->Cell(0,5,'SARANA MENCAPAI CITA - CITA BERSAMA',0,1,'L');
		$this->Cell(0,5,'PEDAGANG BESAR FARMASI Izin PBF No.'.$ijinpbf,0,1,'L');
		$this->Cell(0,5,'PUSAT  : '.$alamatpbf,0,1,'L');
		$this->Cell(0,5,$telppbf.' - '.$faxpbf,0,1,'L');
		$this->SetLineWidth(1);
		$this->Line(10,30,290,30);
		$this->Ln(4);
		//echo '--9--'.$kode."</br>";
	}
	
	//Page footer
	function Footer()
	{
			//echo '--10--'.$kode."</br>";
			$kode = $_GET[kode];
			//echo '--11--'.$kode."</br>";
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
							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang,b.kandungan,b.sediaan FROM retur_beli_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,b.kandungan,b.sediaan,
												cc.`alasan`,dd.`asal_retur_ket` FROM retur_beli_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												LEFT JOIN mst_alasan_retur cc ON a.`alasan_retur`=cc.`kode_alasan`
												LEFT JOIN `mst_asal_retur` dd ON a.`asal_retur`=dd.`kode_asal`												
												WHERE a.kode_retur='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,0,30,40,0,20,10,50);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){

						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[5],5,$data['kode_barang'],1,0,'L',$fill);
						  $this->Cell($w[7],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[6],5,$data['jumlah_retur'],1,0,'C',$fill);
						  $this->Cell($w[6],5,$data['satuan'],1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['batch'],1,0,'L',$fill);
						  $this->Cell($w[5],5,$data['ed'],1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['do_pusat'],1,0,'L',$fill);
						  $this->Cell($w[2],5,$data['tgl_do_pusat'],1,0,'C',$fill);
						  $this->Cell($w[3],5,$data['alasan'],1,0,'L',$fill);
						  $this->Cell($w[3],5,$data['asal_retur_ket'],1,0,'L',$fill);
						  $this->Ln();  
						  $no++;
						}
							$this->Cell(285,0,'','T');
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
//    $pdf->AddPage();
	$pdf->SetTopMargin(1);
    $pdf->AddPage('L');
    $pdf->SetTitle('PR PRECURSOR');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
	$tgl = $tanggal_pr;
	$pdf->SetLeftMargin(10);
    $pdf->SetFont('arial','BU',10);
    $pdf->Cell(0,5,'FORM PELAPORAN PENGEMBALIAN BARANG RUSAK DAN ED KE SUPPLIER (PSIKOTROPIKA)',0,1,'C');
	
	$pdf->Ln(2);
    $pdf->SetFont('arial','B',10);
    $pdf->Cell(50,5,'Kepada Yth. Supplier ',0,0,'L');
    $pdf->Cell(50,5,': '.nama_retur_edit($kode),0,1,'L');
    $pdf->Cell(50,5,'Dari Cabang ',0,0,'L');
    $pdf->Cell(50,5,': '.cari_nama_cabang($kode),0,1,'L');
    $pdf->Cell(50,5,'No. Form ',0,0,'L');
    $pdf->Cell(75,5,': '.$kode,0,0,'L');
    $pdf->Cell(50,5,'No. Validation ',0,0,'L');
    $pdf->Cell(75,5,': - ',0,1,'L');

	
	$pdf->Ln(2);
//	$pdf->SetLeftMargin(2);
	$pdf->SetFont('arial','B',7);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
//	$pdf->SetX(2);	
	$pdf->Cell(5,5,'No.',1,0,'C',true);
	$pdf->Cell(20,5,'Kode Barang',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Barang',1,0,'C',true);
	$pdf->Cell(10,5,'Unit',1,0,'C',true);
	$pdf->Cell(10,5,'Satuan',1,0,'C',true);
	$pdf->Cell(30,5,'Batch NO',1,0,'C',true);
	$pdf->Cell(20,5,'Expired Date',1,0,'C',true);
	$pdf->Cell(30,5,'No DO',1,0,'C',true);
	$pdf->Cell(30,5,'Tgl DO',1,0,'C',true);
	$pdf->Cell(40,5,'Alasan Retur',1,0,'C',true);
	$pdf->Cell(40,5,'Asal',1,1,'C',true);
	
	$pdf->SetFont('arial','B',7);
	$pdf->SetLineWidth(.1);
	$pdf->FancyTable();	

	$pdf->Ln(1);
	$pdf->SetFont('arial','B',9);	
	$pdf->Cell(70,3,'',0,0,'',false);
	$pdf->Cell(70,3,'',0,0,'C',false);
	$pdf->Cell(70,3,'',0,0,'C',false);
	$pdf->Cell(70,3,"Total Koli : ".koli_retur_edit($kode)." Koli",0,1,'R',false);

	
	$pdf->Ln(5);
	$pdf->SetFont('arial','',9);	
	$pdf->Cell(70,3,cari_kota_pr($kode).", ".$tgl,0,0,'C',false);
	$pdf->Cell(70,3,'',0,0,'C',false);
	$pdf->Cell(70,3,'',0,0,'C',false);
	$pdf->Cell(70,3,'',0,1,'R',false);
	$pdf->Ln(2);

	$pdf->Cell(70,3,'Pengirim, ',0,0,'C',false);
	$pdf->Cell(70,3,'Menyetujui,',0,0,'C',false);
	$pdf->Cell(70,3,'Ekspedisi,',0,0,'C',false);
	$pdf->Cell(70,3,'Penerima,',0,1,'C',false);

	$pdf->Ln(15);

	$pdf->Cell(70,4,'_______________________',0,0,'C',false);
	$pdf->Cell(70,4,'_______________________',0,0,'C',false);
	$pdf->Cell(70,4,'___________________________________',0,0,'C',false);
	$pdf->Cell(70,4,'_______________________',0,1,'C',false);

	$pdf->Cell(70,4,'Ka.Gudang',0,0,'C',false);
	$pdf->Cell(70,4,'Bussiness Manager',0,0,'C',false);
	$pdf->Cell(70,4,'Nama Jelas & Cap & RESI TERLAMPIR',0,0,'C',false);
	$pdf->Cell(70,4,'Nama Jelas & Cap',0,1,'C',false);

	$pdf->Cell(70,4,'Nama Jelas & Cap',0,0,'C',false);
	$pdf->Cell(70,4,'Nama Jelas & Cap',0,0,'C',false);
	$pdf->Cell(70,4,'',0,0,'C',false);
	$pdf->Cell(70,4,'',0,1,'C',false);

	$pdf->Ln(5);
	$pdf->Cell(70,4,'Perhatian :',0,1,'L',false);
	$pdf->Ln(1);
	$pdf->Cell(70,4,'1. Supplier/Pabrik penerima barang, dimohon memberikan bukti penerimaan barang ke :',0,1,'L',false);
	$pdf->Cell(70,4,'  (a) Cabang Pengirim, dan',0,1,'L',false);
	$pdf->Cell(70,4,'  (b) PT. Sapta Sari Tama Pusat di nomor 022-6026254/022-6026309 atau email ke logist@saptasaritama.com',0,1,'L',false);
	$pdf->Cell(70,4,'2. Bila dalam 15(Lima Belas) hari, tidak ada pemberitahuan dari Pihak Penerima, maka kami anggap barang sudah di terima di Supplier/Pabrik',0,1,'L',false);
	$pdf->Cell(70,4,'3. Bila dalam 1(Satu) bulan, tidak ada penggantian barang, maka kami akan usulkan untuk di perhitungkan/dipotongkan di Tagihan Supplier',0,1,'L',false);

	
	$pdf->Output('Cetak_RETUR_'.$kode.'.pdf','D'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
	echo $query;
  }
  
  
?>