<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$kode	= $_POST['kode'];
$cabang	= $_POST['cabang'];
$kodepr	= $_POST['kodepr'];
					
$text = "SELECT a.`Kode Produk` as text_kodeproduk, a.Produk as text_namaproduk, a.Satuan as text_satuan,
			b.HNA
			FROM mproduk a, t_harga b where a.`Kode Produk`=b.Produk AND a.`Kode Produk`='$kode'";
//echo $text.'<br>';
$sql 	= mysql_query($text) or die(mysql_error());
$row	= mysql_num_rows($sql);
//echo $text;
$produksyrat = produk_black_list($kode);
$produkoustandingbtb = cari_produk_btb_outstanding($cabang,$kode);
$produkoustandingpr = cari_produk_pr_outstanding($cabang,$kode,$kodepr);
$produkoustandingpr_pr = cari_produk_pr_outstanding_adapr($cabang,$kode,$kodepr);
$produkoustandingpr_nopr = cari_produk_pr_outstanding_nopr($cabang,$kode,$kodepr);
$produkoustandingpr_nopo = cari_produk_pr_outstanding_adapo($cabang,$kode,$kodepr);
$produkharga = number_format(produk_harga($kode));
$produktidaksp = produk_sp($cabang,$kode);
//echo $produksyrat;
//echo $cabang;

if ($row>0){
	while ($r=mysql_fetch_array($sql)){	
        
		$data['kode_barang']		    	= $r[text_kodeproduk];
		$data['nama_barang']		    	= $r[text_namaproduk];
		$data['satuan']			         	= $r[text_satuan];
		$data['t_ratio']	           		= $r[text_ratio];
		$data['t_avg']			           	= $r[text_avg];
		$data['t_stok']			        	= $r[text_stok];
		$data['t_kd_prins']		           	= $r[text_kodeprinsipal];
		$data['t_po_out']		           	= $r[outstading_po];
		$data['t_harga']		           	= $r[HNA];
		$data['produksyarat']		       	= $produksyrat;
		$data['produkoutstanding']	       	= $produkoustandingbtb;
		$data['produkoutstandingpr']		= $produkoustandingpr;
		$data['produkoutstandingpr_pr']		= $produkoustandingpr_pr;
		$data['produkoutstandingpr_nopr']	= $produkoustandingpr_nopr;
		$data['produkoutstandingpr_nopo']	= $produkoustandingpr_nopo;
		$data['kategorikhusus']		     	= $r[kategorikhusus];
		$data['t_produk_sp']		    	= $produktidaksp;
		
		echo json_encode($data);
	}
}else{
		$text	= "SELECT `Kode Produk` as kodeproduk, Produk as namaproduk, satuan as satuan FROM mproduk where `Kode Produk`='$kode'";
		$sql 	= mysql_query($text) or die(mysql_error());
		$row	= mysql_num_rows($sql);
		
		if ($row>0){
				while ($r=mysql_fetch_array($sql)){	
		
				$data['kode_barang']	       		= $r[kodeproduk];
				$data['nama_barang']	       		= $r[namaproduk];
				$data['satuan']			          	= $r[satuan];
				$data['t_ratio']		        	= 0;
				$data['t_avg']			           	= 0;
				$data['t_stok']		           		= 0;
				$data['t_po_out']	          		= 0;
				$data['t_harga']	           		= $produkharga;
				$data['produksyarat']      			= $produksyrat;
				$data['t_kd_prins']		           	= $r[kodeprinsipal];				
				$data['produkoutstanding']	     	= $produkoustandingbtb;
				$data['produkoutstandingpr']		= $produkoustandingpr;
				$data['produkoutstandingpr_pr']		= $produkoustandingpr_pr;
				$data['produkoutstandingpr_nopr']	= $produkoustandingpr_nopr;
				$data['produkoutstandingpr_nopo']	= $produkoustandingpr_nopo;
				$data['kategorikhusus']		       	= $r[kategorikhusus];
				$data['t_produk_sp']		      	= $produktidaksp;
			echo json_encode($data);
				}
			}
			else{
				$data['nama_barang']			= '';
				$data['satuan']				= '';
				$data['t_ratio']			= '';
				$data['t_avg']				= '';
				$data['t_stok']				= '';
				$data['t_ratio']			= 0;
				$data['t_avg']				= 0;
				$data['t_stok']				= 0;
				$data['t_po_out']			= 0;
				$data['t_harga']			= 0;
				$data['produksyarat']			= "";
				$data['t_kd_prins']			= "";
				$data['produkoutstanding']		= $produkoustandingbtb;
				$data['produkoutstandingpr']		= $produkoustandingpr;
				$data['produkoutstandingpr_pr']		= $produkoustandingpr_pr;
				$data['produkoutstandingpr_nopr']	= $produkoustandingpr_nopr;
				$data['produkoutstandingpr_nopo']	= $produkoustandingpr_nopo;
				$data['kategorikhusus']			= "";
				$data['t_produk_sp']			= "";

				echo json_encode($data);
			}
}
?>