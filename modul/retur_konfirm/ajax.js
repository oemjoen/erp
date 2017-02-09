// JavaScript Document
$(document).ready(function() {
					   
	$("#txt_tgl_beli").datepicker({
				dateFormat      : "dd-mm-yy",        
	  showOn          : "button",
	  buttonImage     : "images/calendar.gif",
	  buttonImageOnly : true				
	});

	tampil_data();
	
	function tampil_data(){
		$("#info").load("modul/retur_konfirm/tampil_data.php");
	}

	$("#txt_tgl_beli").change(function() {
		var tgl		= $("#txt_tgl_beli").val();
		var prins	= $("#cbo_prinsipal").val();
		var cabang1 = $("#textcabang").val();
		
		var error = false;

		if(tgl.length == 0){
           var error = true;
           alert("Maaf, Tanggal Retur tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return (false);
         }
		 
		if(tgl.prins == 0){
           var error = true;
           alert("Maaf, Ptinsipal tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return (false);
         }

		 if(error == false){
			$.ajax({
				type	: "POST",
				url		: "modul/retur_konfirm/list_kode.php",
				data	: "tgl="+tgl+"&cabang1="+cabang1+"&prins="+prins,
				success	: function(data){
					$("#cbo_beli").html(data);
				}
			});
		}
		return false; 

	});
	
	function buat_kode() {
		var	kode	= $("#cbo_beli").val();
		var tgl		= $("#txt_tgl_beli").val();
		var prins	= $("#cbo_prinsipal").val();
		var cabang1 = $("#textcabang").val();
		$.ajax({
			type	: "POST",
			url		: "modul/retur_konfirm/buat_nomor.php",
			data	: "kode="+kode+"&tgl="+tgl+"&cabang1="+cabang1+"&prins="+prins,
			dataType: "json",
			success	: function(data){
				$("#txt_kode").val(data.kode_returkonfirm);
				$("#counterr").val(data.counter_retur);
				//alert(data.kode_returkonfirm);
			}
		});		
	}
	
	$("#cari").click(function(){
		
		var	tgl		= $("#txt_tgl_beli").val();
		var	kode	= $("#cbo_beli").val();
		var	cabang1	= $("#textcabang").val();
	
		var error = false;

		if(tgl.length == 0){
           var error = true;
           alert("Maaf, Tanggal Retur tidak boleh kosong");
		   $("#txt_tgl_beli").focus();
		   return (false);
         }
		if(kode.length == 0){
           var error = true;
           alert("Maaf, Kode Retur tidak boleh kosong");
		   $("#cbo_beli").focus();
		   return (false);
         }		 
		if(error == false){
		$.ajax({
			type	: "POST",
			url		: "modul/retur_konfirm/tampil_data.php",
			data	: "kode="+kode+"&cabang1="+cabang1,
			//timeout	: 3000,
			beforeSend	: function(){		
				$("#info").html("<img src='loading.gif' />");			
			},				  
			success	: function(data){
				$("#info").html(data);
				//buat_kode();
			}
		});
		}
		return false; 
		
	});

	$("#cetak2").click(function() {
		var kode	= $("#txt_kode").val();
		window.location.href="modul/laporan/cetak_retur_konfirm.php?kode="+kode;
	});

	$("#tambah_po").click(function() {
		$(".input").val('');
		$("#txt_kode").val('');
		$("#txt_tgl_beli").val('');
		$("#cbo_beli").val('');
		$("#cbo_prinsipal").val('');
		location.reload(); 
		$("#txt_kode").focus();
	});

	$("#keluar").click(function(){
		document.location='?module=home';
	});

});

