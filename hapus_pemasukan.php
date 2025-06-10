<?php
include 'db.php';

$id = $_GET['id'] ?? null;
$from = $_GET['from'] ?? 'pemasukan'; // Default balik ke pemasukan.php

if ($id) {
    // Hapus data pemasukan berdasarkan id
    mysqli_query($conn, "DELETE FROM pemasukan WHERE id = $id");
}

// Redirect ke halaman asal dengan status
header("Location: {$from}.php?status=deleted");
exit;
