<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$id_pelanggan = $_POST['id_pelanggan'];
$id_unit = $_POST['id_unit'];
$lama_main = $_POST['lama_main'];

$cek_pelanggan_aktif = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM transaksi
    WHERE id_pelanggan='$id_pelanggan' AND status_transaksi='dipakai'
");
$data_pelanggan_aktif = mysqli_fetch_assoc($cek_pelanggan_aktif);

if ($data_pelanggan_aktif['total'] > 0) {
    header("location:transaksi.php?pesan=pelangganaktif");
    exit;
}

$cek_unit = mysqli_query($koneksi, "SELECT * FROM unit_ps WHERE id_unit='$id_unit' AND status='kosong'");
$data_unit = mysqli_fetch_assoc($cek_unit);

if (!$data_unit) {
    header("location:transaksi.php?pesan=unittidaktersedia");
    exit;
}

$harga = $data_unit['harga_perjam'];
$total_bayar = $harga * $lama_main;
$status_transaksi = 'dipakai';
$tanggal = date('Y-m-d H:i:s');

mysqli_query($koneksi, "INSERT INTO transaksi (
    id_pelanggan, id_unit, lama_main, harga, total_bayar, status_transaksi, tanggal
) VALUES (
    '$id_pelanggan', '$id_unit', '$lama_main', '$harga', '$total_bayar', '$status_transaksi', '$tanggal'
)");

mysqli_query($koneksi, "UPDATE unit_ps SET status='dipakai' WHERE id_unit='$id_unit'");

header("location:transaksi.php?pesan=tambah");
exit;
?>