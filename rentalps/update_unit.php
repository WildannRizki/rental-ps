<?php
include "koneksi.php";

$id_unit = $_POST['id_unit'];
$nama_unit = $_POST['nama_unit'];
$tipe_ps = $_POST['tipe_ps'];
$harga_perjam = $_POST['harga_perjam'];
$status = $_POST['status'];

mysqli_query($koneksi, "UPDATE unit_ps SET
    nama_unit='$nama_unit',
    tipe_ps='$tipe_ps',
    harga_perjam='$harga_perjam',
    status='$status'
    WHERE id_unit='$id_unit'
");

header("location:unit_ps.php?pesan=update");
exit;
?>