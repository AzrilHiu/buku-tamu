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

if (isset($_GET['id_tamu'])) {
    if (hapus_tamu($_GET['id_tamu'])) {
        echo "<script>alert('Data tamu berhasil dihapus!'); window.location='buku-tamu.php';</script>";
    } else {
        echo "<script>alert('Data tamu gagal dihapus!'); window.location='buku-tamu.php';</script>";
    }
} else {
    header("Location: buku-tamu.php");
}
