<!DOCTYPE html>
<html>
<head>
	<title>Laporan Pembayaran</title>
</head>
<body>
<center>
<h1>Rumah Kontrakan Sungai Cubadak</h1>
<h3>Laporan Pembayaran Kontrakan</h3>
<p>Alamat : Sungai Cubadak, Telpon / HP : 085767720388</p>
<hr style="margin-top:10px; margin-bottom: 0px;">
<hr style="margin-top:0px; margin-bottom: 10px;">
</center>
<table cellpadding="1" cellspacing="0" width="100%" border="1">
	<tr>
		<td align="center">No</td>
		<td align="center">Id Pembayaran</td>
		<td align="center">Kontrakan Disewa</td>
		<td align="center">Nama Penyewa</td>
		<td align="center">Harga Sewa</td>
		<td align="center">Periode</td>
		<td align="center">Total Biaya</td>
		<td align="center">Jumlah Bayar</td>
		<td align="center">Sisa</td>
		<td align="center">Status Bayar</td>
		<td align="center">Masa Sewa</td>
		<td align="center">Status Sewa</td>
	</tr>
	<?php
	include "koneksi.php";
	$grand_total_biaya=0;
	$total_jumlah_bayar=0;
	$total_sisa=0;
	if (isset($_POST['status_bayar'])){
		$status_bayar=$_POST['status'];
		$query=$con->prepare("select * from tbl_penyewa,tbl_pembayaran,tbl_kontrakan where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_penyewa.id_penyewa=tbl_pembayaran.id_penyewa and tbl_pembayaran.status_bayar='$status_bayar' order by id_pembayaran desc");		
	}else if (isset($_POST['periode_sewa'])){
		$periode=$_POST['periode'];
		$query=$con->prepare("select * from tbl_penyewa,tbl_pembayaran,tbl_kontrakan where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_penyewa.id_penyewa=tbl_pembayaran.id_penyewa and tbl_pembayaran.periode='$periode' order by id_pembayaran desc");		
	}else{
	$query=$con->prepare("select * from tbl_penyewa,tbl_pembayaran,tbl_kontrakan where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_penyewa.id_penyewa=tbl_pembayaran.id_penyewa order by id_pembayaran desc");
	}
	$query->execute();
	$query=$query->fetchAll();
	$n=1;
	foreach ($query as $q) { ?>
	<tr>
		<td align="center"><?php echo $n; ?></td>
		<td align="center"><?php echo $q['id_pembayaran']; ?></td>
		<td><?php echo $q['kontrakan']; ?></td>
		<td><?php echo $q['nama_penyewa']; ?></td>
		<td align="right">Rp.<?php echo number_format($q['harga_sewa'],0,".","."); ?></td>
		<td align="center"><?php echo $q['periode']; ?> Tahun</td>
		<td align="right">Rp.<?php echo number_format($q['total_biaya'],0,".","."); ?></td>
		<td align="right">Rp.<?php echo number_format($q['jml_bayar'],0,".","."); ?></td>
		<td align="right">Rp.<?php echo number_format($q['sisa'],0,".","."); ?></td>
		<td align="center"><?php echo $q['status_bayar']; ?></td>
		<td align="center"><?php echo $q['tgl_mulai']; ?> Sampai <?php echo $q['tgl_akhir']; ?></td>
		<td align="center"><?php echo $q['status_sewa']; ?></td>
	</tr>	
	<?php
	$grand_total_biaya+=$q['total_biaya'];
	$total_jumlah_bayar+=$q['jml_bayar'];
	$total_sisa+=$q['sisa'];
	$n++; }
	?>
</table>
<div style="float: left; margin-top: 20px;">
Grand Total Biaya : <b>Rp.<?php echo number_format($grand_total_biaya,0,'.','.'); ?></b><br>
Grand Total Jumlah Bayar : <b>Rp.<?php echo number_format($total_jumlah_bayar,0,'.','.'); ?></b><br>
Grand Total Sisa : <b>Rp.<?php echo number_format($total_sisa,0,'.','.'); ?></b>
</div>
<div style="float: right; margin-top: 20px; margin-right: 50px;">
	Sungai Cubadak, <?php echo date('d-M-Y'); ?>
	<br><br><br><br>
	<center><?php echo strtoupper($_SESSION['username']); ?></center>
</div>
</body>
</html>