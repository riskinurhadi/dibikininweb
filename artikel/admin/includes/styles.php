<?php
/**
 * Admin Styles - CSS Terpusat
 * Modern, Clean & Professional Design
 */
?>
<style>
    :root {
        --primary-color: #18A7D2;
        --primary-dark: #0d6efd;
        --secondary-color: #6c757d;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --dark-color: #1a1d29;
        --sidebar-bg: #1a1d29;
        --sidebar-hover: #252836;
        --sidebar-active: #2d3242;
        --main-bg: #f5f6fa;
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --border-color: #e2e8f0;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        --card-shadow-hover: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: var(--main-bg);
        color: var(--text-primary);
        overflow-x: hidden;
    }
    
    /* ============================================
       SIDEBAR
       ============================================ */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        height: 100vh;
        background: var(--sidebar-bg);
        color: white;
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s ease;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-logo {
        padding: 24px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.05);
    }
    
    .sidebar-logo i {
        font-size: 32px;
        color: var(--primary-color);
    }
    
    .sidebar-logo-text {
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    .sidebar-menu {
        padding: 20px 0;
    }
    
    .menu-section {
        margin-bottom: 32px;
    }
    
    .menu-section-title {
        padding: 0 24px 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.4);
    }
    
    .menu-item {
        display: flex;
        align-items: center;
        padding: 14px 24px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
        font-size: 14px;
        font-weight: 500;
    }
    
    .menu-item:hover {
        background: var(--sidebar-hover);
        color: white;
        padding-left: 28px;
    }
    
    .menu-item.active {
        background: var(--sidebar-active);
        color: white;
    }
    
    .menu-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary-color);
    }
    
    .menu-item i {
        width: 24px;
        margin-right: 12px;
        font-size: 18px;
        text-align: center;
    }
    
    .menu-item-text {
        flex: 1;
    }
    
    .badge-menu {
        background: var(--primary-color);
        color: white;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }
    
    /* ============================================
       MAIN CONTENT
       ============================================ */
    .main-content {
        margin-left: 280px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }
    
    /* ============================================
       HEADER
       ============================================ */
    .content-header {
        background: white;
        padding: 24px 32px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: var(--card-shadow);
    }
    
    .header-left h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    
    .header-info {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--text-secondary);
        font-size: 14px;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        background: var(--success-color);
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .notification-btn {
        position: relative;
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 10px;
        border-radius: 10px;
        transition: all 0.2s;
    }
    
    .notification-btn:hover {
        background: var(--main-bg);
        color: var(--text-primary);
    }
    
    .notification-dot {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background: var(--danger-color);
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .btn-tulis-berita {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(24, 167, 210, 0.3);
    }
    
    .btn-tulis-berita:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(24, 167, 210, 0.4);
        color: white;
    }
    
    /* ============================================
       CONTENT BODY
       ============================================ */
    .content-body {
        padding: 32px;
    }
    
    /* ============================================
       CARDS
       ============================================ */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        margin-bottom: 24px;
    }
    
    .card:hover {
        box-shadow: var(--card-shadow-hover);
    }
    
    .card-header {
        background: white;
        border-bottom: 1px solid var(--border-color);
        padding: 20px 24px;
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* ============================================
       BUTTONS
       ============================================ */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(24, 167, 210, 0.3);
        color: white;
    }
    
    .btn-success {
        background: var(--success-color);
        border: none;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: var(--danger-color);
        border: none;
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-outline-primary,
    .btn-outline-warning,
    .btn-outline-danger {
        border-width: 2px;
        font-weight: 500;
    }
    
    /* ============================================
       FORMS
       ============================================ */
    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .form-control,
    .form-select {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(24, 167, 210, 0.1);
        outline: none;
    }
    
    .form-control-lg {
        font-size: 16px;
        padding: 12px 16px;
    }
    
    /* ============================================
       TABLES
       ============================================ */
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        background: var(--main-bg);
        border-bottom: 2px solid var(--border-color);
        font-weight: 600;
        color: var(--text-primary);
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 16px;
    }
    
    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }
    
    .table tbody tr:hover {
        background: var(--main-bg);
    }
    
    /* ============================================
       BADGES
       ============================================ */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* ============================================
       ALERTS
       ============================================ */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    /* ============================================
       MOBILE MENU TOGGLE
       ============================================ */
    .mobile-menu-toggle {
        display: none;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1001;
        background: var(--sidebar-bg);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    /* ============================================
       STATS CARDS
       ============================================ */
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s;
        border-left: 4px solid var(--primary-color);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
    }
    
    .stat-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
        background: rgba(24, 167, 210, 0.1);
        color: var(--primary-color);
    }
    
    .stat-card-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .stat-card-label {
        font-size: 14px;
        color: var(--text-secondary);
        font-weight: 500;
    }
    
    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
        }
        
        .mobile-menu-toggle {
            display: block;
        }
        
        .content-header {
            padding: 20px;
        }
        
        .content-body {
            padding: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .header-left h1 {
            font-size: 24px;
        }
        
        .content-body {
            padding: 16px;
        }
        
        .btn-tulis-berita {
            padding: 10px 16px;
            font-size: 14px;
        }
    }
    
    /* ============================================
       SCROLLBAR
       ============================================ */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }
    
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
</style>
