<?php
session_start();
header('Content-Type: application/json');

// Pastikan log aktivitas selalu dikembalikan
if (!isset($_SESSION['activity_log'])) {
    $_SESSION['activity_log'] = [];
}

echo json_encode(array_reverse($_SESSION['activity_log'])); // Log terbaru di atas
?>
