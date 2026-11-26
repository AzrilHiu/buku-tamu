<?php
/**
 * TOOL BANTU - Generate Hash Password
 * -------------------------------------
 * Gunakan file ini untuk membuat hash password baru (bcrypt) yang valid,
 * lalu copy hasilnya ke kolom 'password' pada tabel 'users' di phpMyAdmin.
 *
 * Cara pakai:
 * 1. Jalankan di browser: http://localhost/buku-tamu/generate_password.php
 * 2. Ketik password yang diinginkan di form, klik "Generate"
 * 3. Copy hasil hash yang muncul, lalu paste ke kolom password user terkait
 *
 * PENTING: Hapus atau pindahkan file ini setelah selesai dipakai,
 * supaya tidak bisa diakses sembarangan di server production.
 */

$hash = '';
if (isset($_POST['generate'])) {
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_BCRYPT);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Generate Password</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; }
        input[type=text] { width: 100%; padding: 8px; margin: 8px 0; }
        button { padding: 8px 16px; }
        .hasil { background: #f4f4f4; padding: 10px; margin-top: 15px; word-break: break-all; }
    </style>
</head>
<body>
    <h3>Generate Hash Password (bcrypt)</h3>
    <form method="POST">
        <label>Password:</label>
        <input type="text" name="password" required>
        <button type="submit" name="generate">Generate</button>
    </form>

    <?php if ($hash): ?>
    <div class="hasil">
        <strong>Hash hasil:</strong><br>
        <?php echo htmlspecialchars($hash); ?>
    </div>
    <p>Copy hash di atas ke kolom <code>password</code> pada tabel <code>users</code> di phpMyAdmin.</p>
    <?php endif; ?>
</body>
</html>
