// JavaScript Document
$(document).ready(function() {
	//membuat text kode barang menjadi Kapital
	$("#kode_barang").keyup(function(e){
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
	

	$(".tanggal").datepicker({
	  dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  changeMonth: true,
	  changeYear: true,
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
	
		//hanya angka yang dapat dientry
	$(".angka").keypress(function(data){
		if (data.which!=8 && data.which!=0 && (data.which<48 || data.which>57)) 
		{
			return false;
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
			url		: "modul/retur_beli/cari_nomor.php",
			data	: "&tgl="+tgl+"&cabang1="+cabang1+"&noerdit="+noerdit,
			dataType : "json",
			success	: function(data){
				$("#txt_kode_beli").val(data.nomor);
				$("#cbo_supplier").val(data.supp);
				$("#txt_tgl_beli").val(data.tglpredit);
				$("#txt_jumlah_koli").val(data.koli);
				tampil_data();
			}
		});		
	}

	function tampil_data() {
		var koderetur 	= $("#txt_kode_beli").val();
		$.ajax({
				type	: "POST",
				url		: "modul/retur_beli/tampil_data.php",
				data	: "koderetur="+koderetur,
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
	
	$("#txt_kode_barang").autocomplete("modul/retur_beli/list_barang.php", {
				width:100,
				//max: 10,
				scroll:true,
	});
	
	function cari_kode() {
		var kode		= $("#txt_kode_barang").val();
		var cabang		= $("#textcabang").val();
		var kode_btb	= $("#txt_kode_beli").val();
		
		$.ajax({
			type	: "POST",
			url		: "modul/retur_beli/cari_barang.php",
			data	: "kode="+kode+"&cabang="+cabang+"&kode_btb="+kode_btb,
			dataType : "json",
			success	: function(data){
				//alert (cabang); 
				$("#txt_nama_barang").val(data.namaproduk);
				$("#txt_satuan").val(data.satuan);
			}
		});		
	}
	
	$("#txt_kode_barang").keyup(function() {
		cari_kode();
	});
	$("#txt_kode_barang").focus(function() {
		cari_kode();
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

		var	koderetur		= $("#txt_kode_beli").val();
		var	tgl				= $("#txt_tgl_beli").val();
		var username	 	= $("#textusername").val();
		var supplier 		= $("#cbo_supplier").val();
		var koli	 		= $("#txt_jumlah_koli").val();
		var cabang	 		= $("#textcabang").val();
		
		var kode_brg 		= $("#txt_kode_barang").val();
		var satuan	 		= $("#txt_satuan").val();
		var jmlterima 		= $("#txt_jumlah1").val();
		var batchnumber 	= $("#txt_batch1").val();
		var ed 				= $("#txt_ed1").val();
		var refdopst 		= $("#txt_ref_do_pst").val();
		var tglrefdopst 	= $("#txt_ref_tgl_do_pst").val();
		var alasan 			= $("#txt_alasan_retur").val();
		var dari 			= $("#txt_asal_retur").val();

		var error = false;

		if(tgl.length == 0){
           alert("Maaf, Tanggal Retur tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return false;
         }

		if(koderetur.length == 0){
           alert("Maaf, Kode Retur tidak boleh kosong");
		   $("#txt_kode_beli").focus();
		   return false;
         }
		 
		if(supplier.length == 0){
           alert("Maaf, Supplier tidak boleh kosong");
		   $("#cbo_supplier").focus();
		   return false;
         }	
		
		
		if(koli.length == 0){
           alert("Maaf, Jumlah tidak boleh kosong");
		   $("#txt_resi").focus();
		   return false;
         }	
		 
		if(error == false){
		$.ajax({
			type	: "POST",
			url		: "modul/retur_beli/simpan.php",
			data	: "koderetur="+koderetur+
						"&tgl="+tgl+
						"&kode_brg="+kode_brg+
						"&supplier="+supplier+
						"&koli="+koli+
						"&satuan="+satuan+
						"&jmlterima="+jmlterima+
						"&batchnumber="+batchnumber+
						"&ed="+ed+
						"&refdopst="+refdopst+
						"&tglrefdopst="+tglrefdopst+
						"&alasan="+alasan+
						"&dari="+dari+
						"&cabang="+cabang+
						"&username="+username,
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
		window.location.href="modul/laporan/cetak_retur.php?kode="+kode;
	});

	$("#tambah_beli").click(function() {
		window.location.href='media.php?module=retur_beli';
		$(".input").val('');
		kosong();
		cari_nomor();
		$("#txt_tgl_beli").focus();
	});

	$("#keluar").click(function(){
		document.location='?module=home';
	});

});

