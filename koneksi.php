<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "db_penjualan2";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
