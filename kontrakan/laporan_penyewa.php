<!DOCTYPE html>
<html>
<head>
	<title>Lapora Penyewa Kontrakan</title>
</head>
<body onload="window.print();">
<center>
<h1>Rumah Kontrakan Sungai Cubadak</h1>
<h3>Laporan Penyewa Kontrakan</h3>
<p>Alamat : Sungai Cubadak, Telpon / HP : 085767720388</p>
<hr style="margin-top:10px; margin-bottom: 0px;">
<hr style="margin-top:0px; margin-bottom: 10px;">
</center>
<table cellpadding="5" cellspacing="0" width="100%" border="1">
	<tr>
		<td align="center">No</td>
		<td align="center">No KTP</td>
		<td align="center">Nama Penyewa</td>
		<td align="center">HP</td>
		<td align="center">Alamat</td>
		<td align="center">Pekerjaan</td>
		<td align="center">Status</td>
		<td align="center">Umur</td>
		<td align="center">Kontrakan Disewa</td>
		<td align="center">Status Sewa</td>
		<td align="center">Akhir Sewa</td>
	</tr>
	<?php
	include "koneksi.php";
	if (isset($_POST['tahun_akhir'])){
		$tahun_akhir=$_POST['tahun'];
		$query=$con->prepare("select * from tbl_penyewa,tbl_pembayaran,tbl_kontrakan where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_penyewa.id_penyewa=tbl_pembayaran.id_penyewa and year(tbl_pembayaran.tgl_akhir)='$tahun_akhir' order by tbl_pembayaran.tgl_akhir asc");	
	}else if (isset($_POST['status_sewa'])){
		$status_sewa=$_POST['status'];
		$query=$con->prepare("select * from tbl_penyewa,tbl_pembayaran,tbl_kontrakan where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_penyewa.id_penyewa=tbl_pembayaran.id_penyewa and tbl_pembayaran.status_sewa='$status_sewa' order by tbl_pembayaran.tgl_akhir asc");	
	}else{
	$query=$con->prepare("select * from tbl_penyewa,tbl_pembayaran,tbl_kontrakan where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_penyewa.id_penyewa=tbl_pembayaran.id_penyewa order by tbl_penyewa.date desc");
	}
	$query->execute();
	$query=$query->fetchAll();
	$n=1;
	foreach ($query as $q) {
		?>
		<tr>
			<td align="center"><?php echo $n; ?></td>
			<td align="center"><?php echo $q['no_ktp']; ?></td>
			<td><?php echo $q['nama_penyewa']; ?></td>
			<td align="center"><?php echo $q['hp']; ?></td>
			<td><?php echo $q['alamat']; ?></td>
			<td><?php echo $q['pekerjaan']; ?></td>
			<td align="center"><?php echo $q['status_hubungan']; ?></td>
			<td align="center"><?php echo $q['umur']; ?> Tahun</td>
			<td><?php echo $q['kontrakan']; ?></td>
			<td align="center"><?php echo $q['status_sewa']; ?></td>
			<td align="center"><?php echo $q['tgl_akhir']; ?></td>
		</tr>
		<?php
	$n++; }
	?>
</table>

<div style="float: right; margin-top: 20px; margin-right: 50px;">
	Sungai Cubadak, <?php echo date('d-M-Y'); ?>
	<br><br><br><br>
	<center><?php echo strtoupper($_SESSION['username']); ?></center>
</div>
</body>
</html>