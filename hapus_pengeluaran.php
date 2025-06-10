<?php
include 'db.php';

$id = $_GET['id'];

$query = "DELETE FROM pengeluaran WHERE id = $id";
$koneksi->query($query);

header("Location: pengeluaran.php");
?>
