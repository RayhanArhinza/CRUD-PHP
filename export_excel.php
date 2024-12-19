<?php
require 'vendor/autoload.php'; // Autoload PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'conn.php';

// Fetch data from database
$query = "SELECT * FROM produk";
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nama');
$sheet->setCellValue('C1', 'Harga');
$sheet->setCellValue('D1', 'Deskripsi');

$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue("A$row", $data['id']);
    $sheet->setCellValue("B$row", $data['nama']);
    $sheet->setCellValue("C$row", $data['harga']);
    $sheet->setCellValue("D$row", $data['deskripsi']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'produk.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$filename");
$writer->save('php://output');
exit;
?>
