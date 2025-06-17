<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';

// Tentukan asal halaman (default ke kategori)
$allowedPages = ['kategori', 'pengaturan'];
$redirectPage = in_array($_GET['from'] ?? '', $allowedPages) ? $_GET['from'] : 'kategori';

// Inisialisasi error
$errors = [];

// Proses form submit
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama'] ?? '');
    $jenis = $_POST['jenis'] ?? '';

    // Validasi
    if ($nama === '') $errors[] = "Nama kategori wajib diisi.";
    if (!in_array($jenis, ['pemasukan', 'pengeluaran'])) $errors[] = "Jenis kategori tidak valid.";

    // Jika valid
    if (empty($errors)) {
        $nama = mysqli_real_escape_string($conn, $nama);
        $jenis = mysqli_real_escape_string($conn, $jenis);

        $insert = mysqli_query($conn, "
            INSERT INTO kategori (nama_kategori, jenis)
            VALUES ('$nama', '$jenis')
        ");

        if ($insert) {
            header("Location: {$redirectPage}.php?status=added");
            exit;
        } else {
            $errors[] = "Gagal menambah kategori: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container-fluid p-4">
  <h2>Tambah Kategori</h2>

  <?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $err) : ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="" method="POST">
    <div class="mb-3">
      <label for="nama" class="form-label">Nama Kategori</label>
      <input type="text" id="nama" name="nama" class="form-control"
             value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="jenis" class="form-label">Jenis</label>
      <select id="jenis" name="jenis" class="form-select" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="pemasukan" <?= (($_POST['jenis'] ?? '') === 'pemasukan') ? 'selected' : '' ?>>Pemasukan</option>
        <option value="pengeluaran" <?= (($_POST['jenis'] ?? '') === 'pengeluaran') ? 'selected' : '' ?>>Pengeluaran</option>
      </select>
    </div>

    <button type="submit" name="submit" class="btn btn-success">Simpan</button>
    <a href="<?= htmlspecialchars($redirectPage) ?>.php" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>