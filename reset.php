<?php
require 'koneksi.php';

$password_baru = password_hash('654321', PASSWORD_DEFAULT);

$query = "UPDATE users SET password = '$password_baru' WHERE username IN ('admin', 'siswa')";

if (mysqli_query($koneksi, $query)) {
    echo "<h3>Password berhasil direset!</h3>";
    echo "<p>Password untuk akun <b>admin</b> dan <b>siswa</b> sekarang adalah: <b>654321</b></p>";
    echo "<a href='login.php'>Klik disini untuk Login</a>";
} else {
    echo "Gagal mereset password: " . mysqli_error($koneksi);
}
?>