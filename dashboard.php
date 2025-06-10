<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';
// Contoh data dummy (bisa diganti dengan data dari database nanti)
$tanggal = date("d M Y");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Cashiary</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap & Chart.js CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* body { background-color: #f8f9fc; } */
    .card { border: none; border-radius: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .nav-link.active { color: #0d6efd !important; font-weight: bold; }
    .sidebar { width: 240px; }
  </style>
</head>
<body>


  <!-- Main content -->
  <div class="p-4 w-100">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold">Dashboard</h3>
        <p class="text-muted">Ringkasan keuangan Anda per tanggal <?= $tanggal ?></p>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="card p-3">
          <div class="text-muted">Saldo Saat Ini</div>
          <h4 class="fw-bold">Rp 8.750.000</h4>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-3">
          <div class="text-muted">Pemasukan Bulan Ini</div>
          <h4 class="fw-bold text-success">Rp 12.500.000</h4>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-3">
          <div class="text-muted">Pengeluaran Bulan Ini</div>
          <h4 class="fw-bold text-danger">Rp 3.750.000</h4>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-3">
          <div class="text-muted">Tabungan</div>
          <h4 class="fw-bold">Rp 25.000.000</h4>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
      <div class="col-md-8 mb-3">
        <div class="card p-3">
          <div class="d-flex justify-content-between">
            <h5>Ringkasan Bulanan</h5>
            <select class="form-select w-auto">
              <option>Juni 2025</option>
            </select>
          </div>
          <canvas id="lineChart" height="120"></canvas>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card p-3">
          <div class="d-flex justify-content-between">
            <h5>Pengeluaran per Kategori</h5>
            <select class="form-select w-auto">
              <option>Bulan Ini</option>
            </select>
          </div>
          <canvas id="donutChart" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card p-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Transaksi Terbaru</h5>
        <a href="transaksi.php" class="text-primary fw-bold">Lihat Semua</a>
      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>TANGGAL</th>
            <th>KATEGORI</th>
            <th>DESKRIPSI</th>
            <th>JUMLAH</th>
            <th>AKSI</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>09 Jun 2025</td>
            <td>Belanja</td>
            <td>Supermarket Hari Hari</td>
            <td class="text-danger">- Rp 350.000</td>
            <td>
              <a href="#" class="text-primary me-2">✏️</a>
              <a href="#" class="text-danger">🗑️</a>
            </td>
          </tr>
          <tr>
            <td>08 Jun 2025</td>
            <td>Gaji</td>
            <td>Gaji Bulanan PT Maju Bersama</td>
            <td class="text-success">+ Rp 12.500.000</td>
            <td>
              <a href="#" class="text-primary me-2">✏️</a>
              <a href="#" class="text-danger">🗑️</a>
            </td>
          </tr>
          <tr>
            <td>07 Jun 2025</td>
            <td>Makanan</td>
            <td>Makan Malam di Restoran Padang</td>
            <td class="text-danger">- Rp 125.000</td>
            <td>
              <a href="#" class="text-primary me-2">✏️</a>
              <a href="#" class="text-danger">🗑️</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

<!-- Chart.js Scripts -->
<script>
  const ctxLine = document.getElementById('lineChart').getContext('2d');
  new Chart(ctxLine, {
    type: 'line',
    data: {
      labels: ['1 Jun', '5 Jun', '10 Jun', '15 Jun', '20 Jun', '25 Jun', '30 Jun'],
      datasets: [
        {
          label: 'Pemasukan',
          data: [3000000, 2800000, 12500000, 2700000, 2500000, 2700000, 2900000],
          borderColor: 'blue',
          backgroundColor: 'rgba(0, 123, 255, 0.1)',
          fill: true
        },
        {
          label: 'Pengeluaran',
          data: [3500000, 3400000, 13750000, 3100000, 3300000, 3200000, 3100000],
          borderColor: 'red',
          backgroundColor: 'rgba(255, 0, 0, 0.1)',
          fill: true
        }
      ]
    }
  });

  const ctxDonut = document.getElementById('donutChart').getContext('2d');
  new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
      labels: ['Makanan', 'Transportasi', 'Belanja', 'Rumah', 'Hiburan'],
      datasets: [{
        data: [25, 20, 15, 30, 10],
        backgroundColor: ['#0d6efd', '#0dcaf0', '#ffc107', '#dc3545', '#6c757d']
      }]
    }
  });
</script>

</body>
</html>
