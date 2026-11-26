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

if (isset($_GET['id_user'])) {
    // Cegah admin menghapus akunnya sendiri yang sedang login
    $userTarget = query("SELECT username FROM users WHERE id_user = '" . mysqli_real_escape_string($koneksi, $_GET['id_user']) . "'");
    if (!empty($userTarget) && $userTarget[0]['username'] === $_SESSION['username']) {
        echo "<script>alert('Kamu tidak bisa menghapus akunmu sendiri!'); window.location='users.php';</script>";
        exit;
    }

    if (hapus_user($_GET['id_user'])) {
        echo "<script>alert('Data user berhasil dihapus!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Data user gagal dihapus!'); window.location='users.php';</script>";
    }
} else {
    header("Location: users.php");
}
