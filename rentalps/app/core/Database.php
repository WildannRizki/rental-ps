<?php

namespace App\Core;

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "db_rentalps";
    private $port = 3307;

    protected $koneksi;

    public function __construct()
    {
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );

        if (!$this->koneksi) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }
    }

    public function getConnection()
    {
        return $this->koneksi;
    }
}