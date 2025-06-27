<?php
// admin/edit_pegawai.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: ../login.php'); exit();
}
require_once '../db/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: tambah_pegawai.php'); exit();
}
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM users WHERE id=$id AND level='pegawai'");
if (!$row = mysqli_fetch_assoc($q)) {
    header('Location: tambah_pegawai.php'); exit();
}

$msg = '';
if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $set_pass = $password ? ", password='$password'" : '';
    $sql = "UPDATE users SET username='$username', nama='$nama' $set_pass WHERE id=$id AND level='pegawai'";
    if (mysqli_query($conn, $sql)) {
        $msg = '<div class="alert alert-success">Data pegawai berhasil diupdate!</div>';
        $row['username'] = $username;
        $row['nama'] = $nama;
        if ($password) $row['password'] = $password;
    } else {
        $msg = '<div class="alert alert-danger">Gagal update data pegawai.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1565c0;
        }
        .edit-card {
            background: rgba(255,255,255,0.93);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .navbar, .navbar.bg-primary, .navbar.navbar-dark.bg-primary {
            background-color: #1565c0 !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">SIPAS BGTK - Sistem Informasi Pengarsipan Surat.
</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
    <div class="edit-card">
        <h4>Edit Pegawai</h4>
        <?php echo $msg; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="tambah_pegawai.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
