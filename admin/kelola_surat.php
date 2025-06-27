<?php
// admin/kelola_surat.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: ../login.php'); exit();
}
require_once '../db/koneksi.php';

// Hapus surat
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $q = mysqli_query($conn, "SELECT file_path FROM surat WHERE id=$id");
    if ($r = mysqli_fetch_assoc($q)) {
        $file = '../assets/uploads/' . $r['file_path'];
        if (file_exists($file)) unlink($file);
    }
    mysqli_query($conn, "DELETE FROM surat WHERE id=$id");
    header('Location: kelola_surat.php');
    exit();
}

// Ambil data surat
$surat = mysqli_query($conn, "SELECT s.*, u.nama as uploader FROM surat s LEFT JOIN users u ON s.uploaded_by=u.id ORDER BY s.uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #fff;
            background-image: repeating-linear-gradient(135deg, #f5f5f5 0px, #f5f5f5 2px, transparent 2px, transparent 24px);
            background-size: 24px 24px;
        }
        .bg-blur {
            background: rgba(255,255,255,0.92);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-bottom: 2rem;
        }
        .table thead th {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">SIPAS BGTK - Sistem Informasi Pengarsipan Surat.</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
    <div class="bg-blur">
        <h4>Kelola Surat</h4>
        <a href="upload_surat.php" class="btn btn-success mb-3">Upload Surat Baru</a>
        <a href="export_surat_excel.php" class="btn btn-outline-success mb-3">Unduh Excel</a>
        <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Kegiatan</th>
                    <th>Kategori</th>
                    <th>Kode Surat</th>
                    <th>Laci</th>
                    <th>Lemari</th>
                    <th>Uploader</th>
                    <th>File</th>
                    <th>Rincian Biaya</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
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
                    <td><?= htmlspecialchars($row['uploader']) ?></td>
                    <td>
                        <a href="../assets/uploads/<?= urlencode($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-primary">Preview</a>
                        <a href="../assets/uploads/<?= urlencode($row['file_path']) ?>" download class="btn btn-sm btn-success">Download</a>
                    </td>
                    <td>
                        <?php if (!empty($row['rincian_biaya'])): ?>
                            <a href="../assets/uploads/<?= urlencode($row['rincian_biaya']) ?>" target="_blank" class="btn btn-sm btn-info">Rincian Biaya</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($row['bukti'])): ?>
                            <a href="../assets/uploads/<?= urlencode($row['bukti']) ?>" target="_blank" class="btn btn-sm btn-warning">Bukti</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_surat.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus surat ini?')">Hapus</a>
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
