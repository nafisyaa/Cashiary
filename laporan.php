<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';

// Ambil filter bulan dan tahun dari GET (default: bulan dan tahun sekarang)
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// Ambil pemasukan
$qPemasukan = mysqli_query($conn, "
  SELECT tanggal, deskripsi, jumlah, k.nama_kategori 
  FROM pemasukan p 
  JOIN kategori k ON p.kategori_id = k.kategori_id 
  WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
");

// Ambil pengeluaran
$qPengeluaran = mysqli_query($conn, "
  SELECT tanggal, deskripsi, jumlah, k.nama_kategori 
  FROM pengeluaran p 
  JOIN kategori k ON p.kategori_id = k.kategori_id 
  WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
");

$transaksi = [];

// Gabungkan ke satu array
while ($row = mysqli_fetch_assoc($qPemasukan)) {
  $row['tipe'] = 'Pemasukan';
  $transaksi[] = $row;
}
while ($row = mysqli_fetch_assoc($qPengeluaran)) {
  $row['tipe'] = 'Pengeluaran';
  $transaksi[] = $row;
}

// Urutkan berdasarkan tanggal
usort($transaksi, function($a, $b) {
  return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

// Total
$totalMasuk = array_sum(array_column(array_filter($transaksi, fn($t) => $t['tipe'] == 'Pemasukan'), 'jumlah'));
$totalKeluar = array_sum(array_column(array_filter($transaksi, fn($t) => $t['tipe'] == 'Pengeluaran'), 'jumlah'));
?>

<div class="container-fluid p-4">
  <h2 class="mb-4">Laporan Bulanan</h2>

    <a href="export_laporan.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" 
    class="btn btn-danger mb-3" 
    target="_blank">
    <i class="bi bi-file-earmark-arrow-down"></i> Download Laporan PDF
    </a>
    
  <form class="row g-3 mb-4" method="GET">
    <div class="col-auto">
      <select name="bulan" class="form-select">
        <?php for ($i = 1; $i <= 12; $i++): ?>
          <option value="<?= sprintf('%02d', $i) ?>" <?= $bulan == sprintf('%02d', $i) ? 'selected' : '' ?>>
            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-auto">
      <select name="tahun" class="form-select">
        <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
          <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Tampilkan</button>
    </div>
  </form>

  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card p-3">
        <p class="text-muted">Total Pemasukan</p>
        <h4 class="text-success fw-bold">Rp <?= number_format($totalMasuk, 0, ',', '.') ?></h4>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <p class="text-muted">Total Pengeluaran</p>
        <h4 class="text-danger fw-bold">Rp <?= number_format($totalKeluar, 0, ',', '.') ?></h4>
      </div>
    </div>
  </div>

  <div class="card p-3">
    <h5>Detail Transaksi</h5>
    <table class="table table-striped mt-3">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Kategori</th>
          <th>Deskripsi</th>
          <th>Jumlah</th>
          <th>Jenis</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($transaksi) == 0): ?>
          <tr><td colspan="5" class="text-center">Tidak ada data transaksi</td></tr>
        <?php else: ?>
          <?php foreach ($transaksi as $t): ?>
            <tr>
              <td><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
              <td><?= $t['nama_kategori'] ?></td>
              <td><?= $t['deskripsi'] ?></td>
              <td class="<?= $t['tipe'] == 'Pengeluaran' ? 'text-danger' : 'text-success' ?>">
                <?= $t['tipe'] == 'Pengeluaran' ? '- ' : '+ ' ?>
                Rp <?= number_format($t['jumlah'], 0, ',', '.') ?>
              </td>
              <td><span class="badge bg-<?= $t['tipe'] == 'Pengeluaran' ? 'danger' : 'success' ?>">
                <?= $t['tipe'] ?>
              </span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
