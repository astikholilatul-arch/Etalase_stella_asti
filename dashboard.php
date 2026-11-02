<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'];

// Tangkap keyword pencarian jika ada
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, trim($_GET['cari'])) : '';

// Query dengan filter pencarian berdasarkan judul project atau nama siswa
if ($cari !== '') {
    $query = "SELECT projects.*, users.nama FROM projects JOIN users ON projects.user_id = users.id WHERE projects.judul LIKE '%$cari%' OR users.nama LIKE '%$cari%' ORDER BY projects.id DESC";
} else {
    $query = "SELECT projects.*, users.nama FROM projects JOIN users ON projects.user_id = users.id ORDER BY projects.id DESC";
}

$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Etalase Digital Stella</title>
    <!-- Framework & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <!-- Mengganti navbar brand dengan logo gambar -->
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="dashboard.php">
            <img src="logo.png" alt="Logo" style="height: 35px; width: auto;">
            <span>Etalase Stella</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-grid-fill"></i> Galeri Karya</a></li>
                <?php if($role === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="kelola_siswa.php"><i class="bi bi-people-fill"></i> Kelola Siswa</a></li>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center text-white gap-3">
                <span>Halo, <b><?= htmlspecialchars($_SESSION['nama']); ?></b> <span class="badge bg-primary text-uppercase"><?= $role; ?></span></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-5">
    <!-- Bagian Atas: Judul & Form Pencarian / Tombol Tambah -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-5">
            <h2 class="fw-bold mb-1">Galeri Project Siswa</h2>
            <p class="text-muted mb-0">Kumpulan karya dan pameran digital siswa Stella</p>
        </div>
        <div class="col-md-7">
            <form method="GET" action="dashboard.php" class="d-flex gap-2 justify-content-md-end align-items-center">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="cari" class="form-control" placeholder="cari karya/nama project" value="<?= htmlspecialchars($cari); ?>">
                    <?php if($cari !== ''): ?>
                        <a href="dashboard.php" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="tambah.php" class="btn btn-success rounded-pill px-4 text-nowrap"><i class="bi bi-plus-lg me-1"></i> Tambah Project</a>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4">
                    <!-- Card diklik akan memicu Modal Popup berdasarkan ID project -->
                    <div class="card h-100 project-card" data-bs-toggle="modal" data-bs-target="#modalProject<?= $row['id']; ?>">
                        <img src="uploads/<?= $row['gambar']; ?>" class="card-img-top project-img" alt="Gambar Project">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($row['judul']); ?></h5>
                            <p class="card-text text-muted small mb-2"><i class="bi bi-person-circle"></i> Oleh: <b><?= htmlspecialchars($row['nama']); ?></b></p>
                            <p class="card-text flex-grow-1"><?= nl2br(htmlspecialchars(substr($row['deskripsi'], 0, 120))); ?>...</p>
                            
                            <div class="text-primary small fw-semibold mt-2">
                                <i class="bi bi-arrows-fullscreen"></i> Klik untuk lihat detail
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal / Popup Detail Project -->
                <div class="modal fade" id="modalProject<?= $row['id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title fw-bold" id="modalLabel<?= $row['id']; ?>"><i class="bi bi-journal-code text-primary me-2"></i><?= htmlspecialchars($row['judul']); ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <img src="uploads/<?= $row['gambar']; ?>" class="img-fluid rounded-3 shadow-sm w-100 object-fit-cover" style="max-height: 300px;" alt="Gambar Project">
                                    </div>
                                    <div class="col-md-6 d-flex flex-column justify-content-between">
                                        <div>
                                            <p class="text-muted small mb-2"><i class="bi bi-person-circle"></i> Pembuat: <b><?= htmlspecialchars($row['nama']); ?></b></p>
                                            <p class="text-muted small mb-3"><i class="bi bi-calendar-event"></i> Dibuat pada: <?= date('d M Y', strtotime($row['created_at'])); ?></p>
                                            <h6 class="fw-bold">Deskripsi Lengkap:</h6>
                                            <p class="text-secondary" style="white-space: pre-line;"><?= htmlspecialchars($row['deskripsi']); ?></p>
                                        </div>

                                        <div class="d-flex gap-2 mt-3">
                                            <?php if($row['link_pendukung']): ?>
                                                <a href="<?= $row['link_pendukung']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-link-45deg"></i> Pendukung</a>
                                            <?php endif; ?>
                                            <?php if($row['link_demo']): ?>
                                                <a href="<?= $row['link_demo']; ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-play-circle"></i> Lihat Demo</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <!-- Hak Akses Edit/Hapus dalam Popup -->
                                    <?php if ($role === 'admin' || $row['user_id'] == $user_id): ?>
                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm text-dark fw-semibold"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm fw-semibold" onclick="return confirm('Apakah Anda yakin ingin menghapus project ini?')"><i class="bi bi-trash"></i> Hapus</a>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Akhir Modal -->

            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-folder-x text-muted display-1"></i>
                <p class="text-muted mt-3">Tidak ada project atau karya yang ditemukan.</p>
                <?php if($cari !== ''): ?>
                    <a href="dashboard.php" class="btn btn-sm btn-outline-primary mt-2">Reset Pencarian</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>