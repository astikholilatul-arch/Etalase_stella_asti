<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses khusus Admin!");
}

if (isset($_POST['tambah_siswa'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($koneksi, "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$password', 'siswa')");
    header("Location: kelola_siswa.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id_siswa = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id_siswa' AND role = 'siswa'");
    header("Location: kelola_siswa.php");
    exit();
}

// Reset Password Siswa oleh Admin
if (isset($_GET['reset_id'])) {
    $id_siswa = $_GET['reset_id'];
    $password_baru = password_hash('654321', PASSWORD_DEFAULT); // Default reset jadi 654321
    
    mysqli_query($koneksi, "UPDATE users SET password = '$password_baru' WHERE id = '$id_siswa' AND role = 'siswa'");
    header("Location: kelola_siswa.php");
    exit();
}

$siswa_query = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'siswa' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Siswa - Admin</title>
    <!-- Framework & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/kelola_siswa.css">
</head>
<body>


<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</nav>

<div class="container">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom p-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-person-plus-fill text-primary"></i> Tambah Siswa</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Nama Siswa">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Password">
                    </div>
                    <button type="submit" name="tambah_siswa" class="btn btn-primary w-100"><i class="bi bi-person-check-fill"></i> Simpan Data Siswa</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-custom p-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-people-fill text-dark"></i> Daftar Akun Siswa</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($siswa_query)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><b><?= htmlspecialchars($row['nama']); ?></b></td>
                                <td><code><?= htmlspecialchars($row['username']); ?></code></td>
                                <td>
                                    <a href="kelola_siswa.php?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus akun siswa ini beserta karya yang diunggah?')"><i class="bi bi-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <td>
                           <a href="kelola_siswa.php?reset_id=<?= $row['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Reset password siswa ini menjadi 654321?')"><i class="bi bi-key"></i> Reset Password</a>
                           <a href="kelola_siswa.php?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus akun siswa ini?')"><i class="bi bi-trash"></i> Hapus</a>
                        </td>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>