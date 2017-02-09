<?php
  include_once('../../inc/inc.koneksi.php');
  include_once('../../inc/fungsi_hdt.php');
  include_once('../../inc/fungsi_indotgl.php');
  include_once('../../inc/fungsi_tanggal.php');
  include_once('../../inc/fungsi_rupiah.php');  
  date_default_timezone_set('Asia/Jakarta'); 
  
	
$kode1	= jin_date_sql($_GET['kode1']);
$kode2	= $_GET['kode2'];
$cabang	= $_GET['cabang'];
//cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime($kode1));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));
	
//Definisi
define('FPDF_FONTPATH','../font/');
require('fpdf.php');

class PDF extends FPDF{





	function Header() 
	{
		$kode2 =$_GET['kode2'];
		$tgl_awal = date('Y-m-01', strtotime(jin_date_sql($_GET['kode1'])));
		$tgl_akhir = date('Y-m-t', strtotime(jin_date_sql($_GET['kode1'])));
		$sqlProd = mysql_query("SELECT `Kode Produk` AS kodep, `Produk` AS nama,`Satuan` FROM `mproduk` WHERE `Kode Produk`='$kode2'");
		$hasilProd=mysql_fetch_array($sqlProd);
		$this->Image('images/logo_sp.png',11,2,100);
		$this->SetFont('arial','B',9);
		$this->Cell(0,5,'LAPORAN KARTU GUDANG',0,1,'R');
		$this->SetFont('arial','B',7);
		$this->Cell(0,3,$_GET['cabang'],0,1,'R');
		$this->Cell(0,3,'Produk : '.$hasilProd['kodep']." - ".$hasilProd['nama'],0,1,'R');
		$this->Cell(0,3,'Satuan : '.$hasilProd['Satuan'],0,1,'R');
		$this->Cell(0,3,'Periode '.tgl_indo($tgl_awal).' s/d '.tgl_indo($tgl_akhir) ,0,1,'R');
		$this->SetFont('arial','B',6);
		$this->Cell(0,3,'Hal '.$this->PageNo().'/{nb}'.' - '.$_GET['cabang']." - ".$_GET['kode2'],0,1,'R');
		$this->Ln(10);
//		$this->SetX(10);
		$this->SetFont('arial','B',8);
		$this->SetLineWidth(.1);
		$this->SetFillColor(229,229,229);
		$this->Cell(5,5,'No.',1,0,'C',true);
		$this->Cell(15,5,'Tanggal',1,0,'C',true);
		$this->Cell(65,5,'Terma dari /  Kirim Kepada',1,0,'C',true);
		$this->Cell(40,5,'No Dok',1,0,'C',true);
		$this->Cell(15,5,'Batch',1,0,'C',true);
		$this->Cell(15,5,'Exp',1,0,'C',true);
		$this->Cell(15,5,'Masuk',1,0,'C',true);
		$this->Cell(15,5,'Keluar',1,0,'C',true);	
		$this->Cell(15,5,'Saldo',1,1,'C',true);

	}
	
	//Page footer
	function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',7);
		//Page number
		$this->Cell(0,10,'Hal '.$this->PageNo().'/{nb}'.' - '.$_GET['cabang']." - ".$_GET['kode2'],0,0,'C');
	}
	
	




   function FancyTable(){

$kode1	= jin_date_sql($_GET['kode1']);
$kode1a	= jin_date_sql($_GET['kode1a']);
$kode2	= $_GET['kode2'];
$cabang	= $_GET['cabang'];
//cek Tgl
$hari_ini = date("Y-m-d");
// Tanggal pertama pada bulan ini
$tgl_awal = date('Y-m-01', strtotime($kode1));
// Tanggal terakhir pada bulan ini
$tgl_akhir = date('Y-m-t', strtotime(isset($kode1));
//ambil bulan saja
$mth = date('m', strtotime($kode1));
$mth1 = date('m', strtotime($kode1.'+ 1 months'));

	if (empty($kode1) || empty($kode2) || empty($cabang)){
	
	}else{
	$query = "SELECT *
					FROM(
					SELECT DATE(`Tgl BPB`) AS `Tanggal`, 
						`Supplier` AS Pelanggan,
						`No BPB`AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0))) AS `Mutasi`, 		
						`Time BPB` AS dTime,
						Produk,`Counter BPB` AS counter, `Keterangan` AS Keterangan, 
						`HPC1` AS cogs,
						`Value BPB` AS val, 
						ROUND((`Value BPB`/((IFNULL(`Qty Terima`,0) + IFNULL(`Bonus`,0)))),0) AS cogs2
					FROM `dbpbdodetail`
					WHERE Cabang='$cabang' AND Produk='$kode2'
					AND (`Status BPB` = 'Open' OR `Status BPB` = 'DO' OR `Status BPB` = 'BKB Retur'
					OR `Status BPB` = 'BPB Retur' OR `Status BPB` = 'BPB Relokasi' OR `Status BPB` = 'BKB Relokasi'
					OR `Status BPB` = 'BKB Koreksi' OR `Status BPB` = 'BPB Koreksi'
					OR `Status BPB` = 'BKB Konversi' OR `Status BPB` = 'BPB Konversi')
					UNION ALL
					SELECT DATE(`Tanggal`) AS `Tanggal`,
						`Nama Faktur` AS Pelanggan,
						`No Faktur` AS NoDok,
						IFNULL(`Batch No`,'') AS batch, 
						IFNULL(DATE(`Exp Date`),'') AS expr,
						((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 ) AS `Mutasi`, 
						`Time` AS dTime,
						Produk,`Counter` AS counter, '' AS Keterangan, 
						ROUND((`Total COGS`/((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 )),0) AS cogs , 
						`Total COGS` AS val, 
						ROUND((`Total COGS`/((IFNULL(`Jumlah`,0) + IFNULL(`Bonus Faktur`,0)) * -1 )),0) AS cogs2
					FROM `dsalesdetail` 
					WHERE Cabang='$cabang' AND Produk='$kode2'
					AND (`Status`='Faktur' OR `Status`='Retur')
					 )a
					 WHERE a.Tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
					 ORDER BY a.Tanggal,a.Counter,a.dTime";		
	}
	
	
	
	$sql 	= mysql_query($query);
	$row	= mysql_num_rows($sql);
	$sqlProd = mysql_query("SELECT `Kode Produk` AS kodep, `Produk` AS nama,`Satuan` FROM `mproduk` WHERE `Kode Produk`='$kode2'");
	$hasilProd=mysql_fetch_array($sqlProd);


	$w=array(5,15,15,75,20,80);
	//$w=array(10,22,60,20,20,20,20,20);
	$sAwal = SaldoAwalStok($kode2,$kode1,$cabang);
	$tglD = $tgl_awal;
	$msk = $sAwal[0];
	$klr = 0;
	$bal = $sAwal[0];
	$no=1;
//Saldo Awal
		$this->SetFont('arial','',6);
		$this->Cell(5,4,$no,1,0,'C',$fill);
		$this->Cell(15,4,$tgl_awal,1,0,'C',$fill);	
		$this->Cell(165,4,"Saldo Awal ".$hasilProd['kodep']." - ".$hasilProd['nama']."",1,0,'L',$fill);
		$this->Cell(15,4,format_rupiah($bal),1,1,'L',$fill);
//Detail
	while($data=mysql_fetch_array($sql)){
		$sld= $data['Mutasi'];
		$vsld = $data['cogs'];
		$bal= $bal + $sld;
		$no++;		
		$this->SetFont('arial','',6);
		$this->Cell(5,4,$no,1,0,'C',$fill);
		$this->Cell(15,4,$data['Tanggal'],1,0,'C',$fill);
		if (strpos($data['NoDok'], "KOR") !== false) {
			$this->Cell(65,4,$data['Keterangan'],1,0,'L',$fill);
		}else
		{
			$this->Cell(65,4,$data['Pelanggan'],1,0,'L',$fill);
		}
		$this->Cell(40,4,$data['NoDok'],1,0,'L',$fill);
		$this->Cell(15,4,$data['batch'],1,0,'C',$fill);
		$this->Cell(15,4,$data['expr'],1,0,'C',$fill);
		if($sld>0)
		{	  
			$this->Cell(15,4,format_rupiah($sld),1,0,'R',$fill);
			$this->Cell(15,4,'',1,0,'R',$fill);	
		}else
		{
			$this->Cell(15,4,'',1,0,'R',$fill);
			$this->Cell(15,4,format_rupiah(($sld*-1)),1,0,'R',$fill);	
		}
		$this->Cell(15,4,format_rupiah($bal),1,0,'R',$fill);
		$this->Ln();  
		}
		//Saldo Akhir
		$this->SetFont('arial','',6);
		$this->Cell(5,4,$no+1,1,0,'C',$fill);
		$this->Cell(15,4,$tgl_akhir,1,0,'C',$fill);	
		$this->Cell(165,4,"Saldo Akhir ".$hasilProd['kodep']." - ".$hasilProd['nama']."",1,0,'L',$fill);
		$this->Cell(15,4,format_rupiah($bal),1,1,'L',$fill);

		$this->Cell(200,4,'',0,1,'L',$fill);
		$this->Cell(200,4,'',0,1,'L',$fill);
		
//		$this->Cell(200,0,'','T');
		
		//Saldo Awal
		$this->SetFont('arial','',6);
		$this->Cell(185,4,"Saldo Awal ".$hasilProd['kodep']." - ".$hasilProd['nama']."",1,0,'L',$fill);
		$this->Cell(15,4,format_rupiah($sAwal[0]),1,1,'L',$fill);
		$mth = date('m', strtotime($tglD));
		$sqlg	= "SELECT IFNULL((SAwal$mth ),0) AS unit,IFNULL((VAwal$mth),0) AS val, Gudang FROM `dinventorysummary` WHERE `Produk`='$kode2' 
						AND `cabang`='$cabang' ORDER BY Gudang";		
		$queryg = mysql_query($sqlg);
		while($g_data=mysql_fetch_array($queryg)){
		//echo "<tr><td colspan='5'>Gudang ".$g_data[Gudang]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'>".format_rupiah($g_data[unit])."</td><td align='right'>".format_rupiah($g_data[val])."</td><td align='right' colspan='2'></td></tr>";		
		$this->Cell(170,4," *Gudang ".$g_data[Gudang]." - ".$hasilProd['kodep']." - ".$hasilProd['nama'],1,0,'L',$fill);
		$this->Cell(15,4,format_rupiah($g_data[unit]),1,1,'L',$fill);		
		}
		
		
		
		//cek Saldo Akhir
		$tglcek = date('Y-m-01', strtotime($kode1));
		$cekhariberjalan = date('Y-m-01', strtotime($hari_ini));
		//echo $hari_ini." - ".$tglcek." - ".$cekhariberjalan;
  		if ($cekhariberjalan == $tglcek)
		{
		$this->Cell(185,4,"Saldo Akhir ".$hasilProd['kodep']." - ".$hasilProd['nama']."",1,0,'L',$fill);
		$this->Cell(15,4,format_rupiah(SaldoAwalStok($kode2,$tglD,$cabang)[3]),1,1,'L',$fill);		

			$sqlgs	= "SELECT IFNULL((`Unit Stok`),0) AS unit,IFNULL((`Value Stok`),0) AS val, Gudang FROM `dinventorysummary` WHERE `Produk`='$kode2' 
							AND `cabang`='$cabang' ORDER BY Gudang";		
			$querygs = mysql_query($sqlgs);
			while($gs_data=mysql_fetch_array($querygs)){
			$this->Cell(170,4," *Gudang ".$gs_data[Gudang]." - ".$hasilProd['kodep']." - ".$hasilProd['nama'],1,0,'L',$fill);
			$this->Cell(15,4,format_rupiah($gs_data[unit]),1,1,'L',$fill);		
			//echo "<tr><td colspan='5'>Gudang ".$gs_data[Gudang]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'>".format_rupiah($gs_data[unit])."</td><td align='right'>".format_rupiah($gs_data[val])."</td><td align='right'  colspan='2'></td></tr>";		
			}
		}else
		{
		$this->Cell(185,4,"Saldo Akhir ".$hasilProd['kodep']." - ".$hasilProd['nama']."",1,0,'L',$fill);
		$this->Cell(15,4,format_rupiah(SaldoAwalStok($kode2,date('Y-m-d', strtotime($tglD. ' + 1 months')),$cabang)[0]),1,1,'L',$fill);		

			$mth1 = date('m', strtotime($tglD.' + 1 months'));
			$sqlg1	= "SELECT IFNULL((SAwal$mth1 ),0) AS unit,IFNULL((VAwal$mth1),0) AS val, Gudang FROM `dinventorysummary` WHERE `Produk`='$kode2' 
							AND `cabang`='$cabang' ORDER BY Gudang";		
			$queryg1 = mysql_query($sqlg1);
			while($g_data1=mysql_fetch_array($queryg1)){
			$this->Cell(170,4," *Gudang ".$g_data1[Gudang]." - ".$hasilProd['kodep']." - ".$hasilProd['nama'],1,0,'L',$fill);
			$this->Cell(15,4,format_rupiah($g_data1[unit]),1,1,'L',$fill);		
			//echo "<tr><td colspan='5'>Gudang ".$g_data1[Gudang]." : ".$hasilProd['kodep']." - ".$hasilProd['nama']."</td><td align='right'>".format_rupiah($g_data1[unit])."</td><td align='right'>".format_rupiah($g_data1[val])."</td><td align='right' colspan='2'></td></tr>";		
			}
		}
		
  }
}

	$sqlProd = mysql_query("SELECT `Kode Produk` AS kodep, `Produk` AS nama,`Satuan` FROM `mproduk` WHERE `Kode Produk`='$kode2'");
	$hasilProd=mysql_fetch_array($sqlProd);


//Instanciation of inherited class
$A4[0]=210;
$A4[1]=297;
$Q[0]=216;
$Q[1]=279;
$S[0]=230;
$S[1]=145;
$pdf=new PDF('P','mm',$Q);
$pdf->SetMargins(10,1,5);
//$pdf->SetTopMargin(1);	
$pdf->SetAutoPageBreak(True,15);	
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('PO RUTIN');
$pdf->SetAuthor('(c) SST-ITEDP');
$pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
$tgl = $tanggal_po_beli;
//$pdf->Image('images/logo_sp.png',11,2,100);
//$pdf->Ln(2);
// $pdf->SetFont('arial','B',8);
// $pdf->SetLineWidth(.1);
// $pdf->SetFillColor(229,229,229);
// $pdf->Cell(5,5,'No.',1,0,'C',true);
// $pdf->Cell(15,5,'Tanggal',1,0,'C',true);
// $pdf->Cell(65,5,'Terma dari /  Kirim Kepada',1,0,'C',true);
// $pdf->Cell(40,5,'No Dok',1,0,'C',true);
// $pdf->Cell(15,5,'Batch',1,0,'C',true);
// $pdf->Cell(15,5,'Exp',1,0,'C',true);
// $pdf->Cell(15,5,'Masuk',1,0,'C',true);
// $pdf->Cell(15,5,'Keluar',1,0,'C',true);	
// $pdf->Cell(15,5,'Saldo',1,1,'C',true);
$pdf->SetFont('arial','',12);
$pdf->SetLineWidth(.1);
$pdf->FancyTable();	
$pdf->AddPage();
$pdf->SetFont('arial','B',7);	
$pdf->Cell(30,5,'*Kartu Gudang ini harap bisa dipertanggungjawabkan oleh pihak berwenang ',0,1,'L',false);
$pdf->Ln(1);
$pdf->SetFont('arial','',7);	
$pdf->Cell(60,3,'',0,0,'C',false);
$pdf->Cell(60,3,'',0,0,'C',false);
$pdf->Cell(60,3,$cabang.", ".tgl_indo(date("Y-m-d")),0,1,'R',false);
$pdf->Cell(60,3,'Kepala Gudang',0,0,'C',false);
$pdf->Cell(60,3,'Apoteker',0,0,'C',false);
$pdf->Cell(60,3,'Branch Manager',0,1,'C',false);
$pdf->Ln(10);
$pdf->Cell(60,3,'_______________________',0,0,'C',false);
$pdf->Cell(60,3,'_______________________',0,0,'C',false);
$pdf->Cell(60,3,'_______________________',0,1,'C',false);
$pdf->Cell(60,3,'Cap & Nama',0,0,'C',false);
$pdf->Cell(60,3,'',0,0,'C',false);
$pdf->Cell(60,3,'Cap & Nama',0,1,'C',false);
$pdf->Cell(60,3,'SIK No: ',0,0,'C',false);
$pdf->Cell(60,3,'Cap & Nama',0,0,'C',false);
$pdf->Cell(60,3,'',0,1,'C',false);

$pdf->Output('KARTU_GUDANG_'.$kode2.'.pdf','D'); //D=Download, I= ,


?>