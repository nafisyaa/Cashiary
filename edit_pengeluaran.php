<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';

// Ambil id pengeluaran
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: pengeluaran.php');
    exit;
}

// Tentukan asal halaman untuk redirect (default: pengeluaran.php)
$allowedPages = ['transaksi', 'pengeluaran'];
$redirectPage = in_array($_GET['from'] ?? '', $allowedPages) ? $_GET['from'] : 'pengeluaran';

// Ambil data pengeluaran
$dataQuery = mysqli_query($conn, "SELECT * FROM pengeluaran WHERE id = $id");
$pengeluaran = mysqli_fetch_assoc($dataQuery);
if (!$pengeluaran) {
    header("Location: {$redirectPage}.php");
    exit;
}

// Ambil kategori untuk pengeluaran
$kategoriQuery = mysqli_query($conn, "
    SELECT kategori_id, nama_kategori 
    FROM kategori 
    WHERE nama_kategori IN (
        'makanan & minuman', 
        'transportasi', 
        'hiburan', 
        'tagihan & utilitas', 
        'kesehatan & obat-obatan', 
        'pendidikan'
    )
    ORDER BY nama_kategori
");

// Proses update jika disubmit
if (isset($_POST['submit'])) {
    $tanggal = $_POST['tanggal'] ?? '';
    $kategori_id = $_POST['kategori_id'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $jumlah = $_POST['jumlah'] ?? 0;

    $errors = [];
    if (!$tanggal) $errors[] = "Tanggal wajib diisi.";
    if (!$kategori_id) $errors[] = "Kategori wajib dipilih.";
    if (!$jumlah || !is_numeric($jumlah) || $jumlah <= 0) $errors[] = "Jumlah harus berupa angka positif.";

    if (empty($errors)) {
        $tanggal = mysqli_real_escape_string($conn, $tanggal);
        $kategori_id = (int)$kategori_id;
        $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
        $jumlah = (float)$jumlah;

        $update = mysqli_query($conn, "
            UPDATE pengeluaran 
            SET tanggal='$tanggal', kategori_id=$kategori_id, deskripsi='$deskripsi', jumlah=$jumlah 
            WHERE id=$id
        ");

        if ($update) {
            header("Location: {$redirectPage}.php?status=updated");
            exit;
        } else {
            $errors[] = "Gagal update data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container-fluid p-4">
  <h2>Edit Pengeluaran</h2>

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
      <label for="tanggal" class="form-label">Tanggal</label>
      <input type="date" id="tanggal" name="tanggal" class="form-control" 
        value="<?= htmlspecialchars($_POST['tanggal'] ?? $pengeluaran['tanggal']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="kategori_id" class="form-label">Kategori</label>
      <select id="kategori_id" name="kategori_id" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while ($kategori = mysqli_fetch_assoc($kategoriQuery)) : ?>
          <?php 
            $selected = $_POST['kategori_id'] ?? $pengeluaran['kategori_id']; 
            $isSelected = ($kategori['kategori_id'] == $selected) ? 'selected' : '';
          ?>
          <option value="<?= $kategori['kategori_id'] ?>" <?= $isSelected ?>>
            <?= htmlspecialchars($kategori['nama_kategori']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="deskripsi" class="form-label">Deskripsi</label>
      <input type="text" id="deskripsi" name="deskripsi" class="form-control" 
        value="<?= htmlspecialchars($_POST['deskripsi'] ?? $pengeluaran['deskripsi']) ?>">
    </div>
    <div class="mb-3">
      <label for="jumlah" class="form-label">Jumlah (Rp)</label>
      <input type="number" id="jumlah" name="jumlah" class="form-control" min="1" step="any" 
        value="<?= htmlspecialchars($_POST['jumlah'] ?? $pengeluaran['jumlah']) ?>" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Update</button>
    <a href="<?= htmlspecialchars($redirectPage) ?>.php" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>
