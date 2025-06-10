<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';

// Ambil total saldo, total pemasukan, total pengeluaran, dan total transaksi
$totalPemasukan = 0;
$totalPengeluaran = 0;
$totalTransaksi = 0;

$resPemasukan = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan");
if ($row = mysqli_fetch_assoc($resPemasukan)) {
    $totalPemasukan = $row['total'] ?? 0;
}

$resPengeluaran = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran");
if ($row = mysqli_fetch_assoc($resPengeluaran)) {
    $totalPengeluaran = $row['total'] ?? 0;
}

$totalSaldo = $totalPemasukan - $totalPengeluaran;

// Hitung transaksi bulan ini
$bulanIni = date('Y-m');
$resTransaksiBulanIniP = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni'");
$resTransaksiBulanIniK = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni'");

$pemasukanBulanIni = mysqli_fetch_assoc($resTransaksiBulanIniP)['total'] ?? 0;
$pengeluaranBulanIni = mysqli_fetch_assoc($resTransaksiBulanIniK)['total'] ?? 0;

// Total transaksi bulan ini
$resTransaksiCount = mysqli_query($conn, "SELECT 
  (SELECT COUNT(*) FROM pemasukan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni') +
  (SELECT COUNT(*) FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni') AS total");
$totalTransaksiBulanIni = mysqli_fetch_assoc($resTransaksiCount)['total'] ?? 0;

// Ambil data transaksi gabungan pemasukan dan pengeluaran
// dengan union all, tandai jenis transaksi dan ambil nama kategori
$queryTransaksi = "
    SELECT id, tanggal, kategori.nama_kategori AS kategori, deskripsi, jumlah, 'pemasukan' AS jenis
    FROM pemasukan 
    JOIN kategori ON pemasukan.kategori_id = kategori.kategori_id

    UNION ALL

    SELECT id, tanggal, kategori.nama_kategori AS kategori, deskripsi, jumlah, 'pengeluaran' AS jenis
    FROM pengeluaran
    JOIN kategori ON pengeluaran.kategori_id = kategori.kategori_id

    ORDER BY tanggal DESC, jenis DESC
";

$resTransaksi = mysqli_query($conn, $queryTransaksi);

$donutLabels = [];
$donutData = [];

$resDonut = mysqli_query($conn, "
    SELECT k.nama_kategori, SUM(p.jumlah) as total 
    FROM pengeluaran p 
    JOIN kategori k ON p.kategori_id = k.kategori_id 
    WHERE DATE_FORMAT(p.tanggal, '%Y-%m') = '$bulanIni'
    GROUP BY p.kategori_id
");

while ($row = mysqli_fetch_assoc($resDonut)) {
    $donutLabels[] = $row['nama_kategori'];
    $donutData[] = (int)$row['total'];
}

// Line Chart: pemasukan dan pengeluaran 6 bulan terakhir
$months = [];
$pemasukanLine = [];
$pengeluaranLine = [];

for ($i = 5; $i >= 0; $i--) {
    $bulan = date('Y-m', strtotime("-$i month"));
    $months[] = date('M', strtotime($bulan));

    // Total pemasukan bulan ini
    $resIn = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM pemasukan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan'");
    $pemasukanLine[] = (int)(mysqli_fetch_assoc($resIn)['total'] ?? 0);

    // Total pengeluaran bulan ini
    $resOut = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan'");
    $pengeluaranLine[] = (int)(mysqli_fetch_assoc($resOut)['total'] ?? 0);
}
?>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid p-4">
  <h2>Transaksi</h2>
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card p-3">
        <p>Total Saldo</p>
        <h4>Rp <?= number_format($totalSaldo, 0, ',', '.') ?></h4>
        <small>Per <?= date('d M Y') ?></small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <p>Pemasukan Bulan Ini</p>
        <h4 class="text-success">Rp <?= number_format($pemasukanBulanIni, 0, ',', '.') ?></h4>
        <small class="text-success">+12% dari bulan lalu</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <p>Pengeluaran Bulan Ini</p>
        <h4 class="text-danger">Rp <?= number_format($pengeluaranBulanIni, 0, ',', '.') ?></h4>
        <small class="text-danger">+8% dari bulan lalu</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <p>Total Transaksi</p>
        <h4><?= $totalTransaksiBulanIni ?></h4>
        <small>Bulan <?= date('F Y') ?></small>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card p-3">
        <h5>Ringkasan Pengeluaran</h5>
        <canvas id="donutChart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <h5>Tren Keuangan</h5>
        <canvas id="lineChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Tabel Transaksi -->
  <div class="card p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Semua Transaksi</a></li>
        <li class="nav-item"><a class="nav-link active" href="pemasukan.php">Pemasukan</a></li>
        <li class="nav-item"><a class="nav-link" href="pengeluaran.php">Pengeluaran</a></li>
      </ul>
      <a href="tambah_pemasukan.php?from=pemasukan" class="btn btn-success">+ Tambah Pemasukan</a>

    </div>

    <table class="table">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Kategori</th>
          <th>Deskripsi</th>
          <th>Jumlah</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = mysqli_query($conn, "SELECT pemasukan.*, kategori.nama_kategori FROM pemasukan JOIN kategori ON pemasukan.kategori_id = kategori.kategori_id ORDER BY tanggal DESC");
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
          <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
          <td><span class="badge bg-primary"><?= $row['nama_kategori'] ?></span></td>
          <td><?= $row['deskripsi'] ?></td>
          <td class="text-success">+Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
          <td>
            <a href="edit_pemasukan.php?id=<?= $row['id'] ?>&from=pemasukan" class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus_pemasukan.php?id=<?= $row['id'] ?>&from=pemasukan" class="text-danger" onclick="return confirm('Yakin ingin menghapus?')"><i class="bi bi-trash-fill"></i></a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
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
