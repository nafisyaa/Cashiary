<?php
include 'db.php';

$id = $_GET['id'];
$from = $_GET['from'] ?? 'pengeluaran';

if ($id) {
    // Hapus data pemasukan berdasarkan id
    mysqli_query($conn, "DELETE FROM pengeluaran WHERE id = $id");
}

// Redirect ke halaman asal dengan status
header("Location: {$from}.php?status=deleted");
exit;
?>
