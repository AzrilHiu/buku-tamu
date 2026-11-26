<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (($_SESSION['role'] ?? '') !== 'operator') {
    header("Location: index.php");
    exit;
}

// Ambil data tamu berdasarkan id_tamu di URL
if (!isset($_GET['id_tamu'])) {
    header("Location: buku-tamu.php");
    exit;
}

$id_tamu = $_GET['id_tamu'];
$tamu = query("SELECT * FROM buku_tamu WHERE id_tamu = '" . mysqli_real_escape_string($koneksi, $id_tamu) . "'");

if (empty($tamu)) {
    echo "<script>alert('Data tamu tidak ditemukan!'); window.location='buku-tamu.php';</script>";
    exit;
}
$tamu = $tamu[0];

// Proses simpan perubahan
if (isset($_POST['simpan'])) {
    if (ubah_tamu()) {
        echo "<script>alert('Data tamu berhasil diubah!'); window.location='buku-tamu.php';</script>";
        exit;
    } else {
        echo "<script>alert('Data tamu gagal diubah!');</script>";
    }
}

include_once('templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit Data Tamu</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Tamu</h6>
        </div>
        <div class="card-body">
            <form action="edit-tamu.php?id_tamu=<?php echo urlencode($tamu['id_tamu']); ?>" method="POST"
                enctype="multipart/form-data">

                <input type="hidden" name="id_tamu" value="<?php echo htmlspecialchars($tamu['id_tamu']); ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($tamu['gambar']); ?>">

                <div class="form-group">
                    <label>ID Tamu</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($tamu['id_tamu']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                        value="<?php echo htmlspecialchars($tamu['tanggal']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Nama Tamu</label>
                    <input type="text" name="nama_tamu" class="form-control"
                        value="<?php echo htmlspecialchars($tamu['nama_tamu']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" required><?php echo htmlspecialchars($tamu['alamat']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control"
                        value="<?php echo htmlspecialchars($tamu['no_hp']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Bertemu Dengan</label>
                    <input type="text" name="bertemu" class="form-control"
                        value="<?php echo htmlspecialchars($tamu['bertemu']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Kepentingan</label>
                    <input type="text" name="kepentingan" class="form-control"
                        value="<?php echo htmlspecialchars($tamu['kepentingan']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Gambar Saat Ini</label><br>
                    <?php if (!empty($tamu['gambar'])): ?>
                    <img src="assets/upload_gambar/<?php echo htmlspecialchars($tamu['gambar']); ?>" width="100" class="mb-2"><br>
                    <?php else: ?>
                    <span class="text-muted">Belum ada gambar</span><br>
                    <?php endif; ?>
                    <label class="mt-2">Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" class="form-control-file">
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                <a href="buku-tamu.php" class="btn btn-secondary">Batal</a>

            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>
