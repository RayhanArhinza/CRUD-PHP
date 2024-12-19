<?php
// pages/create.php
session_start();
require_once 'conn.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $pdo->prepare("INSERT INTO produk (nama, harga, deskripsi) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $harga, $deskripsi]);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link href="../css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Tambah Produk Baru</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block mb-2">Nama Produk</label>
                    <input type="text" name="nama" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block mb-2">Harga</label>
                    <input type="number" name="harga" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block mb-2">Deskripsi</label>
                    <textarea name="deskripsi" 
                              class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-between">
                    <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>