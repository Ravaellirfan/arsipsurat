<?php
// admin/tambah_pegawai.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: ../login.php'); exit();
}
require_once '../db/koneksi.php';

$msg = '';
$pegawai_baru = null;
if (isset($_POST['tambah'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $msg = '<div class="alert alert-danger">Username sudah terdaftar!</div>';
    } else {
        $sql = "INSERT INTO users (username, password, nama, level) VALUES ('$username', '$password', '$nama', '$level')";
        if (mysqli_query($conn, $sql)) {
            $msg = '<div class="alert alert-success">Pengguna berhasil ditambahkan!</div>';
            $pegawai_baru = [
                'username' => $username,
                'nama' => $nama,
                'password' => $password,
                'level' => $level
            ];
        } else {
            $msg = '<div class="alert alert-danger">Gagal menambah pengguna.</div>';
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE id=$id AND level='pegawai'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM users WHERE id=$id AND level='pegawai'");
        $msg = '<div class="alert alert-success">Pegawai berhasil dihapus!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Pegawai tidak ditemukan atau bukan pegawai!"></div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai/Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1565c0;
        }
        .tambah-card {
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
    <a class="navbar-brand fw-bold" href="dashboard.php">SIPAS BGTK - Sistem Informasi Pengarsipan Surat.</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
    <div class="tambah-card">
        <h4>Tambah Pegawai/Admin</h4>
        <?php echo $msg; ?>
        <form method="post">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Level</label>
                <select name="level" class="form-control" required>
                    <option value="pegawai">Pegawai</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </form>
        <?php if ($pegawai_baru): ?>
        <div class="alert alert-info mt-3">
            <strong>Pengguna baru berhasil ditambahkan:</strong><br>
            Username: <b><?= htmlspecialchars($pegawai_baru['username']) ?></b><br>
            Nama: <b><?= htmlspecialchars($pegawai_baru['nama']) ?></b><br>
            Password: <b><?= htmlspecialchars($pegawai_baru['password']) ?></b><br>
            Level: <b><?= htmlspecialchars($pegawai_baru['level']) ?></b>
        </div>
        <?php endif; ?>
        <hr>
        <h5>Daftar Pengguna</h5>
        <a href="export_pegawai_excel.php" class="btn btn-outline-success btn-sm mb-2">Unduh Excel</a>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $q = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
            while($row = mysqli_fetch_assoc($q)):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['level']) ?></td>
                <td>
                    <a href="edit_pegawai.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="tambah_pegawai.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
