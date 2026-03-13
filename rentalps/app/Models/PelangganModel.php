<?php

namespace App\Models;

use App\Entities\PelangganEntity;

class PelangganModel extends BaseModel
{
    protected $table = "pelanggan";
    protected $primaryKey = "id_pelanggan";

    public function simpan(array $data)
    {
        $nama = mysqli_real_escape_string($this->db, $data['nama_pelanggan']);
        $hp = mysqli_real_escape_string($this->db, $data['no_hp']);

        return mysqli_query(
            $this->db,
            "INSERT INTO pelanggan (nama_pelanggan, no_hp)
             VALUES ('$nama', '$hp')"
        );
    }

    public function update($id, array $data)
    {
        $id = mysqli_real_escape_string($this->db, $id);
        $nama = mysqli_real_escape_string($this->db, $data['nama_pelanggan']);
        $hp = mysqli_real_escape_string($this->db, $data['no_hp']);

        return mysqli_query(
            $this->db,
            "UPDATE pelanggan
             SET nama_pelanggan = '$nama', no_hp = '$hp'
             WHERE id_pelanggan = '$id'"
        );
    }

    public function findEntityById($id)
    {
        $result = $this->getById($id);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            return null;
        }

        $pelanggan = new PelangganEntity();
        $pelanggan->id_pelanggan = $row['id_pelanggan'];
        $pelanggan->nama_pelanggan = $row['nama_pelanggan'];
        $pelanggan->no_hp = $row['no_hp'];

        return $pelanggan;
    }
}