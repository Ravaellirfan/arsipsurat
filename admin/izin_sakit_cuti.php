<?php
// admin/izin_sakit_cuti.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: ../login.php'); exit();
}
require_once '../db/koneksi.php';

$msg = '';
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $sql = "INSERT INTO izin_sakit_cuti (nama, jabatan, nip, keterangan, kategori, created_at) VALUES ('$nama', '$jabatan', '$nip', '$keterangan', '$kategori', NOW())";
    if (mysqli_query($conn, $sql)) {
        $msg = '<div class="alert alert-success">Data berhasil disimpan!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Gagal menyimpan data: '.mysqli_error($conn).'</div>';
    }
}
// Ambil data untuk ditampilkan
$data = mysqli_query($conn, "SELECT * FROM izin_sakit_cuti ORDER BY created_at DESC") or die(mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Izin/Sakit/Cuti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1565c0;
        }
        .izin-card {
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
    <div class="izin-card mb-4">
        <h4>Input Izin/Sakit/Cuti</h4>
        <?php echo $msg; ?>
        <form method="post">
            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Cuti">Cuti</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Jabatan</label>
                <input type="text" name="jabatan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>NIP</label>
                <input type="text" name="nip" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" required></textarea>
            </div>
            <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            <a href="export_izin_excel.php" class="btn btn-outline-success float-end">Download Excel</a>
        </form>
    </div>
    <div class="izin-card">
        <h4>Data Izin/Sakit/Cuti</h4>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>NIP</th>
                    <th>Keterangan</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
            <?php $no=1; while($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['jabatan']) ?></td>
                    <td><?= htmlspecialchars($row['nip']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['keterangan'])) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
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
