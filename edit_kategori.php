<?php
include 'header.php';
include 'sidebar.php';
include 'db.php';

// Ambil ID kategori
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: kategori.php');
    exit;
}

// Ambil data kategori berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM kategori WHERE kategori_id = $id");
$kategori = mysqli_fetch_assoc($query);
if (!$kategori) {
    header('Location: kategori.php');
    exit;
}

// Proses update jika disubmit
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama'] ?? '');
    $jenis = $_POST['jenis'] ?? '';
    $errors = [];

    if ($nama === '') $errors[] = "Nama kategori wajib diisi.";
    if (!in_array($jenis, ['pemasukan', 'pengeluaran'])) $errors[] = "Jenis tidak valid.";

    if (empty($errors)) {
        $nama = mysqli_real_escape_string($conn, $nama);
        $jenis = mysqli_real_escape_string($conn, $jenis);

        $update = mysqli_query($conn, "
            UPDATE kategori SET nama_kategori='$nama', jenis='$jenis'
            WHERE kategori_id = $id
        ");

        if ($update) {
            header("Location: kategori.php?status=updated");
            exit;
        } else {
            $errors[] = "Gagal update data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container-fluid p-4">
  <h2>Edit Kategori</h2>

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
             value="<?= htmlspecialchars($_POST['nama'] ?? $kategori['nama_kategori']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="jenis" class="form-label">Jenis</label>
      <select id="jenis" name="jenis" class="form-select" required>
        <option value="pemasukan" <?= (($kategori['jenis'] === 'pemasukan') ? 'selected' : '') ?>>
          Pemasukan
        </option>
        <option value="pengeluaran" <?= (($kategori['jenis'] === 'pengeluaran') ? 'selected' : '') ?>>
          Pengeluaran
        </option>
      </select>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Update</button>
    <a href="kategori.php" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>