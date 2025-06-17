<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid p-4">
  <h2>Transaksi</h2>
  <div class="card p-4 mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <ul class="nav nav-tabs mb-2 mb-md-0">
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Semua Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="pemasukan.php">Pemasukan</a></li>
        <li class="nav-item"><a class="nav-link active" href="pengeluaran.php">Pengeluaran</a></li>
      </ul>
      <a href="tambah_pengeluaran.php?from=pengeluaran" class="btn btn-danger">+ Tambah Pengeluaran</a>
    </div>

    <!-- Tabel -->
    <div class="table-responsive">
      <table class="table table-striped w-100 align-middle">
        <thead class="table-light">
          <tr>
            <th style="min-width: 120px;">Tanggal</th>
            <th style="min-width: 150px;">Kategori</th>
            <th style="min-width: 300px;">Deskripsi</th>
            <th style="min-width: 150px;">Jumlah</th>
            <th style="min-width: 100px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = mysqli_query($conn, "SELECT pengeluaran.*, kategori.nama_kategori 
                                         FROM pengeluaran 
                                         JOIN kategori ON pengeluaran.kategori_id = kategori.kategori_id 
                                         ORDER BY tanggal DESC");
          while ($row = mysqli_fetch_assoc($query)) {
          ?>
            <tr>
              <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
              <td><span class="badge bg-danger"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
              <td><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td class="text-danger">-Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
              <td>
                <a href="edit_pengeluaran.php?id=<?= $row['id'] ?>&from=pengeluaran" class="text-primary me-2">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="hapus_pengeluaran.php?id=<?= $row['id'] ?>&from=pengeluaran" class="text-danger" onclick="return confirm('Yakin ingin menghapus?')">
                  <i class="bi bi-trash-fill"></i>
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
