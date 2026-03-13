<?php

namespace App\Contracts;

interface CrudInterface
{
    public function getAll();
    public function getById($id);
    public function simpan(array $data);
    public function update($id, array $data);
    public function hapus($id);
}