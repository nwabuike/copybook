<?php
require_once 'php/auth.php';
requireLogin();

$currentUser = getCurrentUser();
$canDelete = canPerform('delete_agent');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Management | Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include 'php/content_protection.php'; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a7c42;
            --primary-dark: #066633;
            --primary-light: #e8f5e9;
            --secondary: #ff6b6b;
            --accent: #ffd166;
            --dark: #2d3047;
            --light: #f7f9fc;
            --text: #333333;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Layout with Sidebar */
        .layout-wrapper {
            display: flex;
            flex: 1;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid #eee;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-logo-icon {
            font-size: 28px;
        }
        
        .sidebar-logo-text {
            font-size: 18px;
            font-weight: 700;
        }

        .sidebar-nav {
            padding: 20px 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu-item {
            margin-bottom: 5px;
        }
        
        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: var(--text);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .sidebar-menu-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .sidebar-menu-link.active {
            background: var(--primary-light);
            color: var(--primary);
            border-right: 3px solid var(--primary);
        }
        
        .sidebar-menu-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-divider {
            height: 1px;
            background: #eee;
            margin: 15px 20px;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            margin-top: auto;
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--light);
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .sidebar-user-info {
            flex: 1;
        }
        
        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }
        
        .sidebar-user-role {
            font-size: 0.75rem;
            color: #666;
            text-transform: capitalize;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            width: 45px;
            height: 45px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }
        
        .sidebar-toggle:hover {
            background: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }
        
        /* Main Content Area */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Header Styles */
        header {
            background: white;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #eee;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .page-breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .page-breadcrumb a:hover {
            text-decoration: underline;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background: var(--light);
            border-radius: 8px;
        }
        
        .header-user-avatar {
            width: 35px;
            height: 35px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .header-user-info {
            display: flex;
            flex-direction: column;
        }
        
        .header-user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .header-user-role {
            font-size: 0.75rem;
            color: #666;
            text-transform: capitalize;
        }

        /* Main Content */
        .main-content {
            padding: 30px 0;
            flex: 1;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .stat-info h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--accent);
            color: var(--dark);
        }
        
        .btn-secondary:hover {
            background: #ffc145;
            transform: translateY(-2px);
        }

        .table-container {
            padding: 20px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: var(--primary-light);
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 1px solid #eee;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        tbody tr {
            transition: var(--transition);
        }
        
        tbody tr:hover {
            background: #f9f9f9;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .edit-btn {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .edit-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .delete-btn {
            background: #f8d7da;
            color: #721c24;
        }
        
        .delete-btn:hover {
            background: #dc3545;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(10, 124, 66, 0.1);
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        input, select, textarea {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(10, 124, 66, 0.1);
        }

        .states-selection {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            max-height: 200px;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            background: white;
        }

        .state-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .state-checkbox input {
            width: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }



        /* Responsive table wrapper */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 998;
            display: none;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .main-wrapper {
                margin-left: 0;
            }
            
            /* Adjust header for toggle button */
            .page-breadcrumb {
                margin-left: 50px;
            }
        }

        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
            }
            
            .container {
                width: 100%;
                max-width: 100%;
                padding: 0 15px;
                overflow-x: hidden;
            }
            
            .main-wrapper {
                overflow-x: hidden;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-card h3 {
                font-size: 1.5rem;
            }
            
            .stat-card p {
                font-size: 0.85rem;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .card-header h2 {
                font-size: 1.2rem;
            }
            
            .card-header .btn {
                width: 100%;
                justify-content: center;
            }

            .table-responsive {
                margin: 0 -15px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            #agents-table {
                min-width: 100%;
                width: max-content;
            }
            
            #agents-table th:nth-child(1), /* ID */
            #agents-table td:nth-child(1) {
                width: 50px;
                min-width: 50px;
            }
            
            #agents-table th:nth-child(2), /* Name */
            #agents-table td:nth-child(2) {
                width: 120px;
                min-width: 120px;
            }
            
            #agents-table th:nth-child(3), /* Email */
            #agents-table td:nth-child(3) {
                width: 150px;
                min-width: 150px;
            }
            
            #agents-table th:nth-child(4), /* Phone */
            #agents-table td:nth-child(4) {
                width: 110px;
                min-width: 110px;
            }
            
            #agents-table th:nth-child(5), /* States Covered */
            #agents-table td:nth-child(5) {
                width: 100px;
                min-width: 100px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            #agents-table th:nth-child(6), /* Total Orders */
            #agents-table td:nth-child(6) {
                width: 80px;
                min-width: 80px;
                text-align: center;
            }
            
            #agents-table th:nth-child(7), /* Status */
            #agents-table td:nth-child(7) {
                width: 80px;
                min-width: 80px;
            }
            
            #agents-table th:nth-child(8), /* Actions */
            #agents-table td:nth-child(8) {
                width: 90px;
                min-width: 90px;
            }

            th, td {
                padding: 10px 8px;
                font-size: 11px;
            }

            .action-buttons {
                display: flex;
                flex-direction: row;
                gap: 5px;
                justify-content: center;
            }

            .action-btn {
                padding: 6px 8px;
                font-size: 12px;
            }
            
            .action-btn i {
                font-size: 11px;
            }

            .status-badge {
                font-size: 10px;
                padding: 4px 8px;
                white-space: nowrap;
            }

            .modal-content {
                width: 95%;
                max-width: 95%;
                padding: 20px;
                max-height: 85vh;
                overflow-y: auto;
            }
            
            .modal-header h2 {
                font-size: 1.2rem;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                font-size: 13px;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                font-size: 14px;
            }

            .states-selection {
                grid-template-columns: repeat(2, 1fr);
                max-height: 150px;
                font-size: 12px;
            }
        }

        @media (max-width: 576px) {
            .sidebar-toggle {
                width: 48px;
                height: 48px;
                bottom: 15px;
                right: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .card-header h2 {
                font-size: 1.1rem;
            }
            
            .page-breadcrumb {
                font-size: 12px;
            }
            
            header {
                padding: 15px 0;
            }

            .btn {
                font-size: 11px;
                padding: 8px 12px;
            }

            .action-buttons .btn i {
                display: none;
            }

            .modal-content {
                width: 98%;
                padding: 15px;
            }

            .modal-header h3 {
                font-size: 18px;
            }

            .states-selection {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .stat-content h3 {
                font-size: 24px;
            }
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: white;
            border-top: 1px solid #eee;
            margin-top: 20px;
            border-radius: 0 0 12px 12px;
        }

        .pagination-info {
            font-size: 14px;
            color: #666;
        }

        .pagination-controls {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .pagination-btn {
            padding: 8px 14px;
            border: 1px solid #ddd;
            background: white;
            color: var(--text);
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
            min-width: 38px;
        }

        .pagination-btn:hover:not(.disabled), .pagination-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .entries-per-page {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #666;
        }

        .entries-per-page select {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .pagination {
                flex-direction: column;
                gap: 12px;
                padding: 15px 10px;
            }

            .pagination-info {
                width: 100%;
                text-align: center;
                font-size: 13px;
            }

            .pagination-controls {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
                gap: 5px;
            }

            .pagination-btn {
                padding: 8px 10px;
                font-size: 12px;
                min-width: 35px;
            }

            .entries-per-page {
                width: 100%;
                justify-content: center;
                font-size: 13px;
            }
            
            .entries-per-page select {
                padding: 6px 10px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <!-- Layout Wrapper -->
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <div class="sidebar-logo-text">Emerald Tech Hub</div>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item">
                        <a href="index.php" class="sidebar-menu-link">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="admin_dashboard_crm.php" class="sidebar-menu-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard CRM</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="bulk_messaging.php" class="sidebar-menu-link">
                            <i class="fas fa-paper-plane"></i>
                            <span>Bulk Messaging</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="agent_management.php" class="sidebar-menu-link active">
                            <i class="fas fa-user-tie"></i>
                            <span>Delivery Agents</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="sales_notifications.php" class="sidebar-menu-link">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    <?php if (canPerform('add_expense')): ?>
                    <li class="sidebar-menu-item">
                        <a href="stock_management.php" class="sidebar-menu-link">
                            <i class="fas fa-boxes"></i>
                            <span>Stock Management</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (isAdmin()): ?>
                    <div class="sidebar-divider"></div>
                    <li class="sidebar-menu-item">
                        <a href="user_management.php" class="sidebar-menu-link">
                            <i class="fas fa-users-cog"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="pricing_management.php" class="sidebar-menu-link">
                            <i class="fas fa-tags"></i>
                            <span>Pricing Management</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="analytics.php" class="sidebar-menu-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="profit_loss_report.php" class="sidebar-menu-link">
                            <i class="fas fa-chart-line"></i>
                            <span>Profit/Loss Report</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="activity_logs.php" class="sidebar-menu-link">
                            <i class="fas fa-history"></i>
                            <span>Activity Logs</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        <?= strtoupper(substr($currentUser['full_name'], 0, 2)) ?>
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?= htmlspecialchars($currentUser['full_name']) ?></div>
                        <div class="sidebar-user-role"><?= htmlspecialchars($currentUser['role']) ?></div>
                    </div>
                </div>
                <a href="logout.php" class="btn btn-secondary" style="width: 100%; justify-content: center;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>
        
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
        
        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <!-- Header -->
            <header>
                <div class="container">
                    <div class="header-content">
                        <div class="page-breadcrumb">
                            <i class="fas fa-home"></i>
                            <a href="index.php">Home</a>
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                            <span>Agent Management</span>
                        </div>
                        <div class="header-actions">
                            <div class="header-user">
                                <div class="header-user-avatar">
                                    <?= strtoupper(substr($currentUser['full_name'], 0, 2)) ?>
                                </div>
                                <div class="header-user-info">
                                    <div class="header-user-name"><?= htmlspecialchars($currentUser['full_name']) ?></div>
                                    <div class="header-user-role"><?= htmlspecialchars($currentUser['role']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <section class="main-content">
                <div class="container">

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="total-agents">0</h3>
                                <p>Total Agents</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="active-agents">0</h3>
                                <p>Active Agents</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="total-states">0</h3>
                                <p>States Covered</p>
                            </div>
                        </div>
                    </div>

                    <div class="content-card">
                        <div class="card-header">
                            <h2>All Delivery Agents</h2>
                            <button class="btn btn-primary" id="add-agent-btn">
                                <i class="fas fa-plus"></i> Add New Agent
                            </button>
                        </div>

                        <div class="table-container">
                            <div class="table-responsive">
                                <table id="agents-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>States Covered</th>
                                            <th>Total Orders</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="agents-tbody">
                                        <tr>
                                            <td colspan="8" class="loading">
                                                <i class="fas fa-spinner fa-spin"></i> Loading agents...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="pagination">
                            <div class="pagination-info" id="pagination-info">Showing 0 of 0 agents</div>
                            <div class="pagination-controls" id="pagination-controls">
                                <!-- Pagination buttons will be populated by JavaScript -->
                            </div>
                            <div class="entries-per-page">
                                <label for="entries-select">Show:</label>
                                <select id="entries-select" onchange="changeEntriesPerPage()">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div><!-- End main-wrapper -->
    </div><!-- End layout-wrapper -->

    <!-- Add/Edit Agent Modal -->
    <div class="modal" id="agent-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Add New Agent</h2>
                <button class="close-btn" id="close-modal">&times;</button>
            </div>

            <form id="agent-form">
                <input type="hidden" id="agent-id">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="agent-name">Full Name *</label>
                        <input type="text" id="agent-name" required>
                    </div>

                    <div class="form-group">
                        <label for="agent-email">Email</label>
                        <input type="email" id="agent-email">
                    </div>

                    <div class="form-group">
                        <label for="agent-phone">Phone *</label>
                        <input type="tel" id="agent-phone" required>
                    </div>

                    <div class="form-group">
                        <label for="agent-alt-phone">Alternative Phone</label>
                        <input type="tel" id="agent-alt-phone">
                    </div>

                    <div class="form-group full-width">
                        <label for="agent-address">Address</label>
                        <textarea id="agent-address" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="agent-status">Status</label>
                        <select id="agent-status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>States to Cover *</label>
                        <div class="states-selection" id="states-selection">
                            <!-- States checkboxes will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Agent</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Nigerian states
        const nigerianStates = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe', 'Imo',
            'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa',
            'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba',
            'Yobe', 'Zamfara'
        ];

        let agents = [];
        let allAgents = [];
        let currentEditingAgentId = null;
        let currentPage = 1;
        let agentsPerPage = window.innerWidth <= 768 ? 10 : 20;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadAgents();
            setupEventListeners();
            populateStatesCheckboxes();
            setupSidebarToggle();
            setEntriesPerPage();
        });

        // Set default entries per page based on screen size
        function setEntriesPerPage() {
            const select = document.getElementById('entries-select');
            if (select) {
                select.value = agentsPerPage;
            }
        }

        // Change entries per page
        function changeEntriesPerPage() {
            const select = document.getElementById('entries-select');
            agentsPerPage = parseInt(select.value);
            currentPage = 1;
            renderAgentsTable();
            updatePagination();
        }

        // Sidebar toggle for mobile
        function setupSidebarToggle() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
        }

        function setupEventListeners() {
            document.getElementById('add-agent-btn').addEventListener('click', () => openModal());
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('cancel-btn').addEventListener('click', closeModal);
            document.getElementById('agent-form').addEventListener('submit', saveAgent);
            
            document.getElementById('agent-modal').addEventListener('click', (e) => {
                if (e.target.id === 'agent-modal') closeModal();
            });
        }

        function populateStatesCheckboxes() {
            const container = document.getElementById('states-selection');
            container.innerHTML = nigerianStates.map(state => `
                <div class="state-checkbox">
                    <input type="checkbox" id="state-${state}" value="${state}">
                    <label for="state-${state}" style="font-weight: normal; margin: 0;">${state}</label>
                </div>
            `).join('');
        }

        async function loadAgents() {
            try {
                const response = await fetch('api/agents.php?action=list');
                const data = await response.json();

                if (data.success) {
                    allAgents = data.data;
                    agents = allAgents;
                    currentPage = 1;
                    renderAgentsTable();
                    updateStats();
                    updatePagination();
                } else {
                    console.error('Failed to load agents:', data.message);
                }
            } catch (error) {
                console.error('Error loading agents:', error);
                document.getElementById('agents-tbody').innerHTML = `
                    <tr><td colspan="8" class="empty-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading agents. Please refresh the page.</p>
                    </td></tr>
                `;
            }
        }

        function updateStats() {
            const totalAgents = agents.length;
            const activeAgents = agents.filter(a => a.status === 'active').length;
            const statesCovered = new Set();
            agents.forEach(agent => {
                if (agent.states) {
                    agent.states.split(', ').forEach(state => statesCovered.add(state.trim()));
                }
            });

            document.getElementById('total-agents').textContent = totalAgents;
            document.getElementById('active-agents').textContent = activeAgents;
            document.getElementById('total-states').textContent = statesCovered.size;
        }

        function renderAgentsTable() {
            const tbody = document.getElementById('agents-tbody');

            if (agents.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="8" class="empty-state">
                        <i class="fas fa-users"></i>
                        <p>No agents found. Click "Add New Agent" to get started.</p>
                    </td></tr>
                `;
                updatePagination();
                return;
            }

            // Calculate pagination
            const startIndex = (currentPage - 1) * agentsPerPage;
            const endIndex = startIndex + agentsPerPage;
            const paginatedAgents = agents.slice(startIndex, endIndex);

            tbody.innerHTML = paginatedAgents.map(agent => `
                <tr>
                    <td>${agent.id}</td>
                    <td>${agent.name}</td>
                    <td>${agent.email || 'N/A'}</td>
                    <td>${agent.phone}</td>
                    <td>${agent.states || 'None'} (${agent.state_count || 0})</td>
                    <td>${agent.total_orders || 0}</td>
                    <td><span class="status-badge status-${agent.status}">${agent.status.toUpperCase()}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit-btn" onclick="editAgent(${agent.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete-btn" onclick="deleteAgent(${agent.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
            updatePagination();
        }

        // Update pagination controls
        function updatePagination() {
            const totalAgents = agents.length;
            const totalPages = Math.ceil(totalAgents / agentsPerPage);
            const startIndex = (currentPage - 1) * agentsPerPage + 1;
            const endIndex = Math.min(currentPage * agentsPerPage, totalAgents);

            // Update pagination info
            const paginationInfo = document.getElementById('pagination-info');
            paginationInfo.textContent = totalAgents > 0 
                ? `Showing ${startIndex}-${endIndex} of ${totalAgents} agents`
                : 'Showing 0 of 0 agents';

            // Update pagination controls
            const paginationControls = document.getElementById('pagination-controls');
            let buttonsHtml = '';

            // Previous button
            buttonsHtml += `
                <button class="pagination-btn ${currentPage === 1 ? 'disabled' : ''}" 
                        onclick="changePage(${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Page numbers
            const maxVisiblePages = window.innerWidth <= 768 ? 3 : 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // First page
            if (startPage > 1) {
                buttonsHtml += `<button class="pagination-btn" onclick="changePage(1)">1</button>`;
                if (startPage > 2) {
                    buttonsHtml += `<span style="padding: 0 5px;">...</span>`;
                }
            }

            // Page number buttons
            for (let i = startPage; i <= endPage; i++) {
                buttonsHtml += `
                    <button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                            onclick="changePage(${i})">
                        ${i}
                    </button>
                `;
            }

            // Last page
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    buttonsHtml += `<span style="padding: 0 5px;">...</span>`;
                }
                buttonsHtml += `<button class="pagination-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;
            }

            // Next button
            buttonsHtml += `
                <button class="pagination-btn ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}" 
                        onclick="changePage(${currentPage + 1})" 
                        ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            paginationControls.innerHTML = buttonsHtml;
        }

        // Change page
        function changePage(page) {
            const totalPages = Math.ceil(agents.length / agentsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderAgentsTable();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function openModal(agentId = null) {
            const modal = document.getElementById('agent-modal');
            const title = document.getElementById('modal-title');
            
            if (agentId) {
                title.textContent = 'Edit Agent';
                currentEditingAgentId = agentId;
                loadAgentDetails(agentId);
            } else {
                title.textContent = 'Add New Agent';
                currentEditingAgentId = null;
                document.getElementById('agent-form').reset();
                document.querySelectorAll('.state-checkbox input').forEach(cb => cb.checked = false);
            }

            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('agent-modal').style.display = 'none';
        }

        async function loadAgentDetails(agentId) {
            try {
                const response = await fetch(`api/agents.php?action=single&id=${agentId}`);
                const data = await response.json();

                if (data.success) {
                    const agent = data.data;
                    document.getElementById('agent-id').value = agent.id;
                    document.getElementById('agent-name').value = agent.name;
                    document.getElementById('agent-email').value = agent.email || '';
                    document.getElementById('agent-phone').value = agent.phone;
                    document.getElementById('agent-alt-phone').value = agent.alt_phone || '';
                    document.getElementById('agent-address').value = agent.address || '';
                    document.getElementById('agent-status').value = agent.status;

                    // Check states
                    document.querySelectorAll('.state-checkbox input').forEach(cb => cb.checked = false);
                    if (agent.states) {
                        agent.states.forEach(state => {
                            const checkbox = document.getElementById(`state-${state}`);
                            if (checkbox) checkbox.checked = true;
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading agent details:', error);
                alert('Error loading agent details');
            }
        }

        async function saveAgent(e) {
            e.preventDefault();

            const selectedStates = Array.from(document.querySelectorAll('.state-checkbox input:checked'))
                .map(cb => cb.value);

            if (selectedStates.length === 0) {
                alert('Please select at least one state');
                return;
            }

            const agentData = {
                name: document.getElementById('agent-name').value,
                email: document.getElementById('agent-email').value,
                phone: document.getElementById('agent-phone').value,
                alt_phone: document.getElementById('agent-alt-phone').value,
                address: document.getElementById('agent-address').value,
                status: document.getElementById('agent-status').value,
                states: selectedStates
            };

            try {
                let url = 'api/agents.php';
                let method = 'POST';

                if (currentEditingAgentId) {
                    agentData.id = currentEditingAgentId;
                    method = 'PUT';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(agentData)
                });

                const data = await response.json();

                if (data.success) {
                    alert(currentEditingAgentId ? 'Agent updated successfully!' : 'Agent created successfully!');
                    closeModal();
                    loadAgents();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error saving agent:', error);
                alert('Error saving agent. Please try again.');
            }
        }

        function editAgent(agentId) {
            openModal(agentId);
        }

        async function deleteAgent(agentId) {
            if (!confirm('Are you sure you want to delete this agent? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch('api/agents.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: agentId })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Agent deleted successfully!');
                    loadAgents();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error deleting agent:', error);
                alert('Error deleting agent. Please try again.');
            }
        }

        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.sidebar-menu-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });

        // Close sidebar on window resize if desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebar-overlay');
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }
        });
    </script>
    
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
</body>
</html>
