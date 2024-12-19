<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    $query = "UPDATE produk SET nama = ?, harga = ?, deskripsi = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdsi', $nama, $harga, $deskripsi, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php');
}
?>
