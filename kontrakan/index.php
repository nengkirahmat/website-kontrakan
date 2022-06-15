<?php
include "koneksi.php";

$cek=$con->prepare("select * from tbl_pembayaran where status<>'Belum Bayar' order by id_pembayaran asc");
$cek->execute();
$cek=$cek->fetchAll();
$date=date('Y-m-d');
foreach ($cek as $cek) {
	if ($date>$cek['tgl_akhir']){
		$id_kontrakan=$cek['id_kontrakan'];
		$id_penyewa=$cek['id_penyewa'];
		$query=$con->prepare("update tbl_kontrakan set status='Tersedia' where id_kontrakan=:id_kontrakan");
		$query->BindParam(":id_kontrakan",$id_kontrakan);
		$query->execute();
		$id_pembayaran=$cek['id_pembayaran'];
		$query=$con->prepare("update tbl_pembayaran set status_sewa='Telah Habis' where id_pembayaran=:id_pembayaran");
		$query->BindParam(":id_pembayaran",$id_pembayaran);
		$query->execute();
	}
}


if (!empty($_GET['akhiri_sewa'])){
	$id_pembayaran=$_GET['akhiri_sewa'];
	$cek=$con->prepare("select * from tbl_pembayaran where id_pembayaran=:id_pembayaran");
	$cek->BindParam(":id_pembayaran",$id_pembayaran);
	$cek->execute();
	$cek=$cek->fetch();
		$id_kontrakan=$cek['id_kontrakan'];
		$query=$con->prepare("update tbl_kontrakan set status='Tersedia' where id_kontrakan=:id_kontrakan");
		$query->BindParam(":id_kontrakan",$id_kontrakan);
		$query->execute();
		$query=$con->prepare("update tbl_pembayaran set status_sewa='Telah Habis' where id_pembayaran=:id_pembayaran");
		$query->BindParam(":id_pembayaran",$id_pembayaran);
		$query->execute();
}


if (isset($_POST['daftar'])){
        $nama_lengkap=$_POST['nama_lengkap'];
        $username=$_POST['username'];
        $password=$_POST['password'];
        $repassword=$_POST['repassword'];
        $level="user";
        $tgl_daftar=date('Y-m-d h:i:s');
        $cek_user=$con->prepare("select * from tbl_user where username=:username");
        $cek_user->BindParam(":username",$username);
        $cek_user->execute();
        $res=$cek_user->rowCount();
        if ($res>0){
            $_SESSION['peringatan']="Username Sudah Terdaftar.";
        }else
        if ($password<>$repassword){
            $_SESSION['peringatan']="Password Harus Sama.";
        }
        else
        {
            $query=$con->prepare("insert into tbl_user values('',:nama_lengkap,:username,:password,:level,:tgl_daftar)");
            $query->BindParam(":nama_lengkap",$nama_lengkap);
            $query->BindParam(":username",$username);
            $query->BindParam(":password",$password);
            $query->BindParam(":level",$level);
            $query->BindParam(":tgl_daftar",$tgl_daftar);
            $query->execute();
            $_SESSION['peringatan']="Berhasil mendaftar, Silahkan Login.";
            
        }
    }



    if (isset($_POST['login'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $query=$con->prepare("select * from tbl_user where username=:username and password=:password");
        $query->BindParam(":username",$username);
        $query->BindParam(":password",$password);
        $query->execute();
        $qu=$query->rowCount();
        if ($qu>0){
            $query=$query->fetch();
            $_SESSION['id_user']=$query['id_user'];
            $_SESSION['username']=$query['username'];
            $_SESSION['level']=$query['level'];
           header('location:index.php');
        }
        else{
            $_SESSION['peringatan']="Login Gagal.";
        }
    }


if (!empty($_GET['keluar']) and $_GET['keluar']=="true"){
    session_destroy();
    $_SESSION['peringatan']="Anda Telah Keluar.";
    header('location:index.php');
}



if (isset($_POST['simpan_kontrakan'])){
	if (!empty($_SESSION['id_user']) and $_SESSION['level']=="admin"){
	$kontrakan=$_POST['kontrakan'];
	$luas_tanah=$_POST['luas_tanah'];
	$luas_kontrakan=$_POST['luas_kontrakan'];
	$lokasi=$_POST['lokasi'];
	$harga=$_POST['harga'];
	$status=$_POST['status'];
	$keterangan=$_POST['keterangan'];
	$query=$con->prepare("insert into tbl_kontrakan values('','$kontrakan','$luas_tanah','$luas_kontrakan','$lokasi','$harga','$status','$keterangan')");
	$query->execute();
	$query=$con->prepare("select id_kontrakan from tbl_kontrakan where kontrakan=:kontrakan order by id_kontrakan desc");
	$query->BindParam(":kontrakan",$kontrakan);
	$query->execute();
	$q=$query->fetch();
	$id_kontrakan=$q['id_kontrakan'];
	$folder="gambar/";
	if (!empty($_FILES['gambar1']['name'])){
	$gambar1=$_FILES['gambar1']['name'];
	$target1=$folder.basename($gambar1);
	move_uploaded_file($_FILES['gambar1']['tmp_name'], $target1);
	$query=$con->prepare("insert into tbl_gambar values('',:id_kontrakan,:gambar)");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->BindParam(":gambar",$gambar1);
	$query->execute();
	}
	if (!empty($_FILES['gambar2']['name'])){
	$gambar2=$_FILES['gambar2']['name'];
	$target2=$folder.basename($gambar2);
	move_uploaded_file($_FILES['gambar2']['tmp_name'], $target2);
	$query=$con->prepare("insert into tbl_gambar values('',:id_kontrakan,:gambar)");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->BindParam(":gambar",$gambar2);
	$query->execute();
	}
	if (!empty($_FILES['gambar3']['name'])){
	$gambar3=$_FILES['gambar3']['name'];
	$target3=$folder.basename($gambar3);
	move_uploaded_file($_FILES['gambar3']['tmp_name'], $target3);
	$query=$con->prepare("insert into tbl_gambar values('',:id_kontrakan,:gambar)");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->BindParam(":gambar",$gambar3);
	$query->execute();
	}
	if (!empty($_FILES['gambar4']['name'])){
	$gambar4=$_FILES['gambar4']['name'];
	$target4=$folder.basename($gambar4);
	move_uploaded_file($_FILES['gambar4']['tmp_name'], $target4);
	$query=$con->prepare("insert into tbl_gambar values('',:id_kontrakan,:gambar)");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->BindParam(":gambar",$gambar4);
	$query->execute();
	$_SESSION['peringatan']="Kontrakan Disimpan, anda dapat menambahkan Fasilitas Kontrakan melalui Tabel Kontrakan.";
	header('location:index.php?halaman=kontrakan');
	}
	}else{
			$_SESSION['peringatan']="Silahkan Login.";
			header('location:index.php?halaman=login');
		}
}



if (isset($_POST['simpan_fasilitas'])){
	if (!empty($_SESSION['id_user']) and $_SESSION['level']=="admin"){
	$id_kontrakan=$_POST['id_kontrakan'];
	$fasilitas=$_POST['fasilitas'];
	$query=$con->prepare("insert into tbl_fasilitas values ('',:id_kontrakan,:fasilitas)");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->BindParam(":fasilitas",$fasilitas);
	$query->execute();
	$_SESSION['peringatan']="Fasilitas Ditambahkan.";
	header('location:index.php?fasilitas='.$id_kontrakan);
	}else{
			$_SESSION['peringatan']="Silahkan Login.";
			header('location:index.php?halaman=login');
		}
}




if (isset($_POST['simpan_penyewa'])){
	if (!empty($_SESSION['id_user'])){
	$id_kontrakan=$_POST['id_kontrakan'];
	$query=$con->prepare("select status from tbl_kontrakan where id_kontrakan=:id_kontrakan");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$qc=$query->fetch();
	if ($qc['status']<>"Tersedia"){
		$_SESSION['peringatan']="Kontrakan yang dipilih Telah Disewa / Tidak Tersedia, Silahkan pilih Kontrakan Lain.";
			header('location:index.php');
	}else{
	$harga=$_POST['harga'];
	$periode=$_POST['periode'];
	$total=$harga*$periode;
	$no_ktp=$_POST['ktp'];
	$nama=$_POST['nama'];
	$hp=$_POST['hp'];
	$alamat=$_POST['alamat'];
	$pekerjaan=$_POST['pekerjaan'];
	$status=$_POST['status'];
	$umur=$_POST['umur'];
	$foto=$_FILES['foto']['name'];
	$folder="gambar/";
	$target=$folder.basename($foto);
	move_uploaded_file($_FILES['foto']['tmp_name'], $target);
	$date=date('Y-m-d h:i:s');
	$id_user=$_SESSION['id_user'];
	$query=$con->prepare("insert into tbl_penyewa values ('',:no_ktp,:nama,:hp,:alamat,:pekerjaan,:status,:umur,:foto,:date,:id_user)");
	$query->BindParam(":no_ktp",$no_ktp);
	$query->BindParam(":nama",$nama);
	$query->BindParam(":hp",$hp);
	$query->BindParam(":alamat",$alamat);
	$query->BindParam(":pekerjaan",$pekerjaan);
	$query->BindParam(":status",$status);
	$query->BindParam(":umur",$umur);
	$query->BindParam(":foto",$foto);
	$query->BindParam(":date",$date);
	$query->BindParam(":id_user",$id_user);
	$query->execute();

	$query=$con->prepare("select * from tbl_penyewa where no_ktp=:no_ktp and id_user=:id_user order by id_penyewa desc");
	$query->BindParam(":no_ktp",$no_ktp);
	$query->BindParam(":id_user",$id_user);
	$query->execute();
	$ambil=$query->fetch();

	$id_penyewa=$ambil['id_penyewa'];
	$status_sewa="Belum Bayar";
	$query=$con->prepare("insert into tbl_pembayaran (id_penyewa,id_kontrakan,harga_sewa,periode,total_biaya,status_sewa,date_bayar) values (:id_penyewa,:id_kontrakan,:harga,:periode,:total,:status_sewa,Now())");
	$query->BindParam(":id_penyewa",$id_penyewa);
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->BindParam(":harga",$harga);
	$query->BindParam(":periode",$periode);
	$query->BindParam(":total",$total);
	$query->BindParam(":status_sewa",$status_sewa);
	$query->execute();

	$query=$con->prepare("select * from tbl_pembayaran where id_penyewa=:id_penyewa order by id_pembayaran desc");
	$query->BindParam(":id_penyewa",$id_penyewa);
	$query->execute();
	$id=$query->fetch();
	$id_pembayaran=$id['id_pembayaran'];
	$_SESSION['peringatan']="Berhasil Disimpan, ID Pembayaran anda : ".$id_pembayaran;
	header('location:index.php?halaman=konfirmasi_sewa&token='.$id_pembayaran);
	}
	}else{
			$_SESSION['peringatan']="Silahkan Login.";
			header('location:index.php?halaman=login');
		}

}



if (isset($_POST['simpan_pembayaran'])){
	if (!empty($_SESSION['id_user']) and $_SESSION['level']=="admin"){
	$id_kontrakan=$_POST['id_kontrakan'];
	$query=$con->prepare("select status from tbl_kontrakan where id_kontrakan=:id_kontrakan");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$qc=$query->fetch();
	if ($qc['status']<>"Tersedia"){
		$_SESSION['peringatan']="Kontrakan yang dipilih Telah Disewa / Tidak Tersedia, Silahkan pilih Kontrakan Lain.";
			header('location:index.php');
	}else{
	$id_pembayaran=$_POST['id_pembayaran'];
	$harga=$_POST['harga'];
	$periode=$_POST['periode'];
	$total=$harga*$periode;
	$jumlah_bayar=$_POST['jumlah_bayar'];
	$sisa=$total-$jumlah_bayar;
	if ($sisa<0){
		$sisa="0";
	}
	if ($sisa<=0){
		$status="Lunas";
	}
	else{
		$status="Kurang";
	}
	$tgl1=$_POST['tgl1'];
	$tgl2=$_POST['tgl2'];
	$status_sewa="Berlaku";
	$date=date('Y-m-d');
	$query=$con->prepare("update tbl_pembayaran set harga_sewa=:harga,periode=:periode,total_biaya=:total,jml_bayar=:jumlah_bayar,sisa=:sisa,status_bayar=:status,tgl_mulai=:tgl1,tgl_akhir=:tgl2,status_sewa=:status_sewa,date_bayar=:date where id_pembayaran=:id_pembayaran");
	$query->BindParam(":harga",$harga);
	$query->BindParam(":periode",$periode);
	$query->BindParam(":total",$total);
	$query->BindParam(":jumlah_bayar",$jumlah_bayar);
	$query->BindParam(":sisa",$sisa);
	$query->BindParam(":status",$status);
	$query->BindParam(":tgl1",$tgl1);
	$query->BindParam(":tgl2",$tgl2);
	$query->BindParam(":status_sewa",$status_sewa);
	$query->BindParam(":date",$date);
	$query->BindParam(":id_pembayaran",$id_pembayaran);
	$query->execute();

	$query=$con->prepare("select id_kontrakan from tbl_pembayaran where id_pembayaran=:id_pembayaran");
	$query->BindParam(":id_pembayaran",$id_pembayaran);
	$query->execute();
	$q=$query->fetch();
	$id_kontrakan=$q['id_kontrakan'];
	$query=$con->prepare("update tbl_kontrakan set status='Telah Disewa' where id_kontrakan=:id_kontrakan");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();

	$_SESSION['peringatan']="Pembayaran telah Disimpan.";
	header('location:index.php?halaman=tabel_pembayaran');
	}
	}else{
			$_SESSION['peringatan']="Silahkan Login.";
			header('location:index.php?halaman=login');
		}
}

	if (!empty($_GET['telah_dilunasi'])){
		if (!empty($_SESSION['id_user']) and $_SESSION['level']=="admin"){
		$id_pembayaran=$_GET['telah_dilunasi'];
		$query=$con->prepare("select * from tbl_pembayaran where id_pembayaran=:id_pembayaran");
		$query->BindParam(":id_pembayaran",$id_pembayaran);
		$query->execute();
		$q=$query->fetch();
		$total=$q['total_biaya'];
		$query=$con->prepare("update tbl_pembayaran set status_bayar='Lunas',sisa='0',jml_bayar='$total' where id_pembayaran=:id_pembayaran");
		$query->BindParam(":id_pembayaran",$id_pembayaran);
		$query->execute();
		$_SESSION['peringatan']="Berhasil Melunasi Pembayaran.";
		header('location:index.php?halaman=tabel_pembayaran');
		}else{
			$_SESSION['peringatan']="Silahkan Login.";
			header('location:index.php?halaman=login');
		}		
	}

	if (!empty($_GET['hapus_kontrakan'])){
		$id_kontrakan=$_GET['hapus_kontrakan'];
		$query=$con->prepare("delete from tbl_kontrakan where id_kontrakan=:id_kontrakan");
		$query->BindParam(":id_kontrakan",$id_kontrakan);
		$query->execute();
		$_SESSION['peringatan']="Kontrakan berhasil dihapus.";
			header('location:index.php?halaman=tabel_kontrakan');
	}

	if (!empty($_GET['hapus_penyewa'])){
		$id_penyewa=$_GET['hapus_penyewa'];
		$query=$con->prepare("delete from tbl_penyewa where id_penyewa=:id_penyewa");
		$query->BindParam(":id_penyewa",$id_penyewa);
		$query->execute();
		$_SESSION['peringatan']="Penyewa berhasil dihapus.";
			header('location:index.php?halaman=tabel_penyewa');
	}

	if (!empty($_GET['hapus_fasilitas'])){
		$id_fasilitas=$_GET['hapus_fasilitas'];
		$token=$_GET['token'];
		$query=$con->prepare("delete from tbl_fasilitas where id_fasilitas=:id_fasilitas");
		$query->BindParam(":id_fasilitas",$id_fasilitas);
		$query->execute();
		$_SESSION['peringatan']="Fasilitas berhasil dihapus.";
			header('location:index.php?fasilitas='.$token);
	}


	if (!empty($_GET['hapus_user'])){
		$id_user=$_GET['hapus_user'];
		$query=$con->prepare("delete from tbl_user where id_user=:id_user");
		$query->BindParam(":id_user",$id_user);
		$query->execute();
		$_SESSION['peringatan']="User berhasil dihapus.";
			header('location:index.php?halaman=tabel_user');
	}


	if (!empty($_GET['set_admin'])){
		$id_user=$_GET['set_admin'];
		$query=$con->prepare("update tbl_user set level='admin' where id_user=:id_user");
		$query->BindParam(":id_user",$id_user);
		$query->execute();
		header('location:index.php?halaman=tabel_user');
	}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kontrakan Sungai Cubadak</title>

        <!-- Bootstrap core CSS -->

        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <link href="bootstrap/fonts/css/font-awesome.min.css" rel="stylesheet">
        <link href="bootstrap/css/animate.min.css" rel="stylesheet">

        <!-- Documentation extras -->

        <link href="bootstrap/css/docs.min.css" rel="stylesheet">

        <script src="bootstrap/js/jquery.min.js"></script>

        <script type="text/javascript" src="bootstrap/js/shCore.js"></script>
        <script type="text/javascript" src="bootstrap/js/shBrushXml.js"></script>
        <link type="text/css" rel="stylesheet" href="bootstrap/css/shCoreDefault.css"/>
        <script type="text/javascript">SyntaxHighlighter.all();</script>


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            body{
                font-size: 14px;
                color: #5900ab;
            }
            h3{
            	color: rgb(245, 5, 123);
            }
        </style>
    </head>
<body data-twttr-rendered="true" cz-shortcut-listen="true" style="background: antiquewhite;">

       <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><i class="glyphicon glyphicon-home"></i> Kontrakan Sungai Cubadak</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php"><i class="fa fa-home"></i> Beranda</a></li>
            <li><a href="index.php?halaman=contact"><i class="fa fa-phone"></i> Hubungi Kami</a></li>
            <li><a href="index.php?halaman=tentang"><i class="fa fa-about"></i> Tentang Kami</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          <?php if (empty($_SESSION['id_user'])){ ?>
            <li><a href="index.php?halaman=daftar"><i class="glyphicon glyphicon-user"></i> Daftar</a></li>
            <li><a href="index.php?halaman=login"><i class="glyphicon glyphicon-log-in"></i> Login</a></li>
            <?php }else{ ?>
            <li><a><i class="glyphicon glyphicon-user"></i> <?php echo $_SESSION['username']; ?></a></li>
            <li><a href="index.php?keluar=true"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
            <?php } ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<br><br><br>
<?php if (!empty($_SESSION['peringatan'])){ ?>
<div class="alert alert-warning" style="border-radius: 0;">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['peringatan']; ?>
</div>
<?php unset($_SESSION['peringatan']); } ?>



<?php if (!empty($_SESSION['username'])){ ?>  
<style>
	.menu{
		background: #9e0885;
		border-radius: 0 10px 10px 0;
		margin-bottom: 10px;
	}
	.menu ul{
		list-style: none;
		overflow: hidden;
	}
	.menu ul li a{
		padding: 10px 16px;
		display: block;
		color: white;
	}
	.menu ul li a:hover{
		color: #fff;
		text-decoration: none;
		background: #bc1490;
	}
</style>
<div class="col-md-3 col-sm-4 col-xs-12 menu">
<h3 class="page-header" style="margin-top: 10px; color: #f5d6d6;"><i class="glyphicon glyphicon-user"></i> Administrator</h3>
<?php if ($_SESSION['level']=="admin"){ ?>
<ul style="list-style: none; padding: 0;">
	<li><a href="index.php?halaman=kontrakan"><i class="fa fa-edit"></i> Tambah Kontrakan</a></li>
</ul>
<ul style="list-style: none; padding: 0;">
	<li><a href="index.php?halaman=tabel_user"><i class="fa fa-table"></i> Tabel User</a></li>
	<li><a href="index.php?halaman=tabel_penyewa"><i class="fa fa-table"></i> Tabel Penyewa</a></li>
	<li><a href="index.php?halaman=tabel_kontrakan"><i class="fa fa-table"></i> Tabel Kontrakan</a></li>
	<li><a href="index.php?halaman=tabel_pembayaran"><i class="fa fa-table"></i> Tabel Pembayaran</a></li>	
</ul>
<ul style="list-style: none;padding: 0;">
	<li><a href="index.php?halaman=pembayaran"><i class="fa fa-dollar"></i> Pembayaran Sewa</a></li>
</ul>
<ul style="list-style: none;padding: 0;">
	<li><a href="index.php?laporan=kontrakan"><i class="fa fa-bar-chart-o"></i> Laporan Kontrakan</a></li>
	<li><a href="index.php?laporan=penyewa"><i class="fa fa-bar-chart-o"></i> Laporan Penyewa</a></li>
	<li><a href="index.php?laporan=pembayaran"><i class="fa fa-bar-chart-o"></i> Laporan Pembayaran</a></li>
</ul>
<?php
 }

if (!empty($_SESSION['id_user']) and $_SESSION['level']=="user"){
?>
<ul style="list-style: none;padding: 0;">
	<li><a href="index.php?halaman=tabel_penyewa"><i class="fa fa-table"></i> Data Penyewa</a></li>
	<li><a href="index.php?halaman=tabel_pembayaran"><i class="fa fa-table"></i> Data Pembayaran</a></li>
</ul>
<?php } ?>
</div>
<?php } ?>

<?php if (empty($_SESSION['id_user'])){ ?>
<div class="col-md-12 col-sm-12 col-xs-12">

<?php }else{ ?>
<div class="col-md-9 col-sm-8 col-xs-12">
	<?php } ?>




<?php if (!empty($_SESSION['username']) and $_SESSION['level']=="admin"){ ?>






<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="tabel_pembayaran"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px; overflow: scroll; max-height: 800px;">
<h3 class="page-header" style="margin-top: 10px;">Tabel Pembayaran</h3>
<ul>
	<li>Klik tombol <strong>Pembayaran</strong> jika pengguna ingin / telah melakukan pembayaran dan penentuan waktu Kontrak</li>
	<li>Jika pembayaran sebelumnya kurang dan telah dilunasi, silahkan klik tombol <strong>Telah Dilunasi</strong></li>
	<li>Anda dapat mengakhiri Kontrak / Sewa yang masih berlaku dengan melakukan klik pada tombol <strong>Akhiri Kontrak</strong> dan kontrakan tersebut dapat disewa oleh orang lain</li>
	<li>Jika tanggal sekarang lebih besar dari Tanggal Akhir kontrak, Maka secara otomatis Status Sewa menjadi Telah Habis</li>
</ul>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Id</th>
				<th>Nama Penyewa</th>
				<th>Kontrakan</th>
				<th>Periode</th>
				<th>Total</th>
				<th>Bayar</th>
				<th>Sisa</th>
				<th>Status Bayar</th>
				<th>Tgl Mulai</th>
				<th>Tgl Akhir</th>
				<th>Status Sewa</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$query=$con->prepare("select * from tbl_penyewa,tbl_kontrakan,tbl_pembayaran where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_pembayaran.id_penyewa=tbl_penyewa.id_penyewa order by tbl_pembayaran.id_pembayaran desc");
				$query->execute();
				$query=$query->fetchAll();
				foreach ($query as $q) { ?>
				<tr><td><?php echo $q['id_pembayaran']; ?></td>
				<td><?php echo $q['nama_penyewa']; ?></td>
				<td><a href="index.php?detail=<?php echo $q['id_kontrakan']; ?>"><?php echo $q['kontrakan']; ?></a></td>
				<td><?php echo $q['periode']; ?> Tahun</td>
				<td>Rp.<?php echo number_format($q['total_biaya'],0,".","."); ?></td>
				<td>Rp.<?php echo number_format($q['jml_bayar'],0,".","."); ?></td>
				<td>Rp.<?php echo number_format($q['sisa'],0,".","."); ?></td>
				<td><?php echo $q['status_bayar']; ?></td>
				<td><?php echo $q['tgl_mulai']; ?></td>
				<td><?php echo $q['tgl_akhir']; ?></td>
				<td><?php echo $q['status_sewa']; ?></td>
				<td>
				<?php if ($q['status_bayar']=="Kurang" and $q['status_sewa']<>"Telah Habis"){ ?><a class="btn btn-success" href="index.php?telah_dilunasi=<?php echo $q['id_pembayaran']; ?>">Telah Dilunasi</a><?php } ?>
				<?php if ($q['status_sewa']=="Belum Bayar" and $q['status_sewa']<>"Telah Habis"){ ?><a class="btn btn-info" href="index.php?pembayaran_sewa=<?php echo $q['id_pembayaran']; ?>">Pembayaran</a><?php } ?>
				<?php if ($q['status_sewa']=="Berlaku"){ ?><a class="btn btn-info" href="index.php?akhiri_sewa=<?php echo $q['id_pembayaran']; ?>">Akhiri Kontrak</a><?php } ?>
				</td>
				</tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>






<?php if (isset($_POST['pembayaran']) or !empty($_GET['pembayaran_sewa'])){ 
	if (isset($_POST['pembayaran'])){
	$id_pembayaran=$_POST['id_pembayaran'];
	}
	if (!empty($_GET['pembayaran_sewa'])){
	$id_pembayaran=$_GET['pembayaran_sewa'];	
	}
	$query=$con->prepare("select status_bayar,status_sewa from tbl_pembayaran where id_pembayaran=:id_pembayaran");
	$query->BindParam(":id_pembayaran",$id_pembayaran);
	$query->execute();
	$r=$query->rowCount();
	$s=$query->fetch();
	if ($s['status_bayar']=="Lunas"){
		$_SESSION['peringatan']="Pembayaran telah Lunas.";
		?>
		<script>
			window.location.href="index.php?halaman=pembayaran";
		</script>
		<?php
	}else if ($r<1){
		$_SESSION['peringatan']="Id Pembayaran tidak Ditemukan.";
		?>
		<script>
			window.location.href="index.php?halaman=pembayaran";
		</script>
		<?php
	}else if ($s['status_bayar']=="Kurang" and $s['status_sewa']<>"Telah Habis"){
		$_SESSION['peringatan']="Silahkan klik tombol <strong>Telah Dilunasi</strong> pada Tabel Pembayaran jika penyewa telah melunasi pembayaran.";
		?>
		<script>
			window.location.href="index.php?halaman=pembayaran";
		</script>
		<?php
	}else if ($s['status_sewa']=="Telah Habis"){
		$_SESSION['peringatan']="Tidak dapat melakukan pembayaran untuk Id Pembayaran tersebut.";
		?>
		<script>
			window.location.href="index.php?halaman=pembayaran";
		</script>
		<?php
	}else{
?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px;">
<h3 class="page-header" style="margin-top: 10px;">Pembayaran Sewa Kontrakan</h3>
<?php
$query=$con->prepare("select tbl_pembayaran.periode,tbl_pembayaran.id_pembayaran,tbl_kontrakan.kontrakan,tbl_kontrakan.harga,tbl_penyewa.id_penyewa,tbl_pembayaran.id_kontrakan,tbl_penyewa.nama_penyewa from tbl_kontrakan,tbl_penyewa,tbl_pembayaran where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_penyewa.id_penyewa=tbl_pembayaran.id_penyewa and tbl_pembayaran.id_pembayaran=:id_pembayaran");
$query->BindParam(":id_pembayaran",$id_pembayaran);
$query->execute();
$q=$query->fetch();
$total=$q['harga']*$q['periode'];
?>
<form action="#" method="POST">
	Id Pembayaran<br>
	<input type="text" class="form-control" name="id_pembayaran" required="" readonly="" value="<?php echo $q['id_pembayaran']; ?>"><br>
	Id Penyewa<br>
	<input type="text" class="form-control" name="id_penyewa" required="" readonly="" value="<?php echo $q['id_penyewa']; ?>"><br>
	Nama Penyewa<br>
	<input type="text" class="form-control" name="nama_penyewa" readonly="" value="<?php echo $q['nama_penyewa']; ?>"><br>
	Id Kontrakan<br>
	<input type="text" class="form-control" name="id_kontrakan" readonly="" value="<?php echo $q['id_kontrakan']; ?>"><br>
	Nomor / Nama Kontrakan<br>
	<input type="text" class="form-control" name="nama_kontrakan" readonly="" value="<?php echo $q['kontrakan']; ?>"><br>
	Harga Kontrakan / Tahun<br>
	<input type="text" class="form-control" name="harga" id="harga" readonly="" value="<?php echo $q['harga']; ?>"><br>
	Periode Sewa / Tahun<br>
	<input type="number" class="form-control" onkeyup="sum()" onclick="sum()" name="periode" id="periode"  value="<?php echo $q['periode']; ?>" required=""><br>
	Total Biaya Sewa<br>
	<input type="text" class="form-control" name="total" id="total" value="<?php echo $total; ?>" required="" readonly=""><br>
	Jumlah Bayar<br>
	<input type="text" class="form-control" onchange="sum2()" onclick="sum2()" onkeyup="sum2()" name="jumlah_bayar" id="bayar"><br>
	Sisa Bayar<br>
	<input type="text" class="form-control" name="sisa" id="sisa" readonly=""><br>
	Status Bayar<br>
	<input type="text" class="form-control" name="status" id="status" readonly=""><br>
	Tanggal Mulai<br>
	<input type="date" class="form-control" name="tgl1" placeholder="YYYY-MM-DD"><br>
	Tanggal Berakhir<br>
	<input type="date" class="form-control" name="tgl2" placeholder="YYYY-MM-DD"><br>
	<input type="submit" class="btn btn-success btn-md" name="simpan_pembayaran" onmouseover="sum2()" value="Simpan">
	<br><br>
</form>
	<script>
	function sum(){
		var a=document.getElementById("harga").value;
		var b=document.getElementById("periode").value;
		var c=parseInt(a)*parseInt(b);
		if (!isNaN(c)){
			document.getElementById("total").value=c;
		}
	}

	function sum2(){
		var a=document.getElementById("total").value;
		var b=document.getElementById("bayar").value;
		var c=parseInt(a)-parseInt(b);
		if (!isNaN(c)){
			if (c>0){
			document.getElementById("status").value="Kurang";
			document.getElementById("sisa").value=c;
			}else{
				document.getElementById("status").value="Lunas";
				document.getElementById("sisa").value="0";
			}
		}
		else{
			document.getElementById("status").value="Isikan Jumlah Bayar";	
		}
	}
</script>
</div>
<?php } } ?>





<?php if (!empty($_GET['fasilitas'])){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; ">
<?php
$id_kontrakan=$_GET['fasilitas'];
$query=$con->prepare("select * from tbl_kontrakan where id_kontrakan=:id_kontrakan");
$query->BindParam(":id_kontrakan",$id_kontrakan);
$query->execute();
$q=$query->fetch();
?>
<h3>Tambah Fasilitas Kontrakan</h3>
<form action="#" method="POST">
	Id Kontrakan<br>
	<input type="text" class="form-control" readonly="" required="" name="id_kontrakan" value="<?php echo $q['id_kontrakan']; ?>"><br>
	Nomor / Nama Kontrakan<br>
	<input type="text" class="form-control" readonly="" name="kontrakan" value="<?php echo $q['kontrakan']; ?>"><br>
	Nama Fasilitas<br>
	<input type="text" class="form-control" name="fasilitas" maxlength="30"><br>
	<input type="submit" class="btn btn-success btn-md" name="simpan_fasilitas" value="Simpan">
</form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; overflow: scroll; max-height: 600px;">
<h3>Tabel Fasilitas</h3>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Id Fasilitas</th>
				<th>Id Kontrakan</th>
				<th>Nomor / Nama Kontrakan</th>
				<th>Fasilitas</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$query=$con->prepare("select tbl_fasilitas.id_fasilitas,tbl_fasilitas.id_kontrakan,tbl_fasilitas.fasilitas,tbl_kontrakan.kontrakan from tbl_kontrakan,tbl_fasilitas where tbl_kontrakan.id_kontrakan=tbl_fasilitas.id_kontrakan and tbl_fasilitas.id_kontrakan=:id_kontrakan order by tbl_fasilitas.id_fasilitas desc");
				$query->BindParam(":id_kontrakan",$id_kontrakan);
				$query->execute();
				$query=$query->fetchAll();
				foreach ($query as $q) { ?>
				<tr><td><?php echo $q['id_fasilitas']; ?></td>
				<td><?php echo $q['id_kontrakan']; ?></td>
				<td><?php echo $q['kontrakan']; ?></td>
				<td><?php echo $q['fasilitas']; ?></td>
				<td><a class="btn btn-danger btn-xs" style="margin: 2px;" href="index.php?hapus_fasilitas=<?php echo $q['id_fasilitas']; ?>&token=<?php echo $q['id_kontrakan']; ?>"><i class="glyphicon glyphicon-trash"></i> Hapus</a></td></tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>




<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="tabel_penyewa"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px; overflow: scroll; max-height: 600px;">
<h3 class="page-header" style="margin-top: 10px;">Tabel Penyewa</h3>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Id</th>
				<th>No KTP</th>
				<th>Nama Penyewa</th>
				<th>HP</th>
				<th>Alamat</th>
				<th>Pekerjaan</th>
				<th>Status Hubungan</th>
				<th>Umur</th>
				<th>Foto</th>
				<th>Id User</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$query=$con->prepare("select * from tbl_penyewa order by nama_penyewa asc");
				$query->execute();
				$query=$query->fetchAll();
				foreach ($query as $q) { ?>
				<tr><td><?php echo $q['id_penyewa']; ?></td>
				<td><?php echo $q['no_ktp']; ?></td>
				<td><?php echo $q['nama_penyewa']; ?></td>
				<td><?php echo $q['hp']; ?></td>
				<td><?php echo $q['alamat']; ?></td>
				<td><?php echo $q['pekerjaan']; ?></td>
				<td><?php echo $q['status_hubungan']; ?></td>
				<td><?php echo $q['umur']; ?></td>
				<td><a href="gambar/<?php echo $q['foto']; ?>">Lihat</a></td>
				<td><?php echo $q['id_user']; ?></td>
				<td><a class="btn btn-danger btn-xs" href="index.php?hapus_penyewa=<?php echo $q['id_penyewa']; ?>"><i class="glyphicon glyphicon-trash"></i> Hapus</a></td>
				</tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>







<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="tabel_kontrakan"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px; overflow: scroll; max-height: 600px;">
<h3 class="page-header" style="margin-top: 10px;">Tabel Kontrakan</h3>
<ul>
	<li>Status Kontrakan secara otomatis berubah menjadi Telah Disewa jika calon penyewa telah melakukan Pembayaran</li>
	<li>Jika calon Penyewa belum melakukan pembayaran, maka Status Kontrakan tersebut masih Tersedia dan dapat di Sewa oleh orang lain</li>
</ul>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Id</th>
				<th>Nomor / Nama Kontrakan</th>
				<th>Harga Sewa</th>
				<th>Satus Kontrakan</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$query=$con->prepare("select id_kontrakan,kontrakan,harga,status from tbl_kontrakan order by id_kontrakan desc");
				$query->execute();
				$query=$query->fetchAll();
				foreach ($query as $q) { ?>
				<tr><td><?php echo $q['id_kontrakan']; ?></td>
				<td><a href="index.php?detail=<?php echo $q['id_kontrakan']; ?>"><?php echo $q['kontrakan']; ?></a></td>
				<td>Rp.<?php echo number_format($q['harga'],0,".","."); ?></td>
				<td><?php echo $q['status']; ?></td>
				<td><a class="btn btn-info btn-xs" style="margin: 2px;" href="index.php?fasilitas=<?php echo $q['id_kontrakan']; ?>"><i class="glyphicon glyphicon-plus"></i> Fasilitas</a> <a class="btn btn-danger btn-xs" style="margin: 2px;" href="index.php?hapus_kontrakan=<?php echo $q['id_kontrakan']; ?>"><i class="glyphicon glyphicon-trash"></i> Hapus</a></td></tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>

<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="tabel_user"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px; overflow: scroll; max-height: 600px;">
<h3 class="page-header" style="margin-top: 10px;">Tabel User</h3>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Id User</th>
				<th>Nama Lengkap</th>
				<th>Username</th>
				<th>Level</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$query=$con->prepare("select * from tbl_user order by nama_lengkap asc");
				$query->execute();
				$query=$query->fetchAll();
				foreach ($query as $q) { ?>
				<tr><td><?php echo $q['id_user']; ?></td>
				<td><?php echo $q['nama_lengkap']; ?></td>
				<td><?php echo $q['username']; ?></td>
				<td><?php echo $q['level']; ?></td>
				<td>
				<?php if ($q['level']<>"admin"){ ?>
				<a class="btn btn-primary btn-xs" style="margin: 2px;" href="index.php?set_admin=<?php echo $q['id_user']; ?>"><i class="glyphicon glyphicon-user"></i> Jadikan Admin</a>
				<?php } ?>
				<a class="btn btn-danger btn-xs" style="margin: 2px;" href="index.php?hapus_user=<?php echo $q['id_user']; ?>"><i class="glyphicon glyphicon-trash"></i> Hapus</a>
				</td></tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>




	<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="kontrakan"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px;">
<h3 class="page-header" style="margin-top: 10px;">Tambah Data Kontrakan</h3>
<form action="#" method="POST" enctype="multipart/form-data">
<div class="col-md-6 col-sm-6 col-xs-12">
	Nomor / Nama Kontrakan<br>
	<input type="text" class="form-control" name="kontrakan" maxlength="50" required=""><br>
	Luas Tanah<br>
	<input type="text" class="form-control" name="luas_tanah" maxlength="15" required=""><br>
	Luas Kontrakan<br>
	<input type="text" class="form-control" name="luas_kontrakan" maxlength="15" required=""><br>
	Lokasi Kontrakan<br>
	<textarea class="form-control" name="lokasi" required=""></textarea><br>
	Harga Sewa / Tahun<br>
	<input type="text" class="form-control" name="harga" maxlength="10" required=""><br>
</div>
<div class="col-md-6 col-sm-6 col-xs-12">
	Status Kontrakan<br>
	<select name="status" class="form-control" required="">
		<option value="">Pilih</option>
		<option value="Tersedia">Tersedia</option>
		<option value="Tidak Tersedia">Tidak Tersedia</option>
	</select><br>
	Gambar 1<br>
	<input type="file" name="gambar1" required=""><br>
	Gambar 2<br>
	<input type="file" name="gambar2"><br>
	Gambar 3<br>
	<input type="file" name="gambar3"><br>
	Gambar 4<br>
	<input type="file" name="gambar4"><br>
	Keterangan<br>
	<textarea name="keterangan" class="form-control" required=""></textarea><br>
	<button type="submit" class="btn btn-success btn-lg" name="simpan_kontrakan"><i class="glyphicon glyphicon-save"></i> SIMPAN</button>
</div>
</form>

</div>
<?php } ?>


<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="pembayaran"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px;">
<h3 class="page-header" style="margin-top: 10px;">Pembayaran Sewa Kontrakan</h3>
<form action="#" method="POST">
	Masukkan ID Pembayaran<br>
	<input type="number" name="id_pembayaran" required="">
	<button type="submit" name="pembayaran" class="btn btn-primary" ><i class="glyphicon glyphicon-search"></i> CARI</button>
</form>
<?php } ?>





<?php if (!empty($_GET['laporan']) and $_GET['laporan']=="penyewa"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc;">
<h3 class="page-header" style="margin-top: 10px;">Laporan Penyewa Kontrakan</h3>
	<div class="col-md-3 col-sm-6 col-xs-12">
	<h4>Semua Penyewa</h4>
	<form action="laporan_penyewa.php" method="POST">
		<button class="btn btn-primary" type="submit" name="semua_penyewa">Tampilkan Semua</button>
	</form>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 10px;">
	<h4>Tahun Berakhir Sewa</h4>
	<form action="laporan_penyewa.php" method="POST">
		<select class="form-control" name="tahun">
			<option value="">Pilih Tahun</option>
			<?php $thn=date('Y'); while ($thn <= 2025) { ?>
				<option value="<?php echo $thn; ?>"><?php echo $thn; ?></option>
			<?php $thn++; } ?>
		</select>
		<button class="btn btn-primary" style="margin-top: 5px;" type="submit" name="tahun_akhir">Tampilkan</button>
	</form>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 10px;">
	<h4>Status Sewa</h4>
	<form action="laporan_penyewa.php" method="POST">
		<select class="form-control" name="status">
			<option value="">Pilih Status Sewa</option>
				<option value="Berlaku">Berlaku</option>
				<option value="Telah Habis">Telah Habis</option>
				<option value="Belum Bayar">Belum Bayar</option>
		</select>
		<button class="btn btn-primary" style="margin-top: 5px;" type="submit" name="status_sewa">Tampilkan</button>
	</form>
	</div>

</div>
<?php } ?>




<?php if (!empty($_GET['laporan']) and $_GET['laporan']=="pembayaran"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc;">
<h3 class="page-header" style="margin-top: 10px;">Laporan Pembayaran Kontrakan</h3>
	<div class="col-md-3 col-sm-6 col-xs-12">
	<h4>Semua Pembayaran</h4>
	<form action="laporan_pembayaran.php" method="POST">
		<button class="btn btn-success" type="submit" name="semua_pembayaran">Tampilkan Semua</button>
	</form>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 10px;">
	<h4>Status Pembayaran</h4>
	<form action="laporan_pembayaran.php" method="POST">
		<select class="form-control" name="status">
			<option value="">Pilih Status</option>
				<option value="Lunas">Lunas</option>
				<option value="Kurang">Kurang</option>
		</select>
		<button class="btn btn-success" style="margin-top: 5px;" type="submit" name="status_bayar">Tampilkan</button>
	</form>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 10px;">
	<h4>Periode Sewa</h4>
	<form action="laporan_pembayaran.php" method="POST">
		<select class="form-control" name="periode">
			<option value="">Pilih Periode</option>
				<?php $periode=1; while ($periode <= 10) { ?>
				<option value="<?php echo $periode; ?>"><?php echo $periode; ?> Tahun</option>
			<?php $periode++; } ?>
		</select>
		<button class="btn btn-success" style="margin-top: 5px;" type="submit" name="periode_sewa">Tampilkan</button>
	</form>
	</div>

</div>
<?php } ?>



<?php if (!empty($_GET['laporan']) and $_GET['laporan']=="kontrakan"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc;">
<h3 class="page-header" style="margin-top: 10px;">Laporan Kontrakan</h3>
	<div class="col-md-3 col-sm-6 col-xs-12">
	<h4>Semua Kontrakan</h4>
	<form action="laporan_kontrakan.php" method="POST">
		<button class="btn btn-danger" type="submit" name="semua_kontrakan">Tampilkan Semua</button>
	</form>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 10px;">
	<h4>Status Kontrakan</h4>
	<form action="laporan_kontrakan.php" method="POST">
		<select class="form-control" name="status">
			<option value="">Pilih Status</option>
				<option value="Tersedia">Tersedia</option>
				<option value="Telah Disewa">Telah Disewa</option>
		</select>
		<button class="btn btn-danger" style="margin-top: 5px;" type="submit" name="status_kontrakan">Tampilkan</button>
	</form>
	</div>

</div>
<?php } ?>


<?php } ?>










<?php if (!empty($_GET['halaman']) and !empty($_GET['token'])){ ?>
<div class="col-md-12 col-sm-12 col-xs-12">
ID Pembayaran : <font size="7"><?php echo $_GET['token']; ?></font>
<ul>
	<li>Simpan ID Pembayaran anda</li>
	<li>ID Pembayaran akan digunakan saat melakukan pembayaran kontrakan yang anda Sewa</li>
</ul>
</div>
<?php } ?>



<?php if (!empty($_GET['sewa_kontrakan'])){
 if (!empty($_SESSION['id_user'])){
	$id_kontrakan=$_GET['sewa_kontrakan'];
	$query=$con->prepare("select * from tbl_kontrakan where id_kontrakan=:id_kontrakan");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$q=$query->fetch();
?>
<div class="col-md-8 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px;">
<h3 class="page-header" style="margin-top: 10px;">Sewa Kontrakan</h3>
	<form action="#" method="POST" enctype="multipart/form-data">
		Id Kontrakan<br>
		<input type="text" class="form-control" name="id_kontrakan" readonly="" required="" value="<?php echo $q['id_kontrakan']; ?>"><br>
		Nomor / Nama Kontrakan<br>
		<input type="text" class="form-control" name="nama_kontrakan" readonly="" required="" value="<?php echo $q['kontrakan']; ?>"><br>
		Harga Kontrakan / Tahun<br>
	<input type="text" class="form-control" name="harga" id="harga" readonly="" value="<?php echo $q['harga']; ?>"><br>
	Periode Sewa / Tahun<br>
	<input type="number" class="form-control" onkeyup="sum()" onclick="sum()" name="periode" id="periode" required=""><br>
	Total Biaya Sewa<br>
	<input type="text" class="form-control" name="total" id="total" required="" readonly=""><br>
		Nomor KTP<br>
		<input type="text" class="form-control" name="ktp" maxlength="30"><br>
		Nama Lengkap Penyewa<br>
		<input type="text" class="form-control" name="nama" maxlength="30"><br>
		No HP<br>
		<input type="text" class="form-control" name="hp"><br>
		Alamat Asal<br>
		<textarea name="alamat" class="form-control"></textarea><br>
		Pekerjaan<br>
		<input type="text" class="form-control" name="pekerjaan"><br>
		Status Hubungan<br>
		<select name="status" class="form-control">
			<option value="">Pilih</option>
			<option value="Belum Menikah">Belum Menikah</option>
			<option value="Menikah">Menikah</option>
			<option value="Duda">Duda</option>
			<option value="Janda">Janda</option>
		</select><br>
		Umur Sekarang<br>
		<input type="number" class="form-control" name="umur" maxlength="3"><br>
		Upload Foto Anda<br>
		<input type="file" name="foto"><br>
		<input type="submit" class="btn btn-success btn-lg" name="simpan_penyewa" value="Kirim">
	</form>
	</div>
	<script>
	function sum(){
		var a=document.getElementById("harga").value;
		var b=document.getElementById("periode").value;
		var c=parseInt(a)*parseInt(b);
		if (!isNaN(c)){
			document.getElementById("total").value=c;
		}
	}
	</script>
<?php }else{ ?>
<div class="alert alert-warning" style="border-radius: 0;">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Untuk melakukan Sewa Kontrakan Silahkan Login.
</div>
	<?php } } ?>




<?php if (!empty($_SESSION['level']) and $_SESSION['level']=="user"){ ?>



<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="tabel_penyewa"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px; overflow: scroll; max-height: 600px;">
<h3 class="page-header" style="margin-top: 10px;">Data Penyewa</h3>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Id Penyewa</th>
				<th>No KTP</th>
				<th>Nama Penyewa</th>
				<th>HP</th>
				<th>Alamat</th>
				<th>Pekerjaan</th>
				<th>Status Hubungan</th>
				<th>Umur</th>
				<th>Foto</th>
				<th>Id User</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$id_user=$_SESSION['id_user'];
				$query=$con->prepare("select * from tbl_penyewa where id_user=:id_user order by nama_penyewa asc");
				$query->BindParam(":id_user",$id_user);
				$query->execute();
				$query=$query->fetchAll();
				foreach ($query as $q) { ?>
				<tr><td><?php echo $q['id_penyewa']; ?></td>
				<td><?php echo $q['no_ktp']; ?></td>
				<td><?php echo $q['nama_penyewa']; ?></td>
				<td><?php echo $q['hp']; ?></td>
				<td><?php echo $q['alamat']; ?></td>
				<td><?php echo $q['pekerjaan']; ?></td>
				<td><?php echo $q['status_hubungan']; ?></td>
				<td><?php echo $q['umur']; ?></td>
				<td><a href="gambar/<?php echo $q['foto']; ?>">Lihat</a></td>
				<td><?php echo $q['id_user']; ?></td>
				</tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>

<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="tabel_pembayaran"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px; overflow: scroll; max-height: 800px;">
<h3 class="page-header" style="margin-top: 10px;">Data Pembayaran</h3>
<ul>
<li>Jika tanggal sekarang lebih besar dari Tanggal Akhir kontrak, Maka secara otomatis Status Sewa menjadi Telah Habis</li>
</ul>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Id Pembayaran</th>
				<th>Nama Penyewa</th>
				<th>Kontrakan</th>
				<th>Periode</th>
				<th>Total</th>
				<th>Bayar</th>
				<th>Sisa</th>
				<th>Status Bayar</th>
				<th>Tgl Mulai</th>
				<th>Tgl Akhir</th>
				<th>Status Sewa</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$id_user=$_SESSION['id_user'];
				$query=$con->prepare("select * from tbl_penyewa,tbl_kontrakan,tbl_pembayaran where tbl_kontrakan.id_kontrakan=tbl_pembayaran.id_kontrakan and tbl_pembayaran.id_penyewa=tbl_penyewa.id_penyewa and tbl_penyewa.id_user='$id_user' order by tbl_pembayaran.id_pembayaran desc");
				$query->execute();
				$query=$query->fetchAll();
				foreach ($query as $q) { ?>
				<tr><td><?php echo $q['id_pembayaran']; ?></td>
				<td><?php echo $q['nama_penyewa']; ?></td>
				<td><a href="index.php?detail=<?php echo $q['id_kontrakan']; ?>"><?php echo $q['kontrakan']; ?></a></td>
				<td><?php echo $q['periode']; ?> Tahun</td>
				<td>Rp.<?php echo number_format($q['total_biaya'],0,".","."); ?></td>
				<td>Rp.<?php echo number_format($q['jml_bayar'],0,".","."); ?></td>
				<td>Rp.<?php echo number_format($q['sisa'],0,".","."); ?></td>
				<td><?php echo $q['status_bayar']; ?></td>
				<td><?php echo $q['tgl_mulai']; ?></td>
				<td><?php echo $q['tgl_akhir']; ?></td>
				<td><?php echo $q['status_sewa']; ?></td>
				
				</tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>



<?php } ?>



<?php if (empty($_GET['halaman']) and empty($_GET['detail']) and empty($_GET['sewa_kontrakan']) and empty($_GET['pembayaran']) and empty($_GET['pembayaran_sewa']) and empty($_GET['fasilitas']) and empty($_GET['laporan'])){ ?>
	<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px;">
	<?php
	$query=$con->prepare("select * from tbl_kontrakan where status<>'Telah Disewa' order by id_kontrakan desc");
	$query->execute();
	$kontrakan=$query->fetchAll();
	foreach ($kontrakan as $k) { ?>
	<div class="col-md-4 col-sm-4 col-xs-12">
	<?php
	$id_kontrakan=$k['id_kontrakan'];
	$query=$con->prepare("select * from tbl_gambar where id_kontrakan=:id_kontrakan order by id_gambar asc");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$g=$query->fetch();
	?>
	<center><a href="index.php?detail=<?php echo $k['id_kontrakan']; ?>"><img style="max-height: 200px; max-width: 100%;" src="gambar/<?php echo $g['gambar']; ?>"/></a></center>
	</div>
	<div class="col-md-5 col-sm-8 col-xs-12">
	<h3 class="page-header" style="margin: 0;"><a href="index.php?detail=<?php echo $k['id_kontrakan']; ?>"><?php echo $k['kontrakan']; ?></a></h3>
	<strong>Fasilitas</strong><br>
	<?php
	$query=$con->prepare("select * from tbl_fasilitas where id_kontrakan=:id_kontrakan order by id_fasilitas asc");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$fasilitas=$query->fetchAll();
	foreach ($fasilitas as $f){  echo $f['fasilitas'].', '; } ?>
	<br>
	<strong>Luas Tanah</strong><br>
	<p><?php echo $k['luas_tanah']; ?></p>
	<strong>Luas Kontrakan</strong><br>
	<p><?php echo $k['luas_kontrakan']; ?></p>
	<h3>Rp.<?php echo number_format($k['harga'],0,".","."); ?> / Tahun</h3>
	</div>
	<div class="col-md-3 col-sm-12 col-xs-12">
	<a class="btn btn-danger" style="margin-bottom: 20px; min-width: 200px; text-align: center;" href="index.php?detail=<?php echo $k['id_kontrakan']; ?>">Detail Kontrakan</a> <a class="btn btn-primary" style="margin-bottom: 20px; min-width: 200px; text-align: center;" href="index.php?sewa_kontrakan=<?php echo $k['id_kontrakan']; ?>">Sewa Kontrakan</a>
	</div>
	<div style="width: 100%; float: left;"><hr></div>
	<?php } ?>

	</div>
	<?php } ?>







<?php if (!empty($_GET['detail'])){ 
	$id_kontrakan=$_GET['detail'];
	$query=$con->prepare("select * from tbl_kontrakan where id_kontrakan=:id_kontrakan");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$res=$query->fetch();
	$query=$con->prepare("select * from tbl_gambar where id_kontrakan=:id_kontrakan order by id_gambar asc");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$q=$query->fetch();
	$q1=$query->fetchAll();
	$query=$con->prepare("select * from tbl_fasilitas where id_kontrakan=:id_kontrakan order by id_fasilitas asc");
	$query->BindParam(":id_kontrakan",$id_kontrakan);
	$query->execute();
	$fasilitas=$query->fetchAll();
	?>
	<div class="col-md-12 col-sm-12 col-xs-12" style="background: #feffdc; margin-bottom: 10px;">
	<div class="col-md-6 col-sm-12 col-xs-12">
	<div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
	<img width="100%" style="margin-top: 20px;" src="gambar/<?php echo $q['gambar']; ?>">
	<?php foreach ($q1 as $q1) { ?>
	<div class="col-md-4 col-sm-4 col-xs-4" style="margin-top: 10px;">
		<a target="_blank" href="gambar/<?php echo $q1['gambar']; ?>">
		<img style="max-width: 100%; max-height: 100px;" src="gambar/<?php echo $q1['gambar']; ?>">
		</a>
	</div>
	<?php } ?>
	</div>
	</div>
	<div class="col-md-6 col-sm-12 col-xs-12" style="background: #feffdc; ">
		<h3 class="prod_title"><?php echo $res['kontrakan']; ?></h3>

                      <p><?php echo $res['keterangan']; ?></p>
                      <br />

                      <div class="">
                        <h2>Fasilitas</h2>
                        <ol>
                          <?php foreach ($fasilitas as $f) { ?>
                            <li><?php echo $f['fasilitas']; ?></li>
                          <?php } ?>
                          </ol>
                      </div>
                      <br>
                      <div class="">
                        <h4>Luas Tanah</h4>
                        <ul class="list-inline prod_size">
                          <li>
                            <?php echo $res['luas_tanah']; ?>
                          </li>
                        </ul>
                      </div>

                      <div class="">
                        <h4>Luas Kontrakan</h4>
                        <ul class="list-inline prod_size">
                          <li>
                            <?php echo $res['luas_kontrakan']; ?>
                          </li>
                        </ul>
                      </div>

                      <div class="">
                        <h4>Status Kontrakan</h4>
                        <ul class="list-inline prod_size">
                          <li>
                            <?php echo $res['status']; ?>
                          </li>
                        </ul>
                      </div>
                      <br />

                      <div class="">
                        <div class="product_price">
                          <h3 class="price">Rp.<?php echo number_format($res['harga'],0,".","."); ?> / Tahun</h3>
                          <br>
                        </div>
                      </div>
                      <?php if ($res['status']=="Tersedia"){ ?>
                      <div class="">
                        <a class="btn btn-success" href="index.php?sewa_kontrakan=<?php echo $res['id_kontrakan']; ?>">Sewa Kontrakan</a><br><br>
                    
                        * Sebelum melakukan penyewaan, kami sarankan untuk menghubungi kami terlebih dahulu.
                      
                        <br><br>
                      </div>
                      <?php } ?>
	</div>

	</div>
<?php } ?>
	





<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="login"){ ?> 
<div class="col-md-4 col-sm-4 col-xs-12"></div>
<div class="col-md-4 col-sm-4 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px;">
<form action="#" method="POST">
<h3 class="page-header" style="margin-top: 0;">Login</h3>
    <label>Username</label>
    <input type="text" class="form-control" required="" name="username"><br>
    <label>Password</label>
    <input type="password" class="form-control" required="" name="password"><br>
    <button type="submit" name="login" class="btn btn-success"><i class="glyphicon glyphicon-log-in"></i> Login</button>
</form>      
</div>
<div class="col-md-4 col-sm-4 col-xs-12"></div>              
<?php } ?>



<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="daftar"){ ?> 
<div class="col-md-4 col-sm-4 col-xs-12"></div>
<div class="col-md-4 col-sm-4 col-xs-12" style="background: #feffdc; padding: 10px; margin-bottom: 10px;">
<form action="#" method="POST">
<h3 class="page-header" style="margin-top: 0;">Pendaftaran</h3>
    <label>Nama Lengkap</label>
    <input type="text" class="form-control" required="" name="nama_lengkap"><br>
    <label>Username</label>
    <input type="text" class="form-control" required="" name="username"><br>
    <label>Password</label>
    <input type="password" class="form-control" required="" name="password"><br>
    <label>Ulangi Password</label>
    <input type="password" class="form-control" required="" name="repassword"><br>
    <input type="submit" name="daftar" class="btn btn-success" value="Daftar">
</form>      
</div>
<div class="col-md-4 col-sm-4 col-xs-12"></div>              
<?php } ?>




<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="contact"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" id="contact" style="background: #feffdc; margin-bottom: 10px; margin-top: 10px;">
	<h3 class="page-header" style="margin-top: 10px;">Hubungi Kami</h3>
	<img width="200px" src="contoh.jpg"/>
	<h2>Nengki Rahmat</h2>
	<p>HP : +6285767720388</p>
	<p>Facebook : <a target="_blank" href="https://www.facebook.com/pubocor">https://www.facebook.com/pubocor</a></p>
	<p>Email : <a target="_blank" href="mailto:nengkirahmat@gmail.com">nengkirahmat@gmail.com</a></p>
</div>
<?php } ?>


<?php if (!empty($_GET['halaman']) and $_GET['halaman']=="tentang"){ ?>
<div class="col-md-12 col-sm-12 col-xs-12" id="tentang" style="background: #feffdc;">
	<h3 class="page-header" style="margin-top: 10px;">Tentang</h3>
	<p><strong>Kontrakan Sungai Cubadak</strong> adalah sebuah bla bla bla saiodj asiodsjaid siadosa dsa djoiasj dosajdo;sadjisoaj dosajdoisa ioasjdio asjdoi sdioajs idsaj d;sjodsijod; saijd osdjosaidj oasjdiosjdisa dosiajdsajdosijd wj9dj ao9jdoa;sjdiwjoiasnjl ssnadsaoid asjdoisjadio saiodjsioajdiosajdiosa osaidsd.</p>
	<p>Sikjo dsf fdksof isdoifdso;if dsifijosd jnioskf oisdfjoidjfois osnjfiojdofif nsdfidsjfidsjfi sdjfoidj siofjdoi idosjf sdjfoisdjfio sdifjdsio jfsdifj sdjf sdjfiosdj sdjfoidjsiofjs dfjdsoifjdofjiodsjf sdjfiodsjfiosdj iofsdiof jsdoifj sdjfoidsjfisdfjsidf</p>
</div>
<?php } ?>

</div>
<div style="background: #9e0885; color: #fff; display: block; clear: both; width: 100%;"><p style="margin: 0; padding: 10px; text-align: right;">&copy; Kontrakan Sungai Cubadak - <?php echo date('Y'); ?></p></div>
       <!-- Footer
        ================================================== -->

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="bootstrap/js/jquery.min.js"></script>


        <script src="bootstrap/js/bootstrap.min.js"></script>

    </body></html>