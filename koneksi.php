<?php
/**
 * File koneksi database untuk aplikasi buku tamu.
 * Sesuaikan konfigurasi berikut jika nama database atau kredensial Anda berbeda.
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "app_bukutamu_baru";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi = mysqli_connect($host, $user, $pass, $db);
    mysqli_set_charset($koneksi, "utf8mb4");
} catch (Exception $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
