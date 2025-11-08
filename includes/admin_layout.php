<?php
// File: includes/admin_layout.php
include 'auth.php';
include '../config/database.php';
redirectIfNotAdmin();

if (!isset($page_title)) {
    $page_title = "Admin Panel";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Siomay Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Admin Layout Styles */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .admin-main {
            flex: 1;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
        }
        
        .admin-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            padding-bottom: 80px; /* Space for mobile bottom nav */
        }
        
        /* Sidebar Styles */
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .admin-details h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .admin-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-section {
            padding: 1rem 1.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.7;
            font-weight: 600;
        }
        
        .menu-item {
            margin: 0.2rem 0;
        }
        
        .menu-item a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .menu-item a:hover {
            background: rgba(255,255,255,0.1);
            color: #3498db;
        }
        
        .menu-item.active a {
            background: #3498db;
            color: white;
        }
        
        .menu-item a i {
            width: 20px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        
        .menu-badge {
            background: #e74c3c;
            color: white;
            border-radius: 10px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            margin-left: auto;
        }
        
        .badge-warning {
            background: #f39c12;
        }
        
        .logout-item a {
            color: #e74c3c !important;
        }
        
        .logout-item a:hover {
            background: rgba(231, 76, 60, 0.1) !important;
        }
        
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-footer p {
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-footer small {
            opacity: 0.7;
        }
        
        /* Top Bar */
        .admin-topbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.5rem;
        }
        
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2c3e50;
            cursor: pointer;
        }
        
        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .bottom-nav-items {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .bottom-nav-item {
            flex: 1;
            text-align: center;
        }
        
        .bottom-nav-item a {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.8rem 0.5rem;
            text-decoration: none;
            color: #7f8c8d;
            font-size: 0.7rem;
            transition: all 0.3s ease;
        }
        
        .bottom-nav-item a.active {
            color: #3498db;
        }
        
        .bottom-nav-item i {
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }
        
        .bottom-nav-badge {
            position: absolute;
            top: 5px;
            right: 10px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }
            
            .admin-sidebar {
                display: none; /* Hide sidebar on mobile */
            }
            
            .admin-content {
                padding: 1rem;
                padding-bottom: 70px; /* Space for bottom nav */
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .mobile-bottom-nav {
                display: block;
            }
            
            .admin-topbar {
                padding: 1rem;
            }
            
            .page-title h1 {
                font-size: 1.2rem;
            }
        }
        
        /* Desktop only */
        @media (min-width: 769px) {
            .mobile-bottom-nav {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include 'admin_sidebar.php'; ?>
        
        <div class="admin-main">
            <div class="admin-topbar">
                <div class="page-title">
                    <h1><?= $page_title ?></h1>
                </div>
                
                <div class="topbar-actions">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="user-menu">
                        <span>Welcome, <?= $_SESSION['nama_lengkap'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="admin-content">