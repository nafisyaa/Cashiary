<?php
$halaman = basename($_SERVER['PHP_SELF']);
?>

<style>
  .bg-custom-dark {
    background-color: #2c3e50; 
  }
</style>

<div class="d-flex" style="min-height: 100vh;">
  
  <nav class="bg-custom-dark p-4 d-flex flex-column justify-content-between" style="width: 250px; position: sticky; top: 0; height: 100vh;">
    
    <div>
      <a href="index.php" class="text-decoration-none">
        <h4 class="text-white fw-bold mb-4">
          Cashiary
        </h4>
      </a>
      <ul class="nav flex-column gap-2">
        <li class="nav-item">
          <a href="index.php" class="nav-link py-2 d-flex align-items-center <?= $halaman == 'index.php' ? 'text-white fw-bold active' : 'text-white-50' ?>">
            <i class="bi bi-grid-fill me-3"></i>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="transaksi.php" class="nav-link py-2 d-flex align-items-center <?= in_array($halaman, ['transaksi.php', 'pemasukan.php', 'pengeluaran.php', 'tambah_pemasukan.php', 'tambah_pengeluaran.php', 'edit_pemasukan.php', 'edit_pengeluaran.php']) ? 'text-white fw-bold active' : 'text-white-50' ?>">
            <i class="bi bi-arrow-left-right me-3"></i>
            Transaksi
          </a>
        </li>
        <li class="nav-item">
          <a href="kategori.php" class="nav-link py-2 d-flex align-items-center <?= in_array($halaman, ['kategori.php', 'tambah_kategori.php', 'edit_kategori.php']) ? 'text-white fw-bold active' : 'text-white-50' ?>">
            <i class="bi bi-tags-fill me-3"></i>
            Kategori
          </a>
        </li>
        <li class="nav-item">
          <a href="laporan.php" class="nav-link py-2 d-flex align-items-center <?= $halaman == 'laporan.php' ? 'text-white fw-bold active' : 'text-white-50' ?>">
            <i class="bi bi-file-earmark-bar-graph-fill me-3"></i>
            Laporan
          </a>
        </li>
      </ul>
    </div>

    <div>
      <hr class="text-secondary">
      <a href="logout.php" class="nav-link py-2 d-flex align-items-center text-danger fw-bold" onclick="return confirm('Apakah Anda yakin ingin Logout?')">
        <i class="bi bi-box-arrow-right me-3"></i>
        Logout
      </a>
    </div>
  </nav>
