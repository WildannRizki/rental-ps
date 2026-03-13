<?php

namespace App\Models;

class TransaksiModel extends BaseModel
{
    protected $table = "transaksi";
    protected $primaryKey = "id_transaksi";

    public function simpan(array $data)
    {
        $id_pelanggan = mysqli_real_escape_string($this->db, $data['id_pelanggan']);
        $id_unit = mysqli_real_escape_string($this->db, $data['id_unit']);
        $lama_main = mysqli_real_escape_string($this->db, $data['lama_main']);
        $harga = mysqli_real_escape_string($this->db, $data['harga']);
        $total_bayar = mysqli_real_escape_string($this->db, $data['total_bayar']);
        $status_transaksi = mysqli_real_escape_string($this->db, $data['status_transaksi']);
        $tanggal = mysqli_real_escape_string($this->db, $data['tanggal']);

        return mysqli_query(
            $this->db,
            "INSERT INTO transaksi
             (id_pelanggan, id_unit, lama_main, harga, total_bayar, status_transaksi, tanggal)
             VALUES
             ('$id_pelanggan', '$id_unit', '$lama_main', '$harga', '$total_bayar', '$status_transaksi', '$tanggal')"
        );
    }

    public function update($id, array $data)
    {
        $id = mysqli_real_escape_string($this->db, $id);
        $id_pelanggan = mysqli_real_escape_string($this->db, $data['id_pelanggan']);
        $id_unit = mysqli_real_escape_string($this->db, $data['id_unit']);
        $lama_main = mysqli_real_escape_string($this->db, $data['lama_main']);
        $harga = mysqli_real_escape_string($this->db, $data['harga']);
        $total_bayar = mysqli_real_escape_string($this->db, $data['total_bayar']);
        $status_transaksi = mysqli_real_escape_string($this->db, $data['status_transaksi']);

        return mysqli_query(
            $this->db,
            "UPDATE transaksi
             SET id_pelanggan = '$id_pelanggan',
                 id_unit = '$id_unit',
                 lama_main = '$lama_main',
                 harga = '$harga',
                 total_bayar = '$total_bayar',
                 status_transaksi = '$status_transaksi'
             WHERE id_transaksi = '$id'"
        );
    }

    public function countByStatus($status)
    {
        $status = mysqli_real_escape_string($this->db, $status);
        $query = mysqli_query(
            $this->db,
            "SELECT COUNT(*) AS total FROM transaksi WHERE status_transaksi = '$status'"
        );
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }

    public function sumTotalBayar()
    {
        $query = mysqli_query(
            $this->db,
            "SELECT COALESCE(SUM(total_bayar),0) AS total FROM transaksi"
        );
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }

    public function getLatest($limit = 5)
    {
        $limit = (int) $limit;

        return mysqli_query(
            $this->db,
            "SELECT 
                t.*,
                p.nama_pelanggan,
                u.nama_unit,
                u.tipe_ps
             FROM transaksi t
             JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
             JOIN unit_ps u ON t.id_unit = u.id_unit
             ORDER BY t.id_transaksi DESC
             LIMIT $limit"
        );
    }
}