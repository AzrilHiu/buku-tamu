<?php
session_start();
require_once 'function.php';

// Hanya role 'operator' yang boleh mengakses halaman ini
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (($_SESSION['role'] ?? '') !== 'operator' && ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

// Proses tambah data
if (isset($_POST['simpan'])) {
    if (tambah_tamu()) {
        echo "<script>alert('Data tamu berhasil ditambahkan!');</script>";
    } else {
        echo "<script>alert('Data tamu gagal ditambahkan!');</script>";
    }
}

include_once('templates/header.php');

$data_tamu = query("SELECT * FROM buku_tamu ORDER BY tanggal DESC");
$id_baru   = generateIdTamu();
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Buku Tamu</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Tamu</h6>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Tamu
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Nama Tamu</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Bertemu Dengan</th>
                            <th>Kepentingan</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_tamu as $tamu): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tamu['id_tamu']); ?></td>
                            <td><?php echo htmlspecialchars($tamu['tanggal']); ?></td>
                            <td><?php echo htmlspecialchars($tamu['nama_tamu']); ?></td>
                            <td><?php echo htmlspecialchars($tamu['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($tamu['no_hp']); ?></td>
                            <td><?php echo htmlspecialchars($tamu['bertemu']); ?></td>
                            <td><?php echo htmlspecialchars($tamu['kepentingan']); ?></td>
                            <td>
                                <?php if (!empty($tamu['gambar'])): ?>
                                <img src="assets/upload_gambar/<?php echo htmlspecialchars($tamu['gambar']); ?>"
                                    width="60">
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit-tamu.php?id_tamu=<?php echo urlencode($tamu['id_tamu']); ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus-tamu.php?id_tamu=<?php echo urlencode($tamu['id_tamu']); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data tamu ini?');">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Modal Tambah Tamu -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="buku-tamu.php" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Data Tamu</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>ID Tamu</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($id_baru); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Tamu</label>
                        <input type="text" name="nama_tamu" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Bertemu Dengan</label>
                        <input type="text" name="bertemu" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Kepentingan</label>
                        <input type="text" name="kepentingan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Gambar (opsional)</label>
                        <input type="file" name="gambar" class="form-control-file">
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });
</script>

<?php
include_once('templates/footer.php');
?>
