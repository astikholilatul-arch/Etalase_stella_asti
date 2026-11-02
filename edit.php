<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);
$project = mysqli_fetch_assoc($result);

if ($_SESSION['role'] !== 'admin' && $project['user_id'] != $_SESSION['user_id']) {
    die("Akses ditolak! Anda tidak berhak mengedit project ini.");
}

if (isset($_POST['update'])) {
    $judul          = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi      = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $link_pendukung = mysqli_real_escape_string($koneksi, $_POST['link_pendukung']);
    $link_demo      = mysqli_real_escape_string($koneksi, $_POST['link_demo']);

    if ($_FILES['gambar']['name'] != '') {
        $filename = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $ext      = pathinfo($filename, PATHINFO_EXTENSION);
        $gambar   = time() . '.' . $ext;
        move_uploaded_file($tmp_name, 'uploads/' . $gambar);
        
        if(file_exists('uploads/'.$project['gambar'])) {
            unlink('uploads/'.$project['gambar']);
        }
    } else {
        $gambar = $project['gambar'];
    }

    $update_query = "UPDATE projects SET 
                     judul='$judul', deskripsi='$deskripsi', gambar='$gambar', 
                     link_pendukung='$link_pendukung', link_demo='$link_demo' 
                     WHERE id='$id'";

    if (mysqli_query($koneksi, $update_query)) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Project - Stella</title>
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
                <h3 class="fw-bold mb-4"><i class="bi bi-pencil-square text-warning"></i> Edit Project Karya</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Project</label>
                        <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($project['judul']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Project</label>
                        <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($project['deskripsi']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">Gambar Saat Ini</label>
                        <img src="uploads/<?= $project['gambar']; ?>" class="img-thumbnail preview-img mb-2">
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">* Biarkan kosong jika tidak ingin mengganti gambar.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Pendukung</label>
                        <input type="url" name="link_pendukung" class="form-control" value="<?= htmlspecialchars($project['link_pendukung']); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Link Demo (Opsional)</label>
                        <input type="url" name="link_demo" class="form-control" value="<?= htmlspecialchars($project['link_demo']); ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="update" class="btn btn-warning px-4"><i class="bi bi-save"></i> Update Project</button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>