<?php
// Menyertakan file autoload untuk PhpSpreadsheet
require 'vendor/autoload.php'; // Pastikan path sudah benar
use PhpOffice\PhpSpreadsheet\IOFactory;

// Menyertakan koneksi ke database
include 'conn.php';

// Mengecek apakah ada file yang diupload
if (isset($_FILES['excel_file']['tmp_name'])) {
    // Mendapatkan file yang diupload
    $filePath = $_FILES['excel_file']['tmp_name'];

    // Membaca file Excel
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    // Menyaring baris kosong
    $filteredData = array_filter($data, function ($row) {
        return !empty($row['A']) && !empty($row['B']) && !empty($row['C']) && !empty($row['D']);
    });

    // Menampilkan data dalam bentuk tabel HTML
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<thead><tr><th>ID</th><th>Nama</th><th>Harga</th><th>Deskripsi</th></tr></thead>';
    echo '<tbody>';
    foreach ($filteredData as $row) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['A']) . '</td>';
        echo '<td>' . htmlspecialchars($row['B']) . '</td>';
        echo '<td>' . htmlspecialchars($row['C']) . '</td>';
        echo '<td>' . htmlspecialchars($row['D']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';

    // Menyimpan data ke database
    foreach ($filteredData as $row) {
        $id = $row['A'];
        $nama = $row['B'];
        $harga = $row['C'];
        $deskripsi = $row['D'];

        // Query untuk memasukkan data ke tabel produk
        $query = "INSERT INTO produk (id, nama, harga, deskripsi) VALUES ('$id', '$nama', '$harga', '$deskripsi')";
        if ($conn->query($query) === TRUE) {
            echo "Data berhasil dimasukkan: $id, $nama<br>";
        } else {
            echo "Error: " . $conn->error . "<br>";
        }
    }
}
?>
