<?php
// pegawai/view_surat.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'pegawai') {
    header('Location: ../login.php'); exit();
}
require_once '../db/koneksi.php';

$search = '';
$where = '';
if (isset($_GET['cari'])) {
    $search = mysqli_real_escape_string($conn, $_GET['kode_surat']);
    $where = "WHERE kode_surat LIKE '%$search%'";
}
$surat = mysqli_query($conn, "SELECT s.*, u.nama as uploader FROM surat s LEFT JOIN users u ON s.uploaded_by=u.id $where ORDER BY s.uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1565c0;
        }
        .view-card {
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
    <div class="view-card">
        <h4>Daftar Surat</h4>
        <form class="row g-3 mb-3" method="get">
            <div class="col-auto">
                <input type="text" name="kode_surat" class="form-control" placeholder="Cari kode surat..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" name="cari" class="btn btn-primary">Cari</button>
            </div>
        </form>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Kegiatan</th>
                    <th>Kategori</th>
                    <th>Kode Surat</th>
                    <th>Laci</th>
                    <th>Lemari</th>
                    <th>Keterangan</th>
                    <th>Uploader</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
            <?php $no=1; while($row = mysqli_fetch_assoc($surat)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nomor_surat']) ?></td>
                    <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= htmlspecialchars($row['kode_surat']) ?></td>
                    <td><?= htmlspecialchars($row['nomor_laci']) ?></td>
                    <td><?= htmlspecialchars($row['nomor_lemari']) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= htmlspecialchars($row['uploader']) ?></td>
                    <td>
                        <a href="../assets/uploads/<?= urlencode($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-primary">Preview</a>
                        <a href="../assets/uploads/<?= urlencode($row['file_path']) ?>" download class="btn btn-sm btn-success">Download</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <a href="dashboard.php" class="btn btn-secondary mt-2">Kembali</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
