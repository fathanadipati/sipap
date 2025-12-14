// Konfirmasi sebelum hapus
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Generate Loker Grid Selector (1-50)
function initLokerGrid(occupiedLokers = []) {
    const lokerGrid = document.getElementById('loker-grid');
    if (!lokerGrid) return; // Exit if not on loker page
    
    // Clear existing loker buttons
    lokerGrid.innerHTML = '';
    
    // Get currently selected loker from hidden input
    const currentLoker = document.getElementById('nomor_loker').value;
    
    // Convert occupiedLokers to Set for faster lookup
    const occupiedSet = new Set(occupiedLokers || []);
    
    // Generate 50 loker buttons (10 kolom x 5 baris)
    for (let i = 1; i <= 50; i++) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'loker-btn';
        btn.textContent = i;
        btn.dataset.loker = i;
        
        // Check if loker is occupied
        const isOccupied = occupiedSet.has(i);
        if (isOccupied) {
            btn.classList.add('occupied');
            btn.disabled = true;
            btn.title = 'Loker sudah ditempati';
        }
        
        // If this loker was previously selected, mark it as selected
        if (currentLoker && parseInt(currentLoker) === i) {
            btn.classList.add('selected');
        }
        
        if (!isOccupied) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                selectLoker(i, this);
            });
        }
        
        lokerGrid.appendChild(btn);
    }
}

// Handle loker selection
function selectLoker(lokerNumber, element) {
    // Remove previous selection
    document.querySelectorAll('.loker-btn').forEach(btn => {
        btn.classList.remove('selected');
    });
    
    // Set new selection
    element.classList.add('selected');
    document.getElementById('nomor_loker').value = lokerNumber;
    
    // Show selected loker display
    const displayBadge = document.getElementById('selected-loker-display');
    const displayText = document.getElementById('selected-loker-text');
    displayText.textContent = 'No. ' + lokerNumber;
    displayBadge.style.display = 'inline-block';
}

// Initialize loker grid on page load
document.addEventListener('DOMContentLoaded', function() {
    initLokerGrid();
});


// Auto-refresh statistik dashboard setiap 10 detik
function refreshDashboardStats() {
    fetch('/sipap/modules/paket/get_stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Untuk Admin
                if (data.total_penghuni !== undefined) {
                    const penghuni_elem = document.querySelector('[data-stat="total_penghuni"]');
                    const paket_elem = document.querySelector('[data-stat="total_paket"]');
                    const loker_elem = document.querySelector('[data-stat="paket_loker"]');
                    const users_elem = document.querySelector('[data-stat="total_users"]');
                    
                    if (penghuni_elem) penghuni_elem.textContent = data.total_penghuni;
                    if (paket_elem) paket_elem.textContent = data.total_paket;
                    if (loker_elem) loker_elem.textContent = data.paket_loker;
                    if (users_elem) users_elem.textContent = data.total_users;
                }
                
                // Untuk Receptionist
                if (data.paket_loker !== undefined && data.paket_diambil_hari !== undefined) {
                    const loker_elem = document.querySelector('[data-stat="paket_loker"]');
                    const diambil_elem = document.querySelector('[data-stat="paket_diambil_hari"]');
                    const total_elem = document.querySelector('[data-stat="total_paket"]');
                    
                    if (loker_elem) loker_elem.textContent = data.paket_loker;
                    if (diambil_elem) diambil_elem.textContent = data.paket_diambil_hari;
                    if (total_elem) total_elem.textContent = data.total_paket;
                }
                
                // Untuk Penghuni
                if (data.paket_menunggu !== undefined) {
                    const menunggu_elem = document.querySelector('[data-stat="paket_menunggu"]');
                    const diambil_elem = document.querySelector('[data-stat="paket_diambil"]');
                    
                    if (menunggu_elem) menunggu_elem.textContent = data.paket_menunggu;
                    if (diambil_elem) diambil_elem.textContent = data.paket_diambil;
                }
            }
        })
        .catch(error => console.log('Error refreshing stats:', error));
}

// Quick-mark paket sebagai diambil/disimpan via AJAX
function quickMarkPaket(paketId, element) {
    if (!confirm('Apakah Anda yakin ingin mengubah status paket ini?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('id', paketId);
    
    fetch('/sipap/modules/paket/quick_mark.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            
            // Find the button that was clicked
            const clickedBtn = element.querySelector('button[data-paket-id="' + paketId + '"]');
            if (clickedBtn) {
                // Replace with disabled success button
                const newBtn = document.createElement('button');
                newBtn.type = 'button';
                newBtn.className = 'btn btn-sm btn-success';
                newBtn.title = 'Sudah Diambil';
                newBtn.disabled = true;
                newBtn.innerHTML = '<i class="bi bi-check2-all"></i> Diambil';
                clickedBtn.replaceWith(newBtn);
            }
            
            // Update visual status badge
            const statusBadge = element.querySelector('[data-status-badge]');
            if (statusBadge) {
                statusBadge.className = 'badge bg-success';
                statusBadge.textContent = 'Diambil';
            }
            
            // Refresh stats setelah 1 detik
            setTimeout(refreshDashboardStats, 1000);
        } else {
            showToast(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'danger');
    });
}

// Auto-refresh setiap 10 detik jika di halaman dashboard
if (window.location.pathname.includes('dashboard.php')) {
    setInterval(refreshDashboardStats, 10000);
    // Refresh immediately on page load
    refreshDashboardStats();
}

// Format tanggal
function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Show alert toast
function showToast(message, type = 'success') {
    const alertDiv = document.createElement('div');
    const bgClass = type === 'success' ? 'alert-success' : type === 'danger' ? 'alert-danger' : 'alert-info';
    const iconClass = type === 'success' ? 'bi-check-circle-fill' : type === 'danger' ? 'bi-exclamation-circle-fill' : 'bi-info-circle-fill';
    
    alertDiv.className = `alert ${bgClass} alert-dismissible fade show position-fixed`;
    alertDiv.style.top = '80px';
    alertDiv.style.left = '50%';
    alertDiv.style.transform = 'translateX(-50%)';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '350px';
    alertDiv.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    alertDiv.style.borderRadius = '8px';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="bi ${iconClass}" style="font-size: 1.2rem;"></i>
            <span style="flex: 1;">${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => {
            alertDiv.remove();
        }, 150);
    }, 4000);
}

// Validasi form
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form.checkValidity() === false) {
        return true;
    }
    
    event.preventDefault();
    event.stopPropagation();
    form.classList.add('was-validated');
    return false;
}

// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(el => new bootstrap.Tooltip(el));
});
