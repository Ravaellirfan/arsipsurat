<?php
// index.php
session_start();
if (isset($_SESSION['level'])) {
    if ($_SESSION['level'] == 'admin') {
        header('Location: admin/dashboard.php');
        exit();
    } elseif ($_SESSION['level'] == 'pegawai') {
        header('Location: pegawai/dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Surat Kepegawaian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #fff;
            background-image: repeating-linear-gradient(135deg, #f5f5f5 0px, #f5f5f5 2px, transparent 2px, transparent 24px);
            background-size: 24px 24px;
        }
        .hero {
            margin-top: 10vh;
            background: rgba(255,255,255,0.95);
            border-radius: 1rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
        }
        .btn-custom {
            background: #2575fc;
            color: #fff;
            border-radius: 2rem;
            font-weight: bold;
            transition: 0.2s;
        }
        .btn-custom:hover {
            background: #6a11cb;
            color: #fff;
        }
        .logo {
            width: 80px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-7">
        <div class="p-5 text-center hero">
            <img src="logo.jpg" alt="Logo bgtk" class="logo">
            <h1 class="mb-3 fw-bold">SIPAS BGTK - Sistem Informasi Pengarsipan Surat.</h1>
            <p class="mb-4">Sistem pengarsipan surat kepegawaian digital, aman, dan mudah diakses.</p>
            <a href="/arsipsurat/login.php" class="btn btn-custom btn-lg px-5">Login</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
