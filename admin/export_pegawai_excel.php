<?php
// admin/export_pegawai_excel.php
require_once '../db/koneksi.php';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daftar_pegawai.xls");

$result = mysqli_query($conn, "SELECT username, nama FROM users WHERE level='pegawai' ORDER BY id DESC");

echo "<table border='1'>";
echo "<tr><th>Username</th><th>Nama Pegawai</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".htmlspecialchars($row['username'])."</td>";
    echo "<td>".htmlspecialchars($row['nama'])."</td>";
    echo "</tr>";
}
echo "</table>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Pegawai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            backdrop-filter: blur(10px);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        th {
            background-color: rgba(255, 255, 255, 0.2);
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
</body>
</html>
