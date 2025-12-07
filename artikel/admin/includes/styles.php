<?php
/**
 * Styles Admin - External Component
 * CSS untuk semua halaman admin
 */
?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    :root {
        --sidebar-bg: #1a1d29;
        --sidebar-hover: #252836;
        --sidebar-active: #2d3242;
        --main-bg: #f5f6fa;
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --border-color: #e2e8f0;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--main-bg);
        overflow-x: hidden;
    }
    
    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        height: 100vh;
        background: var(--sidebar-bg);
        color: white;
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s;
    }
    
    .sidebar-logo {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .sidebar-logo i {
        font-size: 28px;
        color: #ef4444;
    }
    
    .sidebar-logo-text {
        font-size: 20px;
        font-weight: 700;
    }
    
    .sidebar-menu {
        padding: 20px 0;
    }
    
    .menu-section {
        margin-bottom: 30px;
    }
    
    .menu-section-title {
        padding: 0 20px;
        margin-bottom: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.5);
    }
    
    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.2s;
        position: relative;
    }
    
    .menu-item:hover {
        background: var(--sidebar-hover);
        color: white;
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
        width: 3px;
        background: #ef4444;
    }
    
    .menu-item i {
        width: 24px;
        margin-right: 12px;
        font-size: 18px;
    }
    
    .menu-item-text {
        flex: 1;
    }
    
    .badge-menu {
        background: #ef4444;
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    /* Main Content */
    .main-content {
        margin-left: 260px;
        min-height: 100vh;
    }
    
    /* Header */
    .content-header {
        background: white;
        padding: 24px 32px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .header-left h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
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
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
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
        padding: 8px;
    }
    
    .notification-dot {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
    }
    
    .btn-tulis-berita {
        background: #ef4444;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background 0.2s;
    }
    
    .btn-tulis-berita:hover {
        background: #dc2626;
        color: white;
    }
    
    /* Content Body */
    .content-body {
        padding: 32px;
    }
    
    /* Mobile Menu Toggle */
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
        border-radius: 8px;
        font-size: 20px;
    }
    
    /* Responsive */
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
    }
</style>

