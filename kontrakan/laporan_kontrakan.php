<!DOCTYPE html>
<html>
<head>
	<title>Laporan Kontrakan</title>
</head>
<body onload="window.print();">
<center>
<h1>Rumah Kontrakan Sungai Cubadak</h1>
<h3>Laporan Kontrakan</h3>
<p>Alamat : Sungai Cubadak, Telpon / HP : 085767720388</p>
<hr style="margin-top:10px; margin-bottom: 0px;">
<hr style="margin-top:0px; margin-bottom: 10px;">
</center>
<table cellpadding="5" cellspacing="0" border="1" width="100%">
	<tr>
		<td align="center">No</td>
		<td align="center">Id Kontrakan</td>
		<td align="center">Kontrakan</td>
		<td align="center">Luas Tanah</td>
		<td align="center">Luas Kontrakan</td>
		<td align="center">Lokasi</td>
		<td align="center">Harga Sewa</td>
		<td align="center">Status</td>
		<td align="center">Fasilitas</td>
		<td align="center">Keterangan</td>
	</tr>
	<?php
	include "koneksi.php";
	if (isset($_POST['status_kontrakan'])){
		$status_kontrakan=$_POST['status'];
		$query=$con->prepare("select * from tbl_kontrakan where status='$status_kontrakan' order by kontrakan asc");	
	}else{
	$query=$con->prepare("select * from tbl_kontrakan order by kontrakan asc");
	}
	$query->execute();
	$query=$query->fetchAll();
	$n=1;
	foreach ($query as $q) { ?>
	<tr>
		<td align="center"><?php echo $n; ?></td>
		<td align="center"><?php echo $q['id_kontrakan']; ?></td>
		<td><?php echo $q['kontrakan']; ?></td>
		<td align="center"><?php echo $q['luas_tanah']; ?></td>
		<td align="center"><?php echo $q['luas_kontrakan']; ?></td>
		<td><?php echo $q['lokasi']; ?></td>
		<td align="right">Rp.<?php echo number_format($q['harga'],0,".","."); ?></td>
		<td align="center"><?php echo $q['status']; ?></td>
		<td>
			<?php
			$id_kontrakan=$q['id_kontrakan'];
			$query=$con->prepare("select * from tbl_fasilitas where id_kontrakan='$id_kontrakan'");
			$query->execute();
			$f=$query->fetchAll();
			foreach ($f as $f) { echo $f['fasilitas'].','; } ?>
		</td>
		<td><?php echo $q['keterangan']; ?></td>
	</tr>
	<?php $n++; } ?>	
</table>
<div style="float: right; margin-top: 20px; margin-right: 50px;">
	Sungai Cubadak, <?php echo date('d-M-Y'); ?>
	<br><br><br><br>
	<center><?php echo strtoupper($_SESSION['username']); ?></center>
</div>
</body>
</html>