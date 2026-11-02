<?php
require 'koneksi.php';

// Jika pengguna sudah login, alihkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi Kelengkapan & Konfirmasi Password
    if (empty($nama) || empty($username) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah pernah terdaftar
        $check_user = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error = "Username sudah digunakan, silakan pilih username lain!";
        } else {
            // Hash password secara aman
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Daftarkan user dengan role default 'siswa'
            $query = "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$hashed_password', 'siswa')";
            if (mysqli_query($koneksi, $query)) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Gagal mendaftar: " . mysqli_error($koneksi);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Etalase Digital Stella</title>
    <!-- Framework & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="card card-login p-4 my-4">
    <div class="text-center mb-4">
        <img src="logo.png" alt="Logo Telkom Schools" style="max-height: 60px; width: auto;" class="mb-2">
        <h3 class="fw-bold mt-2">Registrasi Siswa</h3>
        <p class="text-muted small">Buat akun siswa untuk memamerkan karya Anda</p>
    </div>
    <!-- Form register selanjutnya ... -->

    <?php if($error): ?>
        <div class="alert alert-danger py-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error; ?>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success py-2" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= $success; ?>
            <div class="mt-2">
                <a href="login.php" class="btn btn-sm btn-success w-100">Ke Halaman Login</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!$success): ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Buat username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Buat password" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
            </div>
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100 py-2 fw-semibold mb-3">Daftar Sekarang <i class="bi bi-arrow-right-short"></i></button>
    </form>
    <?php endif; ?>

    <div class="text-center pt-2 border-top">
        <span class="text-muted small">Sudah punya akun?</span> 
        <a href="login.php" class="text-primary fw-semibold small text-decoration-none">Login di sini</a>
    </div>
</div>

</body>
</html>