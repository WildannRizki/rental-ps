<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$id_transaksi = $_POST['id_transaksi'];
$id_pelanggan = $_POST['id_pelanggan'];
$id_unit_baru = $_POST['id_unit'];
$id_unit_lama = $_POST['id_unit_lama'];
$status_lama = $_POST['status_lama'];
$lama_main = $_POST['lama_main'];
$status_transaksi = $_POST['status_transaksi'];

$q_transaksi_lama = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id_transaksi'");
$transaksi_lama = mysqli_fetch_assoc($q_transaksi_lama);

if (!$transaksi_lama) {
    header("location:transaksi.php");
    exit;
}

$cek_pelanggan_aktif = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM transaksi
    WHERE id_pelanggan='$id_pelanggan'
    AND status_transaksi='dipakai'
    AND id_transaksi != '$id_transaksi'
");
$data_pelanggan_aktif = mysqli_fetch_assoc($cek_pelanggan_aktif);

if ($status_transaksi == 'dipakai' && $data_pelanggan_aktif['total'] > 0) {
    header("location:transaksi.php?pesan=pelangganaktif");
    exit;
}

$q_unit_baru = mysqli_query($koneksi, "SELECT * FROM unit_ps WHERE id_unit='$id_unit_baru'");
$unit_baru = mysqli_fetch_assoc($q_unit_baru);

if (!$unit_baru) {
    header("location:transaksi.php?pesan=unittidaktersedia");
    exit;
}

if ($status_transaksi == 'dipakai') {
    if ($id_unit_baru != $id_unit_lama) {
        if ($unit_baru['status'] != 'kosong') {
            header("location:transaksi.php?pesan=unittidaktersedia");
            exit;
        }
    } else {
        if ($status_lama != 'dipakai' && $unit_baru['status'] != 'kosong') {
            header("location:transaksi.php?pesan=unittidaktersedia");
            exit;
        }
    }
}

$harga = $unit_baru['harga_perjam'];
$total_bayar = $harga * $lama_main;

mysqli_query($koneksi, "UPDATE transaksi SET
    id_pelanggan='$id_pelanggan',
    id_unit='$id_unit_baru',
    lama_main='$lama_main',
    harga='$harga',
    total_bayar='$total_bayar',
    status_transaksi='$status_transaksi'
    WHERE id_transaksi='$id_transaksi'
");

if ($id_unit_baru != $id_unit_lama) {
    if ($status_lama == 'dipakai') {
        mysqli_query($koneksi, "UPDATE unit_ps SET status='kosong' WHERE id_unit='$id_unit_lama'");
    }

    if ($status_transaksi == 'dipakai') {
        mysqli_query($koneksi, "UPDATE unit_ps SET status='dipakai' WHERE id_unit='$id_unit_baru'");
    } else {
        mysqli_query($koneksi, "UPDATE unit_ps SET status='kosong' WHERE id_unit='$id_unit_baru'");
    }
} else {
    if ($status_transaksi == 'dipakai') {
        mysqli_query($koneksi, "UPDATE unit_ps SET status='dipakai' WHERE id_unit='$id_unit_baru'");
    } else {
        mysqli_query($koneksi, "UPDATE unit_ps SET status='kosong' WHERE id_unit='$id_unit_baru'");
    }
}

header("location:transaksi.php?pesan=update");
exit;
?>