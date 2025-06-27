<?php
// admin/upload_surat.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: ../login.php'); exit();
}
require_once '../db/koneksi.php';

$msg = '';
if (isset($_POST['upload'])) {
    $nomor_surat = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $nama_kegiatan = mysqli_real_escape_string($conn, $_POST['nama_kegiatan']);
    $nomor_laci = mysqli_real_escape_string($conn, $_POST['nomor_laci']);
    $nomor_lemari = mysqli_real_escape_string($conn, $_POST['nomor_lemari']);
    $kode_surat = mysqli_real_escape_string($conn, $_POST['kode_surat']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed = ['pdf'];
    $rincian_biaya = null;
    $bukti = null;
    // Upload file utama
    if (in_array($ext, $allowed)) {
        $newname = uniqid('surat_', true) . '.' . $ext;
        $path = '../assets/uploads/' . $newname;
        if (move_uploaded_file($tmp, $path)) {
            // Upload rincian biaya jika ada
            if (!empty($_FILES['rincian_biaya']['name'])) {
                $rb_ext = strtolower(pathinfo($_FILES['rincian_biaya']['name'], PATHINFO_EXTENSION));
                if (in_array($rb_ext, $allowed)) {
                    $rb_name = uniqid('rincian_', true) . '.' . $rb_ext;
                    $rb_path = '../assets/uploads/' . $rb_name;
                    if (move_uploaded_file($_FILES['rincian_biaya']['tmp_name'], $rb_path)) {
                        $rincian_biaya = $rb_name;
                    }
                }
            }
            // Upload bukti jika ada
            if (!empty($_FILES['bukti']['name'])) {
                $b_ext = strtolower(pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION));
                if (in_array($b_ext, $allowed)) {
                    $b_name = uniqid('bukti_', true) . '.' . $b_ext;
                    $b_path = '../assets/uploads/' . $b_name;
                    if (move_uploaded_file($_FILES['bukti']['tmp_name'], $b_path)) {
                        $bukti = $b_name;
                    }
                }
            }
            $sql = "INSERT INTO surat (nomor_surat, nama_kegiatan, nomor_laci, nomor_lemari, kode_surat, kategori, keterangan, file_path, rincian_biaya, bukti, uploaded_by) VALUES ('$nomor_surat', '$nama_kegiatan', '$nomor_laci', '$nomor_lemari', '$kode_surat', '$kategori', '$keterangan', '$newname', " . ($rincian_biaya ? "'$rincian_biaya'" : 'NULL') . ", " . ($bukti ? "'$bukti'" : 'NULL') . ", '{$_SESSION['user_id']}')";
            if (mysqli_query($conn, $sql)) {
                $msg = '<div class="alert alert-success">Surat berhasil di-upload!</div>';
            } else {
                $msg = '<div class="alert alert-danger">Gagal upload surat.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Gagal upload file.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">File harus berformat PDF.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1565c0;
        }
        .bg-blur {
            background: rgba(255,255,255,0.92);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-bottom: 2rem;
        }
        .navbar, .navbar.bg-primary, .navbar.navbar-dark.bg-primary {
            background-color: #1565c0 !important;
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
<div class="container mt-4 bg-blur">
    <h4>Upload Surat</h4>
    <?php echo $msg; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nomor Laci</label>
            <input type="text" name="nomor_laci" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nomor Lemari</label>
            <input type="text" name="nomor_lemari" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kode Surat</label>
            <input type="text" name="kode_surat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="SPT">SPT</option>
                <option value="LPJ">LPJ</option>
                <option value="SPPD">SPPD</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Cuti">Cuti</option>
                <option value="SK">SK</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>File Surat (PDF)</label>
            <input type="file" name="file" class="form-control" accept="application/pdf" required>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="adaRincian" onclick="toggleRincian()">
            <label class="form-check-label" for="adaRincian">Ada Rincian Biaya</label>
        </div>
        <div class="mb-3" id="rincianField" style="display:none;">
            <label>Rincian Biaya (PDF, opsional)</label>
            <input type="file" name="rincian_biaya" class="form-control" accept="application/pdf">
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="adaBukti" onclick="toggleBukti()">
            <label class="form-check-label" for="adaBukti">Ada Bukti</label>
        </div>
        <div class="mb-3" id="buktiField" style="display:none;">
            <label>Bukti (PDF, opsional)</label>
            <input type="file" name="bukti" class="form-control" accept="application/pdf">
        </div>
        <button type="submit" name="upload" class="btn btn-success">Upload</button>
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        <a href="export_surat_excel.php?uploaded=1" class="btn btn-outline-success ms-2">Unduh Excel Surat Terupload</a>
    </form>
</div>
<script>
function toggleRincian() {
    document.getElementById('rincianField').style.display = document.getElementById('adaRincian').checked ? 'block' : 'none';
}
function toggleBukti() {
    document.getElementById('buktiField').style.display = document.getElementById('adaBukti').checked ? 'block' : 'none';
}
</script>
</body>
</html>
