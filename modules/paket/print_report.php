<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

// Only allow admin role
requireRole('admin');

$page_title = 'Cetak Laporan Penerimaan Paket';

// Get filter parameters
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$status_filter = $_GET['status'] ?? '';

// Build query
$query = "SELECT p.id, p.nomor_paket, ph.nomor_unit, u.nama_lengkap, ph.nomor_hp,
                 p.nama_pengirim, p.nama_ekspedisi, p.nomor_loker, p.status, 
                 p.tanggal_terima, p.tanggal_diambil
          FROM paket p
          JOIN penghuni ph ON p.penghuni_id = ph.id
          JOIN users u ON ph.user_id = u.id
          WHERE DATE(p.tanggal_terima) BETWEEN ? AND ?";

$params = [$start_date, $end_date];
$types = 'ss';

if ($status_filter && in_array($status_filter, ['disimpan', 'diambil'])) {
    $query .= " AND p.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$query .= " ORDER BY p.tanggal_terima DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Calculate statistics and store data
$stats = [
    'total' => 0,
    'disimpan' => 0,
    'diambil' => 0
];
$data_rows = [];

while ($row = $result->fetch_assoc()) {
    $stats['total']++;
    $stats[$row['status']]++;
    $data_rows[] = $row;
}

// Check if print mode
$print_mode = ($_GET['print'] ?? '') === '1';
?>
<?php if (!$print_mode): ?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 style="font-weight: bold; color: #333; margin-bottom: 0.5rem;">
                    <i class="bi bi-printer-fill" style="font-size: 2rem; color: #dc3545;"></i> Cetak Laporan Penerimaan Paket
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Kelola dan cetak laporan penerimaan paket sistem AureliaBox</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-secondary" style="padding: 0.6rem 1.5rem;">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- Filter Form -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-dark text-white" style="border-bottom: 2px solid #555;">
                <h5 class="mb-0"><i class="bi bi-funnel-fill"></i> Filter Data</h5>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-bold" style="font-size: 0.95rem;">Dari Tanggal</label>
                        <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" 
                               value="<?php echo htmlspecialchars($start_date); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-bold" style="font-size: 0.95rem;">Sampai Tanggal</label>
                        <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" 
                               value="<?php echo htmlspecialchars($end_date); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-bold" style="font-size: 0.95rem;">Status</label>
                        <select class="form-select form-select-lg" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="disimpan" <?php echo $status_filter === 'disimpan' ? 'selected' : ''; ?>>Disimpan</option>
                            <option value="diambil" <?php echo $status_filter === 'diambil' ? 'selected' : ''; ?>>Diambil</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100" style="font-weight: bold;">
                            <i class="bi bi-search"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-0 shadow-sm h-100" style="transition: transform 0.2s; border-left: 4px solid #007bff;">
                    <div class="card-body text-center">
                        <div style="font-size: 2.5rem; color: #007bff; font-weight: bold; margin-bottom: 0.5rem;">
                            <?php echo $stats['total']; ?>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 1rem; font-weight: 500;">Total Paket</p>
                        <small class="text-muted d-block mt-2">Data dari periode terpilih</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-0 shadow-sm h-100" style="transition: transform 0.2s; border-left: 4px solid #17a2b8;">
                    <div class="card-body text-center">
                        <div style="font-size: 2.5rem; color: #17a2b8; font-weight: bold; margin-bottom: 0.5rem;">
                            <?php echo $stats['disimpan']; ?>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 1rem; font-weight: 500;">Disimpan</p>
                        <small class="text-muted d-block mt-2">Dalam penyimpanan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-0 shadow-sm h-100" style="transition: transform 0.2s; border-left: 4px solid #28a745;">
                    <div class="card-body text-center">
                        <div style="font-size: 2.5rem; color: #28a745; font-weight: bold; margin-bottom: 0.5rem;">
                            <?php echo $stats['diambil']; ?>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 1rem; font-weight: 500;">Diambil</p>
                        <small class="text-muted d-block mt-2">Sudah diambil pengguna</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="mb-4">
            <a href="?start_date=<?php echo urlencode($start_date); ?>&end_date=<?php echo urlencode($end_date); ?>&status=<?php echo urlencode($status_filter); ?>&print=1" 
               class="btn btn-success btn-lg" target="_blank" style="padding: 0.7rem 2rem; font-weight: bold; font-size: 1.05rem;">
                <i class="bi bi-file-earmark-pdf"></i> Cetak Laporan PDF
            </a>
        </div>

        <!-- Table Preview -->
        <div class="card border-0 shadow">
            <div class="card-header bg-dark text-white" style="border-bottom: 2px solid #555; padding: 1.5rem;">
                <h5 class="mb-0"><i class="bi bi-table"></i> Preview Data Laporan</h5>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <?php if (count($data_rows) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover" style="margin-bottom: 0; border-collapse: separate; border-spacing: 0;">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr>
                                <th style="padding: 1rem; font-weight: bold; text-align: center; width: 5%;">No.</th>
                                <th style="padding: 1rem; font-weight: bold;">No. Paket</th>
                                <th style="padding: 1rem; font-weight: bold;">Unit</th>
                                <th style="padding: 1rem; font-weight: bold;">Penghuni</th>
                                <th style="padding: 1rem; font-weight: bold;">Pengirim</th>
                                <th style="padding: 1rem; font-weight: bold;">Ekspedisi</th>
                                <th style="padding: 1rem; font-weight: bold; text-align: center;">Penyimpanan</th>
                                <th style="padding: 1rem; font-weight: bold; text-align: center;">Status</th>
                                <th style="padding: 1rem; font-weight: bold;">Tgl Terima</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data_rows as $row):
                            ?>
                            <tr style="border-bottom: 1px solid #dee2e6; transition: background-color 0.2s;">
                                <td style="padding: 1rem; text-align: center; color: #666;"><?php echo $no++; ?></td>
                                <td style="padding: 1rem;"><strong><?php echo htmlspecialchars($row['nomor_paket']); ?></strong></td>
                                <td style="padding: 1rem;"><?php echo htmlspecialchars($row['nomor_unit']); ?></td>
                                <td style="padding: 1rem;"><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td style="padding: 1rem;"><?php echo htmlspecialchars($row['nama_pengirim']); ?></td>
                                <td style="padding: 1rem;"><?php echo htmlspecialchars($row['nama_ekspedisi']); ?></td>
                                <td style="padding: 1rem; text-align: center;">
                                    <?php
                                    if ($row['nomor_loker'] === 'WAREHOUSE') {
                                        echo '<span class="badge" style="background-color: #0066cc; padding: 0.5rem 0.75rem; font-size: 0.85rem;"><i class="bi bi-building"></i> ' . htmlspecialchars($row['nomor_loker']) . '</span>';
                                    } else {
                                        echo '<span class="badge bg-info" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">' . htmlspecialchars($row['nomor_loker']) . '</span>';
                                    }
                                    ?>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <?php
                                    $status_badges = [
                                        'diterima' => '<span class="badge bg-warning" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">Diterima</span>',
                                        'disimpan' => '<span class="badge bg-info" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">Disimpan</span>',
                                        'diambil' => '<span class="badge bg-success" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">Diambil</span>'
                                    ];
                                    echo $status_badges[$row['status']] ?? '<span class="badge bg-secondary" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">Unknown</span>';
                                    ?>
                                </td>
                                <td style="padding: 1rem;"><?php echo date('d M Y H:i', strtotime($row['tanggal_terima'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info m-0">
                    <i class="bi bi-info-circle"></i> Tidak ada data paket sesuai dengan filter yang dipilih.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<?php else: ?>
<!-- PRINT MODE -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penerimaan Paket - AureliaBox</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .header p {
            font-size: 12px;
            margin: 3px 0;
            opacity: 0.95;
        }
        .apartment-name {
            font-size: 13px;
            font-weight: 600;
            margin-top: 8px;
            border-top: 2px solid rgba(255,255,255,0.3);
            padding-top: 8px;
        }
        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            background-color: #f5f5f5;
            padding: 12px 15px;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        .info-section div {
            flex: 1;
        }
        .info-section strong {
            display: inline-block;
            width: 140px;
            font-weight: 600;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table thead {
            background-color: #667eea;
            color: white;
        }
        table th {
            border: 2px solid #333;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        table td {
            border: 1px solid #333;
            padding: 8px 10px;
            font-size: 10px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        table tbody tr:hover {
            background-color: #f0f0f0;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warehouse {
            background-color: #0066cc;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #667eea;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .stats {
            margin: 25px 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .stat-box {
            border: 2px solid #667eea;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }
        .stat-box h4 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #667eea;
        }
        .stat-box p {
            font-size: 11px;
            color: #555;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            a[href]::after {
                content: "" !important;
                display: none !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-size: 28px; margin-bottom: 8px;">📦</div>
        <h1>LAPORAN PENERIMAAN PAKET</h1>
        <div class="apartment-name">THE GRAND AURELIA RESIDENCE</div>
        <p style="margin-top: 10px;">Periode: <?php echo date('d M Y', strtotime($start_date)); ?> - <?php echo date('d M Y', strtotime($end_date)); ?></p>
        <?php if ($status_filter): ?>
        <p>Status Filter: <strong><?php echo ucfirst($status_filter); ?></strong></p>
        <?php endif; ?>
    </div>

    <div class="info-section">
        <div>
            <strong>Tanggal Cetak:</strong> <?php echo date('d M Y H:i', time()); ?>
        </div>
        <div>
            <strong>Total Data:</strong> <?php echo $stats['total']; ?> Paket
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stat-box">
            <h4><?php echo $stats['total']; ?></h4>
            <p>Total Paket</p>
        </div>
        <div class="stat-box">
            <h4><?php echo $stats['disimpan']; ?></h4>
            <p>Disimpan</p>
        </div>
        <div class="stat-box">
            <h4><?php echo $stats['diambil']; ?></h4>
            <p>Diambil</p>
        </div>
    </div>

    <!-- Data Table -->
    <?php if (count($data_rows) > 0): ?>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No.</th>
                <th style="width: 12%;">No. Paket</th>
                <th style="width: 8%;">Unit</th>
                <th style="width: 12%;">Penghuni</th>
                <th style="width: 12%;">Pengirim</th>
                <th style="width: 12%;">Ekspedisi</th>
                <th style="width: 10%; text-align: center;">Penyimpanan</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 12%;">Tgl Terima</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data_rows as $row):
            ?>
            <tr>
                <td class="text-center"><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['nomor_paket']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_unit']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_pengirim']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_ekspedisi']); ?></td>
                <td class="text-center">
                    <?php
                    if ($row['nomor_loker'] === 'WAREHOUSE') {
                        echo '<span class="badge badge-warehouse">WAREHOUSE</span>';
                    } else {
                        echo '<span class="badge badge-info">' . htmlspecialchars($row['nomor_loker']) . '</span>';
                    }
                    ?>
                </td>
                <td class="text-center">
                    <?php
                    $status_classes = [
                        'diterima' => 'badge-warning',
                        'disimpan' => 'badge-info',
                        'diambil' => 'badge-success'
                    ];
                    $status_text = [
                        'diterima' => 'Diterima',
                        'disimpan' => 'Disimpan',
                        'diambil' => 'Diambil'
                    ];
                    $class = $status_classes[$row['status']] ?? 'badge-warning';
                    $text = $status_text[$row['status']] ?? 'Unknown';
                    echo '<span class="badge ' . $class . '">' . $text . '</span>';
                    ?>
                </td>
                <td><?php echo date('d M Y H:i', strtotime($row['tanggal_terima'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="text-align: center; padding: 20px; color: #666;">
        Tidak ada data paket sesuai dengan filter yang dipilih.
    </p>
    <?php endif; ?>

    <div class="footer">
        <p>Laporan ini dicetak secara otomatis dari sistem AureliaBox Package Management</p>
        <p>© <?php echo date('Y'); ?> - THE GRAND AURELIA RESIDENCE. All Rights Reserved</p>
    </div>

    <script>
        // Hide URL footer when printing
        window.onbeforeprint = function() {
            document.body.style.margin = '0';
            document.body.style.padding = '0';
        };
        window.print();
    </script>
</body>
</html>
<?php endif; ?>
