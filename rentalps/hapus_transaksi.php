<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$id = $_GET['id'];

$q = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id'");
$data = mysqli_fetch_assoc($q);

if ($data) {
    if ($data['status_transaksi'] == 'dipakai') {
        mysqli_query($koneksi, "UPDATE unit_ps SET status='kosong' WHERE id_unit='$data[id_unit]'");
    }

    mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_transaksi='$id'");
}

header("location:transaksi.php?pesan=hapus");
exit;
?>