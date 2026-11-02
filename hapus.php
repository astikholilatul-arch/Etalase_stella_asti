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

// Cek Hak Akses Hapus
if ($_SESSION['role'] === 'admin' || $project['user_id'] == $_SESSION['user_id']) {
    if (file_exists('uploads/' . $project['gambar'])) {
        unlink('uploads/' . $project['gambar']);
    }
    mysqli_query($koneksi, "DELETE FROM projects WHERE id = '$id'");
}

header("Location: dashboard.php");
exit();
?>