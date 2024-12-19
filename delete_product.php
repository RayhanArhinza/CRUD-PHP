<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM produk WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: index.php');
?>
