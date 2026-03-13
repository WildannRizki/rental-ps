<?php

namespace App\Models;

class UnitPSModel extends BaseModel
{
    protected $table = "unit_ps";
    protected $primaryKey = "id_unit";

    public function simpan(array $data)
    {
        $nama = mysqli_real_escape_string($this->db, $data['nama_unit']);
        $tipe = mysqli_real_escape_string($this->db, $data['tipe_ps']);
        $harga = mysqli_real_escape_string($this->db, $data['harga_perjam']);
        $status = mysqli_real_escape_string($this->db, $data['status']);

        return mysqli_query(
            $this->db,
            "INSERT INTO unit_ps (nama_unit, tipe_ps, harga_perjam, status)
             VALUES ('$nama', '$tipe', '$harga', '$status')"
        );
    }

    public function update($id, array $data)
    {
        $id = mysqli_real_escape_string($this->db, $id);
        $nama = mysqli_real_escape_string($this->db, $data['nama_unit']);
        $tipe = mysqli_real_escape_string($this->db, $data['tipe_ps']);
        $harga = mysqli_real_escape_string($this->db, $data['harga_perjam']);
        $status = mysqli_real_escape_string($this->db, $data['status']);

        return mysqli_query(
            $this->db,
            "UPDATE unit_ps
             SET nama_unit = '$nama',
                 tipe_ps = '$tipe',
                 harga_perjam = '$harga',
                 status = '$status'
             WHERE id_unit = '$id'"
        );
    }

    public function countByStatus($status)
    {
        $status = mysqli_real_escape_string($this->db, $status);
        $query = mysqli_query(
            $this->db,
            "SELECT COUNT(*) AS total FROM unit_ps WHERE status = '$status'"
        );
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }
}