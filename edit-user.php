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

if (!isset($_GET['id_user'])) {
    header("Location: users.php");
    exit;
}

$id_user = $_GET['id_user'];
$user = query("SELECT * FROM users WHERE id_user = '" . mysqli_real_escape_string($koneksi, $id_user) . "'");

if (empty($user)) {
    echo "<script>alert('Data user tidak ditemukan!'); window.location='users.php';</script>";
    exit;
}
$user = $user[0];

if (isset($_POST['simpan'])) {
    if (ubah_user()) {
        echo "<script>alert('Data user berhasil diubah!'); window.location='users.php';</script>";
        exit;
    } else {
        echo "<script>alert('Data user gagal diubah!');</script>";
    }
}

include_once('templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit User</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit User</h6>
        </div>
        <div class="card-body">
            <form action="edit-user.php?id_user=<?php echo urlencode($user['id_user']); ?>" method="POST">

                <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($user['id_user']); ?>">

                <div class="form-group">
                    <label>ID User</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['id_user']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control"
                        value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="operator" <?php echo $user['role'] === 'operator' ? 'selected' : ''; ?>>Operator</option>
                    </select>
                </div>

                <p class="text-muted">Untuk mengganti password, gunakan tombol "Password" di halaman daftar user.</p>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                <a href="users.php" class="btn btn-secondary">Batal</a>

            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>
