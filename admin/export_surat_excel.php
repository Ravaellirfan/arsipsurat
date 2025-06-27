<?php
// admin/export_surat_excel.php
require_once '../db/koneksi.php';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daftar_surat.xls");

// Filter jika hanya surat yang sudah di-upload (file_path tidak kosong)
if (isset($_GET['uploaded']) && $_GET['uploaded'] == '1') {
    $result = mysqli_query($conn, "SELECT * FROM surat WHERE file_path IS NOT NULL AND file_path != '' ORDER BY uploaded_at DESC");
} else {
    $result = mysqli_query($conn, "SELECT * FROM surat ORDER BY uploaded_at DESC");
}

echo "<table border='1'>";
echo "<tr><th>Nomor Surat</th><th>Nama Kegiatan</th><th>Kategori</th><th>Kode Surat</th><th>Laci</th><th>Lemari</th><th>Uploader</th><th>Keterangan</th><th>Tanggal Upload</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".htmlspecialchars($row['nomor_surat'])."</td>";
    echo "<td>".htmlspecialchars($row['nama_kegiatan'])."</td>";
    echo "<td>".htmlspecialchars($row['kategori'])."</td>";
    echo "<td>".htmlspecialchars($row['kode_surat'])."</td>";
    echo "<td>".htmlspecialchars($row['nomor_laci'])."</td>";
    echo "<td>".htmlspecialchars($row['nomor_lemari'])."</td>";
    echo "<td>".htmlspecialchars($row['uploaded_by'])."</td>";
    echo "<td>".htmlspecialchars($row['keterangan'])."</td>";
    echo "<td>".htmlspecialchars($row['uploaded_at'])."</td>";
    echo "</tr>";
}
echo "</table>";
?>
<style>
    body {
        background: url('path_to_your_image.jpg') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        backdrop-filter: blur(10px);
    }
    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: rgba(255, 255, 255, 0.8);
    }
    tr:hover {
        background-color: rgba(255, 255, 255, 0.5);
    }
</style>
