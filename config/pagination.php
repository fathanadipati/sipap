<?php
// Pagination Helper Functions

class Pagination {
    private $conn;
    private $items_per_page = 10;
    private $current_page = 1;
    private $total_items = 0;
    private $total_pages = 0;
    
    public function __construct($conn, $items_per_page = 10) {
        $this->conn = $conn;
        $this->items_per_page = $items_per_page;
        $this->current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    }
    
    /**
     * Get total items count from query
     */
    public function setTotalItems($total) {
        $this->total_items = $total;
        $this->total_pages = ceil($this->total_items / $this->items_per_page);
        
        // Validate current page
        if ($this->current_page > $this->total_pages && $this->total_pages > 0) {
            $this->current_page = $this->total_pages;
        }
    }
    
    /**
     * Get offset for LIMIT clause
     */
    public function getOffset() {
        return ($this->current_page - 1) * $this->items_per_page;
    }
    
    /**
     * Get limit
     */
    public function getLimit() {
        return $this->items_per_page;
    }
    
    /**
     * Get current page
     */
    public function getCurrentPage() {
        return $this->current_page;
    }
    
    /**
     * Get total pages
     */
    public function getTotalPages() {
        return $this->total_pages;
    }
    
    /**
     * Get total items
     */
    public function getTotalItems() {
        return $this->total_items;
    }
    
    /**
     * Generate pagination HTML
     */
    public function render($base_url) {
        if ($this->total_pages <= 1) {
            return '';
        }
        
        $html = '<nav aria-label="Page navigation"><ul class="pagination">';
        
        // Previous button
        if ($this->current_page > 1) {
            $prev_page = $this->current_page - 1;
            $prev_url = strpos($base_url, '?') ? $base_url . '&page=' : $base_url . '?page=';
            $html .= '<li class="page-item"><a class="page-link" href="' . $prev_url . $prev_page . '">← Sebelumnya</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">← Sebelumnya</span></li>';
        }
        
        // Page numbers
        $start_page = max(1, $this->current_page - 2);
        $end_page = min($this->total_pages, $this->current_page + 2);
        
        if ($start_page > 1) {
            $url = strpos($base_url, '?') ? $base_url . '&page=' : $base_url . '?page=';
            $html .= '<li class="page-item"><a class="page-link" href="' . $url . '1">1</a></li>';
            if ($start_page > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $this->current_page) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $url = strpos($base_url, '?') ? $base_url . '&page=' : $base_url . '?page=';
                $html .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '">' . $i . '</a></li>';
            }
        }
        
        if ($end_page < $this->total_pages) {
            if ($end_page < $this->total_pages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $url = strpos($base_url, '?') ? $base_url . '&page=' : $base_url . '?page=';
            $html .= '<li class="page-item"><a class="page-link" href="' . $url . $this->total_pages . '">' . $this->total_pages . '</a></li>';
        }
        
        // Next button
        if ($this->current_page < $this->total_pages) {
            $next_page = $this->current_page + 1;
            $next_url = strpos($base_url, '?') ? $base_url . '&page=' : $base_url . '?page=';
            $html .= '<li class="page-item"><a class="page-link" href="' . $next_url . $next_page . '">Selanjutnya →</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Selanjutnya →</span></li>';
        }
        
        $html .= '</ul></nav>';
        
        return $html;
    }
    
    /**
     * Get info text
     */
    public function getInfo() {
        $start = ($this->current_page - 1) * $this->items_per_page + 1;
        $end = min($this->current_page * $this->items_per_page, $this->total_items);
        
        return "Menampilkan " . $start . " - " . $end . " dari " . $this->total_items . " data";
    }
}
?>
