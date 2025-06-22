<?php
session_start();

include 'header.php';
include 'sidebar.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid p-4">
  <h2>Transaksi</h2>
  <div class="card p-4 mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <ul class="nav nav-tabs mb-2 mb-md-0">
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Semua Transaksi</a></li>
        <li class="nav-item"><a class="nav-link active" href="pemasukan.php">Pemasukan</a></li>
        <li class="nav-item"><a class="nav-link" href="pengeluaran.php">Pengeluaran</a></li>
      </ul>
      <a href="tambah_pemasukan.php?from=pemasukan" class="btn btn-success">+ Tambah Pemasukan</a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover w-100 align-middle">
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
          $query = mysqli_query($conn, 
              "SELECT pemasukan.*, kategori.nama_kategori 
               FROM pemasukan 
               JOIN kategori ON pemasukan.kategori_id = kategori.kategori_id 
               WHERE pemasukan.user_id = $user_id 
               ORDER BY tanggal DESC"
          );
          
          while ($row = mysqli_fetch_assoc($query)) {
          ?>
            <tr>
              <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
              <td>
                <span class="badge bg-primary"><?= htmlspecialchars($row['nama_kategori']) ?></span>
              </td>
              <td><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td class="text-success">+Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
              <td>
                <a href="edit_pemasukan.php?id=<?= $row['id'] ?>&from=pemasukan" class="text-primary me-2">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="hapus_pemasukan.php?id=<?= $row['id'] ?>&from=pemasukan" class="text-danger" onclick="return confirm('Yakin ingin menghapus?')">
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
