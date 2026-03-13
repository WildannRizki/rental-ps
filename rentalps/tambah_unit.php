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
    <title>Tambah Unit PS</title>
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
                <h1>✨ Tambah Unit PS</h1>
                <p>Tambahkan unit baru agar bisa digunakan dalam transaksi rental.</p>
            </div>

            <div class="unit-form-card">
                <form method="POST" action="simpan_unit.php">

                    <div class="unit-form-grid">
                        <div class="unit-form-group">
                            <label>Nama Unit</label>
                            <input type="text" name="nama_unit" placeholder="Contoh: PS4 Room 1" required>
                        </div>

                        <div class="unit-form-group">
                            <label>Tipe PS</label>
                            <select name="tipe_ps" required>
                                <option value="">-- Pilih Tipe PS --</option>
                                <option value="PS3">PS3</option>
                                <option value="PS4">PS4</option>
                                <option value="PS5">PS5</option>
                            </select>
                        </div>

                        <div class="unit-form-group">
                            <label>Harga per Jam</label>
                            <input type="number" name="harga_perjam" placeholder="Contoh: 7000" min="0" required>
                        </div>

                        <div class="unit-form-group">
                            <label>Status</label>
                            <select name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="kosong">Kosong</option>
                                <option value="dipakai">Dipakai</option>
                            </select>
                        </div>
                    </div>

                    <div class="unit-form-buttons">
                        <button type="submit" class="unit-btn-save">💾 Simpan Data</button>
                        <a href="unit_ps.php" class="unit-btn-back">↩ Kembali</a>
                    </div>

                </form>
            </div>

        </div>

    </div>

</body>

</html>