<?php
require_once __DIR__ . '/config/session.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard.php');
    exit();
}

// Check if background image exists
$background_image = null;
$image_dir = __DIR__ . '/assets/images/';
if (is_dir($image_dir)) {
    $files = scandir($image_dir);
    foreach ($files as $file) {
        if (preg_match('/^background-login\.(jpg|jpeg|png|webp)$/i', $file)) {
            $background_image = BASE_URL . '/assets/images/' . $file;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AureliaBox - Smart Package Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            <?php if ($background_image): ?>
            background-image: url('<?php echo $background_image; ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
            <?php else: ?>
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            <?php endif; ?>
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        <?php if ($background_image): ?>
        /* Overlay for better text readability when background image is used */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.35);
            z-index: 0;
            pointer-events: none;
        }
        <?php endif; ?>

        .container {
            position: relative;
            z-index: 1;
        }

        .hero {
            text-align: center;
            color: white;
            animation: fadeIn 0.8s ease-in;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.6), 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero .apartment-name {
            font-size: 1.2rem;
            font-weight: 300;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 1;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }

        .hero-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .btn-lg {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
        }

        .features {
            background: rgba(0, 0, 0, 0.45);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 3rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .feature-item {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .feature-item i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }

        .feature-item h5 {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }

        .feature-item p {
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
        }

        .btn-light {
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background-color: #fff;
            box-shadow: 0 6px 16px rgba(0,0,0,0.3);
            transform: translateY(-2px);
        }

        footer {
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="hero">
            <div class="hero-icon">
                <i class="bi bi-box"></i>
            </div>

            <h1>AureliaBox</h1>
            <p class="apartment-name">THE GRAND AURELIA RESIDENCE</p>
            <p>Smart Package Management System</p>
            <p class="lead">"<i>Elegant Living, Effortless Delivery</i>"</p>

            <div class="mb-4">
                <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>

            <div class="features">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="bi bi-people"></i>
                            <h5>Multi-Role Access</h5>
                            <p class="small">Admin, Receptionist & Resident</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="bi bi-bell"></i>
                            <h5>Smart Notifications</h5>
                            <p class="small">Real-time updates for residents</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="bi bi-door-closed"></i>
                            <h5>Package Management</h5>
                            <p class="small">Track packages on your phone</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 small">
                <p>© 2025 AureliaBox - The Grand Aurelia Residence</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
