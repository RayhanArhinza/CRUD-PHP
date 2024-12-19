<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO produk (nama, harga, deskripsi) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sds', $nama, $harga, $deskripsi);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php');
}
?>
