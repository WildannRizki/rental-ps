<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Entity;
use App\Contracts\CrudInterface;

abstract class BaseModel extends Entity implements CrudInterface
{
    protected $db;
    protected $table;
    protected $primaryKey;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll()
    {
        return mysqli_query(
            $this->db,
            "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC"
        );
    }

    public function getById($id)
    {
        $id = mysqli_real_escape_string($this->db, $id);

        return mysqli_query(
            $this->db,
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = '$id'"
        );
    }

    public function hapus($id)
    {
        $id = mysqli_real_escape_string($this->db, $id);

        return mysqli_query(
            $this->db,
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = '$id'"
        );
    }

    public function countAll()
    {
        $query = mysqli_query($this->db, "SELECT COUNT(*) AS total FROM {$this->table}");
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }

    abstract public function simpan(array $data);
    abstract public function update($id, array $data);
}