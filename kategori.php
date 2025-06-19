<?php

$pageTitle = 'Manajemen Kategori - Cashiary'; // Judul halaman spesifik
include 'sidebar.php';
include 'header.php'; // Memulai HTML dan <head>, dan membuka <body>
include 'db.php'; // Koneksi ke database

// Pesan status dari operasi CRUD (tambah, edit, hapus)
$status_message = '';
$status_type = ''; // 'success' or 'danger'

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'added') {
        $status_message = 'Kategori berhasil ditambahkan!';
        $status_type = 'success';
    } elseif ($_GET['status'] == 'updated') {
        $status_message = 'Kategori berhasil diperbarui!';
        $status_type = 'success';
    } elseif ($_GET['status'] == 'deleted') {
        $status_message = 'Kategori berhasil dihapus!';
        $status_type = 'success';
    } elseif ($_GET['status'] == 'error' && isset($_GET['message'])) {
        $status_message = htmlspecialchars($_GET['message']);
        $status_type = 'danger';
    }
}

// --- LOGIC CRUD ---

// 1. Tambah Kategori
if (isset($_POST['add_kategori_submit'])) {
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');
    $jenis_kategori = $_POST['jenis_kategori'] ?? '';

    if (empty($nama_kategori) || empty($jenis_kategori)) {
        header('Location: kategori.php?status=error&message=' . urlencode('Nama kategori dan jenis tidak boleh kosong.'));
        exit;
    }

    // Cek apakah kategori dengan nama DAN jenis tersebut sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '" . mysqli_real_escape_string($conn, $nama_kategori) . "' AND jenis = '" . mysqli_real_escape_string($conn, $jenis_kategori) . "'");
    if (mysqli_num_rows($check_query) > 0) {
        header('Location: kategori.php?status=error&message=' . urlencode('Kategori dengan nama dan jenis tersebut sudah ada.'));
        exit;
    }

    $insert = mysqli_query($conn, "INSERT INTO kategori (nama_kategori, jenis) VALUES ('" . mysqli_real_escape_string($conn, $nama_kategori) . "', '" . mysqli_real_escape_string($conn, $jenis_kategori) . "')");

    if ($insert) {
        header('Location: kategori.php?status=added');
        exit;
    } else {
        header('Location: kategori.php?status=error&message=' . urlencode('Gagal menambahkan kategori: ' . mysqli_error($conn)));
        exit;
    }
}

// 2. Edit Kategori
if (isset($_POST['edit_kategori_submit'])) {
    $id_to_edit = $_POST['edit_kategori_id'] ?? null;
    $nama_kategori_baru = trim($_POST['nama_kategori'] ?? ''); // Nama input dari modal
    $jenis_kategori_baru = $_POST['jenis_kategori'] ?? ''; // Nama input dari modal

    if (!$id_to_edit || !is_numeric($id_to_edit) || empty($nama_kategori_baru) || empty($jenis_kategori_baru)) {
        header('Location: kategori.php?status=error&message=' . urlencode('Data edit tidak valid.'));
        exit;
    }

    // Cek apakah nama kategori DAN jenis baru sudah ada (selain kategori yang sedang diedit)
    $check_query = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '" . mysqli_real_escape_string($conn, $nama_kategori_baru) . "' AND jenis = '" . mysqli_real_escape_string($conn, $jenis_kategori_baru) . "' AND kategori_id != " . (int)$id_to_edit);
    if (mysqli_num_rows($check_query) > 0) {
        header('Location: kategori.php?status=error&message=' . urlencode('Kategori dengan nama dan jenis tersebut sudah ada.'));
        exit;
    }

    $update = mysqli_query($conn, "UPDATE kategori SET nama_kategori = '" . mysqli_real_escape_string($conn, $nama_kategori_baru) . "', jenis = '" . mysqli_real_escape_string($conn, $jenis_kategori_baru) . "' WHERE kategori_id = " . (int)$id_to_edit);

    if ($update) {
        header('Location: kategori.php?status=updated');
        exit;
    } else {
        header('Location: kategori.php?status=error&message=' . urlencode('Gagal memperbarui kategori: ' . mysqli_error($conn)));
        exit;
    }
}

// 3. Hapus Kategori
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'] ?? null;

    if (!$id_to_delete || !is_numeric($id_to_delete)) {
        header('Location: kategori.php?status=error&message=' . urlencode('ID kategori tidak valid untuk dihapus.'));
        exit;
    }

    $delete = mysqli_query($conn, "DELETE FROM kategori WHERE kategori_id = " . (int)$id_to_delete);

    if ($delete) {
        header('Location: kategori.php?status=deleted');
        exit;
    } else {
        header('Location: kategori.php?status=error&message=' . urlencode('Gagal menghapus kategori. Pastikan tidak ada transaksi yang terkait dengan kategori ini, atau ubah kategori transaksi yang terkait terlebih dahulu. Error: ' . mysqli_error($conn)));
        exit;
    }
}

// Ambil semua kategori dari database untuk ditampilkan
$kategori_query = mysqli_query($conn, "SELECT kategori_id, nama_kategori, jenis FROM kategori ORDER BY nama_kategori ASC");

?>

<div class="d-flex" style="min-height: 100vh;">
    <?php include 'sidebar.php'; // Sidebar yang Anda inginkan ada di sini ?>

    <main class="flex-grow-1 p-4">
        <h3 class="fw-bold text-dark mb-4">Manajemen Kategori</h3>

        <?php if ($status_message): ?>
            <div class="alert alert-<?= $status_type ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i><?= $status_message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddEditKategori" data-mode="add">
                <i class="bi bi-plus"></i> Tambah Kategori
            </button>
        </div>

        <div class="card p-3 shadow-sm">
            <h5 class="mb-3"><i class="bi bi-list-ul me-2"></i> Daftar Kategori</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="width:100%; table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th style="width: 50%;">Nama Kategori</th>
                            <th style="width: 15%;">Jenis</th>
                            <th class="text-center" style="width: 25%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($kategori_query) == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada kategori. Tambahkan yang pertama!</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($kategori = mysqli_fetch_assoc($kategori_query)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($kategori['kategori_id']) ?></td>
                                    <td><?= htmlspecialchars($kategori['nama_kategori']) ?></td>
                                    <td>
                                        <?php if ($kategori['jenis'] == 'pemasukan'): ?>
                                            <span class="badge bg-success">Pemasukan</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Pengeluaran</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="text-decoration-none me-2 text-primary"
                                           data-bs-toggle="modal" data-bs-target="#modalAddEditKategori"
                                           data-mode="edit"
                                           data-id="<?= $kategori['kategori_id'] ?>"
                                           data-nama="<?= htmlspecialchars($kategori['nama_kategori']) ?>"
                                           data-jenis="<?= htmlspecialchars($kategori['jenis']) ?>"
                                           title="Edit Kategori">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="kategori.php?action=delete&id=<?= $kategori['kategori_id'] ?>" class="text-decoration-none text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua transaksi yang terkait akan kehilangan kategorinya (atau menyebabkan error jika ada foreign key constraint).')" title="Hapus Kategori">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalAddEditKategori" tabindex="-1" aria-labelledby="modalAddEditKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="kategori.php">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddEditKategoriLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_kategori_id" name="edit_kategori_id">
                <div class="mb-3">
                    <label for="nama_kategori_modal" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control" id="nama_kategori_modal" name="nama_kategori" required>
                </div>
                <div class="mb-3">
                    <label for="jenis_kategori_modal" class="form-label">Jenis</label>
                    <select class="form-select" id="jenis_kategori_modal" name="jenis_kategori" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" id="submitModalBtn" name="add_kategori_submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // JavaScript untuk mengelola modal Tambah/Edit
    var modalAddEditKategori = document.getElementById('modalAddEditKategori');
    modalAddEditKategori.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Tombol yang memicu modal
        var mode = button.getAttribute('data-mode'); // Ambil mode: 'add' atau 'edit'

        var modalTitle = modalAddEditKategori.querySelector('.modal-title');
        var submitButton = modalAddEditKategori.querySelector('#submitModalBtn');
        var form = modalAddEditKategori.querySelector('form');
        var namaKategoriInput = modalAddEditKategori.querySelector('#nama_kategori_modal');
        var kategoriIdInput = modalAddEditKategori.querySelector('#edit_kategori_id');
        var jenisKategoriSelect = modalAddEditKategori.querySelector('#jenis_kategori_modal');

        if (mode === 'add') {
            modalTitle.textContent = 'Tambah Kategori';
            submitButton.textContent = 'Simpan';
            submitButton.name = 'add_kategori_submit'; // Nama submit untuk operasi tambah
            form.action = 'kategori.php'; // Arahkan form ke kategori.php
            namaKategoriInput.value = ''; // Kosongkan input
            kategoriIdInput.value = ''; // Kosongkan ID
            jenisKategoriSelect.value = 'pengeluaran'; // Default jenis ke pengeluaran
        } else if (mode === 'edit') {
            modalTitle.textContent = 'Edit Kategori';
            submitButton.textContent = 'Update';
            submitButton.name = 'edit_kategori_submit'; // Nama submit untuk operasi edit
            form.action = 'kategori.php'; // Arahkan form ke kategori.php
            
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var jenis = button.getAttribute('data-jenis'); 

            kategoriIdInput.value = id;
            namaKategoriInput.value = nama;
            if (jenisKategoriSelect && jenis) jenisKategoriSelect.value = jenis; // Set jenis
        }
    });
</script>
