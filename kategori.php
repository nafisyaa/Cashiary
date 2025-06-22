<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Sertakan file pendukung
include 'header.php';
include 'sidebar.php';
include 'db.php';

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

$stmt_cek_kategori = $conn->prepare("SELECT COUNT(*) AS total FROM kategori WHERE user_id = ?");
$stmt_cek_kategori->bind_param("i", $user_id);
$stmt_cek_kategori->execute();
$cek_result = $stmt_cek_kategori->get_result()->fetch_assoc();

if ($cek_result['total'] == 0) {
    // Ambil kategori default (id tertentu dan user_id NULL)
    $result_default = $conn->query("
        SELECT nama_kategori, jenis 
        FROM kategori 
        WHERE kategori_id IN (1,2,3,4,5,6,7,8,9,11,13) AND user_id IS NULL
    ");

    $stmt_insert = $conn->prepare("INSERT INTO kategori (nama_kategori, jenis, user_id) VALUES (?, ?, ?)");

    while ($row = $result_default->fetch_assoc()) {
        $stmt_insert->bind_param("ssi", $row['nama_kategori'], $row['jenis'], $user_id);
        $stmt_insert->execute();
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $kategori_id_hapus = (int)$_GET['id'];

    // Cek apakah kategori digunakan dalam transaksi
    $stmt_cek = $conn->prepare("
        SELECT 
            (SELECT COUNT(*) FROM pemasukan WHERE kategori_id = ? AND user_id = ?) +
            (SELECT COUNT(*) FROM pengeluaran WHERE kategori_id = ? AND user_id = ?) 
        AS total_transaksi
    ");
    $stmt_cek->bind_param("iiii", $kategori_id_hapus, $user_id, $kategori_id_hapus, $user_id);
    $stmt_cek->execute();
    $hasil_cek = $stmt_cek->get_result()->fetch_assoc();

    if ($hasil_cek['total_transaksi'] == 0) {
        // Aman untuk menghapus
        $stmt_hapus = $conn->prepare("DELETE FROM kategori WHERE kategori_id = ? AND user_id = ?");
        $stmt_hapus->bind_param("ii", $kategori_id_hapus, $user_id);
        if ($stmt_hapus->execute()) {
            header("Location: kategori.php?status=sukses_hapus");
            exit;
        }
    } else {
        $pesan_error = urlencode("Kategori tidak bisa dihapus karena masih digunakan oleh transaksi.");
        header("Location: kategori.php?status=gagal_hapus&pesan={$pesan_error}");
        exit;
    }
}

$stmt_list = $conn->prepare("SELECT * FROM kategori WHERE user_id = ? ORDER BY nama_kategori ASC");
$stmt_list->bind_param("i", $user_id);
$stmt_list->execute();
$list_kategori = $stmt_list->get_result();

// Ambil status notifikasi
$status = $_GET['status'] ?? '';
$pesan = $_GET['pesan'] ?? '';
?>

<div class="container-fluid p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">Kelola Kategori</h2>
    <a href="tambah_kategori.php" class="btn btn-primary">
      <i class="bi bi-plus-circle-fill me-2"></i>Tambah Kategori Baru
    </a>
  </div>
  <hr>

  <?php if ($status == 'gagal_hapus'): ?>
  <div class="alert alert-danger">
      <?= htmlspecialchars(urldecode($pesan)) ?>
  </div>
  <?php elseif ($status == 'sukses_hapus'): ?>
  <div class="alert alert-success">
      Kategori berhasil dihapus.
  </div>
  <?php endif; ?>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="width: 50%;">Nama Kategori</th>
            <th>Jenis</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($list_kategori->num_rows > 0): ?>
            <?php while($row = $list_kategori->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
              <td>
                <span class="badge bg-<?= $row['jenis']=='pemasukan' ? 'success' : 'danger' ?>">
                  <?= ucfirst($row['jenis']) ?>
                </span>
              </td>
              <td class="text-center">
                <a href="edit_kategori.php?id=<?= $row['kategori_id'] ?>" class="btn btn-sm btn-outline-primary me-2" title="Edit">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="kategori.php?aksi=hapus&id=<?= $row['kategori_id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
                <td colspan="3" class="text-center text-muted p-4">
                    Anda belum memiliki kategori. <br>
                    <a href="tambah_kategori.php" class="btn btn-sm btn-primary mt-2">Buat Kategori Pertama Anda</a>
                </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
