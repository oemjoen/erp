// JavaScript Document

function Left(str, n){
	if (n <= 0)
	    return "";
	else if (n > String(str).length)
	    return str;
	else
	    return String(str).substring(0,n);
}
function Right(str, n){
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }
}

$(document).ready(function() {
	//membuat text kode barang menjadi Kapital
	$("#txt_kode_barang").keyup(function(e){
		var isi = $(e.target).val();
		$(e.target).val(isi.toUpperCase());
	});

	// format datepicker untuk tanggal
	$("#txt_tgl_beli").datepicker({
	  dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  buttonImage     : "images/calendar.gif",
	  buttonImageOnly : true				
	});

	$("#txt_tgl_beli_list").datepicker({
	  dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  buttonImage     : "images/calendar.gif",
	  buttonImageOnly : true				
	});
	
	//hanya angka yang dapat dientry
	$("#txt_jumlah").keypress(function(data){
		if (data.which!=8 && data.which!=0 && (data.which<48 || data.which>57)) 
		{
			return false;
		}
	});
	
	function cekratio(){
		var nilairatio	= $("#txt_ratio").val();

		if (nilairatio >= 2) {
			document.getElementById("txt_ratio").style.backgroundColor = "red";
		}else{
			document.getElementById("txt_ratio").style.backgroundColor = "#00FFFF";
		}
		
	}

	function disableTxt() {
		document.getElementById("txt_diskon").disabled = true;
		document.getElementById("txt_diskon").style.backgroundColor = "red";
		cekratio();
	}
	function undisableTxt() {
		document.getElementById("txt_diskon").disabled = false;
		document.getElementById("txt_diskon").style.backgroundColor = "white";
		cekratio();
	}
		
	//disableTxt();
	
	$("#txt_kode_barang").keyup(function(){
			var kodeprins	= $("#txt_prinsipal_prod").val();
			var kodeekat	= $("#txt_kode_barang").val();			
	
			if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				undisableTxt();
			}else{
				$("#txt_diskon").val('');
				disableTxt();
			}
		});

	$("#txt_kode_barang").focus(function(){
			var kodeprins	= $("#txt_prinsipal_prod").val();
			var kodeekat	= $("#txt_kode_barang").val();			
	
			if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				undisableTxt();
			}else{
				$("#txt_diskon").val('');
				disableTxt();
			}
		});	

	$("#txt_kode_barang").blur(function(){
			var kodeprins	= $("#txt_prinsipal_prod").val();
			var kodeekat	= $("#txt_kode_barang").val();			
	
			if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				undisableTxt();
			}else{
				$("#txt_diskon").val('');
				disableTxt();
			}
		});

	$("#txt_jumlah").focus(function(){
			var kodeprins	= $("#txt_prinsipal_prod").val();
			var kodeekat	= $("#txt_kode_barang").val();			
	
			if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				undisableTxt();
			}else{
				$("#txt_diskon").val('');
				disableTxt();
			}
		});
	
	function kosong(){
		$(".detail_readonly").val('');
		$(".input_detail").val('');
	}
	
	function cari_nomor() {
		var no		= 1;
		var cabang1	= $("#textcabang").val();
		var tgl		= $("#txt_tgl_beli").val();	
		var noerdit = $("#kodeedit").val();
		
		$.ajax({
			type	: "POST",
			url		: "modul/pembelian/cari_nomor.php",
			data	: "&tgl="+tgl+"&cabang1="+cabang1+"&noerdit="+noerdit,
			dataType : "json",
			success	: function(data){
				$("#txt_kode_beli").val(data.nomor);
				$("#cbo_supplier").val(data.supp);
				$("#txt_tgl_beli").val(data.tglpredit);
				tampil_data();
			}
		});		
	}

	function tampil_data() {
		var kode 	= $("#txt_kode_beli").val();
		$.ajax({
				type	: "POST",
				url		: "modul/pembelian/tampil_data.php",
				data	: "kode="+kode,
				//timeout	: 6000,
				beforeSend	: function(){		
					$("#info").html("<img src='loading.gif' />");			
				},				  
				success	: function(data){
					$("#info").html(data);
				}
		});			
	}

	
	cari_nomor();
	
	$("#txt_kode_barang").autocomplete("modul/pembelian/list_barang.php", {
				width:100,
				//max: 10,
				scroll:true,
	});
	
	function cari_kode() {
		var kode	= $("#txt_kode_barang").val();
		var cabang	= $("#textcabang").val();
		var kodepr	= $("#txt_kode_beli").val();
		
		$.ajax({
			type	: "POST",
			url		: "modul/pembelian/cari_barang.php",
			data	: "kode="+kode+"&cabang="+cabang+"&kodepr="+kodepr,
			dataType : "json",
			success	: function(data){
				//alert (cabang); 
				$("#txt_nama_barang").val(data.nama_barang);
				$("#txt_satuan").val(data.satuan);
				$("#txt_ratio").val(data.t_ratio);
				$("#txt_stok").val(data.t_stok);
				$("#txt_avg").val(data.t_avg);
				$("#txt_po_outstanding").val(data.t_po_out);
				$("#txt_produksyarat").val(data.produksyarat);
				$("#txt_hargajual").val(data.t_harga);
				$("#txt_prinsipal_prod").val(data.t_kd_prins);
				$("#txt_btb_outstanding").val(data.produkoutstanding);
				$("#txt_pr_outstanding").val(data.produkoutstandingpr);
				$("#txt_pr_outstanding_pr").val(data.produkoutstandingpr_pr);
				$("#txt_pr_outstanding_nopr").val(data.produkoutstandingpr_nopr);
				$("#txt_pr_outstanding_nopo").val(data.produkoutstandingpr_nopo);
				$("#txt_kategorikhusus").val(data.kategorikhusus);
				$("#txt_produk_sp").val(data.t_produk_sp);
			}
		});	
		cekratio();
	}
	
	$("#txt_kode_barang").keyup(function() {
		cari_kode();
		cekratio();
	});
	$("#txt_kode_barang").focus(function() {
		cari_kode();
		cekratio();
	});
	
	//mengalikan jumlah dengan harga
	$("#txt_jumlah").keyup(function(){
		var jml		= $("#txt_jumlah").val();
		var harga	= $("#txt_harga").val();
		if (jml.length!='') {
			var total	= parseInt(jml)*parseInt(harga);
			$("#txt_total").val(total);
		}else{
			$("#txt_total").val(0);
		}
	});


	$("#tambah_detail").click(function(){
		kosong();	
		$("#txt_kode_barang").focus();
	});

	
	$("#simpan").click(function(){
		var kode			= $("#txt_kode_beli").val();	
		var tgl				= $("#txt_tgl_beli").val();	
		var supplier		= $("#cbo_supplier").val();	
		var	kode_barang		= $("#txt_kode_barang").val();
		var	nama_barang		= $("#txt_nama_barang").val();
		var	satuan			= $("#txt_satuan").val();
		var	ket_prins		= $("#textfield_ketprisipal").val();
		var	jumlah			= $("#txt_jumlah").val();
		var ratio			= $("#txt_ratio").val();	
		var stok			= $("#txt_stok").val();	
		var avrg			= $("#txt_avg").val();	
		var ket_cab			= $("#textfield_ketcabang").val();	
		var cabang			= $("#textcabang").val();	
		var username			= $("#textusername").val();	
		var outstanding			= $("#txt_po_outstanding").val();
		var kodeprins			= $("#txt_prinsipal_prod").val();
		var diskonitem			= $("#txt_diskon").val();
		var prodoutstandbtb		= $("#txt_btb_outstanding").val();
		var prodoutstandpr		= $("#txt_pr_outstanding").val();
		var prodoutstandpr_pr		= $("#txt_pr_outstanding_pr").val();
		var prodoutstandpr_nopr		= $("#txt_pr_outstanding_nopr").val();
		var prodoutstandpr_nopo		= $("#txt_pr_outstanding_nopo").val();
		var kategorikhusus		= $("#txt_kategorikhusus").val();
		var produksp			= $("#txt_produk_sp").val();

		if ( avrg == 0 ){
			var hratio = 0	;
		}else
		{
			var hratio = ( (parseInt(jumlah) + parseInt(stok)) / parseInt(avrg));
		}
		

		var error = false;
		
/* 	if((kodeprins == '18') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS' && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}
	
	if((kodeprins == '34') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS' && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}
	
	if((kodeprins == '41') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS' && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	} */
	
/* 	if( hratio > 3 && (kode_barang.substring(0,2) != "EK") && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan. Over Stock....!!! Informasi lebih lanjut hubungi logistik pusat...!!" + hratio);
		   $("#txt_kode_beli").focus();
		   return (false);
	} 
	 
	if((kodeprins == '8') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS' && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang prinsipal SELES. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kodeprins == '1') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS' && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang prinsipal ADITAMA. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kodeprins == '2') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS' && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang prinsipal ESCOLAB. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kodeprins == '9') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS' && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang prinsipal SOLAS REG. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	} */

/* 	if((kodeprins == '16') && (kode_barang.substring(0,2) != "EK") && cabang !='KPS'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang prinsipal TRIFA. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	} */
	
		
/* 	if((kodeprins == '24') && (cabang == 'KPG') && (kode_barang.substring(0,2) != "EK") && (supplier != 'FR') && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang lain-lain. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kodeprins == '24') && (cabang == 'PKB') && (kode_barang.substring(0,2) != "EK") && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang lain-lain. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kodeprins == '24') && (cabang == 'MDN') && (kode_barang.substring(0,2) != "EK") && Right(kode, 2) != 'JO'){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang lain-lain. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kodeprins == '24') && (cabang == 'BDL') && (kode_barang.substring(0,2) != "EK")){
           var error = true;
           alert("Maaf, tidak bisa melakukan penjualan barang lain-lain. Informasi lebih lanjut hubungi logistik pusat...!!");
		   $("#txt_kode_beli").focus();
		   return (false);
	} */

	
	if((kategorikhusus == '01')){
           var error = true;
           alert("Maaf, Bukan Barang Rutin, Barang Precursor. Harap dibuat menggunakan PR Precursor");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kategorikhusus == '04')){
           var error = true;
           alert("Maaf, Bukan Barang Rutin, Barang Psikotropika. Harap dibuat menggunakan PR Psikotropika");
		   $("#txt_kode_beli").focus();
		   return (false);
	}

	if((kodeprins == 24) && (diskonitem.length == 0) && (kode_barang.substring(0,2) != "EK")){
           var error = true;
           alert("Maaf, Barang lain2, diskon harap di isi");
		   $("#txt_diskon").focus();
		   return (false);
	}

	if((kodeprins == 24) && (diskonitem <= 0) && (kode_barang.substring(0,2) != "EK")){
		   var error = true;
		   alert("Maaf, diskon harus lebih besar dari 0 (nol)");
		   $("#txt_diskon").focus();
		   return (false);
	}

/* 	if((kode_barang != prodoutstandbtb) && (kode_barang == prodoutstandpr) && ( kode_barang != prodoutstandpr_nopo) && (kode_barang != prodoutstandpr_pr) && (cabang != "KPS")){
           var error = true;
           alert("Maaf, Produk Tersebut masih ada PR Outstandingnya " + prodoutstandpr_nopr);
		   $("#txt_kode_barang").focus();
		   return (false);
        }

 	if((produksp.length == 0) && (cabang != 'KPS') && (kodeprins == 24)){
           var error = true;
           alert("Maaf, Tidak bisa membuat PR/SP untuk produk tersebut. Hubungi logistik Pusat");
		   $("#txt_kode_beli").focus();
		   return (false);
        }
	 
 	if((produksp.length == 0) && (cabang != 'KPS') && (kodeprins == '37')){
           var error = true;
           alert("Maaf, Tidak bisa membuat PR/SP untuk produk tersebut. Hubungi logistik Pusat");
		   $("#txt_kode_beli").focus();
		   return (false);
        }

 	if((produksp.length == 0) && (cabang != 'KPS') && (kodeprins == '38')){
           var error = true;
           alert("Maaf, Tidak bisa membuat PR/SP untuk produk tersebut. Hubungi logistik Pusat");
		   $("#txt_kode_beli").focus();
		   return (false);
        } */

	 if(kode.length == 0){
           var error = true;
           alert("Maaf, Kode Pembelan tidak boleh kosong");
		   $("#txt_kode_beli").focus();
		   return (false);
        }

	if(tgl.length == 0){
           var error = true;
           alert("Maaf, Tanggal Pembelian tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return (false);
         }

	if(supplier.length == 0){
           var error = true;
           alert("Maaf, Supplier tidak boleh kosong");
		   $("#cbo_supplier").focus();
		   return (false);
        }
	
	if(kode_barang.length == 0){
           var error = true;
           alert("Maaf, Kode barang tidak boleh kosong");
		   $("#txt_kode_barang").focus();
		   return (false);
        }

	if(nama_barang.length == 0){
           var error = true;
           alert("Maaf, Nama Barang tidak boleh kosong");
		   $("#txt_kode_barang").focus();
		   return (false);
        }
	
	if(jumlah.length == 0){
           var error = true;
           alert("Maaf, Jumlah Barang tidak boleh kosong");
		   $("#txt_jumlah").focus();
		   return (false);
        }

		 		 
		if(error == false){
			$.ajax({
				type	: "POST",
				url		: "modul/pembelian/simpan.php",
				data	: "kode="+kode+
							"&tgl="+tgl+
							"&supplier="+supplier+
							"&kode_barang="+kode_barang+
							"&diskonitem="+diskonitem+
							"&jumlah="+jumlah+
							"&satuan="+satuan+
							"&ratio="+ratio+
							"&avrg="+avrg+
							"&stok="+stok+
							"&ket_prins="+ket_prins+
							"&ket_cab="+ket_cab+
							"&outstanding="+outstanding+
							"&username="+username+
							"&cabang="+cabang,
				//timeout	: 3000,
				beforeSend	: function(){		
					$("#info").show();
					$("#info").html("<img src='loading.gif' />");			
				},				  
				success	: function(data){
					$("#info").show();
					$("#info").html(data);
				}
			});
		}
		return false; 
	});
	
	$("#cetak2").click(function() {
		var kode	= $("#txt_kode_beli").val();
		var error = false;
		
		if(kode.length == 0){
           var error = true;
           alert("Maaf, Kode Pembelan tidak boleh kosong");
		   $("#txt_kode_beli").focus();
		   return (false);
         }
		window.location.href="modul/laporan/cetak_pr.php?kode="+kode;
	});

	$("#tambah_beli").click(function() {
		window.location.href='media.php?module=pembelian';
		$(".input").val('');
		kosong();
		cari_nomor();
		$("#txt_tgl_beli").focus();
	});

	$("#keluar").click(function(){
		document.location='?module=home';
	});

});

