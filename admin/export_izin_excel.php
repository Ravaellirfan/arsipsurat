<?php
// admin/export_izin_excel.php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: ../login.php'); exit();
}
require_once '../db/koneksi.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=izin_sakit_cuti_".date('Ymd_His').".xls");

echo "<table border='1'>";
echo "<tr><th>No</th><th>Kategori</th><th>Nama</th><th>Jabatan</th><th>NIP</th><th>Keterangan</th><th>Tanggal Input</th></tr>";

$data = mysqli_query($conn, "SELECT * FROM izin_sakit_cuti ORDER BY created_at DESC") or die(mysqli_error($conn));
$no = 1;
while($row = mysqli_fetch_assoc($data)) {
    echo "<tr>";
    echo "<td>".$no++."</td>";
    echo "<td>".htmlspecialchars($row['kategori'])."</td>";
    echo "<td>".htmlspecialchars($row['nama'])."</td>";
    echo "<td>".htmlspecialchars($row['jabatan'])."</td>";
    echo "<td>".htmlspecialchars($row['nip'])."</td>";
    echo "<td>".nl2br(htmlspecialchars($row['keterangan']))."</td>";
    echo "<td>".htmlspecialchars($row['created_at'])."</td>";
    echo "</tr>";
}
echo "</table>";
exit;
?>
