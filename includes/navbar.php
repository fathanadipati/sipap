<?php
require_once __DIR__ . '/../config/database.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>" title="THE GRAND AURELIA RESIDENCE">
            <i class="bi bi-box"></i> AureliaBox
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard.php">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>
                
                <?php if (hasRole('admin')): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminMenu" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Kelola
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="adminMenu">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/penghuni/list.php">
                            <i class="bi bi-people"></i> Kelola Penghuni
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/paket/list.php">
                            <i class="bi bi-box2"></i> Kelola Paket
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/users.php">
                            <i class="bi bi-person-circle"></i> Kelola Staff
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/background.php">
                            <i class="bi bi-image"></i> Kelola Tampilan
                        </a></li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if (hasRole('receptionist')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/modules/paket/add.php">
                        <i class="bi bi-plus-square"></i> Terima Paket
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item dropdown">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/modules/notifikasi/list.php" id="notifikasiDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-danger" id="notifikasi-count" style="display: none;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifikasiDropdown" id="notifikasi-list">
                        <li><a class="dropdown-item text-muted" href="<?php echo BASE_URL; ?>/modules/notifikasi/list.php"><i class="bi bi-bell"></i> Tidak ada notifikasi baru</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><h6 class="dropdown-header"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></h6></li>
                        <li><small class="dropdown-header text-muted"><?php 
                            $roles = ['admin' => 'Administrator', 'receptionist' => 'Receptionist', 'resident' => 'Resident', 'resepsionis' => 'Receptionist', 'penghuni' => 'Resident'];
                            echo $roles[$_SESSION['role']] ?? $_SESSION['role'];
                        ?></small></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/profile.php">
                            <i class="bi bi-person-vcard"></i> Edit Profil
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/login.php">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
// Load notifikasi setiap 5 detik
function loadNotifikasi() {
    <?php if (isLoggedIn() && hasRole('resident')): ?>
    
    fetch('<?php echo BASE_URL; ?>/modules/notifikasi/get_notifikasi.php')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notifikasi-count');
            const listElement = document.getElementById('notifikasi-list');
            
            if (data.count > 0) {
                countElement.textContent = data.count;
                countElement.style.display = 'inline-block';
                
                let html = '';
                data.notifikasi.forEach(notif => {
                    html += `
                        <li><a class="dropdown-item notif-item" href="#" data-notif-id="${notif.id}" data-notif-link="${notif.link || '#'}">
                            <small>${notif.message}</small>
                            <br><small class="text-muted">${notif.created_at}</small>
                        </a></li>
                    `;
                });
                html += '<li><hr class="dropdown-divider"></li>';
                html += '<li><a class="dropdown-item" onclick="markAllAsRead(); return false;"><i class="bi bi-check-all"></i> Tandai Semua Dibaca</a></li>';
                html += '<li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/notifikasi/list.php"><i class="bi bi-list"></i> Lihat Semua</a></li>';
                listElement.innerHTML = html;
            } else {
                countElement.style.display = 'none';
                // Ketika tidak ada notifikasi, tampilkan pesan tapi dengan link ke halaman notifikasi
                listElement.innerHTML = '<li><a class="dropdown-item text-muted" href="<?php echo BASE_URL; ?>/modules/notifikasi/list.php"><i class="bi bi-bell"></i> Tidak ada notifikasi baru</a></li>';
            }
        })
        .catch(error => console.error('Error loading notifikasi:', error));
    
    <?php endif; ?>
}

function markAsRead(id) {
    console.log('markAsRead called with id:', id);
    
    const payload = {id: id};
    console.log('Sending payload:', payload);
    
    fetch('<?php echo BASE_URL; ?>/modules/notifikasi/mark_read.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            console.log('Mark read successful, reloading notifikasi');
            loadNotifikasi();
        } else {
            console.log('Mark read failed:', data);
        }
    })
    .catch(error => {
        console.error('Error marking as read:', error);
    });
}

function markAllAsRead() {
    console.log('markAllAsRead called');
    
    fetch('<?php echo BASE_URL; ?>/modules/notifikasi/clear_all.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        console.log('Mark all as read response:', data);
        if (data.success) {
            console.log('All notifikasi marked as read');
            loadNotifikasi();
        }
    })
    .catch(error => {
        console.error('Error marking all as read:', error);
    });
}

// Load notifikasi saat halaman dimuat dan setiap 5 detik
function initNotifikasiListeners() {
    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const notifId = this.getAttribute('data-notif-id');
            const notifLink = this.getAttribute('data-notif-link');
            
            console.log('Notif item clicked:', notifId, notifLink);
            
            // Mark as read
            markAsRead(notifId);
            
            // Navigate setelah 100ms
            setTimeout(() => {
                if (notifLink && notifLink !== '#') {
                    window.location.href = notifLink;
                }
            }, 100);
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    loadNotifikasi();
    initNotifikasiListeners();
});

setInterval(() => {
    loadNotifikasi();
    initNotifikasiListeners();
}, 5000);
</script>
