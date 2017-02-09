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
			$query = "SELECT c.`namasupplier`,a.`kodeproduk`,a.`produk`,b.`satuan`,
				COALESCE(ROUND((a.`value`/a.`unit`),0),0) AS hpc,c.kodesupplier,a.`unit`,a.`kodecabang` 
					FROM `mstlogday` a
					LEFT JOIN mstproduk b ON a.`kodeproduk`=b.`kodeproduk`
					LEFT JOIN `mstsupplier2` c ON a.`supplier`=c.`kodesupplier`
					WHERE a.kodecabang='$kodecabbtb'
					ORDER BY c.namasupplier,a.`produk`,a.`kodeproduk`";
		}else{
			$query 	= "SELECT * FROM trans_sobb a WHERE a.kode_sobb='$kode'";
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
	
	
	function Header() 
	{

		$this->Image('images/logo_sp.png',11,2,100);
		$this->SetFont('arial','',7);
		$this->Cell(0,5,'STOK OPNAME BARANG BULANAN (SOBB) SUMMARY',0,1,'R');
		$this->$_GET[kode];
		$this->Ln(25);
		$this->SetFont('arial','B',12);
		$this->Cell(0,5,'LAPORAN HASIL STOK OPNAME BARANG BULANAN (SOBB)',0,1,'C');
		$this->Ln(5); 
		//$this->SetX(10);
	}
	
	//Page footer
	function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Page number
		$this->Cell(0,10,'Hal '.$this->PageNo().'/{nb}'.' - '.$_GET[kode],0,0,'C');
	}


	function selisihTable(){
  	
		$kode	= $_GET[kode]; 
  		
		if(empty($kode)){
			$query = "SELECT c.`namasupplier`,a.`kodeproduk`,a.`produk`,b.`satuan`,
				COALESCE(ROUND((a.`value`/a.`unit`),0),0) AS hpc,c.kodesupplier,a.`unit`,a.`kodecabang` 
					FROM `mstlogday` a
					LEFT JOIN mstproduk b ON a.`kodeproduk`=b.`kodeproduk`
					LEFT JOIN `mstsupplier2` c ON a.`supplier`=c.`kodesupplier`
					WHERE a.kodecabang='$kodecabbtb'
					ORDER BY c.namasupplier,a.`produk`,a.`kodeproduk`";
		}else{
			$query 	= "SELECT * FROM (SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((COALESCE(a.`hpc`,0) * COALESCE(a.qty_komp,0)),0),0) AS valuekomp,
									a.`unit_1`,a.`ed_1`,a.`unit_2`,a.`ed_2`,a.`unit_3`,a.`ed_3`,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobb a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobb='$kode' )sobb
					WHERE 	totalunitselisih <>0
					ORDER BY kode_sobb DESC, kode_produk ASC";
		} 
			
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(8,12,50,20,15,18);

		$no=1;
		
        while($data=mysql_fetch_array($sql)){
		  		  
		  $this->SetFont('arial','',7);
		  $this->Cell($w[0],5,$no,1,0,'C',$fill);
		  //$this->Cell($w[2],5,$data['namasupplier'],1,0,'L',$fill);
		  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
		  $this->SetFont('arial','',8);
		  $this->Cell($w[3],5,$data['kode_produk'],1,0,'L',$fill);
		  $this->Cell($w[1],5,$data['satuan'],1,0,'L',$fill);
		  $this->Cell($w[4],5,$data['unitkomp'],1,0,'C',$fill);
		  $this->Cell($w[5],5,format_rupiah($data['valuekomp']),1,0,'R',$fill);
		  $this->Cell($w[4],5,$data['totalunit'],1,0,'C',$fill);
		  $this->Cell($w[5],5,format_rupiah($data['totalvalue']),1,0,'R',$fill);
		  $this->Cell($w[4],5,$data['totalunitselisih'],1,0,'C',$fill);
		  $this->Cell($w[5],5,format_rupiah($data['totalvalueselisih']),1,0,'R',$fill);
          $this->Ln();  
		  $no++;
        }
        	$this->Cell(array_sum($w),0,'','T');
      }
	  
  
 	function sudahEdTable(){
  	
		$kode	= $_GET[kode]; 
  		
		if(empty($kode)){
			$query = "SELECT c.`namasupplier`,a.`kodeproduk`,a.`produk`,b.`satuan`,
				COALESCE(ROUND((a.`value`/a.`unit`),0),0) AS hpc,c.kodesupplier,a.`unit`,a.`kodecabang` 
					FROM `mstlogday` a
					LEFT JOIN mstproduk b ON a.`kodeproduk`=b.`kodeproduk`
					LEFT JOIN `mstsupplier2` c ON a.`supplier`=c.`kodesupplier`
					WHERE a.kodecabang='$kodecabbtb'
					ORDER BY c.namasupplier,a.`produk`,a.`kodeproduk`";
		}else{
			$query 	= "(	
					SELECT * FROM (
					SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
								b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
								COALESCE(a.qty_komp,0)  AS unitkomp, 
								ROUND(COALESCE((a.hpc * a.`qty_komp`),0),0) AS valuekomp,
								a.`unit_1` AS unit_ed ,a.`ed_1` AS ed,
								COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
								ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
								COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
								ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
							FROM trans_sobb a 
							LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
										LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
							WHERE a.kode_sobb='$kode' AND a.ed_1 <> '0000-00-00')sobb
						WHERE 	ed <= NOW()
				)UNION ALL
				(
					SELECT * FROM (
					SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
								b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
								COALESCE(a.qty_komp,0)  AS unitkomp, 
								ROUND(COALESCE((a.hpc * a.`qty_komp`),0),0) AS valuekomp,
								a.`unit_2` AS unit_ed,a.`ed_2` AS ed,
								COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
								ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
								COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
								ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
							FROM trans_sobb a 
							LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
										LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
							WHERE a.kode_sobb='$kode' AND a.ed_2 <> '0000-00-00')sobb
						WHERE 	ed <= NOW()
				)UNION ALL
				(
					SELECT * FROM (
					SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
								b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
								COALESCE(a.qty_komp,0)  AS unitkomp, 
								ROUND(COALESCE((a.hpc * a.`qty_komp`),0),0) AS valuekomp,
								a.`unit_3` AS unit_ed,a.`ed_3` AS ed,
								COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
								ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
								COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
								ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
							FROM trans_sobb a 
							LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
										LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
							WHERE a.kode_sobb='$kode' AND a.ed_3 <> '0000-00-00')sobb
						WHERE 	ed <= NOW()

				)";
		} 
			
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(8,12,50,20);

		$no=1;
        while($data=mysql_fetch_array($sql)){
		  $this->SetFont('arial','',7);
		  $this->Cell($w[0],5,$no,1,0,'C',$fill);
		  //$this->Cell($w[2],5,$data['namasupplier'],1,0,'L',$fill);
		  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
		  $this->SetFont('arial','',8);
		  $this->Cell($w[3],5,$data['kode_produk'],1,0,'L',$fill);
		  $this->Cell($w[1],5,$data['satuan'],1,0,'L',$fill);
		  $this->Cell($w[3],5,$data['unit_ed'],1,0,'C',$fill);
		  $this->Cell($w[3],5,$data['ed'],1,0,'L',$fill);
          $this->Ln();  
		  $no++;
        }
        	$this->Cell(array_sum($w),0,'','T');
      } 
	  
	  

 	function mendekatiEdTable(){
  	
		$kode	= $_GET[kode]; 
  		
		if(empty($kode)){
			$query = "SELECT c.`namasupplier`,a.`kodeproduk`,a.`produk`,b.`satuan`,
				COALESCE(ROUND((a.`value`/a.`unit`),0),0) AS hpc,c.kodesupplier,a.`unit`,a.`kodecabang` 
					FROM `mstlogday` a
					LEFT JOIN mstproduk b ON a.`kodeproduk`=b.`kodeproduk`
					LEFT JOIN `mstsupplier2` c ON a.`supplier`=c.`kodesupplier`
					WHERE a.kodecabang='$kodecabbtb'
					ORDER BY c.namasupplier,a.`produk`,a.`kodeproduk`";
		}else{
			$query 	= "(	
						SELECT * FROM (
						SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((a.hpc * a.`qty_komp`),0),0) AS valuekomp,
									a.`unit_1` AS unit_ed ,a.`ed_1` AS ed,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobb a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobb='$kode' AND a.ed_1 <> '0000-00-00')sobb
							WHERE 	ed BETWEEN NOW() AND (NOW() + INTERVAL '1' YEAR)  
					)UNION ALL
					(
						SELECT * FROM (
						SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((a.hpc * a.`qty_komp`),0),0) AS valuekomp,
									a.`unit_2` AS unit_ed,a.`ed_2` AS ed,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobb a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobb='$kode' AND a.ed_2 <> '0000-00-00')sobb
							WHERE 	ed BETWEEN NOW() AND (NOW() + INTERVAL '1' YEAR)  
					)UNION ALL
					(
						SELECT * FROM (
						SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((a.hpc * a.`qty_komp`),0),0) AS valuekomp,
									a.`unit_3` AS unit_ed,a.`ed_3` AS ed,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobb a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobb='$kode' AND a.ed_3 <> '0000-00-00')sobb
							WHERE 	ed BETWEEN NOW() AND (NOW() + INTERVAL '1' YEAR)  

					)";
		} 
			
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(8,12,50,20);

		$no=1;
        while($data=mysql_fetch_array($sql)){
		  $this->SetFont('arial','',7);
		  $this->Cell($w[0],5,$no,1,0,'C',$fill);
		  //$this->Cell($w[2],5,$data['namasupplier'],1,0,'L',$fill);
		  $this->Cell($w[2],5,$data['namaproduk'],1,0,'L',$fill);
		  $this->SetFont('arial','',8);
		  $this->Cell($w[3],5,$data['kode_produk'],1,0,'L',$fill);
		  $this->Cell($w[1],5,$data['satuan'],1,0,'L',$fill);
		  $this->Cell($w[3],5,$data['unit_ed'],1,0,'C',$fill);
		  $this->Cell($w[3],5,$data['ed'],1,0,'L',$fill);
          $this->Ln();  
		  $no++;
        }
        	$this->Cell(array_sum($w),0,'','T');
      } 
	  
	  
	  

	  
	function allDataTable(){
  	
		$kode	= $_GET[kode]; 
  		
		if(empty($kode)){
			$query = "SELECT c.`namasupplier`,a.`kodeproduk`,a.`produk`,b.`satuan`,
				COALESCE(ROUND((a.`value`/a.`unit`),0),0) AS hpc,c.kodesupplier,a.`unit`,a.`kodecabang` 
					FROM `mstlogday` a
					LEFT JOIN mstproduk b ON a.`kodeproduk`=b.`kodeproduk`
					LEFT JOIN `mstsupplier2` c ON a.`supplier`=c.`kodesupplier`
					WHERE a.kodecabang='$kodecabbtb'
					ORDER BY c.namasupplier,a.`produk`,a.`kodeproduk`";
		}else{
			$query 	= "SELECT * FROM (SELECT a.`kodecabang`,a.`kode_sobb`,a.`tgl_sobb`,b.namasupplier,a.`kode_produk`,
									b.namaproduk,COALESCE(a.`hpc`,0) AS hpc,b.satuan,
									COALESCE(a.qty_komp,0)  AS unitkomp, 
									ROUND(COALESCE((a.hpc * a.`qty_komp`),0),0) AS valuekomp,
									a.`unit_1`,a.`ed_1`,a.`unit_2`,a.`ed_2`,a.`unit_3`,a.`ed_3`,
									COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0) AS totalunit, 
									ROUND((COALESCE((a.`unit_1` + a.`unit_2` + a.`unit_3`),0)*(COALESCE((a.hpc/a.`qty_komp`),0))),0) AS totalvalue,
									COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0) AS totalunitselisih,
									ROUND((COALESCE(((a.`unit_1` + a.`unit_2` + a.`unit_3`) - a.qty_komp),0)*(COALESCE((a.hpc),0))),0) AS totalvalueselisih
								FROM trans_sobb a 
								LEFT JOIN (SELECT a.`kodeproduk`,a.namaproduk,a.`kodesupplier`,b.`namasupplier`,a.satuan FROM `mstproduk` a 
											LEFT JOIN `mstsupplier2` b ON a.`kodesupplier`=b.`kodesupplier`) b ON a.`kode_produk`=b.kodeproduk 
								WHERE a.kode_sobb='$kode' )sobb
					ORDER BY kode_sobb DESC, kode_produk ASC";
		} 
			
		
		
		$sql 	= mysql_query($query);
		$row	= mysql_num_rows($sql);

		
		$w=array(8,12,50,20,10,18);

		$no=1;
        while($data=mysql_fetch_array($sql)){
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
    $pdf->SetTitle('SOBB SUMMARY');
    $pdf->SetAuthor('(c) SST-ITEDP');
    $pdf->SetCreator('PT. SAPTASARITAMA with fpdf');
//	$tgl = date('d M Y');
	$tgl = $tanggal_sobb;
	$pdf->SetX(10);
    $pdf->SetFont('arial','B',10);
	$pdf->Ln(2); 
    $pdf->Cell(0,5,'Kami Cabang '.$namacabang.' Telah Melaksanakan SOBB dengan Nomor '.$kode.' pada tanggal '.$tgl,0,1,'L');
    $pdf->Cell(0,5,'dengan ini melaporkan hasilnya.',0,1,'L');
	$pdf->Ln(2);
	
//	$pdf->SetLeftMargin(2);
	$pdf->SetFont('arial','',8);
    $pdf->Cell(0,5,'A. Barang yang Selisih',0,1,'L');
	$pdf->Ln(2);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(8,5,'No.',1,0,'C',true);
	//$pdf->Cell(50,5,'Nama Supplier',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Produk',1,0,'C',true);
	$pdf->Cell(20,5,'KProduk',1,0,'C',true);
	$pdf->Cell(12,5,'Satuan',1,0,'C',true);
	$pdf->Cell(15,5,'TUnK',1,0,'C',true);
	$pdf->Cell(18,5,'TValK',1,0,'C',true);
	$pdf->Cell(15,5,'TUnF',1,0,'C',true);
	$pdf->Cell(18,5,'TValF',1,0,'C',true);
	$pdf->Cell(15,5,'TUnSel',1,0,'C',true);
	$pdf->Cell(18,5,'TValSel',1,1,'C',true);

	$pdf->SetLineWidth(.1);
	$pdf->selisihTable();	
	$pdf->Ln(5);
	
	$pdf->SetFont('arial','B',8);
    $pdf->Cell(0,5,'Keterangan',0,1,'L');
    $pdf->Cell(0,5,'* TUnK		= Total Unit Komputer',0,1,'L');
    $pdf->Cell(0,5,'* TValK		= Total Value Komputer',0,1,'L');
    $pdf->Cell(0,5,'* TUnF		= Total Unit Fisik',0,1,'L');
    $pdf->Cell(0,5,'* TValF		= Total Value Fisik',0,1,'L');
    $pdf->Cell(0,5,'* TUnSel	= Total Unit Selisih',0,1,'L');
    $pdf->Cell(0,5,'* TValSel	= Total Value Selisih',0,1,'L');
	$pdf->Ln(5);
	$pdf->SetFont('arial','B',12);
    $pdf->Cell(0,5,'Total Unit Selisih  = '.$totselisihunitsobbxx,0,1,'L');
    $pdf->Cell(0,5,'Total Value Selisih  = '.$totselisihvaluesobbxx,0,1,'L');
	

	$pdf->AddPage();//page break	
//	$pdf->SetLeftMargin(10);
	$pdf->SetFont('arial','',8);
    $pdf->Cell(0,5,'B. Barang yang sudah ED',0,1,'L');
	$pdf->Ln(2);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(8,5,'No.',1,0,'C',true);
//	$pdf->Cell(50,5,'Nama Supplier',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Produk',1,0,'C',true);
	$pdf->Cell(20,5,'KProduk',1,0,'C',true);
	$pdf->Cell(12,5,'Satuan',1,0,'C',true);
	$pdf->Cell(20,5,'Unit ED',1,0,'C',true);
	$pdf->Cell(20,5,'ED',1,1,'C',true);

	$pdf->SetLineWidth(.1);
	$pdf->sudahEdTable();	
	$pdf->Ln(5);	
	
	$pdf->AddPage();//page break	
	$pdf->SetFont('arial','',8);
    $pdf->Cell(0,5,'C. Mendekati  ED',0,1,'L');
	$pdf->Ln(2);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(8,5,'No.',1,0,'C',true);
//	$pdf->Cell(50,5,'Nama Supplier',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Produk',1,0,'C',true);
	$pdf->Cell(20,5,'KProduk',1,0,'C',true);
	$pdf->Cell(12,5,'Satuan',1,0,'C',true);
	$pdf->Cell(20,5,'Unit ED',1,0,'C',true);
	$pdf->Cell(20,5,'ED',1,1,'C',true);

	$pdf->SetLineWidth(.1);
	$pdf->mendekatiEdTable();	
	$pdf->Ln(5);	
	
/* 	$pdf->AddPage();//page break
	$pdf->SetLeftMargin(2);
	$pdf->SetFont('arial','',8);
    $pdf->Cell(0,5,'D. Data Produk SOBB',0,1,'L');
	$pdf->Ln(2);
	$pdf->SetFont('arial','B',8);
	$pdf->SetLineWidth(.1);
	$pdf->SetFillColor(229,229,229);
	$pdf->Cell(8,5,'No.',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Supplier',1,0,'C',true);
	$pdf->Cell(50,5,'Nama Produk',1,0,'C',true);
	$pdf->Cell(20,5,'KProduk',1,0,'C',true);
	$pdf->Cell(12,5,'Satuan',1,0,'C',true);
	$pdf->Cell(10,5,'Unit1',1,0,'C',true);
	$pdf->Cell(18,5,'ED1',1,0,'C',true);
	$pdf->Cell(10,5,'Unit2',1,0,'C',true);
	$pdf->Cell(18,5,'ED2',1,0,'C',true);
	$pdf->Cell(10,5,'Unit3',1,0,'C',true);
	$pdf->Cell(18,5,'ED3',1,1,'C',true);

	$pdf->SetLineWidth(.1);
	$pdf->allDataTable();	
	$pdf->Ln(5); */	
	
	
	$pdf->SetLeftMargin(10);
	$pdf->SetFont('arial','B',7);	
	$pdf->Cell(30,5,'*STOCK OPNAME BARANG BULANAN INI ADALAH BENAR BAHWA CABANG KAMI MELAKUKAN OPNAME DENGAN YANG TERTERA DIATAS.',0,1,'L',false);
	$pdf->Ln(1);
	$pdf->SetFont('arial','',7);	
	$pdf->Cell(70,3,'Penanggung Jawab 1 , ',0,0,'C',false);
	$pdf->Cell(70,3,'Penanggung Jawab 2 ,',0,0,'C',false);
	$pdf->Cell(70,3,'Mengetahui, ',0,1,'C',false);
	$pdf->Ln(10);
	$pdf->Cell(70,3,'_______________________',0,0,'C',false);
	$pdf->Cell(70,3,'_______________________',0,0,'C',false);
	$pdf->Cell(70,3,'_______________________',0,1,'C',false);
	$pdf->Cell(70,3,'Kepala Logistik',0,0,'C',false);
	$pdf->Cell(70,3,'Branch Manager',0,0,'C',false);
	$pdf->Cell(70,3,'Regional Branch Manager',0,1,'C',false);
	$pdf->Ln(3);
	$pdf->SetFont('arial','B',7);
	$pdf->Cell(70,3,'*[Nama jelas dan tanda tangan]',0,1,'L',false);
	
	$pdf->Output('Cetak_SOBB_'.$kode.'.pdf','D'); //D=Download, I= ,
  } else {
    echo "<h1>Maaf, data tidak ditemukan</h1><br>";
	echo $query."</br>";
  }
?>