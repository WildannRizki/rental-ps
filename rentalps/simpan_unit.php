<?php
include "koneksi.php";

$nama_unit = $_POST['nama_unit'];
$tipe_ps = $_POST['tipe_ps'];
$harga_perjam = $_POST['harga_perjam'];
$status = $_POST['status'];

mysqli_query($koneksi, "INSERT INTO unit_ps VALUES (NULL, '$nama_unit', '$tipe_ps', '$harga_perjam', '$status')");

header("location:unit_ps.php?pesan=tambah");
exit;
?>