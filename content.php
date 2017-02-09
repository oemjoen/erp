<?php
include "inc/inc.koneksi.php";
include "inc/library.php";
include "inc/fungsi_indotgl.php";
include "inc/fungsi_combobox.php";
include "inc/class_paging.php";
include "inc/fungsi_rupiah.php";
include "inc/fungsi_tanggal.php";
include "inc/fungsi_hdt.php";

$mod = $_GET['module'];

// Bagian Home
if ($mod=='home'){


	echo "<h2>BACKUP ZOHO PT. SAPTASARITAMA $_SESSION[cabang]</h2>";
	echo "Selamat datang <b>$_SESSION[namalengkap] </b>, di Aplikasi ZOHO BACKUP DAN REPORT SAPTASARITAMA.<br>";
	echo "Semua data Laporan disini, sesuai dengan cabang mengguakan ZOHO<br>";
	echo "Tahap Awal Modul :<br>";
	echo "A. Laporan<br>";
	echo "&nbsp;&nbsp;&nbsp; - Kartu Gudang<br>";
	echo "&nbsp;&nbsp;&nbsp; - Mutasi Gudang<br>";
	echo "&nbsp;&nbsp;&nbsp; - Laporan BPOM<br>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * Laporan Alkes<br>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * Laporan Obat<br>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * Laporan Obat Retur<br>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * Laporan Prekursor/Psikotropik<br>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * Laporan Obat Lintas Provinsi<br>";
	echo "B. Laporan Cabang<br>";
	echo "&nbsp;&nbsp;&nbsp; - Laporan Pembelian<br>";
	echo "&nbsp;&nbsp;&nbsp; - Laporan BPB<br>";
	echo "&nbsp;&nbsp;&nbsp; - Laporan BKB<br>";
	echo "&nbsp;&nbsp;&nbsp; - Laporan PT<br>";
	echo"<p>&nbsp;</p>
          <p align=right>Login : $hari_ini, ";
  echo tgl_indo(date("Y m d")); 
  echo " | "; 
  echo date("H:i:s");
  echo " WIB</p>";
  
}
//users
elseif ($mod=='pengguna'){
    include "modul/users/users.php";
}
elseif ($mod=='barang'){
    include "modul/barang/barang.php";
}

//pembelian_usulan
elseif ($mod=='pembelian_usulan'){
    include "modul/pembelian_usulan/pembelian.php";
}
//pembelian_usulan_pre
elseif ($mod=='pembelian_usulan_pre'){
    include "modul/pembelian_usulan_pre/pembelian.php";
}
//pembelian_usulan_psi
elseif ($mod=='pembelian_usulan_psi'){
    include "modul/pembelian_usulan_psi/pembelian.php";
}

//pembelian_usulan
elseif ($mod=='approval_pr'){
    include "modul/pembelian/list_pr_aprroval.php";
}


//supplier
elseif ($mod=='supplier'){
    include "modul/supplier/supplier.php";
}

//laporan produk jual
elseif ($mod=='lapprodjual'){
    include "/modul/lap_prodjual/lap_produkjual.php";
}


elseif ($mod=='pembelian'){
    include "modul/pembelian/pembelian.php";
}

elseif ($mod=='pembelian_jo'){
    include "modul/pembelian_jo/pembelian.php";
}

elseif ($mod=='penjualan'){
    include "modul/penjualan/penjualan.php";
}

elseif ($mod=='po_pembelian'){
    include "modul/po_pembelian/po_beli.php";
}
//lap_barang
elseif ($mod=='lap_barang'){
    include "modul/lap_barang/lap_barang.php";
}

//lap_barang
elseif ($mod=='lap_serv_lvl'){
    include "modul/lap_svs_lvl/lap_service_lvl.php";
}

//lap_barang
elseif ($mod=='lap_serv_lvl_btb'){
    include "modul/lap_svs_lvl/lap_service_lvl_btb.php";
}

elseif ($mod=='lap_stok'){
    include "modul/lap_stok/lap_stok.php";
}

//cetak pr
elseif ($mod=='cetak_pr'){
    include "modul/cetak_pr/cetak_pr.php";
}

//cetak po
elseif ($mod=='cetak_po'){
    include "modul/cetak_po/cetak_po.php";
}

//LIST PR
elseif ($mod=='list_pr'){
    include "modul/pembelian/list_pr.php";
}

//LIST PR USULAN
elseif ($mod=='list_pr_usulan'){
    include "modul/pembelian_usulan/list_pr.php";
}
//LIST PR USULAN
elseif ($mod=='list_pr_usulan_pre'){
    include "modul/pembelian_usulan_pre/list_pr.php";
}
//LIST PR USULAN
elseif ($mod=='list_pr_usulan_psi'){
    include "modul/pembelian_usulan_psi/list_pr.php";
}
//LIST PO
elseif ($mod=='list_po'){
    include "modul/po_pembelian/list_po.php";
}

//import stok harian
elseif ($mod=='import_data_harian'){
    include "modul/mimport/uploadharian.php";
}

//import stok harian
elseif ($mod=='import_stok_harian_cabang'){
    include "modul/mimport/uploadhariancabang.php";
}

//import stok pusat
elseif ($mod=='import_stok_pusat'){
    include "modul/mimport/uploadstokpusat.php";
}

//import stok bulanan
elseif ($mod=='import_data_bulanan'){
    include "modul/mimport/uploadbulanan.php";
}

//edit po
elseif ($mod=='edit_po_beli'){
    include "modul/edit_po_beli/edit_po_beli.php";
}

//redister po
elseif ($mod=='register_po'){
    include "modul/reg_po/list_reg_po.php";
}

//edit redister po
elseif ($mod=='add_register_po'){
    include "modul/reg_po/edit_po_beli.php";
}

//edit po
elseif ($mod=='edit_po_beli_pre'){
    include "modul/edit_po_beli_pre/edit_po_beli.php";
}

//redister po
elseif ($mod=='register_po_pre'){
    include "modul/reg_po_pre/list_reg_po.php";
}

//edit redister po
elseif ($mod=='add_register_po_pre'){
    include "modul/reg_po_pre/edit_po_beli.php";
}

//edit po
elseif ($mod=='edit_po_beli_psi'){
    include "modul/edit_po_beli_psi/edit_po_beli.php";
}

//redister po
elseif ($mod=='register_po_psi'){
    include "modul/reg_po_psi/list_reg_po.php";
}

//edit redister po
elseif ($mod=='add_register_po_psi'){
    include "modul/reg_po_psi/edit_po_beli.php";
}

//form BTB
elseif ($mod=='btb'){
    include "modul/btb/btb.php";
}

//list BTB
elseif ($mod=='listbtb'){
    include "modul/btb/list_btb.php";
}

//list BTB
elseif ($mod=='editbtb'){
    include "modul/edit_btb/edit_btb.php";
}


//form SOBB
elseif ($mod=='sobb'){
    include "modul/sobb/sobb.php";
}

//Laporan SOBB
elseif ($mod=='lap_sobb'){
    include "modul/sobb/list_sobb.php";
}

//edit sobb
elseif ($mod=='edit_sobb'){
    include "modul/edit_sobb/edit_sobb.php";
}

//form SOBh
elseif ($mod=='sobh'){
    include "modul/sobh/sobh.php";
}

//form SOBh
elseif ($mod=='list_sobh'){
    include "modul/sobh/list_sobh.php";
}

//edit sobb
elseif ($mod=='edit_sobh'){
    include "modul/edit_sobh/edit_sobh.php";
}

//form nonsaleable stock
elseif ($mod=='nsastc'){
    include "modul/nsastc/nsastc.php";
}

//list nonsaleable stock
elseif ($mod=='list_nsastc'){
    include "modul/nsastc/list_nsastc.php";
}

//edit nonsaleable stock
elseif ($mod=='edit_nsastc'){
    include "modul/edit_nsastc/nsastc.php";
}

//edit nonsaleable stock
elseif ($mod=='lap_nsastc'){
    include "modul/nsastc/lap_nsastc.php";
}


//pr precursor
elseif ($mod=='pembelian_pre'){
    include "modul/pembelian_pre/pembelian.php";
}

//pr precursor jo
elseif ($mod=='pembelian_pre_jo'){
    include "modul/pembelian_jo_pre/pembelian.php";
}

//LIST PR
elseif ($mod=='list_pr_pre'){
    include "modul/pembelian_pre/list_pr.php";
}

//pr psikotropika
elseif ($mod=='pembelian_psi'){
    include "modul/pembelian_psi/pembelian.php";
}

//LIST PR
elseif ($mod=='list_pr_psi'){
    include "modul/pembelian_psi/list_pr.php";
}

//po prekursor
elseif ($mod=='po_pembelian_pre'){
    include "modul/po_pembelian_pre/po_beli.php";
}

//LIST psikotropika
elseif ($mod=='list_po_pre'){
    include "modul/po_pembelian_pre/list_po.php";
}

//po psikotropika
elseif ($mod=='po_pembelian_psi'){
    include "modul/po_pembelian_psi/po_beli.php";
}

//LIST prekursor
elseif ($mod=='list_po_psi'){
    include "modul/po_pembelian_psi/list_po.php";
}

// Retur
elseif ($mod=='retur_beli'){
    include "modul/retur_beli/retur_beli.php";
}

// List Retur
elseif ($mod=='list_retur'){
    include "modul/retur_beli/list_retur.php";
}

// Retur konfirm
elseif ($mod=='retur_konfirm'){
    include "modul/retur_konfirm/retur_konfirm.php";
}

// List Retur Konfirm
elseif ($mod=='list_retur_konf'){
    include "modul/retur_konfirm/list_retur_kon.php";
}

// Loist Retur Konfirm
elseif ($mod=='edit_retur_konfirm'){
    include "modul/edit_retur_konf/edit_retur_konf.php";
}

// Retur Pre
elseif ($mod=='retur_beli_pre'){
    include "modul/retur_beli_pre/retur_beli.php";
}

// List Retur Pre
elseif ($mod=='list_retur_pre'){
    include "modul/retur_beli_pre/list_retur.php";
}

// Retur konfirm Pre
elseif ($mod=='retur_konfirm_pre'){
    include "modul/retur_konfirm_pre/retur_konfirm.php";
}

// List Retur Konfirm Pre
elseif ($mod=='list_retur_konf_pre'){
    include "modul/retur_konfirm_pre/list_retur_kon.php";
}

// edit Retur Konfirm Pre
elseif ($mod=='edit_retur_konfirm_pre'){
    include "modul/edit_retur_konf_pre/edit_retur_konf.php";
}

// Retur Psi
elseif ($mod=='retur_beli_psi'){
    include "modul/retur_beli_psi/retur_beli.php";
}

// List Retur Psi
elseif ($mod=='list_retur_psi'){
    include "modul/retur_beli_psi/list_retur.php";
}

// Retur konfirm Psi
elseif ($mod=='retur_konfirm_psi'){
    include "modul/retur_konfirm_psi/retur_konfirm.php";
}

// List Retur Konfirm Psi
elseif ($mod=='list_retur_konf_psi'){
    include "modul/retur_konfirm_psi/list_retur_kon.php";
}

// Edit Retur Konfirm Psi
elseif ($mod=='edit_retur_konfirm_psi'){
    include "modul/edit_retur_konf_psi/edit_retur_konf.php";
}

//redister po
elseif ($mod=='register_po'){
    include "modul/reg_po/list_reg_po.php";
}

//edit redister po
elseif ($mod=='add_register_po'){
    include "modul/reg_po/edit_po_beli.php";
}

//redister Retur
elseif ($mod=='register_retur'){
    include "modul/reg_retur/list_reg_retur.php";
}

//edit redister retur
elseif ($mod=='add_register_retur'){
    include "modul/reg_retur/edit_retur_beli.php";
}

//redister Retur
elseif ($mod=='register_retur_pre'){
    include "modul/reg_retur_pre/list_reg_retur.php";
}

//edit redister retur
elseif ($mod=='add_register_retur_pre'){
    include "modul/reg_retur_pre/edit_retur_beli.php";
}

//redister Retur
elseif ($mod=='register_retur_psi'){
    include "modul/reg_retur_psi/list_reg_retur.php";
}

//edit redister retur
elseif ($mod=='add_register_retur_psi'){
    include "modul/reg_retur_psi/edit_retur_beli.php";
}

elseif ($mod=='lap_pr_outs'){
    include "modul/laporan/laporan_pr/laporan_pr_out.php";
}

elseif ($mod=='cetak_po_all'){
    include "modul/cetak_po_pembelian/po_beli.php";
}
elseif ($mod=='po_approval'){
    include "modul/po_approv/list_po.php";
}
elseif ($mod=='uppurch2'){
    include "modul/mimport/upload_purc_det.php";
}
elseif ($mod=='uppurch'){
//upload_purc_det_csv.php
    include "modul/mimport/upload_purc_det_csv.php";
}
elseif ($mod=='upkb'){
//upload_purc_det_csv.php
    include "modul/mimport/upload_kb_csv.php";
}
elseif ($mod=='upkk'){
//upload_purc_det_csv.php
    include "modul/mimport/upload_kk_csv.php";
}
elseif ($mod=='kartugudang'){
//kartugudang
    include "modul/lapkgud/lap_kgud.php";
}
elseif ($mod=='kartugudang2'){
//kartugudang
    include "modul/lapkgud2/lap_kgud.php";
}

elseif ($mod=='kartugudang_r'){
//kartugudang
    include "modul/lapkgud2/lap_kgud_r.php";
}


elseif ($mod=='alkes'){
//Alkes
    include "modul/lapbpom/lap_alkes.php";
}
elseif ($mod=='retobat'){
//oabr retur
    include "modul/lapbpom/lap_ret_obat.php";
}
elseif ($mod=='obat'){
//oabr retur
    include "modul/lapbpom/lapt_obat.php";
}
elseif ($mod=='lintas'){
//oabr lintas provinsi
    include "modul/lapbpom/lap_obat_lintas.php";
}
elseif ($mod=='obatpsipre'){
//oabr lintas provinsi
    include "modul/lapbpom/lap_psipre.php";
}

elseif ($mod=='obatoot'){
//oabr lintas provinsi
    include "modul/lapbpom/lap_oot.php";
}

elseif ($mod=='lapbeli'){
//oabr lintas provinsi
    include "modul/lapzoho/lappembelian.php";
}

elseif ($mod=='lapbelibkb'){
//oabr lintas provinsi
    include "modul/lapzoho/lapbkb.php";
}

elseif ($mod=='lapbelibpb'){
//oabr lintas provinsi
    include "modul/lapzoho/lapbpb.php";
}

elseif ($mod=='pt'){
//oabr lintas provinsi
    include "modul/lapzoho/lappt.php";
}

elseif ($mod=='mutgud'){
//oabr lintas provinsi
    include "modul/lapzoho/lapmutasigud.php";
}

elseif ($mod=='ceks'){
//oabr lintas provinsi
    include "modul/lapzoho/lapcekstok.php";
}


elseif ($mod=='obatpst'){
//oabr lintas provinsi
    include "modul/lapbpom/lapt_obat_pst.php";
}
elseif ($mod=='alkespst'){
//Alkes
    include "modul/lapbpom/lap_alkes_pst.php";
}
elseif ($mod=='retobatpst'){
//oabr retur
    include "modul/lapbpom/lap_ret_obat_pst.php";
}
elseif ($mod=='obatpsiprepst'){
//oabr lintas provinsi
    include "modul/lapbpom/lap_psipre_pst.php";
}
elseif ($mod=='smsg'){
//oabr lintas provinsi
    include "modul/t_sms/sms.php";
}

elseif ($mod=='tordsp'){
//oabr lintas provinsi
    include "modul/t_order/list_ord_sp.php";
}

elseif ($mod=='t_order'){
//oabr lintas provinsi
    include "modul/t_order/t_order.php";
}

// Apabila modul tidak ditemukan
else{
  echo "<b>MODUL BELUM ADA ATAU BELUM LENGKAP</b>";
}
?>
