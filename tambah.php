<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $user_id        = $_SESSION['user_id'];
    $judul          = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi      = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $link_pendukung = mysqli_real_escape_string($koneksi, $_POST['link_pendukung']);
    $link_demo      = mysqli_real_escape_string($koneksi, $_POST['link_demo']);

    $filename = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $ext      = pathinfo($filename, PATHINFO_EXTENSION);
    $newname  = time() . '.' . $ext;

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (move_uploaded_file($tmp_name, 'uploads/' . $newname)) {
        $query = "INSERT INTO projects (user_id, judul, deskripsi, gambar, link_pendukung, link_demo) 
                  VALUES ('$user_id', '$judul', '$deskripsi', '$newname', '$link_pendukung', '$link_demo')";
        
        if (mysqli_query($koneksi, $query)) {
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Project - Stella</title>
    <!-- Framework & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-form p-4">
                <h3 class="fw-bold mb-4"><i class="bi bi-file-earmark-plus text-success"></i> Tambah Project Baru</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Project</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Aplikasi Kasir Cafe Stella" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Project</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan fitur utama dan teknologi yang digunakan..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Gambar (Preview / Mockup)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Pendukung (GitHub / Drive)</label>
                        <input type="url" name="link_pendukung" class="form-control" placeholder="https://github.com/...">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Link Demo / Video (Opsional)</label>
                        <input type="url" name="link_demo" class="form-control" placeholder="https://youtube.com/...">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="submit" class="btn btn-success px-4"><i class="bi bi-check-lg"></i> Simpan Project</button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>