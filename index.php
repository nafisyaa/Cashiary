<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Silakan login dulu.");
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'db.php';

$user_id = $_SESSION['user_id'];

$totalPemasukan = 0;
$totalPengeluaran = 0;

$resPemasukan = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE user_id = $user_id");
if ($row = mysqli_fetch_assoc($resPemasukan)) {
    $totalPemasukan = $row['total'] ?? 0;
}

$resPengeluaran = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran WHERE user_id = $user_id");
if ($row = mysqli_fetch_assoc($resPengeluaran)) {
    $totalPengeluaran = $row['total'] ?? 0;
}

$totalSaldo = $totalPemasukan - $totalPengeluaran;

$bulanIni = date('Y-m');

$resTransaksiBulanIniP = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni' AND user_id = $user_id");
$pemasukanBulanIni = mysqli_fetch_assoc($resTransaksiBulanIniP)['total'] ?? 0;

$resTransaksiBulanIniK = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni' AND user_id = $user_id");
$pengeluaranBulanIni = mysqli_fetch_assoc($resTransaksiBulanIniK)['total'] ?? 0;

$resTransaksiCount = mysqli_query($conn, "SELECT 
  (SELECT COUNT(*) FROM pemasukan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni' AND user_id = $user_id) +
  (SELECT COUNT(*) FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni' AND user_id = $user_id) AS total");
$totalTransaksiBulanIni = mysqli_fetch_assoc($resTransaksiCount)['total'] ?? 0;


$donutLabels = [];
$donutData = [];

$resDonut = mysqli_query($conn, "
    SELECT k.nama_kategori, SUM(p.jumlah) as total 
    FROM pengeluaran p 
    JOIN kategori k ON p.kategori_id = k.kategori_id 
    WHERE DATE_FORMAT(p.tanggal, '%Y-%m') = '$bulanIni' AND p.user_id = $user_id
    GROUP BY p.kategori_id
");

while ($row = mysqli_fetch_assoc($resDonut)) {
    $donutLabels[] = $row['nama_kategori'];
    $donutData[] = (int)$row['total'];
}

$months = [];
$pemasukanLine = [];
$pengeluaranLine = [];

for ($i = 5; $i >= 0; $i--) {
    $bulan = date('Y-m', strtotime("-$i month"));
    $months[] = date('M', strtotime($bulan));

    $resIn = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM pemasukan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan' AND user_id = $user_id");
    $pemasukanLine[] = (int)(mysqli_fetch_assoc($resIn)['total'] ?? 0);

    $resOut = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan' AND user_id = $user_id");
    $pengeluaranLine[] = (int)(mysqli_fetch_assoc($resOut)['total'] ?? 0);
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid p-4">
  <h2 class="fw-bold">Dashboard</h2>
  <hr>
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card bg-primary text-white shadow">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="card-text mb-1">Total Saldo</p>
              <h4 class="card-title mb-0">Rp <?= number_format($totalSaldo, 0, ',', '.') ?></h4>
            </div>
            <i class="bi bi-wallet2" style="font-size: 2.5rem; opacity: 0.5;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white shadow">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="card-text mb-1">Pemasukan Bulan Ini</p>
              <h4 class="card-title mb-0">Rp <?= number_format($pemasukanBulanIni, 0, ',', '.') ?></h4>
            </div>
            <i class="bi bi-arrow-down-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-danger text-white shadow">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="card-text mb-1">Pengeluaran Bulan Ini</p>
              <h4 class="card-title mb-0">Rp <?= number_format($pengeluaranBulanIni, 0, ',', '.') ?></h4>
            </div>
            <i class="bi bi-arrow-up-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-white shadow">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="card-text mb-1">Total Transaksi</p>
              <h4 class="card-title mb-0"><?= $totalTransaksiBulanIni ?></h4>
            </div>
            <i class="bi bi-receipt" style="font-size: 2.5rem; opacity: 0.5;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card p-3 shadow-sm border-0">
        <h5>Ringkasan Pengeluaran</h5>
        <canvas id="donutChart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3 shadow-sm border-0">
        <h5>Tren Keuangan</h5>
        <canvas id="lineChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    const donutLabels = <?= json_encode($donutLabels) ?>;
    const donutData = <?= json_encode($donutData) ?>;
    const lineLabels = <?= json_encode($months) ?>;
    const pemasukanLine = <?= json_encode($pemasukanLine) ?>;
    const pengeluaranLine = <?= json_encode($pengeluaranLine) ?>;
  </script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="script.js"></script>
