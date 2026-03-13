<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$data = mysqli_query($koneksi, "
    SELECT 
        t.*,
        p.id_pelanggan,
        p.nama_pelanggan,
        p.no_hp,
        u.nama_unit,
        u.tipe_ps,
        (
            SELECT COUNT(*)
            FROM transaksi tx
            WHERE tx.id_pelanggan = t.id_pelanggan
        ) AS jumlah_transaksi
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN unit_ps u ON t.id_unit = u.id_unit
    ORDER BY t.id_transaksi DESC
");

$q_total = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi");
$total = mysqli_fetch_assoc($q_total)['total'];

$q_aktif = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi WHERE status_transaksi='dipakai'");
$aktif = mysqli_fetch_assoc($q_aktif)['total'];

$q_selesai = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi WHERE status_transaksi='selesai'");
$selesai = mysqli_fetch_assoc($q_selesai)['total'];

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Transaksi Rental</title>
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
                <li><a href="laporan.php">Laporan</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main modern-main">

            <div class="page-hero transaksi-hero">
                <div>
                    <span class="hero-badge">Transaksi</span>
                    <h1>🧾 Transaksi Rental</h1>

                </div>

                <a href="tambah_transaksi.php" class="hero-action-btn">+ Tambah Transaksi</a>
            </div>

            <?php if ($pesan == 'tambah') { ?>
                <div class="alert-box success">Transaksi berhasil ditambahkan.</div>
            <?php } elseif ($pesan == 'update') { ?>
                <div class="alert-box info">Transaksi berhasil diperbarui.</div>
            <?php } elseif ($pesan == 'hapus') { ?>
                <div class="alert-box danger">Transaksi berhasil dihapus.</div>
            <?php } elseif ($pesan == 'unittidaktersedia') { ?>
                <div class="alert-box warning">Unit yang dipilih sedang tidak tersedia.</div>
            <?php } elseif ($pesan == 'pelangganaktif') { ?>
                <div class="alert-box warning">Pelanggan ini masih memiliki transaksi aktif, selesaikan dulu transaksi
                    sebelumnya.</div>
            <?php } ?>

            <div class="dashboard-stats-grid">
                <div class="dashboard-stat-card blue-soft">
                    <div class="dash-icon">🧾</div>
                    <div>
                        <h3>
                            <?php echo $total; ?>
                        </h3>
                        <p>Total Transaksi</p>
                    </div>
                </div>

                <div class="dashboard-stat-card red-soft">
                    <div class="dash-icon">⏳</div>
                    <div>
                        <h3>
                            <?php echo $aktif; ?>
                        </h3>
                        <p>Transaksi Aktif</p>
                    </div>
                </div>

                <div class="dashboard-stat-card green-soft">
                    <div class="dash-icon">✅</div>
                    <div>
                        <h3>
                            <?php echo $selesai; ?>
                        </h3>
                        <p>Transaksi Selesai</p>
                    </div>
                </div>
            </div>

            <div class="modern-table-card">
                <div class="table-card-header">
                    <h3>Daftar Transaksi Rental</h3>
                    <p>Setiap pelanggan bisa punya banyak transaksi, tapi hanya satu transaksi aktif dalam satu waktu.
                    </p>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Unit PS</th>
                                <th>Lama Main</th>
                                <th>Harga/Jam</th>
                                <th>Total Bayar</th>
                                <th>Riwayat</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
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
                                            <?php echo $d['jumlah_transaksi']; ?>x
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
                                        <td>
                                            <a href="edit_transaksi.php?id=<?php echo $d['id_transaksi']; ?>"
                                                class="table-btn edit-soft">Edit</a>
                                            <a href="hapus_transaksi.php?id=<?php echo $d['id_transaksi']; ?>"
                                                class="table-btn delete-soft"
                                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</a>
                                            <a href="riwayat_pelanggan.php?id=<?php echo $d['id_pelanggan']; ?>"
                                                class="table-btn history-soft">Riwayat</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="10" class="empty-table">Belum ada transaksi rental.</td>
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