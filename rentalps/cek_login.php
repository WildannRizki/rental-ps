<?php

session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM user WHERE username='$username' AND password='$password'"
);

$cek = mysqli_num_rows($query);

if ($cek > 0) {

    $_SESSION['username'] = $username;

    header("location:dashboard.php");

} else {

    echo "Login gagal";

}
?>