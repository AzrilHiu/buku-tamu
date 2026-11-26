<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Filter tanggal (opsional), sama seperti di laporan.php
$dari   = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';

$where = "";
if ($dari && $sampai) {
    $dari_e   = mysqli_real_escape_string($koneksi, $dari);
    $sampai_e = mysqli_real_escape_string($koneksi, $sampai);
    $where = " WHERE tanggal BETWEEN '$dari_e' AND '$sampai_e'";
}

$data_laporan = query("SELECT * FROM buku_tamu $where ORDER BY tanggal ASC");

/*
 * Catatan:
 * Export ini menggunakan trik "HTML table diberi header Excel" sehingga
 * TIDAK memerlukan library composer (phpoffice/phpspreadsheet).
 * File yang dihasilkan bisa dibuka langsung oleh Microsoft Excel / LibreOffice.
 *
 * Kalau kamu tetap ingin pakai PhpSpreadsheet seperti di tutorial asli,
 * install dulu dengan:
 *   composer require phpoffice/phpspreadsheet
 * lalu ganti isi file ini mengikuti dokumentasi PhpSpreadsheet.
 */

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan-buku-tamu-" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="text-align:center; font-size:16pt;">Laporan Buku Tamu</th>
        </tr>
        <?php if ($dari && $sampai): ?>
        <tr>
            <th colspan="8" style="text-align:center;">Periode: <?php echo htmlspecialchars($dari); ?> s/d <?php echo htmlspecialchars($sampai); ?></th>
        </tr>
        <?php endif; ?>
    </thead>
    <tbody>
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
    </tbody>
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
    </tbody>
</table>
