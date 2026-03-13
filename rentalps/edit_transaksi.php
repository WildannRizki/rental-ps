<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$id = $_GET['id'];

$transaksi = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id'");
$t = mysqli_fetch_assoc($transaksi);

if (!$t) {
    header("location:transaksi.php");
    exit;
}

$pelanggan = mysqli_query($koneksi, "
    SELECT 
        p.id_pelanggan,
        p.nama_pelanggan,
        p.no_hp,
        COUNT(tr.id_transaksi) AS jumlah_transaksi,
        MAX(tr.tanggal) AS terakhir_main,
        SUM(CASE WHEN tr.status_transaksi='dipakai' AND tr.id_transaksi != '$id' THEN 1 ELSE 0 END) AS aktif_lain
    FROM pelanggan p
    LEFT JOIN transaksi tr ON p.id_pelanggan = tr.id_pelanggan
    GROUP BY p.id_pelanggan, p.nama_pelanggan, p.no_hp
    ORDER BY p.nama_pelanggan ASC
");

$unit = mysqli_query($koneksi, "
    SELECT * FROM unit_ps
    WHERE status='kosong' OR id_unit='$t[id_unit]'
    ORDER BY nama_unit ASC
");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi Rental</title>
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
                    <span class="hero-badge">Form Edit</span>
                    <h1>✏️ Edit Transaksi Rental</h1>
                    <p>Perbarui transaksi, ubah status menjadi selesai, atau pindahkan ke unit lain yang masih kosong.</p>
                </div>
            </div>

            <div class="app-form-card">
                <form method="POST" action="update_transaksi.php">

                    <input type="hidden" name="id_transaksi" value="<?php echo $t['id_transaksi']; ?>">
                    <input type="hidden" name="id_unit_lama" value="<?php echo $t['id_unit']; ?>">
                    <input type="hidden" name="status_lama" value="<?php echo $t['status_transaksi']; ?>">

                    <div class="app-form-grid">
                        <div class="app-form-group">
                            <label>Pilih Pelanggan</label>
                            <select name="id_pelanggan" id="id_pelanggan" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php while ($p = mysqli_fetch_array($pelanggan)) { ?>
                                    <option
                                        value="<?php echo $p['id_pelanggan']; ?>"
                                        data-jumlah="<?php echo $p['jumlah_transaksi']; ?>"
                                        data-terakhir="<?php echo $p['terakhir_main'] ? date('d-m-Y H:i', strtotime($p['terakhir_main'])) : '-'; ?>"
                                        data-nohp="<?php echo $p['no_hp']; ?>"
                                        <?php echo ($p['aktif_lain'] > 0 && $p['id_pelanggan'] != $t['id_pelanggan']) ? 'disabled' : ''; ?>
                                        <?php if ($p['id_pelanggan'] == $t['id_pelanggan']) echo 'selected'; ?>>
                                        <?php echo $p['nama_pelanggan']; ?> - <?php echo $p['no_hp']; ?>
                                        <?php echo ($p['aktif_lain'] > 0 && $p['id_pelanggan'] != $t['id_pelanggan']) ? ' (masih aktif)' : ''; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="app-form-group">
                            <label>Pilih Unit PS</label>
                            <select name="id_unit" id="id_unit" required>
                                <option value="">-- Pilih Unit PS --</option>
                                <?php while ($u = mysqli_fetch_array($unit)) { ?>
                                    <option
                                        value="<?php echo $u['id_unit']; ?>"
                                        data-harga="<?php echo $u['harga_perjam']; ?>"
                                        <?php if ($u['id_unit'] == $t['id_unit']) echo 'selected'; ?>>
                                        <?php echo $u['nama_unit']; ?> - <?php echo $u['tipe_ps']; ?> - Rp <?php echo number_format($u['harga_perjam'], 0, ',', '.'); ?>/jam
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="app-form-group">
                            <label>Lama Main (jam)</label>
                            <input type="number" name="lama_main" id="lama_main" min="1" value="<?php echo $t['lama_main']; ?>" required>
                        </div>

                        <div class="app-form-group">
                            <label>Status Transaksi</label>
                            <select name="status_transaksi" required>
                                <option value="dipakai" <?php if ($t['status_transaksi'] == 'dipakai') echo 'selected'; ?>>Dipakai</option>
                                <option value="selesai" <?php if ($t['status_transaksi'] == 'selesai') echo 'selected'; ?>>Selesai</option>
                            </select>
                        </div>

                        <div class="app-form-group">
                            <label>Harga per Jam</label>
                            <input type="text" id="harga_tampil" readonly>
                        </div>

                        <div class="app-form-group">
                            <label>Tanggal Transaksi</label>
                            <input type="text" value="<?php echo date('d-m-Y H:i', strtotime($t['tanggal'])); ?>" readonly>
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
                        <button type="submit" class="btn-primary-modern">💾 Update Transaksi</button>
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

        pelangganSelect.addEventListener('change', updatePelangganInfo);
        unitSelect.addEventListener('change', hitungTotal);
        lamaMain.addEventListener('input', hitungTotal);

        updatePelangganInfo();
        hitungTotal();
    </script>

</body>

</html>