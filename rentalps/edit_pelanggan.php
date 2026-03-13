<?php
session_start();
require_once "app/autoload.php";

use App\Models\PelangganModel;

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$id = $_GET['id'];

$pelangganModel = new PelangganModel();
$pelanggan = $pelangganModel->findEntityById($id);

if (!$pelanggan) {
    header("location:pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>
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
                <li><a href="laporan.php">Laporan</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main modern-main">

            <div class="form-hero">
                <div>
                    <span class="hero-badge">Form Edit OOP</span>
                    <h1>✏️ Edit Pelanggan</h1>
                    <p>Halaman ini memakai entity dengan overloading `__get()` dan `__set()`.</p>
                </div>
            </div>

            <div class="app-form-card">
                <form method="POST" action="update_pelanggan.php">

                    <input type="hidden" name="id" value="<?php echo $pelanggan->id_pelanggan; ?>">

                    <div class="app-form-grid">
                        <div class="app-form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" value="<?php echo $pelanggan->nama_pelanggan; ?>"
                                required>
                        </div>

                        <div class="app-form-group">
                            <label>No HP</label>
                            <input type="text" name="no_hp" value="<?php echo $pelanggan->no_hp; ?>" required>
                        </div>
                    </div>

                    <div class="app-form-buttons">
                        <button type="submit" class="btn-primary-modern">💾 Update</button>
                        <a href="pelanggan.php" class="btn-secondary-modern">↩ Kembali</a>
                    </div>

                </form>
            </div>

        </div>

    </div>

</body>

</html>