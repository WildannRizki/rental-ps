<?php
session_start();
require_once "app/autoload.php";

use App\Models\PelangganModel;

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$pelangganModel = new PelangganModel();
$pelangganModel->hapus($_GET['id']);

header("location:pelanggan.php?pesan=hapus");
exit;