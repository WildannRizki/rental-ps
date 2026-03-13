<?php
include "koneksi.php";

$id = $_GET['id'];

$hapus = mysqli_query($koneksi, "DELETE FROM unit_ps WHERE id_unit='$id'");

if ($hapus) {
    header("location:unit_ps.php?pesan=hapus");
} else {
    header("location:unit_ps.php?pesan=gagalhapus");
}
exit;
?>