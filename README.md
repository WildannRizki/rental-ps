# rental-ps
Aplikasi web **Rental PS** berbasis **PHP Native** dan **MySQL** untuk membantu pengelolaan data pelanggan, data unit PS, transaksi rental, riwayat pelanggan, dan laporan transaksi.

## Fitur Utama
- Login admin
- Dashboard
- CRUD Data Pelanggan
- CRUD Data Unit PS
- Transaksi Rental
- Riwayat Transaksi Pelanggan
- Laporan Transaksi

## Teknologi yang Digunakan
- PHP Native
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP

## Database
Nama database yang digunakan:

`db_rentalps`

Tabel utama:
- `user`
- `pelanggan`
- `unit_ps`
- `transaksi`

## Cara Menjalankan Project
1. Simpan folder project ke dalam folder `htdocs` XAMPP  
   Contoh:
   `C:\xampp\htdocs\rentalps`

2. Jalankan **Apache** dan **MySQL** melalui XAMPP

3. Buka **phpMyAdmin**

4. Buat database baru dengan nama:

   `db_rentalps`

5. Import file database:

   `db_rentalps.sql`

6. Sesuaikan file koneksi database `koneksi.php` jika diperlukan

7. Buka project melalui browser:

   `http://localhost/rentalps`

## Konfigurasi Koneksi Database
Contoh file `koneksi.php`:

```php
<?php
$server = "localhost";
$username = "root";
$password = "";
$database_name = "db_rentalps";
$port = 3307;

$koneksi = mysqli_connect($server, $username, $password, $database_name, $port);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>
Akun Login Default

Gunakan akun berikut untuk login:

Username: admin

Password: admin
