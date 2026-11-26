<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

// Proses tambah user
if (isset($_POST['simpan']) && isset($_POST['username']) && isset($_POST['password'])) {
    if (tambah_user()) {
        echo "<script>alert('Data user berhasil ditambahkan!');</script>";
    } else {
        echo "<script>alert('Data user gagal ditambahkan!');</script>";
    }
}

// Proses ganti password
if (isset($_POST['ganti_password'])) {
    if (ganti_password()) {
        echo "<script>alert('Password berhasil diubah!');</script>";
    } else {
        echo "<script>alert('Password gagal diubah!');</script>";
    }
}

include_once('templates/header.php');

$data_user = query("SELECT * FROM users ORDER BY nama ASC");
$id_baru   = generateIdUser();
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">User</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahUser">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_user as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id_user']); ?></td>
                            <td><?php echo htmlspecialchars($user['nama']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm"
                                    data-toggle="modal" data-target="#modalPassword<?php echo htmlspecialchars($user['id_user']); ?>">
                                    <i class="fas fa-key"></i> Password
                                </button>
                                <a href="edit-user.php?id_user=<?php echo urlencode($user['id_user']); ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus-user.php?id_user=<?php echo urlencode($user['id_user']); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus user ini?');">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Ganti Password per user -->
                        <div class="modal fade" id="modalPassword<?php echo htmlspecialchars($user['id_user']); ?>"
                            tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="users.php" method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ganti Password - <?php echo htmlspecialchars($user['nama']); ?></h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($user['id_user']); ?>">
                                            <div class="form-group">
                                                <label>Password Baru</label>
                                                <input type="password" name="password_baru" class="form-control" required
                                                    minlength="6" placeholder="Minimal 6 karakter">
                                                <small class="form-text text-muted">Password minimal 6 karakter</small>
                                            </div>
                                            <div class="form-group">
                                                <label>Ulangi Password</label>
                                                <input type="password" name="ulangi_password" class="form-control" required
                                                    minlength="6" placeholder="Minimal 6 karakter">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                            <button class="btn btn-primary" type="submit" name="ganti_password">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="users.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>ID User</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($id_baru); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required 
                            minlength="6" placeholder="Minimal 6 karakter">
                        <small class="form-text text-muted">Password minimal 6 karakter</small>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                        </select>
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
