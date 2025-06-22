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

// Ambil ID pemasukan
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: pemasukan.php');
    exit;
}

// Redirect dari halaman mana
$allowedPages = ['transaksi', 'pemasukan'];
$redirectPage = in_array($_GET['from'] ?? '', $allowedPages) ? $_GET['from'] : 'pemasukan';

// Ambil data pemasukan user
$dataQuery = mysqli_query($conn, "SELECT * FROM pemasukan WHERE id = $id AND user_id = $user_id");
$pemasukan = mysqli_fetch_assoc($dataQuery);
if (!$pemasukan) {
    header("Location: {$redirectPage}.php");
    exit;
}

// Ambil kategori 'pemasukan' milik user
$kategoriQuery = mysqli_query($conn, "
    SELECT kategori_id, nama_kategori 
    FROM kategori 
    WHERE jenis = 'pemasukan' AND user_id = $user_id
    ORDER BY nama_kategori
");

// Proses update
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
            UPDATE pemasukan 
            SET tanggal='$tanggal', kategori_id=$kategori_id, deskripsi='$deskripsi', jumlah=$jumlah 
            WHERE id=$id AND user_id=$user_id
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
  <h2>Edit Pemasukan</h2>

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
        value="<?= htmlspecialchars($_POST['tanggal'] ?? $pemasukan['tanggal']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="kategori_id" class="form-label">Kategori</label>
      <?php
        $selectedId = $_POST['kategori_id'] ?? $pemasukan['kategori_id'];
        $kategoriList = [];
        while ($kategori = mysqli_fetch_assoc($kategoriQuery)) {
            $kategoriList[] = $kategori;
        }
        $kategoriFound = false;
      ?>
      <select id="kategori_id" name="kategori_id" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategoriList as $kategori): 
          $isSelected = ($kategori['kategori_id'] == $selectedId) ? 'selected' : '';
          if ($isSelected) $kategoriFound = true;
        ?>
          <option value="<?= $kategori['kategori_id'] ?>" <?= $isSelected ?>>
            <?= htmlspecialchars($kategori['nama_kategori']) ?>
          </option>
        <?php endforeach; ?>

        <?php if (!$kategoriFound && $selectedId) : ?>
          <option value="<?= $selectedId ?>" selected disabled>
            Kategori tidak ditemukan (mungkin sudah dihapus)
          </option>
        <?php endif; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="deskripsi" class="form-label">Deskripsi</label>
      <input type="text" id="deskripsi" name="deskripsi" class="form-control"
        value="<?= htmlspecialchars($_POST['deskripsi'] ?? $pemasukan['deskripsi']) ?>">
    </div>

    <div class="mb-3">
      <label for="jumlah" class="form-label">Jumlah (Rp)</label>
      <input type="number" id="jumlah" name="jumlah" class="form-control" min="1" step="any"
        value="<?= htmlspecialchars($_POST['jumlah'] ?? $pemasukan['jumlah']) ?>" required>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Update</button>
    <a href="<?= htmlspecialchars($redirectPage) ?>.php" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>
