<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tambah Pelanggan</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="wrapper">

        <div class="sidebar">
            <h2>🎮 Rental PS</h2>

            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="pelanggan.php" class="active">Data Pelanggan</a></li>
                <li><a href="unit_ps.php">Data Unit PS</a></li>
                <li><a href="transaksi.php">Transaksi Rental</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main modern-main">

            <div class="form-hero">
                <div>
                    <span class="hero-badge">Form Input</span>
                    <h1>➕ Tambah Pelanggan</h1>
                    <p>Masukkan data pelanggan baru ke dalam sistem Rental PS.</p>
                </div>
            </div>

            <div class="app-form-card">
                <form method="POST" action="simpan_pelanggan.php">

                    <div class="app-form-grid">
                        <div class="app-form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" placeholder="Masukkan nama pelanggan" required>
                        </div>

                        <div class="app-form-group">

                            <label>No HP</label>
                            <input type="text" name="no_hp" placeholder="Masukkan nomor HP" required>
                        </div>
                    </div>

                    <div class="app-form-buttons">
                        <button type="submit" class="btn-primary-modern">💾 Simpan</button>
                        <a href="pelanggan.php" class="btn-secondary-modern">↩ Kembali</a>
                    </div>

                </form>
            </div>

        </div>

    </div>

</body>

</html>