<?php
$server = "localhost";
$username = "root";
$password = "";
$database_name = "db_rentalps";
$port = 3307;

$koneksi = mysqli_connect($server, $username, $password, $database_name, $port);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>