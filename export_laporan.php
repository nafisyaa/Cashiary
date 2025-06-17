<?php
require 'vendor/vendor/vendor/autoload.php'; 
use Dompdf\Dompdf;
include 'db.php';

$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');
$bulanNama = date('F', mktime(0, 0, 0, $bulan, 1));

// Ambil data dari database
$pemasukan = mysqli_query($conn, "SELECT tanggal, deskripsi, jumlah, 'Pemasukan' AS tipe FROM pemasukan WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun");
$pengeluaran = mysqli_query($conn, "SELECT tanggal, deskripsi, jumlah, 'Pengeluaran' AS tipe FROM pengeluaran WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun");

$data = [];
while ($row = mysqli_fetch_assoc($pemasukan)) $data[] = $row;
while ($row = mysqli_fetch_assoc($pengeluaran)) $data[] = $row;

// Urutkan berdasarkan tanggal terbaru
usort($data, fn($a, $b) => strtotime($b['tanggal']) - strtotime($a['tanggal']));

// HTML + CSS
$html = "
<!DOCTYPE html>
<html lang='id'>
<head>
  <meta charset='UTF-8'>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead {
      background-color: #f2f2f2;
    }
    th, td {
      border: 1px solid #333;
      padding: 8px;
      text-align: left;
    }
  </style>
</head>
<body>

<h2>Laporan Transaksi - $bulanNama $tahun</h2>
<table>
  <thead>
    <tr>
      <th>Tanggal</th>
      <th>Deskripsi</th>
      <th>Jumlah</th>
      <th>Tipe</th>
    </tr>
  </thead>
  <tbody>";

if (count($data) == 0) {
  $html .= "<tr><td colspan='4' style='text-align:center;'>Tidak ada data transaksi</td></tr>";
} else {
  foreach ($data as $row) {
    $jumlah = ($row['tipe'] == 'Pengeluaran' ? '-' : '+') . ' Rp ' . number_format($row['jumlah'], 0, ',', '.');
    $html .= "<tr>
      <td>" . date('d M Y', strtotime($row['tanggal'])) . "</td>
      <td>{$row['deskripsi']}</td>
      <td>{$jumlah}</td>
      <td>{$row['tipe']}</td>
    </tr>";
  }
}

$html .= "</tbody></table></body></html>";

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Laporan_{$bulanNama}_{$tahun}.pdf", ["Attachment" => false]); // false = tampil di browser
exit;
