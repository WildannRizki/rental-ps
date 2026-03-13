<?php
session_start();
require_once "app/autoload.php";

use App\Models\PelangganModel;
use App\Models\UnitPSModel;
use App\Models\TransaksiModel;

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$pelangganModel = new PelangganModel();
$unitModel = new UnitPSModel();
$transaksiModel = new TransaksiModel();

$total_pelanggan = $pelangganModel->countAll();
$total_unit = $unitModel->countAll();
$total_kosong = $unitModel->countByStatus('kosong');
$total_dipakai = $unitModel->countByStatus('dipakai');
$total_transaksi = $transaksiModel->countAll();
$total_transaksi_aktif = $transaksiModel->countByStatus('dipakai');
$total_transaksi_selesai = $transaksiModel->countByStatus('selesai');
$total_pendapatan = $transaksiModel->sumTotalBayar();
$transaksi_terbaru = $transaksiModel->getLatest(5);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard Rental PS</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="wrapper">

        <div class="sidebar">
            <h2>🎮 Rental PS</h2>
            <ul>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="pelanggan.php">Data Pelanggan</a></li>
                <li><a href="unit_ps.php">Data Unit PS</a></li>
                <li><a href="transaksi.php">Transaksi Rental</a></li>
                <li><a href="laporan.php">Laporan</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main modern-main">

            <div class="dashboard-hero">
                <div>
                    <span class="hero-badge">Dashboard OOP</span>
                    <h1>Halo, <?php echo $_SESSION['username']; ?> 👋</h1>

                </div>

                <div class="hero-mini-card">
                    <h3><?php echo date("d M Y"); ?></h3>
                    <p>Aktivitas sistem hari ini</p>
                </div>
            </div>

            <div class="dashboard-stats-grid">
                <div class="dashboard-stat-card blue-soft">
                    <div class="dash-icon">👥</div>
                    <div>
                        <h3><?php echo $total_pelanggan; ?></h3>
                        <p>Total Pelanggan</p>
                    </div>
                </div>

                <div class="dashboard-stat-card purple-soft">
                    <div class="dash-icon">🎮</div>
                    <div>
                        <h3><?php echo $total_unit; ?></h3>
                        <p>Total Unit PS</p>
                    </div>
                </div>

                <div class="dashboard-stat-card green-soft">
                    <div class="dash-icon">✅</div>
                    <div>
                        <h3><?php echo $total_kosong; ?></h3>
                        <p>Unit Kosong</p>
                    </div>
                </div>

                <div class="dashboard-stat-card red-soft">
                    <div class="dash-icon">⏳</div>
                    <div>
                        <h3><?php echo $total_dipakai; ?></h3>
                        <p>Unit Dipakai</p>
                    </div>
                </div>

                <div class="dashboard-stat-card gold-soft">
                    <div class="dash-icon">🧾</div>
                    <div>
                        <h3><?php echo $total_transaksi; ?></h3>
                        <p>Total Transaksi</p>
                    </div>
                </div>

                <div class="dashboard-stat-card teal-soft">
                    <div class="dash-icon">💰</div>
                    <div>
                        <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <div class="section-title">
                    <h3>Grafik Ringkasan Sistem</h3>
                    <p>Grafik ini dibuat dengan external library Chart.js</p>
                </div>
                <canvas id="chartRentalPS"></canvas>
            </div>

            <div class="dashboard-section">
                <div class="section-title">
                    <h3>Transaksi Terbaru</h3>
                    <p>5 transaksi terakhir dari sistem Rental PS</p>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Unit</th>
                                <th>Lama Main</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($transaksi_terbaru) > 0) { ?>
                                <?php while ($d = mysqli_fetch_array($transaksi_terbaru)) { ?>
                                    <tr>
                                        <td>#<?php echo $d['id_transaksi']; ?></td>
                                        <td>
                                            <div class="table-user-wrap">
                                                <span class="table-avatar">👤</span>
                                                <span><?php echo $d['nama_pelanggan']; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?php echo $d['nama_unit']; ?></strong><br>
                                            <small><?php echo $d['tipe_ps']; ?></small>
                                        </td>
                                        <td><?php echo $d['lama_main']; ?> jam</td>
                                        <td>Rp <?php echo number_format($d['total_bayar'], 0, ',', '.'); ?></td>
                                        <td>
                                            <?php if ($d['status_transaksi'] == 'dipakai') { ?>
                                                <span class="status-pill status-dipakai">Dipakai</span>
                                            <?php } else { ?>
                                                <span class="status-pill status-selesai">Selesai</span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($d['tanggal'])); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" class="empty-table">Belum ada transaksi.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <script>
        const ctx = document.getElementById('chartRentalPS');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pelanggan', 'Unit', 'Kosong', 'Dipakai', 'Transaksi', 'Aktif', 'Selesai'],
                datasets: [{
                    label: 'Data Rental PS',
                    data: [
                        <?php echo $total_pelanggan; ?>,
                        <?php echo $total_unit; ?>,
                        <?php echo $total_kosong; ?>,
                        <?php echo $total_dipakai; ?>,
                        <?php echo $total_transaksi; ?>,
                        <?php echo $total_transaksi_aktif; ?>,
                        <?php echo $total_transaksi_selesai; ?>
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>