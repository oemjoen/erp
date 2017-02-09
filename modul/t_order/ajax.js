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
	  buttonImageOnly : true,
	  currentText	  : "Now"

	});
//	$("#txt_tgl_beli").datepicker("setDate", Now.Date);

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

	$("#txt_bonus").keypress(function(data){
		if (data.which!=8 && data.which!=0 && (data.which<48 || data.which>57)) 
		{
			return false;
		}
	});
	
	$("#txt_hargajual").keypress(function(data){
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
		//document.getElementById("txt_diskon").disabled = true;
		//document.getElementById("txt_diskon").style.backgroundColor = "red";
		//cekratio();
	}
	function undisableTxt() {
		//document.getElementById("txt_diskon").disabled = false;
		//document.getElementById("txt_diskon").style.backgroundColor = "white";
		//cekratio();
	}
		
	//disableTxt();
	
	// $("#txt_kode_barang").keyup(function(){
			// var kodeprins	= $("#txt_prinsipal_prod").val();
			// var kodeekat	= $("#txt_kode_barang").val();			
	
			// if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				// undisableTxt();
			// }else{
				// $("#txt_diskon").val('');
				// disableTxt();
			// }
		// });

	// $("#txt_kode_barang").focus(function(){
			// var kodeprins	= $("#txt_prinsipal_prod").val();
			// var kodeekat	= $("#txt_kode_barang").val();			
	
			// if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				// undisableTxt();
			// }else{
				// $("#txt_diskon").val('');
				// disableTxt();
			// }
		// });	

	// $("#txt_kode_barang").blur(function(){
			// var kodeprins	= $("#txt_prinsipal_prod").val();
			// var kodeekat	= $("#txt_kode_barang").val();			
	
			// if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				// undisableTxt();
			// }else{
				// $("#txt_diskon").val('');
				// disableTxt();
			// }
		// });

	// $("#txt_jumlah").focus(function(){
			// var kodeprins	= $("#txt_prinsipal_prod").val();
			// var kodeekat	= $("#txt_kode_barang").val();			
	
			// if ((kodeprins == 24) && (kodeekat.substring(0,2) != "EK")) {
				//$("#txt_diskon").val('');
				// undisableTxt();
			// }else{
				// $("#txt_diskon").val('');
				// disableTxt();
			// }
		// });
	
	function kosong(){
		$(".detail_readonly").val('');
		$(".input_detail").val('');
	}
	
	function cari_nomor() {
		var no		= 1;
		var cabang1	= $("#textcabang").val();
		var tgl		= $("#txt_tgl_beli").val();	
		var noerdit = $("#kodeedit").val();
		var kap = $("#textKodeAP").val();
		
		
		$.ajax({
			type	: "POST",
			url		: "modul/t_order/cari_nomor.php",
			data	: "&tgl="+tgl+"&cabang1="+cabang1+"&noerdit="+noerdit+"&kap="+kap,
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
				url		: "modul/t_order/tampil_data.php",
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
	
	$("#txt_kode_barang").autocomplete("modul/t_order/list_barang.php", {
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
			url		: "modul/t_order/cari_barang.php",
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
				//$("#txt_jumlah").focus();
			}
		});	
		cekratio();
	}
	
	$("#txt_kode_barang").keyup(function() {
		cari_kode();
		//cekratio();
	});
	$("#txt_kode_barang").focus(function() {
		cari_kode();
		//cekratio();
	});
	
	//mengalikan jumlah dengan harga
	// $("#txt_jumlah").keyup(function(){
		// var jml		= $("#txt_jumlah").val();
		// var harga	= $("#txt_harga").val();
		// if (jml.length!='') {
			// var total	= parseInt(jml)*parseInt(harga);
			// $("#txt_total").val(total);
		// }else{
			// $("#txt_total").val(0);
		// }
	// });
	
	$("#cbo_retail").change(function() {
		var	koder	= $("#cbo_retail").val();
		var cabang1 = $("#textcabang").val();
		$.ajax({
			type	: "POST",
			url		: "modul/t_order/tampil_limit.php",
			data	: "koder="+koder+"&cabang1="+cabang1,
			success	: function(data){
				$("#info2").html(data);
				$("#txt_kode_barang").focus();
			}
		});		
	});	


	$("#tambah_detail").click(function(){
		kosong();	
		$("#txt_kode_barang").focus();
	});


	$("#proses").click(function() {
		var kode = $("#txt_kode_beli").val(); 
	   var pilih = confirm('Data yang akan diproses kode = '+kode+ '?');
		if (pilih==true) {
			$.ajax({
				type	: "POST",
				url 	: "modul/t_order/proses.php",
				data	: "kode="+kode,
				success	: function(data){
					$("#info").html(data);
                    kosong();
				}
			});
		}
	});	

	
	$("#simpan").click(function(){
		var kode			= $("#txt_kode_beli").val();	
		var tgl				= $("#txt_tgl_beli").val();	
		var kodeO			= $("#txt_id_ord").val();	
		var	kode_barang		= $("#txt_kode_barang").val();
		var	nama_barang		= $("#txt_nama_barang").val();
		var	satuan			= $("#txt_satuan").val();
		var	jumlah			= $("#txt_jumlah").val();
		var	bonus			= $("#txt_bonus").val();
		var diskonitem		= $("#txt_diskon").val();
		var hrg				= $("#txt_hargajual").val();
		var gross			= $("#txt_gross").val();
		var pot				= $("#txt_potongan").val();
		var value			= $("#txt_value").val();
		var ppn				= $("#txt_ppn").val();
		var total			= $("#txt_total").val();
		var cabang			= $("#textcabang").val();	
		var username		= $("#textusername").val();	
//		var tipe			= $("#cbox1").val();

		var error = false;
		
		//alert(reta.length);

	if(kode.length == 0){
	   var error = true;
	   alert("Maaf, Kode Order tidak boleh kosong");
	   $("#txt_kode_beli").focus();
	   return (false);
        }

	if(tgl.length == 0){
           var error = true;
           alert("Maaf, Tanggal Order tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return (false);
         }

	// if(tipe != "Langsung" && reta.length == 0)
	// {
           // var error = true;
           // alert("Maaf, Tipe Order Bukan Langsung, Pelanggan Tidak Boleh Kosong");
		   // $("#cbo_retail").focus();
		   // return (false);
	// }

	if(kode_barang.length == 0){
           var error = true;
           alert("Maaf, Kode barang tidak boleh kosong");
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
				url		: "modul/t_order/simpan.php",
				data	: "kode="+kode+
							"&tgl="+tgl+
							"&kodeO="+kodeO+
							"&kode_barang="+kode_barang+
							"&satuan="+satuan+
							"&jumlah="+jumlah+
							"&bonus="+bonus+
							"&diskonitem="+diskonitem+
							"&hrg="+hrg+
							"&gross="+gross+
							"&pot="+pot+
							"&value="+value+
							"&ppn="+ppn+
							"&total="+total+
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
		window.location.href='media.php?module=ord';
		$(".input").val('');
		kosong();
		cari_nomor();
		$("#txt_tgl_beli").focus();
	});

	$("#keluar").click(function(){
		document.location='?module=home';
	});

});

