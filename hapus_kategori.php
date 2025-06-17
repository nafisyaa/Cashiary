<?php
include 'db.php';

// Ambil ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: kategori.php");
    exit;
}

// Eksekusi hapus
$id = (int)$id;
$result = mysqli_query($conn, "DELETE FROM kategori WHERE kategori_id = $id");

header("Location: kategori.php?status=" . ($result ? "deleted" : "error"));
exit;