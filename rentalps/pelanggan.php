<?php
session_start();
require_once "app/autoload.php";

use App\Models\PelangganModel;

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$pelangganModel = new PelangganModel();
$data = $pelangganModel->getAll();
$total_pelanggan = $pelangganModel->countAll();

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
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

            <div class="page-hero pelanggan-hero">
                <div>
                    <span class="hero-badge">Master Data OOP</span>
                    <h1>👥 Data Pelanggan</h1>

                </div>
                <a href="tambah_pelanggan.php" class="hero-action-btn">+ Tambah Pelanggan</a>
            </div>

            <?php if ($pesan == 'tambah') { ?>
                <div class="alert-box success">Data pelanggan berhasil ditambahkan.</div>
            <?php } elseif ($pesan == 'update') { ?>
                <div class="alert-box info">Data pelanggan berhasil diperbarui.</div>
            <?php } elseif ($pesan == 'hapus') { ?>
                <div class="alert-box danger">Data pelanggan berhasil dihapus.</div>
            <?php } ?>

            <div class="stats-mini-grid">
                <div class="mini-stat-card">
                    <div class="mini-stat-icon">👤</div>
                    <div>
                        <h3><?php echo $total_pelanggan; ?></h3>
                        <p>Total Pelanggan</p>
                    </div>
                </div>
            </div>

            <div class="modern-table-card">
                <div class="table-card-header">
                    <div>
                        <h3>Daftar Pelanggan</h3>
                        <p>Data pelanggan yang tersimpan di sistem Rental PS</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Pelanggan</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($data) > 0) { ?>
                                <?php while ($d = mysqli_fetch_array($data)) { ?>
                                    <tr>
                                        <td>#<?php echo $d['id_pelanggan']; ?></td>
                                        <td>
                                            <div class="table-user-wrap">
                                                <span class="table-avatar">👤</span>
                                                <span><?php echo $d['nama_pelanggan']; ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo $d['no_hp']; ?></td>
                                        <td>
                                            <a href="edit_pelanggan.php?id=<?php echo $d['id_pelanggan']; ?>"
                                                class="table-btn edit-soft">Edit</a>
                                            <a href="hapus_pelanggan.php?id=<?php echo $d['id_pelanggan']; ?>"
                                                class="table-btn delete-soft"
                                                onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4" class="empty-table">Belum ada data pelanggan.</td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>

    </div>

</body>

</html>