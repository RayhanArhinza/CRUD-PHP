<?php
$host = 'localhost'; // atau sesuai dengan host database Anda
$username = 'root'; // username database Anda
$password = '03122001'; // password database Anda
$dbname = 'php';

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
