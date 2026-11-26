<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Filter tanggal (opsional)
$dari  = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';

$where = "";
if ($dari && $sampai) {
    $dari_e   = mysqli_real_escape_string($koneksi, $dari);
    $sampai_e = mysqli_real_escape_string($koneksi, $sampai);
    $where = " WHERE tanggal BETWEEN '$dari_e' AND '$sampai_e'";
}

$data_laporan = query("SELECT * FROM buku_tamu $where ORDER BY tanggal ASC");

include_once('templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Laporan Buku Tamu</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter &amp; Export Laporan</h6>
        </div>
        <div class="card-body">

            <form method="GET" action="laporan.php" class="form-inline mb-3">
                <label class="mr-2">Dari</label>
                <input type="date" name="dari" class="form-control mr-3" value="<?php echo htmlspecialchars($dari); ?>">
                <label class="mr-2">Sampai</label>
                <input type="date" name="sampai" class="form-control mr-3" value="<?php echo htmlspecialchars($sampai); ?>">
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                <a href="export-laporan.php?dari=<?php echo urlencode($dari); ?>&sampai=<?php echo urlencode($sampai); ?>"
                    class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Nama Tamu</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Bertemu Dengan</th>
                            <th>Kepentingan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data_laporan as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['id_tamu']); ?></td>
                            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_tamu']); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                            <td><?php echo htmlspecialchars($row['bertemu']); ?></td>
                            <td><?php echo htmlspecialchars($row['kepentingan']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($data_laporan)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>
