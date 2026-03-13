<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM unit_ps ORDER BY id_unit DESC");

$q_total = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM unit_ps");
$total = mysqli_fetch_assoc($q_total)['total'];

$q_kosong = mysqli_query($koneksi, "SELECT COUNT(*) AS kosong FROM unit_ps WHERE status='kosong'");
$kosong = mysqli_fetch_assoc($q_kosong)['kosong'];

$q_dipakai = mysqli_query($koneksi, "SELECT COUNT(*) AS dipakai FROM unit_ps WHERE status='dipakai'");
$dipakai = mysqli_fetch_assoc($q_dipakai)['dipakai'];

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Unit PS</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="wrapper">

        <div class="sidebar">
            <h2>🎮 Rental PS</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="pelanggan.php">Data Pelanggan</a></li>
                <li><a href="unit_ps.php" class="active">Data Unit PS</a></li>
                <li><a href="transaksi.php">Transaksi Rental</a></li>
                <li><a href="laporan.php">Laporan</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main unit-main">

            <div class="unit-hero">
                <div>
                    <h1>🎮 Data Unit PS</h1>

                </div>
                <a href="tambah_unit.php" class="unit-btn-primary">+ Tambah Unit</a>
            </div>

            <?php if ($pesan == 'tambah') { ?>
                <div class="alert-box success">Data unit berhasil ditambahkan.</div>
            <?php } elseif ($pesan == 'update') { ?>
                <div class="alert-box info">Data unit berhasil diperbarui.</div>
            <?php } elseif ($pesan == 'hapus') { ?>
                <div class="alert-box danger">Data unit berhasil dihapus.</div>
            <?php } elseif ($pesan == 'gagalhapus') { ?>
                <div class="alert-box warning">Data unit gagal dihapus karena masih terhubung dengan transaksi.</div>
            <?php } ?>

            <div class="unit-stats-grid">
                <div class="unit-stat-card glass-blue">
                    <span class="unit-stat-icon">🎮</span>
                    <div>
                        <h3>
                            <?php echo $total; ?>
                        </h3>
                        <p>Total Unit</p>
                    </div>
                </div>

                <div class="unit-stat-card glass-green">
                    <span class="unit-stat-icon">✅</span>
                    <div>
                        <h3>
                            <?php echo $kosong; ?>
                        </h3>
                        <p>Unit Kosong</p>
                    </div>
                </div>

                <div class="unit-stat-card glass-red">
                    <span class="unit-stat-icon">⏳</span>
                    <div>
                        <h3>
                            <?php echo $dipakai; ?>
                        </h3>
                        <p>Unit Dipakai</p>
                    </div>
                </div>
            </div>

            <div class="unit-table-wrapper">

                <div class="unit-table-top">
                    <h3>Daftar Unit PS</h3>
                    <span class="unit-table-subtitle">Data unit yang tersedia dalam sistem rental</span>
                </div>

                <div class="unit-table-scroll">
                    <table class="unit-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Unit</th>
                                <th>Tipe PS</th>
                                <th>Harga / Jam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php while ($d = mysqli_fetch_array($data)) { ?>
                                <tr>
                                    <td>#
                                        <?php echo $d['id_unit']; ?>
                                    </td>
                                    <td>
                                        <div class="unit-name-wrap">
                                            <span class="unit-avatar">🎮</span>
                                            <span>
                                                <?php echo $d['nama_unit']; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $d['tipe_ps']; ?>
                                    </td>
                                    <td>Rp
                                        <?php echo number_format($d['harga_perjam'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <?php if ($d['status'] == 'kosong') { ?>
                                            <span class="badge-status badge-kosong">Kosong</span>
                                        <?php } else { ?>
                                            <span class="badge-status badge-dipakai">Dipakai</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="edit_unit.php?id=<?php echo $d['id_unit']; ?>"
                                            class="unit-btn-action btn-edit-unit">Edit</a>
                                        <a href="hapus_unit.php?id=<?php echo $d['id_unit']; ?>"
                                            class="unit-btn-action btn-hapus-unit"
                                            onclick="return confirm('Yakin ingin menghapus data unit ini?')">Hapus</a>
                                    </td>
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