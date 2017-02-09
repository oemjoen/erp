<script type="text/javascript">
$(document).ready(function() {
    $(function() {
        $("#theList tr:even").addClass("stripe1");
        $("#theList tr:odd").addClass("stripe2");

        $("#theList tr").hover(
            function() {
                $(this).toggleClass("highlight");
            },
            function() {
                $(this).toggleClass("highlight");
            }
        );
    });
});
</script>
<?php
include '../../inc/inc.koneksi.php';
include '../../inc/fungsi_hdt.php';
$koderetur	= $_POST[koderetur];
//$kode	= 'BL0030';
?>
<table id='theList2' width='100%'>
		<tr>
			<th>No.</th>
			<th>Supp</th>
			<th>Nama Barang</th>
			<th>Kode</br>Barang</th>
			<th>Satuan</th>
			<th>Qty Retur</th>
			<th>Batch</th>
			<th>Expired </br> Date</th>
			<th>Ref DO</br>Pusat</th>
			<th>Tgl Ref DO</br>Pusat</th>
			<th>Alasan</th>
			<th>Dari</th>
			<th>Hapus</th>
			<th>Edit</th>
		</tr>
<?php	
		$sql = "SELECT a.*,b.`namaproduk`,b.`satuan`,c.`alasan`,d.`asal_retur_ket` FROM retur_beli a
									LEFT JOIN mstproduk b ON a.`kode_barang`=b.`kodeproduk`
									LEFT JOIN mst_alasan_retur c ON a.`alasan_retur`=c.`kode_alasan`
									LEFT JOIN `mst_asal_retur` d ON a.`asal_retur`=d.`kode_asal`
								WHERE a.kode_retur='$koderetur'
							ORDER BY a.kode_retur DESC, a.kode_barang ASC";

		//echo $sql;
		$query = mysql_query($sql);
		
		$no=1;
		while($r_data=mysql_fetch_array($query)){
			$kodeproduk = $r_data[kode_barang];
			$kode = $r_data[kode_retur].$r_data[kode_supplier].$r_data[kode_barang].$r_data[batch];
			$total	= $r_data[jumlah_retur];
			$totalsisaprod = $r_data[jumlah_po] - total_btb_sisa($r_data[kode_barang],$r_data[cabang],$r_data[kodepo_beli]);
			
			
			echo "<tr>
					<td align='center'>$no</td>
					<td>$r_data[kode_supplier]</td>
					<td>$r_data[namaproduk]</td>
					<td>$r_data[kode_barang]</td>
					<td>$r_data[satuan]</td>
					<td>$r_data[jumlah_retur]</td>
					<td>$r_data[batch]</td>
					<td>$r_data[ed]</td>
					<td>$r_data[do_pusat]</td>
					<td>$r_data[tgl_do_pusat]</td>
					<td>$r_data[alasan]</td>
					<td>$r_data[asal_retur_ket]</td>
					<td align='center'>
					<a href='javascript:void(0)' onClick=\"hapus_data('$kode')\">
					<img src='icon/thumb_down.png' border='0' id='hapus' title='Hapus' width='12' height='12' >
					</a>
					</td>
					<td align='center'>
					<a href='javascript:editRow(\"{$kodeproduk}\")' ><img src='icon/thumb_up.png' border='0' id='edit' title='Edit' width='12' height='12' ></a>
						</td>
					</tr>";
			$no++;
			$g_total = $g_total+$total;
		}
		?>
	</table>
	<table width='100%'>
		<tr>
<?php
	echo "</b></h4></td>
		</tr>
		</table>";
?>