<?php
session_start();
require_once "app/autoload.php";

use App\Models\PelangganModel;

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$pelangganModel = new PelangganModel();

$pelangganModel->simpan([
    'nama_pelanggan' => $_POST['nama_pelanggan'],
    'no_hp' => $_POST['no_hp']
]);

header("location:pelanggan.php?pesan=tambah");
exit;