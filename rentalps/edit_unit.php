<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM unit_ps WHERE id_unit='$id'");
$d = mysqli_fetch_array($data);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Unit PS</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="wrapper">

        <div class="sidebar">
            <h2>🎮 Rental PS</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="pelanggan.php">Data Pelanggan</a></li>
                <li><a href="unit_ps.php">Data Unit PS</a></li>
                <li><a href="transaksi.php">Transaksi Rental</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main unit-main">

            <div class="unit-form-header">
                <h1>🛠 Edit Unit PS</h1>
                <p>Perbarui informasi unit PlayStation yang sudah ada.</p>
            </div>

            <div class="unit-form-card">
                <form method="POST" action="update_unit.php">

                    <input type="hidden" name="id_unit" value="<?php echo $d['id_unit']; ?>">

                    <div class="unit-form-grid">
                        <div class="unit-form-group">
                            <label>Nama Unit</label>
                            <input type="text" name="nama_unit" value="<?php echo $d['nama_unit']; ?>" required>
                        </div>

                        <div class="unit-form-group">
                            <label>Tipe PS</label>
                            <select name="tipe_ps" required>
                                <option value="PS3" <?php if ($d['tipe_ps'] == 'PS3')
                                    echo "selected"; ?>>PS3</option>
                                <option value="PS4" <?php if ($d['tipe_ps'] == 'PS4')
                                    echo "selected"; ?>>PS4</option>
                                <option value="PS5" <?php if ($d['tipe_ps'] == 'PS5')
                                    echo "selected"; ?>>PS5</option>
                            </select>
                        </div>

                        <div class="unit-form-group">
                            <label>Harga per Jam</label>
                            <input type="number" name="harga_perjam" min="0" value="<?php echo $d['harga_perjam']; ?>"
                                required>
                        </div>

                        <div class="unit-form-group">
                            <label>Status</label>
                            <select name="status" required>
                                <option value="kosong" <?php if ($d['status'] == 'kosong')
                                    echo "selected"; ?>>Kosong
                                </option>
                                <option value="dipakai" <?php if ($d['status'] == 'dipakai')
                                    echo "selected"; ?>>Dipakai
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="unit-form-buttons">
                        <button type="submit" class="unit-btn-save">💾 Update Data</button>
                        <a href="unit_ps.php" class="unit-btn-back">↩ Kembali</a>
                    </div>

                </form>
            </div>

        </div>

    </div>

</body>

</html>