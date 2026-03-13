<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';

$where = "";
if (!empty($tgl_awal) && !empty($tgl_akhir)) {
    $where = "WHERE DATE(t.tanggal) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
} elseif (!empty($tgl_awal)) {
    $where = "WHERE DATE(t.tanggal) >= '$tgl_awal'";
} elseif (!empty($tgl_akhir)) {
    $where = "WHERE DATE(t.tanggal) <= '$tgl_akhir'";
}

$data = mysqli_query($koneksi, "
    SELECT 
        t.*,
        p.nama_pelanggan,
        p.no_hp,
        u.nama_unit,
        u.tipe_ps
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN unit_ps u ON t.id_unit = u.id_unit
    $where
    ORDER BY t.id_transaksi DESC
");

$q_summary = mysqli_query($koneksi, "
    SELECT 
        COUNT(*) AS total_transaksi,
        COALESCE(SUM(total_bayar),0) AS total_pendapatan,
        SUM(CASE WHEN status_transaksi='dipakai' THEN 1 ELSE 0 END) AS total_aktif,
        SUM(CASE WHEN status_transaksi='selesai' THEN 1 ELSE 0 END) AS total_selesai
    FROM transaksi t
    " . str_replace("t.", "", $where) . "
");
$summary = mysqli_fetch_assoc($q_summary);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Rental</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="wrapper">

        <div class="sidebar print-hide">
            <h2>🎮 Rental PS</h2>

            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="pelanggan.php">Data Pelanggan</a></li>
                <li><a href="unit_ps.php">Data Unit PS</a></li>
                <li><a href="transaksi.php">Transaksi Rental</a></li>
                <li><a href="laporan.php" class="active">Laporan</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main modern-main">

            <div class="page-hero laporan-hero print-hide">
                <div>
                    <span class="hero-badge">Laporan</span>
                    <h1>📊 Laporan Transaksi Rental</h1>

                </div>
            </div>

            <div class="print-title">
                <h2>Laporan Transaksi Rental PS</h2>
                <p>
                    <?php if (!empty($tgl_awal) || !empty($tgl_akhir)) { ?>
                        Periode:
                        <?php echo !empty($tgl_awal) ? date('d-m-Y', strtotime($tgl_awal)) : 'Awal'; ?>
                        s/d
                        <?php echo !empty($tgl_akhir) ? date('d-m-Y', strtotime($tgl_akhir)) : 'Sekarang'; ?>
                    <?php } else { ?>
                        Semua Data Transaksi
                    <?php } ?>
                </p>
            </div>

            <div class="filter-card print-hide">
                <form method="GET" action="laporan.php">
                    <div class="filter-grid">
                        <div class="app-form-group">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tgl_awal" value="<?php echo $tgl_awal; ?>">
                        </div>

                        <div class="app-form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>">
                        </div>
                    </div>

                    <div class="app-form-buttons">
                        <button type="submit" class="btn-primary-modern">Tampilkan</button>
                        <a href="laporan.php" class="btn-secondary-modern">Reset</a>
                        <button type="button" class="btn-print-modern" onclick="window.print()">🖨 Cetak</button>
                    </div>
                </form>
            </div>

            <div class="dashboard-stats-grid">
                <div class="dashboard-stat-card blue-soft">
                    <div class="dash-icon">🧾</div>
                    <div>
                        <h3>
                            <?php echo $summary['total_transaksi']; ?>
                        </h3>
                        <p>Total Transaksi</p>
                    </div>
                </div>

                <div class="dashboard-stat-card red-soft">
                    <div class="dash-icon">⏳</div>
                    <div>
                        <h3>
                            <?php echo $summary['total_aktif']; ?>
                        </h3>
                        <p>Transaksi Aktif</p>
                    </div>
                </div>

                <div class="dashboard-stat-card green-soft">
                    <div class="dash-icon">✅</div>
                    <div>
                        <h3>
                            <?php echo $summary['total_selesai']; ?>
                        </h3>
                        <p>Transaksi Selesai</p>
                    </div>
                </div>

                <div class="dashboard-stat-card teal-soft">
                    <div class="dash-icon">💰</div>
                    <div>
                        <h3>Rp
                            <?php echo number_format($summary['total_pendapatan'], 0, ',', '.'); ?>
                        </h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
            </div>

            <div class="modern-table-card">
                <div class="table-card-header">
                    <h3>Data Laporan Transaksi</h3>
                    <p>Rekap seluruh transaksi sesuai filter yang dipilih</p>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Unit</th>
                                <th>Lama Main</th>
                                <th>Harga/Jam</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($data) > 0) { ?>
                                <?php while ($d = mysqli_fetch_array($data)) { ?>
                                    <tr>
                                        <td>#
                                            <?php echo $d['id_transaksi']; ?>
                                        </td>
                                        <td>
                                            <div class="table-user-wrap">
                                                <span class="table-avatar">👤</span>
                                                <div>
                                                    <strong>
                                                        <?php echo $d['nama_pelanggan']; ?>
                                                    </strong><br>
                                                    <small>
                                                        <?php echo $d['no_hp']; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>
                                                <?php echo $d['nama_unit']; ?>
                                            </strong><br>
                                            <small>
                                                <?php echo $d['tipe_ps']; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php echo $d['lama_main']; ?> jam
                                        </td>
                                        <td>Rp
                                            <?php echo number_format($d['harga'], 0, ',', '.'); ?>
                                        </td>
                                        <td>Rp
                                            <?php echo number_format($d['total_bayar'], 0, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <?php if ($d['status_transaksi'] == 'dipakai') { ?>
                                                <span class="status-pill status-dipakai">Dipakai</span>
                                            <?php } else { ?>
                                                <span class="status-pill status-selesai">Selesai</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo date('d-m-Y H:i', strtotime($d['tanggal'])); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="8" class="empty-table">Tidak ada data transaksi pada filter ini.</td>
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