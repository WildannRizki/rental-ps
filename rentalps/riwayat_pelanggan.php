<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$id = $_GET['id'];

$pelanggan = mysqli_query($koneksi, "
    SELECT 
        p.*,
        COUNT(t.id_transaksi) AS total_transaksi,
        SUM(CASE WHEN t.status_transaksi='dipakai' THEN 1 ELSE 0 END) AS transaksi_aktif,
        SUM(CASE WHEN t.status_transaksi='selesai' THEN 1 ELSE 0 END) AS transaksi_selesai,
        COALESCE(SUM(t.total_bayar),0) AS total_pengeluaran,
        MAX(t.tanggal) AS terakhir_main
    FROM pelanggan p
    LEFT JOIN transaksi t ON p.id_pelanggan = t.id_pelanggan
    WHERE p.id_pelanggan='$id'
    GROUP BY p.id_pelanggan, p.nama_pelanggan, p.no_hp
");

$p = mysqli_fetch_assoc($pelanggan);

if (!$p) {
    header("location:transaksi.php");
    exit;
}

$riwayat = mysqli_query($koneksi, "
    SELECT 
        t.*,
        u.nama_unit,
        u.tipe_ps
    FROM transaksi t
    JOIN unit_ps u ON t.id_unit = u.id_unit
    WHERE t.id_pelanggan='$id'
    ORDER BY t.id_transaksi DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Riwayat Pelanggan</title>
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
                <li><a href="transaksi.php" class="active">Transaksi Rental</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main modern-main">

            <div class="page-hero transaksi-hero">
                <div>
                    <span class="hero-badge">Riwayat Pelanggan</span>
                    <h1>📜 Riwayat
                        <?php echo $p['nama_pelanggan']; ?>
                    </h1>
                    <p>Lihat sudah berapa kali pelanggan ini melakukan transaksi rental PS.</p>
                </div>

                <a href="transaksi.php" class="hero-action-btn">↩ Kembali ke Transaksi</a>
            </div>

            <div class="dashboard-stats-grid">
                <div class="dashboard-stat-card blue-soft">
                    <div class="dash-icon">👤</div>
                    <div>
                        <h3>
                            <?php echo $p['nama_pelanggan']; ?>
                        </h3>
                        <p>
                            <?php echo $p['no_hp']; ?>
                        </p>
                    </div>
                </div>

                <div class="dashboard-stat-card purple-soft">
                    <div class="dash-icon">🧾</div>
                    <div>
                        <h3>
                            <?php echo $p['total_transaksi']; ?>x
                        </h3>
                        <p>Total Transaksi</p>
                    </div>
                </div>

                <div class="dashboard-stat-card red-soft">
                    <div class="dash-icon">⏳</div>
                    <div>
                        <h3>
                            <?php echo $p['transaksi_aktif']; ?>
                        </h3>
                        <p>Transaksi Aktif</p>
                    </div>
                </div>

                <div class="dashboard-stat-card green-soft">
                    <div class="dash-icon">💰</div>
                    <div>
                        <h3>Rp
                            <?php echo number_format($p['total_pengeluaran'], 0, ',', '.'); ?>
                        </h3>
                        <p>Total Bayar Keseluruhan</p>
                    </div>
                </div>
            </div>

            <div class="modern-table-card">
                <div class="table-card-header">
                    <h3>Detail Riwayat Transaksi</h3>
                    <p>
                        Terakhir main:
                        <?php echo $p['terakhir_main'] ? date('d-m-Y H:i', strtotime($p['terakhir_main'])) : '-'; ?>
                    </p>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Unit PS</th>
                                <th>Lama Main</th>
                                <th>Harga/Jam</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($riwayat) > 0) { ?>
                                <?php while ($r = mysqli_fetch_array($riwayat)) { ?>
                                    <tr>
                                        <td>#
                                            <?php echo $r['id_transaksi']; ?>
                                        </td>
                                        <td>
                                            <strong>
                                                <?php echo $r['nama_unit']; ?>
                                            </strong><br>
                                            <small>
                                                <?php echo $r['tipe_ps']; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php echo $r['lama_main']; ?> jam
                                        </td>
                                        <td>Rp
                                            <?php echo number_format($r['harga'], 0, ',', '.'); ?>
                                        </td>
                                        <td>Rp
                                            <?php echo number_format($r['total_bayar'], 0, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <?php if ($r['status_transaksi'] == 'dipakai') { ?>
                                                <span class="status-pill status-dipakai">Dipakai</span>
                                            <?php } else { ?>
                                                <span class="status-pill status-selesai">Selesai</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo date('d-m-Y H:i', strtotime($r['tanggal'])); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" class="empty-table">Belum ada riwayat transaksi untuk pelanggan ini.
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