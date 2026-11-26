<?php
require_once 'koneksi.php';

/* ==============================================================
 * FUNGSI UMUM QUERY
 * ============================================================== */
function query($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/* ==============================================================
 * BUKU TAMU - GENERATE ID OTOMATIS (contoh: T0001, T0002, ...)
 * ============================================================== */
function generateIdTamu()
{
    global $koneksi;
    $result = mysqli_query($koneksi, "SELECT id_tamu FROM buku_tamu ORDER BY id_tamu DESC LIMIT 1");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $huruf  = substr($row['id_tamu'], 0, 1); // T
        $urutan = (int) substr($row['id_tamu'], 1) + 1;
    } else {
        $huruf  = "T";
        $urutan = 1;
    }

    $id_tamu = $huruf . sprintf("%04d", $urutan);
    return $id_tamu;
}

/* ==============================================================
 * BUKU TAMU - UPLOAD GAMBAR
 * ============================================================== */
function uploadGambar()
{
    $namaFile      = $_FILES['gambar']['name'];
    $ukuranFile    = $_FILES['gambar']['size'];
    $errorUpload   = $_FILES['gambar']['error'];
    $tmpName       = $_FILES['gambar']['tmp_name'];

    // Jika tidak ada file yang dipilih
    if ($errorUpload === 4) {
        return true; // biarkan gambar kosong, bukan error fatal
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensiFile  = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    if (!in_array($ekstensiFile, $ekstensiValid)) {
        echo "<script>alert('Yang diupload harus gambar (jpg/jpeg/png)!');</script>";
        return false;
    }

    if ($ukuranFile > 2000000) { // maksimal 2MB
        echo "<script>alert('Ukuran gambar terlalu besar (maks 2MB)!');</script>";
        return false;
    }

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.' . $ekstensiFile;

    move_uploaded_file($tmpName, 'assets/upload_gambar/' . $namaFileBaru);

    return $namaFileBaru;
}

/* ==============================================================
 * BUKU TAMU - TAMBAH
 * ============================================================== */
function tambah_tamu()
{
    global $koneksi;

    $id_tamu     = generateIdTamu();
    $tanggal     = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $nama_tamu   = mysqli_real_escape_string($koneksi, $_POST['nama_tamu']);
    $alamat      = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp       = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $bertemu     = mysqli_real_escape_string($koneksi, $_POST['bertemu']);
    $kepentingan = mysqli_real_escape_string($koneksi, $_POST['kepentingan']);

    // Upload gambar jika ada
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== 4) {
        $upload = uploadGambar();
        if ($upload === false) {
            return false;
        }
        $gambar = $upload;
    }

    $query = "INSERT INTO buku_tamu
        (id_tamu, tanggal, nama_tamu, alamat, no_hp, bertemu, kepentingan, gambar)
        VALUES
        ('$id_tamu', '$tanggal', '$nama_tamu', '$alamat', '$no_hp', '$bertemu', '$kepentingan', '$gambar')";

    return mysqli_query($koneksi, $query);
}

/* ==============================================================
 * BUKU TAMU - UBAH
 * ============================================================== */
function ubah_tamu()
{
    global $koneksi;

    $id_tamu     = mysqli_real_escape_string($koneksi, $_POST['id_tamu']);
    $tanggal     = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $nama_tamu   = mysqli_real_escape_string($koneksi, $_POST['nama_tamu']);
    $alamat      = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp       = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $bertemu     = mysqli_real_escape_string($koneksi, $_POST['bertemu']);
    $kepentingan = mysqli_real_escape_string($koneksi, $_POST['kepentingan']);

    $gambarLama = $_POST['gambar_lama'] ?? '';
    $gambar = $gambarLama;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== 4) {
        $upload = uploadGambar();
        if ($upload === false) {
            return false;
        }
        $gambar = $upload;

        // hapus gambar lama jika ada
        if ($gambarLama && file_exists('assets/upload_gambar/' . $gambarLama)) {
            unlink('assets/upload_gambar/' . $gambarLama);
        }
    }

    $query = "UPDATE buku_tamu SET
        tanggal = '$tanggal',
        nama_tamu = '$nama_tamu',
        alamat = '$alamat',
        no_hp = '$no_hp',
        bertemu = '$bertemu',
        kepentingan = '$kepentingan',
        gambar = '$gambar'
        WHERE id_tamu = '$id_tamu'";

    return mysqli_query($koneksi, $query);
}

/* ==============================================================
 * BUKU TAMU - HAPUS
 * ============================================================== */
function hapus_tamu($id_tamu)
{
    global $koneksi;
    $id_tamu = mysqli_real_escape_string($koneksi, $id_tamu);

    // Hapus file gambar juga jika ada
    $row = query("SELECT gambar FROM buku_tamu WHERE id_tamu = '$id_tamu'");
    if (!empty($row) && !empty($row[0]['gambar'])) {
        $file = 'assets/upload_gambar/' . $row[0]['gambar'];
        if (file_exists($file)) {
            unlink($file);
        }
    }

    return mysqli_query($koneksi, "DELETE FROM buku_tamu WHERE id_tamu = '$id_tamu'");
}

/* ==============================================================
 * USER - GENERATE ID OTOMATIS (contoh: U0001, U0002, ...)
 * ============================================================== */
function generateIdUser()
{
    global $koneksi;
    $result = mysqli_query($koneksi, "SELECT id_user FROM users ORDER BY id_user DESC LIMIT 1");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $huruf  = substr($row['id_user'], 0, 1); // U
        $urutan = (int) substr($row['id_user'], 1) + 1;
    } else {
        $huruf  = "U";
        $urutan = 1;
    }

    return $huruf . sprintf("%04d", $urutan);
}

/* ==============================================================
 * USER - VALIDASI PASSWORD (minimum 6 karakter)
 * ============================================================== */
function validasi_password($password)
{
    if (strlen($password) < 6) {
        return "Password minimal 6 karakter!";
    }
    return null; // tidak ada error
}

/* ==============================================================
 * USER - TAMBAH
 * ============================================================== */
function tambah_user()
{
    global $koneksi;

    $id_user  = generateIdUser();
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Validasi password minimal 6 karakter
    $error_password = validasi_password($password);
    if ($error_password) {
        echo "<script>alert('$error_password');</script>";
        return false;
    }

    // Cek username unik
    $cek = query("SELECT username FROM users WHERE username = '$username'");
    if (!empty($cek)) {
        echo "<script>alert('Username sudah digunakan!');</script>";
        return false;
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO users (id_user, nama, username, password, role)
        VALUES ('$id_user', '$nama', '$username', '$password_hash', '$role')";

    return mysqli_query($koneksi, $query);
}

/* ==============================================================
 * USER - UBAH
 * ============================================================== */
function ubah_user()
{
    global $koneksi;

    $id_user  = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);

    $query = "UPDATE users SET
        nama = '$nama',
        username = '$username',
        role = '$role'
        WHERE id_user = '$id_user'";

    return mysqli_query($koneksi, $query);
}

/* ==============================================================
 * USER - HAPUS
 * ============================================================== */
function hapus_user($id_user)
{
    global $koneksi;
    $id_user = mysqli_real_escape_string($koneksi, $id_user);
    return mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id_user'");
}

/* ==============================================================
 * USER - GANTI PASSWORD
 * ============================================================== */
function ganti_password()
{
    global $koneksi;

    $id_user       = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $password_baru = $_POST['password_baru'];
    $ulangi        = $_POST['ulangi_password'];

    // Validasi password minimal 6 karakter
    $error_password = validasi_password($password_baru);
    if ($error_password) {
        echo "<script>alert('$error_password');</script>";
        return false;
    }

    if ($password_baru !== $ulangi) {
        echo "<script>alert('Password baru dan ulangi password tidak sama!');</script>";
        return false;
    }

    $password_hash = password_hash($password_baru, PASSWORD_BCRYPT);

    $query = "UPDATE users SET password = '$password_hash' WHERE id_user = '$id_user'";
    return mysqli_query($koneksi, $query);
}
