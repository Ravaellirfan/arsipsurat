<?php
// admin/dashboard.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: ../login.php'); exit();
}
include '../db/koneksi.php';
$msg = '';
// Hapus modul
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $q = mysqli_query($conn, "SELECT file_path FROM modul_bgtk WHERE id=$id");
    $f = mysqli_fetch_assoc($q);
    if ($f) {
        $file_path = '../assets/uploads/' . $f['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        mysqli_query($conn, "DELETE FROM modul_bgtk WHERE id=$id");
        $msg = '<div class="alert alert-success">Data berhasil dihapus!</div>';
        // Refresh agar tidak mengulang hapus jika reload
        echo "<script>location.href='dashboard.php';</script>";
        exit();
    }
}
// Edit modul
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $q = mysqli_query($conn, "SELECT * FROM modul_bgtk WHERE id=$id");
    $edit_data = mysqli_fetch_assoc($q);
}
// Update modul
if (isset($_POST['update_modul'])) {
    $id = intval($_POST['id']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $sql = null;
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];
        if (in_array($ext, $allowed)) {
            $newname = uniqid('modul_', true) . '.' . $ext;
            $path = '../assets/uploads/' . $newname;
            if (move_uploaded_file($tmp, $path)) {
                // Hapus file lama
                $q = mysqli_query($conn, "SELECT file_path FROM modul_bgtk WHERE id=$id");
                $f = mysqli_fetch_assoc($q);
                if ($f && file_exists('../assets/uploads/' . $f['file_path'])) {
                    unlink('../assets/uploads/' . $f['file_path']);
                }
                $sql = "UPDATE modul_bgtk SET judul='$judul', deskripsi='$deskripsi', file_path='$newname' WHERE id=$id";
            } else {
                $msg = '<div class="alert alert-danger">Gagal upload file baru.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">File harus berformat PDF/DOC/PPT/XLS.</div>';
        }
    } else {
        $sql = "UPDATE modul_bgtk SET judul='$judul', deskripsi='$deskripsi' WHERE id=$id";
    }
    if ($sql && mysqli_query($conn, $sql)) {
        $msg = '<div class="alert alert-success">Data berhasil diupdate!</div>';
        echo "<script>location.href='dashboard.php';</script>";
        exit();
    } elseif (!$msg) {
        $msg = '<div class="alert alert-danger">Gagal update data: '.mysqli_error($conn).'</div>';
    }
}
// Upload modul
if (isset($_POST['upload_modul'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];
    if (in_array($ext, $allowed)) {
        $newname = uniqid('modul_', true) . '.' . $ext;
        $path = '../assets/uploads/' . $newname;
        if (move_uploaded_file($tmp, $path)) {
            $sql = "INSERT INTO modul_bgtk (judul, deskripsi, file_path, uploaded_at) VALUES ('$judul', '$deskripsi', '$newname', NOW())";
            if (mysqli_query($conn, $sql)) {
                $msg = '<div class="alert alert-success">Modul/Juklas/Juknis berhasil diupload!</div>';
                echo "<script>location.href='dashboard.php';</script>";
                exit();
            } else {
                $msg = '<div class="alert alert-danger">Gagal upload data: '.mysqli_error($conn).'</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Gagal upload file ke server.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">File harus berformat PDF/DOC/PPT/XLS.</div>';
    }
}
$modul = mysqli_query($conn, "SELECT * FROM modul_bgtk ORDER BY id DESC") or die(mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
    <div class="dashboard-card mb-4">
        <h3>Selamat datang, <?php echo $_SESSION['nama']; ?>!</h3>
        <div class="row mt-4">
            <div class="col-md-3">
                <a href="upload_surat.php" class="btn btn-success w-100 mb-2">Upload Surat</a>
            </div>
            <div class="col-md-3">
                <a href="kelola_surat.php" class="btn btn-info w-100 mb-2">Kelola Surat</a>
            </div>
            <div class="col-md-3">
                <a href="tambah_pegawai.php" class="btn btn-warning w-100 mb-2">Tambah Pegawai</a>
            </div>
            <div class="col-md-3">
                <a href="izin_sakit_cuti.php" class="btn btn-secondary w-100 mb-2">Input Izin/Sakit/Cuti</a>
            </div>
        </div>
    </div>
    <div class="dashboard-card mb-4">
        <h4>Upload Informasi Umum, Kegiatan, dan Modul/Juklas/Juknis BGTK</h4>
        <?php echo $msg; ?>
        <?php if ($edit_data): ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($edit_data['judul']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($edit_data['deskripsi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>File (PDF/DOC/PPT/XLS) <small>(Kosongkan jika tidak ingin ganti file)</small></label>
                <input type="file" name="file" class="form-control">
            </div>
            <button type="submit" name="update_modul" class="btn btn-warning">Update</button>
            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
        </form>
        <?php else: ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>File (PDF/DOC/PPT/XLS)</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" name="upload_modul" class="btn btn-primary">Upload</button>
        </form>
        <?php endif; ?>
    </div>
    <div class="dashboard-card">
        <h4>Data Informasi Umum, Kegiatan, dan Modul/Juklas/Juknis BGTK</h4>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
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
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
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
