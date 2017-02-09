<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  date_default_timezone_set('Asia/Jakarta'); 
  
	
  	$kode	= $_GET[kode];
	
   		if(empty($kode)){
			echo "<h1>Maaf, data tidak ditemukan</h1><br>";
		}else{
			$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM po_pembelian_psi a 
								LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
								LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
								WHERE a.kodepo_beli='$kode' AND a.jumlah_beli_valid<>0";
				}	 
	
  	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);

	
	$ijinpbf = cari_ijin_pbf_pusat($kode);
	$alamatpbf = cari_alamat_pbf_pusat($kode);
	$telppbf = cari_telp_pbf_pusat($kode);
	$faxpbf = cari_fax_pbf_pusat($kode);
	$total_qty_pr = total_barang_pr($kode);
	$tanggal_pr = tgl_indo(tanggal_po_pre($kode));
	
   if ($row>0) {
	
	//Definisi
    define('FPDF_FONTPATH','../font/');
    require('fpdf.php');

    class PDF extends FPDF{
	
	function Header() 
	{
	$kode	= $_GET[kode];
	$ijinpbf = cari_ijin_pbf_pusat($kode);
	$alamatpbf = cari_alamat_pbf_pusat($kode);
	$telppbf = cari_telp_pbf_pusat($kode);
	$faxpbf = cari_fax_pbf_pusat($kode);

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
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,b.kandungan,b.sediaan,b.satuan FROM po_pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kodepo_beli='$kode' AND a.jumlah_beli_valid<>0";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,15,30,70,25,20,10,50);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){
							$popusatket = produk_pusat_po($data['kode_barang']);
							if (empty($popusatket)){$popusatket = $data['ket_prinsipal'];}
							
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[5],5,$data['kode_barang'],1,0,'L',$fill);
						  $this->Cell($w[7],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[2],5,$data['kandungan'],1,0,'L',$fill);
						  $this->Cell($w[1],5,$data['sediaan'],1,0,'C',$fill);
						  $this->Cell($w[6],5,$data['satuan'],1,0,'L',$fill);
						  $this->Cell($w[6],5,number_format($data['jumlah_beli_valid']),1,0,'C',$fill);
						  $this->Cell($w[3],5,terbilang($data['jumlah_beli_valid']),1,0,'L',$fill);
						  $this->Cell($w[5],5,$popusatket,1,0,'L',$fill);
						  $this->Ln();  
						  $no++;
						}
							$this->Cell(array_sum($w),0,'','T');
		}
	
	function FancyTable2(){
  	
			$kode	= $_GET[kode];   		
				if(empty($kode)){


							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po, a.averg, a.stok,a.ratio,  
										COALESCE(((a.`jumlah_beli` + a.`stok` + a.`outstading_po`)/a.`averg`),0) AS HRasio 
										FROM po_pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang,COALESCE(outstading_po,0) AS outstading_po, a.averg, a.stok,a.ratio,
										COALESCE(ROUND(((a.`jumlah_beli` + a.`stok` + a.`outstading_po`)/a.`averg`),4),0) AS HRasio 
										FROM po_pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kodepo_beli='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,15,50,10);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){

						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[1],5,$data['kode_barang'],1,0,'C',$fill);
						  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
						  $this->Cell($w[3],5,number_format($data['averg']),1,0,'L',$fill);
						  $this->Cell($w[3],5,number_format($data['stok']),1,0,'L',$fill);
						  $this->Cell($w[3],5,$data['ratio'],1,0,'L',$fill);
						  //$this->Cell($w[3],5,number_format($data['outstading_po']),1,0,'L',$fill);
						  $this->Cell($w[3],5,'',1,0,'L',$fill);
						  $this->Cell($w[3],5,$data['HRasio'],1,0,'L',$fill);
						  $this->Ln();  
						  $no++;
						}
							$this->Cell(array_sum($w),0,'','T');
		}


		function FancyTable3(){
  	
			$kode	= $_GET[kode];   		
				if(empty($kode)){
							$query = "SELECT a.*,b.`namaproduk`,c.NmCabang FROM po_pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang`";
					}else{
							$query 	= "SELECT a.*,b.`namaproduk`,c.NmCabang FROM po_pembelian_psi a 
												LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk` 
												LEFT JOIN mastercabang c ON a.`cabang`=c.`KdCabang` 
												WHERE a.kodepo_beli='$kode'";
						} 
						
						
						
						$sql 	= mysql_query($query);
						$row	= mysql_num_rows($sql);

						
						$w=array(5,15,35);
						//$w=array(10,22,60,20,20,20,20,20);

						$no=1;
						while($data=mysql_fetch_array($sql)){
						  $this->Cell($w[0],5,$no,1,0,'C',$fill);
						  $this->Cell($w[1],5,$data['kode_barang'],1,0,'C',$fill);
						  $this->Cell($w[2],5,cabang1($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang2($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang3($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang4($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
						  $this->Cell($w[2],5,cabang5($data['cabang'],$data['kode_barang']),1,0,'L',$fill);
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
	$A[0]=235;
	$A[1]=300;
    $pdf=new PDF('P','mm',$A);
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetTitle('PO PSIKOTROPIKA');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
	$tgl = $tanggal_pr;
	$pdf->Ln(10);
	$pdf->SetLeftMargin(10);
    $pdf->SetFont('arial','BU',10);
    $pdf->Cell(0,5,'SURAT PESANAN PSIKOTROPIKA',0,1,'C');
    $pdf->SetFont('arial','B',10);
	$pdf->Cell(117,5,'No. PR  : '.kode_pr_po($kode),0,0,'R');
    $pdf->Cell(117,5,'No. PO  : '.$kode,0,1,'L');
	
	$pdf->Ln(5);
	
    $pdf->SetFont('arial','',9);
    $pdf->Cell(0,5,'Yang bertanda tangan dibawah ini :',0,1,'L');
    $pdf->Cell(30,5,'Nama',0,0,'L');
    $pdf->Cell(30,5,': '.cari_nama_apoteker_pusat($kode),0,1,'L');
    $pdf->Cell(30,5,'Alamat',0,0,'L');
    $pdf->Cell(30,5,': '.cari_alamat_apt_pusat($kode),0,1,'L');    
    $pdf->Cell(30,5,'SIKA',0,0,'L');
    $pdf->Cell(30,5,': '.cari_sika_pusat($kode),0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(0,5,'Mengajukan pemesanan obat mengandung Psikotropika, kepada :',0,1,'L');
    $pdf->Cell(30,5,'Nama Supplier',0,0,'L');
    $pdf->Cell(30,5,': '.nama_supplier_po($kode),0,1,'L');
    $pdf->Cell(30,5,'Alamat',0,0,'L');
    $pdf->Cell(30,5,': '.alamat_supplier_po_data($kode),0,1,'L');
    $pdf->Cell(30,5,'Telp',0,0,'L');
    $pdf->Cell(30,5,': '.telp_supplier_po_data($kode),0,1,'L');
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
    $pdf->Cell(30,5,': PT. Sapta Sari Tama Pusat',0,1,'L');
    $pdf->Cell(30,5,'Alamat PBF',0,0,'L');
    $pdf->Cell(30,5,': '.$alamatpbf,0,1,'L');
    $pdf->Cell(30,5,'Nomor Izin PBF',0,0,'L');
    $pdf->Cell(30,5,': '.$ijinpbf,0,1,'L');


	$pdf->Ln(2);
	$pdf->SetFont('arial','',9);
    $pdf->Cell(0,5,'Untuk Keperluan '.keperluan_pr_pre(kode_pr_po($kode)),0,1,'L');
	$pdf->Ln(2);
    $pdf->Cell(30,5,'Nama',0,0,'L');
    $pdf->Cell(30,5,': '.keper_nama_pr_pre(kode_pr_po($kode)),0,1,'L');
    $pdf->Cell(30,5,'Alamat',0,0,'L');
    $pdf->Cell(30,5,': '.keper_alm_pr_pre(kode_pr_po($kode)),0,1,'L');	
	
	$pdf->Ln(5);
	$pdf->SetFont('arial','',8);	
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'Bandung, '.$tgl,0,1,'C',false);
	$pdf->Ln(2);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'Pemesan, ',0,1,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'Apoteker Penanggung Jawab',0,1,'C',false);
	if(app_apt_pst($kode)=='Y'){$pdf->Image('images/ttd/nining31.png',175,$pdf->GetY()-4,40);}
	$pdf->Ln(10);
	$pdf->SetFont('arial','BU',8);	
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,cari_nama_apoteker_pusat($kode),0,1,'C',false);
	$pdf->SetFont('arial','',8);	
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,' ',0,0,'C',false);
	$pdf->Cell(73,3,'SIK No : '.cari_sika_pusat($kode),0,1,'C',false);
	
	$pdf->SetFont('arial','I',5);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'',0,0,'C',false);
	$pdf->Cell(73,3,'*'.tgl_apt_pst($kode),0,1,'L',false);
	
//keterangan tambahan	
	
		
	$pdf->Output('Cetak_PO_'.$kode.'.pdf','D'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
    echo $query;

  }
?>