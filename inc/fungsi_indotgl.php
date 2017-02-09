<?php
	function tgl_indo($tgl){
			$tanggal = substr($tgl,8,2);
			$bulan = getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}	

	function getBulan($bln){
				switch ($bln){
					case 1: 
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}
			} 

	function tgl_indo_balik($tgl){
			$tanggal = substr($tgl,0,2);
			$bulan = getBulanbalik(substr($tgl,3,3));
			$tahun = substr($tgl,7,4);
			return $tahun."-".$bulan."-".$tanggal;		 
	}	

	function getBulanbalik($bln){
				switch ($bln){
					case "Jan": 
						return "01";
						break;
					case "Feb":
						return "02";
						break;
					case "Mar":
						return "03";
						break;
					case "Apr":
						return "04";
						break;
					case "Mei":
						return "05";
						break;
					case "Jun":
						return "06";
						break;
					case "Jul":
						return "07";
						break;
					case "Aug":
						return "08";
						break;
					case "Sep":
						return "09";
						break;
					case "Oct":
						return "10";
						break;
					case "Nov":
						return "11";
						break;
					case "Dec":
						return "12";
						break;
				}
			} 			
?>
