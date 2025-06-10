<?php $halaman = basename($_SERVER['PHP_SELF']); ?>
<div class="d-flex" style="min-height: 100vh;">
  <nav class="bg-light p-3 d-flex flex-column justify-content-between" style="width: 240px; position: sticky; top: 0; height: 100vh; overflow-y: auto;">
    <div>
      <h4 class="text-primary fw-bold">Cashiary</h4>
      <ul class="nav flex-column mt-4">
        <li class="nav-item">
          <a href="dashboard.php" class="nav-link <?= $halaman == 'dashboard.php' ? 'active text-primary fw-bold' : 'text-dark' ?>">
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="transaksi.php" class="nav-link <?= in_array($halaman, ['transaksi.php', 'pemasukan.php', 'pengeluaran.php']) ? 'active text-primary fw-bold' : 'text-dark' ?>">
            Transaksi
          </a>
        </li>
        <li class="nav-item">
          <a href="kategori.php" class="nav-link <?= $halaman == 'kategori.php' ? 'active text-primary fw-bold' : 'text-dark' ?>">
            Kategori
          </a>
        </li>
        <li class="nav-item">
          <a href="laporan.php" class="nav-link <?= $halaman == 'laporan.php' ? 'active text-primary fw-bold' : 'text-dark' ?>">
            Laporan
          </a>
        </li>
        <li class="nav-item">
          <a href="pengaturan.php" class="nav-link <?= $halaman == 'pengaturan.php' ? 'active text-primary fw-bold' : 'text-dark' ?>">
            Pengaturan
          </a>
        </li>
      </ul>
    </div>

    <div>
      <a href="logout.php" class="nav-link text-danger fw-bold" onclick="return confirm('Apakah Yakin ingin Logout?')">
        Logout
      </a>
    </div>
  </nav>
