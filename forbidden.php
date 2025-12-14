<?php
require_once __DIR__ . '/config/session.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard.php');
    exit();
}

header('HTTP/1.0 403 Forbidden');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - SIPAP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <i class="bi bi-exclamation-triangle" style="font-size: 4rem; color: #dc3545;"></i>
                <h1 class="mt-3">403 - Akses Ditolak</h1>
                <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
