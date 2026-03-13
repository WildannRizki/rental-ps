<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$pelanggan = mysqli_query($koneksi, "
    SELECT 
        p.id_pelanggan,
        p.nama_pelanggan,
        p.no_hp,
        COUNT(t.id_transaksi) AS jumlah_transaksi,
        MAX(t.tanggal) AS terakhir_main,
        SUM(CASE WHEN t.status_transaksi='dipakai' THEN 1 ELSE 0 END) AS aktif
    FROM pelanggan p
    LEFT JOIN transaksi t ON p.id_pelanggan = t.id_pelanggan
    GROUP BY p.id_pelanggan, p.nama_pelanggan, p.no_hp
    ORDER BY p.nama_pelanggan ASC
");

$unit = mysqli_query($koneksi, "SELECT * FROM unit_ps WHERE status='kosong' ORDER BY nama_unit ASC");

$q_jumlah_unit = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM unit_ps WHERE status='kosong'");
$jumlah_unit = mysqli_fetch_assoc($q_jumlah_unit)['total'];

$q_jumlah_pelanggan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pelanggan");
$jumlah_pelanggan = mysqli_fetch_assoc($q_jumlah_pelanggan)['total'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi Rental</title>
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

            <div class="form-hero transaksi-hero">
                <div>
                    <span class="hero-badge">Form Input</span>
                    <h1>➕ Tambah Transaksi Rental</h1>
                    <p>Pilih pelanggan lama yang sudah ada, lalu buat transaksi baru tanpa menambah data pelanggan lagi.
                    </p>
                </div>
            </div>

            <?php if ($jumlah_pelanggan == 0) { ?>
                <div class="alert-box warning">Belum ada data pelanggan. Tambahkan pelanggan terlebih dahulu.</div>
            <?php } ?>

            <?php if ($jumlah_unit == 0) { ?>
                <div class="alert-box warning">Semua unit sedang dipakai. Tidak ada unit kosong untuk transaksi baru.</div>
            <?php } ?>

            <div class="app-form-card">
                <form method="POST" action="simpan_transaksi.php">

                    <div class="app-form-grid">
                        <div class="app-form-group">
                            <label>Pilih Pelanggan</label>
                            <select name="id_pelanggan" id="id_pelanggan" required <?php echo ($jumlah_pelanggan == 0) ? 'disabled' : ''; ?>>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php while ($p = mysqli_fetch_array($pelanggan)) { ?>
                                    <option value="<?php echo $p['id_pelanggan']; ?>"
                                        data-jumlah="<?php echo $p['jumlah_transaksi']; ?>"
                                        data-terakhir="<?php echo $p['terakhir_main'] ? date('d-m-Y H:i', strtotime($p['terakhir_main'])) : '-'; ?>"
                                        data-nohp="<?php echo $p['no_hp']; ?>" <?php echo ($p['aktif'] > 0) ? 'disabled' : ''; ?>>
                                        <?php echo $p['nama_pelanggan']; ?> -
                                        <?php echo $p['no_hp']; ?>
                                        <?php echo ($p['aktif'] > 0) ? ' (masih aktif)' : ''; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <small class="helper-text">Pilih pelanggan yang sudah pernah datang. Tidak perlu input ulang
                                data pelanggan lama.</small>
                        </div>

                        <div class="app-form-group">
                            <label>Pilih Unit PS</label>
                            <select name="id_unit" id="id_unit" required <?php echo ($jumlah_unit == 0) ? 'disabled' : ''; ?>>
                                <option value="">-- Pilih Unit PS --</option>
                                <?php while ($u = mysqli_fetch_array($unit)) { ?>
                                    <option value="<?php echo $u['id_unit']; ?>"
                                        data-harga="<?php echo $u['harga_perjam']; ?>">
                                        <?php echo $u['nama_unit']; ?> -
                                        <?php echo $u['tipe_ps']; ?> - Rp
                                        <?php echo number_format($u['harga_perjam'], 0, ',', '.'); ?>/jam
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="app-form-group">
                            <label>Lama Main (jam)</label>
                            <input type="number" name="lama_main" id="lama_main" min="1"
                                placeholder="Masukkan lama main" required <?php echo ($jumlah_unit == 0 || $jumlah_pelanggan == 0) ? 'disabled' : ''; ?>>
                        </div>

                        <div class="app-form-group">
                            <label>Harga per Jam</label>
                            <input type="text" id="harga_tampil" placeholder="Otomatis dari unit" readonly>
                        </div>
                    </div>

                    <div class="preview-box">
                        <div class="preview-item">
                            <span>Pelanggan Dipilih</span>
                            <h3 id="preview_nama">-</h3>
                            <p id="preview_nohp">-</p>
                        </div>

                        <div class="preview-item">
                            <span>Sudah Berapa Kali Transaksi</span>
                            <h3 id="preview_jumlah">0x</h3>
                            <p>Riwayat transaksi pelanggan</p>
                        </div>

                        <div class="preview-item">
                            <span>Terakhir Main</span>
                            <h3 id="preview_terakhir">-</h3>
                            <p>Data transaksi sebelumnya</p>
                        </div>

                        <div class="preview-item">
                            <span>Total Bayar</span>
                            <h3 id="total_tampil">Rp 0</h3>
                            <p>Harga otomatis dari unit yang dipilih</p>
                        </div>
                    </div>

                    <div class="app-form-buttons">
                        <?php if ($jumlah_unit > 0 && $jumlah_pelanggan > 0) { ?>
                            <button type="submit" class="btn-primary-modern">💾 Simpan Transaksi</button>
                        <?php } ?>
                        <a href="transaksi.php" class="btn-secondary-modern">↩ Kembali</a>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <script>
        const pelangganSelect = document.getElementById('id_pelanggan');
        const unitSelect = document.getElementById('id_unit');
        const lamaMain = document.getElementById('lama_main');
        const hargaTampil = document.getElementById('harga_tampil');
        const totalTampil = document.getElementById('total_tampil');

        const previewNama = document.getElementById('preview_nama');
        const previewNohp = document.getElementById('preview_nohp');
        const previewJumlah = document.getElementById('preview_jumlah');
        const previewTerakhir = document.getElementById('preview_terakhir');

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        function updatePelangganInfo() {
            const selected = pelangganSelect.options[pelangganSelect.selectedIndex];

            if (!selected || !selected.value) {
                previewNama.innerText = '-';
                previewNohp.innerText = '-';
                previewJumlah.innerText = '0x';
                previewTerakhir.innerText = '-';
                return;
            }

            previewNama.innerText = selected.text.split(' - ')[0];
            previewNohp.innerText = selected.getAttribute('data-nohp') || '-';
            previewJumlah.innerText = (selected.getAttribute('data-jumlah') || 0) + 'x';
            previewTerakhir.innerText = selected.getAttribute('data-terakhir') || '-';
        }

        function hitungTotal() {
            const selectedUnit = unitSelect.options[unitSelect.selectedIndex];
            const harga = selectedUnit ? selectedUnit.getAttribute('data-harga') : 0;
            const lama = parseInt(lamaMain.value) || 0;
            const total = (parseInt(harga) || 0) * lama;

            hargaTampil.value = harga ? formatRupiah(harga) : '';
            totalTampil.innerText = formatRupiah(total);
        }

        if (pelangganSelect) pelangganSelect.addEventListener('change', updatePelangganInfo);
        if (unitSelect) unitSelect.addEventListener('change', hitungTotal);
        if (lamaMain) lamaMain.addEventListener('input', hitungTotal);
    </script>

</body>

</html>