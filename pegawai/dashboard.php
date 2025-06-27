<?php
// pegawai/dashboard.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'pegawai') {
    header('Location: ../login.php'); exit();
}
include '../db/koneksi.php'; // Pastikan path koneksi benar
// Ambil daftar modul/juklas juknis dari database
$modul = mysqli_query($conn, "SELECT * FROM modul_bgtk ORDER BY id DESC") or die(mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1565c0;
        }
        .dashboard-card {
            background: rgba(255,255,255,0.93);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .centered-card {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 30vh;
        }
        .centered-content {
            text-align: center;
        }
        .btn-info {
            background: #00c6ff;
            border: none;
        }
        .btn-info:hover {
            background: #0072ff;
        }
        .navbar, .navbar.bg-primary, .navbar.navbar-dark.bg-primary {
            background-color: #1565c0 !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">SIPAS BGTK - Sistem Informasi Pengarsipan Surat.</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
    <div class="dashboard-card mb-4 centered-card">
        <div class="centered-content w-100">
            <h3 class="fw-bold mb-4">Selamat datang, <?php echo $_SESSION['nama']; ?>!</h3>
            <a href="view_surat.php" class="btn btn-info btn-lg px-5">Lihat Surat</a>
        </div>
    </div>
    <div class="dashboard-card">
        <h4 class="text-center mb-4">Informasi Umum, Kegiatan, dan Modul/Juklas/Juknis BGTK</h4>
        <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Tanggal Upload</th>
                </tr>
            </thead>
            <tbody>
            <?php $no=1; while($row = mysqli_fetch_assoc($modul)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><a href="../assets/uploads/<?= urlencode($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-primary">Baca/Download</a></td>
                    <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<!-- Embed YouTube Channel Video -->
<div class="container mt-4 mb-4">
    <div class="dashboard-card">
        <h5 class="text-center mb-3">Video YouTube BGTK Lampung</h5>
        <div class="ratio ratio-16x9">
            <iframe src="https://www.youtube.com/embed/wdFlP2BKXlc" title="YouTube BGTK Lampung" allowfullscreen></iframe>
        </div>
        <div class="text-center mt-2">
            <a href="https://youtube.com/@bgtklampung?si=ebVsQ_plcxbdHuIE" target="_blank" class="btn btn-danger">Kunjungi Channel YouTube BGTK Lampung</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
