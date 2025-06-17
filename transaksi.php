<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';

// Ambil data transaksi gabungan
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
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid p-4">
  <h2>Transaksi</h2>
  <div class="card p-4 mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <ul class="nav nav-tabs mb-2 mb-md-0">
        <li class="nav-item"><a class="nav-link active" href="#">Semua Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="pemasukan.php">Pemasukan</a></li>
        <li class="nav-item"><a class="nav-link" href="pengeluaran.php">Pengeluaran</a></li>
      </ul>
      <div>
        <a href="tambah_pemasukan.php?from=transaksi" class="btn btn-success">+ Tambah Pemasukan</a>
        <a href="tambah_pengeluaran.php?from=transaksi" class="btn btn-danger">+ Tambah Pengeluaran</a>
      </div>
    </div>

    <!-- Table Responsive -->
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
          <?php while($row = mysqli_fetch_assoc($resTransaksi)): ?>
            <tr>
              <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
              <td>
                <span class="badge <?= $row['jenis'] === 'pemasukan' ? 'bg-primary' : 'bg-danger' ?>">
                  <?= htmlspecialchars($row['kategori']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td class="<?= $row['jenis'] === 'pemasukan' ? 'text-success' : 'text-danger' ?>">
                <?= $row['jenis'] === 'pemasukan' ? '+' : '-' ?>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
              </td>
              <td>
                <?php if ($row['jenis'] === 'pemasukan'): ?>
                  <a href="edit_pemasukan.php?id=<?= $row['id'] ?>&from=transaksi" class="text-primary me-2">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="hapus_pemasukan.php?id=<?= $row['id'] ?>&from=transaksi" class="text-danger" onclick="return confirm('Yakin ingin menghapus pemasukan ini?')">
                    <i class="bi bi-trash-fill"></i>
                  </a>
                <?php else: ?>
                  <a href="edit_pengeluaran.php?id=<?= $row['id'] ?>&from=transaksi" class="text-primary me-2">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="hapus_pengeluaran.php?id=<?= $row['id'] ?>&from=transaksi" class="text-danger" onclick="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                    <i class="bi bi-trash-fill"></i>
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
