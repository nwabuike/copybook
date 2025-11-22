<?php
require_once 'php/auth.php';
requireLogin(); // Require authentication

$currentUser = getCurrentUser();
$canDelete = canPerform('delete_order');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders | Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            width: 260px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 999;
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
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 2rem;
            color: var(--dark);
        }
        
        .page-actions {
            display: flex;
            gap: 15px;
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
        
        /* Stats Cards */
        .stats-cards {
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
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Orders Table */
        .orders-table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .table-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .table-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding: 8px 15px 8px 35px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 250px;
        }
        
        .search-box i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
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
        
        .customer-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .customer-phone, .customer-address {
            font-size: 0.85rem;
            color: #666;
        }
        
        .customer-phone i, .customer-address i {
            margin-right: 5px;
            color: var(--primary);
        }
        
        /* Mobile Card View */
        .order-card {
            display: none;
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .order-card-id {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        .order-card-body {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .order-card-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .order-card-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 100px;
        }
        
        .order-card-label i {
            color: var(--primary);
            width: 16px;
        }
        
        .order-card-value {
            text-align: right;
            flex: 1;
            font-size: 0.9rem;
        }
        
        .order-card-footer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-id {
            font-weight: 600;
            color: var(--primary);
        }
        
        .customer-name {
            font-weight: 500;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-processing {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-shipped {
            background: #d4edda;
            color: #155724;
        }
        
        .status-delivered {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
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
        
        .copy-btn {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .copy-btn:hover {
            background: #1976d2;
            color: white;
        }
        
        .whatsapp-btn {
            background: #e8f5e9;
            color: #25d366;
        }
        
        .whatsapp-btn:hover {
            background: #25d366;
            color: white;
        }
        
        .email-btn {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .email-btn:hover {
            background: #f57c00;
            color: white;
        }
        
        /* Copy notification */
        .copy-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            animation: slideIn 0.3s ease;
        }
        
        .copy-notification.show {
            display: flex;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-top: 1px solid #eee;
        }
        
        .pagination-info {
            color: #666;
            font-size: 0.9rem;
        }
        
        .pagination-controls {
            display: flex;
            gap: 5px;
        }
        
        .pagination-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .pagination-btn:hover, .pagination-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination-btn.disabled {
            background: #f5f5f5;
            color: #999;
            cursor: not-allowed;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
            transition: var(--transition);
        }
        
        .modal-close:hover {
            color: var(--secondary);
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
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
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            padding: 40px 0 20px;
            color: white;
            margin-top: auto;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .footer-column {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-column h4 {
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            color: var(--accent);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #999;
            font-size: 0.9rem;
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
            .container {
                width: 100%;
                padding: 0 15px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .page-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .table-controls {
                width: 100%;
                flex-direction: column;
            }
            
            .search-box {
                width: 100%;
            }
            
            .search-box input {
                width: 100%;
            }
            
            #status-filter {
                width: 100%;
            }
            
            /* Keep table on mobile with horizontal scroll */
            .table-responsive {
                display: block;
            }
            
            .table-scroll-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin: 0 -15px;
                padding: 0 15px;
            }
            
            .table-scroll-wrapper table {
                min-width: 800px;
            }
            
            /* Hide card view on mobile (keeping table) */
            .order-card {
                display: none;
            }
            
            .pagination {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .pagination-info {
                width: 100%;
                text-align: center;
                order: -1;
            }
            
            .pagination-buttons {
                justify-content: center;
            }
            
            .pagination button {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .header-user {
                flex: 1;
            }
            
            .page-breadcrumb {
                font-size: 0.8rem;
            }
            
            /* Mobile Table Enhancements */
            .data-table th,
            .data-table td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }
            
            .data-table th {
                position: sticky;
                top: 0;
                background: var(--primary);
                z-index: 10;
            }
            
            .customer-info {
                min-width: 150px;
            }
            
            .customer-phone,
            .customer-address {
                font-size: 0.8rem;
            }
            
            .status-badge {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
            
            .action-btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
            
            .action-btn i {
                margin-right: 0;
            }
            
            .action-btn span {
                display: none;
            }
            
            /* Table scroll hint */
            .table-scroll-wrapper::after {
                content: '← Swipe to see more →';
                display: block;
                text-align: center;
                padding: 10px;
                background: var(--light);
                color: #666;
                font-size: 0.8rem;
                border-top: 1px solid #ddd;
            }
            
            /* Prevent horizontal scroll on dashboard elements */
            body,
            .container,
            .main-wrapper {
                overflow-x: hidden;
            }
            
            .stats-cards,
            .table-header,
            .table-controls,
            .pagination {
                overflow: visible;
            }
            
            .footer-content {
                flex-direction: column;
            }
            
            .copy-notification {
                right: 15px;
                left: 15px;
                bottom: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-cards {
                grid-template-columns: 1fr;
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
                        <a href="customer_orderlist.php" class="sidebar-menu-link active">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="bulk_messaging.php" class="sidebar-menu-link">
                            <i class="fas fa-paper-plane"></i>
                            <span>Bulk Messaging</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="agent_management.php" class="sidebar-menu-link">
                            <i class="fas fa-user-tie"></i>
                            <span>Agents</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="sales_notifications.php" class="sidebar-menu-link">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    
                    <?php if (isAdmin()): ?>
                    <div class="sidebar-divider"></div>
                    <li class="sidebar-menu-item">
                        <a href="analytics.php" class="sidebar-menu-link">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="user_management.php" class="sidebar-menu-link">
                            <i class="fas fa-users-cog"></i>
                            <span>Users</span>
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
                            <span>Customer Orders</span>
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
            <div class="page-header">
                <h1 class="page-title">Customer Orders</h1>
                <div class="page-actions">
                    <button class="btn btn-primary" id="export-btn">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                    <button class="btn btn-secondary" id="refresh-btn">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-value" id="total-orders">0</div>
                    <div class="stat-label">Total Orders</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value" id="confirmed-orders">0</div>
                    <div class="stat-label">Confirmed</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-value" id="shipped-orders">0</div>
                    <div class="stat-label">Shipped</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-value" id="delivered-orders">0</div>
                    <div class="stat-label">Delivered</div>
                </div>
            </div>
            
            <!-- Orders Table -->
            <div class="orders-table-container">
                <div class="table-header">
                    <h2 class="table-title">Recent Orders</h2>
                    <div class="table-controls">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-input" placeholder="Search orders...">
                        </div>
                        <select id="status-filter" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <div class="table-scroll-wrapper">
                        <table id="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Agent</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                    <th style="min-width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="orders-tbody">
                                <!-- Orders will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Mobile Card View -->
                <div id="orders-cards">
                    <!-- Order cards will be populated by JavaScript for mobile -->
                </div>
                
                <div class="pagination">
                    <div class="pagination-info" id="pagination-info">Showing 0 of 0 orders</div>
                    <div class="pagination-controls" id="pagination-controls">
                        <!-- Pagination buttons will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Edit Order Modal -->
    <div class="modal" id="edit-order-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Update Order Status</h3>
                <button class="modal-close" id="edit-modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-order-form">
                    <input type="hidden" id="edit-order-id">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-customer-name">Customer Name</label>
                            <input type="text" class="form-control" id="edit-customer-name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit-package">Package</label>
                            <input type="text" class="form-control" id="edit-package" readonly>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-order-date">Order Date</label>
                            <input type="text" class="form-control" id="edit-order-date" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit-confirmed-date">Confirmed Date</label>
                            <input type="text" class="form-control" id="edit-confirmed-date" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-status">Order Status</label>
                        <select class="form-control" id="edit-status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-notes">Admin Notes</label>
                        <textarea class="form-control" id="edit-notes" rows="3" placeholder="Add any notes about this order..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="edit-cancel-btn">Cancel</button>
                <button class="btn btn-primary" id="edit-save-btn">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Copy Notification -->
    <div class="copy-notification" id="copy-notification">
        <i class="fas fa-check-circle"></i>
        <span>Order details copied to clipboard!</span>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>Emerald Tech Hub</h4>
                    <p>Helping children develop beautiful handwriting through fun, educational copybooks.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h4>Contact Info</h4>
                    <ul class="footer-links">
                        <li><i class="fas fa-phone"></i> 09029026782</li>
                        <li><i class="fas fa-phone"></i> 08102609396</li>
                        <li><i class="fas fa-envelope"></i> info@emeraldtechhub.com</li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Admin Links</h4>
                    <ul class="footer-links">
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Orders</a></li>
                        <li><a href="#">Products</a></li>
                        <li><a href="#">Customers</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2023 Emerald Tech Hub. All rights reserved.
            </div>
        </div>
    </footer>
        </div><!-- End main-wrapper -->
    </div><!-- End layout-wrapper -->
    
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Message Type Selection Modal -->
    <div class="modal" id="message-type-modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Select Message Type</h3>
                <button class="close-modal" onclick="closeMessageTypeModal()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 30px 0;">
                <p style="margin-bottom: 25px; color: #666;">Choose the type of message you want to send to the customer:</p>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <button class="btn" onclick="selectMessageType('confirmation')" style="width: 100%; justify-content: center; padding: 20px;">
                        <i class="fas fa-check-circle"></i>
                        <span style="font-size: 16px;">Order Confirmation</span>
                    </button>
                    <button class="btn" onclick="selectMessageType('reminder')" style="width: 100%; justify-content: center; padding: 20px; background: #ffc107; color: #000;">
                        <i class="fas fa-bell"></i>
                        <span style="font-size: 16px;">Order Reminder</span>
                    </button>
                    <button class="btn" onclick="selectMessageType('not_picking')" style="width: 100%; justify-content: center; padding: 20px; background: #dc3545; color: white;">
                        <i class="fas fa-phone-slash"></i>
                        <span style="font-size: 16px;">Not Picking Calls</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
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
        
        // Message type modal variables
        let currentMessageOrderId = null;
        let currentMessageChannel = null;
        
        // Show message type modal
        function showMessageTypeModal(orderId, channel) {
            currentMessageOrderId = orderId;
            currentMessageChannel = channel;
            document.getElementById('message-type-modal').style.display = 'flex';
        }
        
        // Close message type modal
        function closeMessageTypeModal() {
            document.getElementById('message-type-modal').style.display = 'none';
            currentMessageOrderId = null;
            currentMessageChannel = null;
        }
        
        // Select message type and send
        function selectMessageType(messageType) {
            if (currentMessageOrderId && currentMessageChannel) {
                if (currentMessageChannel === 'whatsapp') {
                    sendWhatsAppWithType(currentMessageOrderId, messageType);
                } else if (currentMessageChannel === 'email') {
                    sendEmailWithType(currentMessageOrderId, messageType);
                }
            }
            closeMessageTypeModal();
        }
    </script>
    
    <script>
        // State variables
        let orders = [];
        let allOrders = [];
        let currentPage = 1;
        let ordersPerPage = window.innerWidth <= 768 ? 10 : 20;
        let filteredOrders = [];
        let currentSearch = '';
        let currentStatusFilter = '';
        let statsData = {};

        // DOM Elements
        const ordersTbody = document.getElementById('orders-tbody');
        const ordersCards = document.getElementById('orders-cards');
        const paginationInfo = document.getElementById('pagination-info');
        const paginationControls = document.getElementById('pagination-controls');
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const editOrderModal = document.getElementById('edit-order-modal');
        const editOrderForm = document.getElementById('edit-order-form');
        const editModalClose = document.getElementById('edit-modal-close');
        const editCancelBtn = document.getElementById('edit-cancel-btn');
        const editSaveBtn = document.getElementById('edit-save-btn');
        const refreshBtn = document.getElementById('refresh-btn');
        const exportBtn = document.getElementById('export-btn');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            loadOrders();
            loadStats();
        });
        
        // Handle window resize for pagination
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const newOrdersPerPage = window.innerWidth <= 768 ? 10 : 20;
                if (newOrdersPerPage !== ordersPerPage) {
                    ordersPerPage = newOrdersPerPage;
                    currentPage = 1;
                    renderOrdersTable(allOrders);
                }
            }, 250);
        });

        // Set up event listeners
        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(loadOrders, 500));
            statusFilter.addEventListener('change', loadOrders);
            editModalClose.addEventListener('click', closeEditModal);
            editCancelBtn.addEventListener('click', closeEditModal);
            editSaveBtn.addEventListener('click', saveOrderChanges);
            refreshBtn.addEventListener('click', refreshOrders);
            exportBtn.addEventListener('click', exportOrders);
            
            // Close modal when clicking outside
            editOrderModal.addEventListener('click', function(e) {
                if (e.target === editOrderModal) {
                    closeEditModal();
                }
            });
        }

        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Load orders from API
        async function loadOrders() {
            try {
                currentSearch = searchInput.value;
                currentStatusFilter = statusFilter.value;
                
                const params = new URLSearchParams({
                    action: 'list',
                    page: currentPage,
                    per_page: ordersPerPage,
                    search: currentSearch,
                    status: currentStatusFilter
                });
                
                const response = await fetch(`api/orders.php?${params}`);
                const data = await response.json();
                
                if (data.success) {
                    orders = data.data;
                    renderOrdersTable(data);
                } else {
                    console.error('Failed to load orders:', data.message);
                    alert('Error loading orders. Please try again.');
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                alert('Error connecting to server. Please check your connection.');
            }
        }

        // Load statistics from API
        async function loadStats() {
            try {
                const response = await fetch('api/orders.php?action=stats');
                const data = await response.json();
                
                if (data.success) {
                    statsData = data.data;
                    updateStats();
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Update statistics cards
        function updateStats() {
            document.getElementById('total-orders').textContent = statsData.total || 0;
            document.getElementById('confirmed-orders').textContent = statsData.confirmed || 0;
            document.getElementById('shipped-orders').textContent = statsData.shipped || 0;
            document.getElementById('delivered-orders').textContent = statsData.delivered || 0;
        }

        // Render orders table with pagination
        function renderOrdersTable(apiData) {
            // Clear current table and cards
            ordersTbody.innerHTML = '';
            ordersCards.innerHTML = '';
            
            if (!orders || orders.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="8" style="text-align: center; padding: 20px;">No orders found</td>';
                ordersTbody.appendChild(row);
                
                const card = document.createElement('div');
                card.style.textAlign = 'center';
                card.style.padding = '40px 20px';
                card.style.color = '#999';
                card.innerHTML = '<i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>No orders found';
                ordersCards.appendChild(card);
                
                paginationInfo.textContent = 'Showing 0 orders';
                paginationControls.innerHTML = '';
                return;
            }
            
            // Populate table and cards
            orders.forEach(order => {
                // Format package name
                const packageNames = {
                    'starter': 'Starter Set',
                    'bundle': 'Learning Bundle',
                    'collection': 'Mastery Collection'
                };
                
                // Calculate amount based on package
                const amounts = {
                    'starter': '₦18,000',
                    'bundle': '₦32,000',
                    'collection': '₦45,000'
                };
                
                // Format dates
                const orderDate = order.created_at ? new Date(order.created_at).toLocaleString() : 'N/A';
                const orderDateShort = order.created_at ? new Date(order.created_at).toLocaleDateString() : 'N/A';
                const status = order.status || 'pending';
                const agentName = order.agent_name || '<span style="color: #999;">Not assigned</span>';
                
                // Create table row
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="order-id">#${order.id}</td>
                    <td>
                        <div class="customer-info">
                            <div class="customer-name">${order.fullname}</div>
                            <div class="customer-phone"><i class="fas fa-phone"></i>${order.phone || 'N/A'}</div>
                            <div class="customer-address"><i class="fas fa-map-marker-alt"></i>${order.state || 'N/A'}${order.address ? ' - ' + (order.address.length > 30 ? order.address.substring(0, 30) + '...' : order.address) : ''}</div>
                        </div>
                    </td>
                    <td>${packageNames[order.pack] || order.pack}</td>
                    <td><strong>${order.formatted_amount || amounts[order.pack] || 'N/A'}</strong></td>
                    <td>${agentName}</td>
                    <td><span class="status-badge status-${status}">${getStatusText(status)}</span></td>
                    <td>${orderDateShort}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn whatsapp-btn" data-id="${order.id}" title="Send WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="action-btn email-btn" data-id="${order.id}" title="Send Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button class="action-btn copy-btn" data-id="${order.id}" title="Copy order details">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="action-btn edit-btn" data-id="${order.id}" title="Edit order">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($canDelete): ?>
                            <button class="action-btn delete-btn" data-id="${order.id}" title="Delete order">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                `;
                ordersTbody.appendChild(row);
                
                // Create mobile card
                const card = document.createElement('div');
                card.className = 'order-card';
                card.innerHTML = `
                    <div class="order-card-header">
                        <div class="order-card-id">#${order.id}</div>
                        <span class="status-badge status-${status}">${getStatusText(status)}</span>
                    </div>
                    <div class="order-card-body">
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-user"></i>Customer</div>
                            <div class="order-card-value"><strong>${order.fullname}</strong></div>
                        </div>
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-phone"></i>Phone</div>
                            <div class="order-card-value"><a href="tel:${order.phone}" style="color: var(--primary);">${order.phone || 'N/A'}</a></div>
                        </div>
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-map-marker-alt"></i>State</div>
                            <div class="order-card-value">${order.state || 'N/A'}</div>
                        </div>
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-location-arrow"></i>Address</div>
                            <div class="order-card-value">${order.address || 'Not provided'}</div>
                        </div>
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-box"></i>Package</div>
                            <div class="order-card-value">${packageNames[order.pack] || order.pack}</div>
                        </div>
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-money-bill-wave"></i>Amount</div>
                            <div class="order-card-value"><strong>${order.formatted_amount || amounts[order.pack] || 'N/A'}</strong></div>
                        </div>
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-user-tie"></i>Agent</div>
                            <div class="order-card-value">${agentName}</div>
                        </div>
                        <div class="order-card-row">
                            <div class="order-card-label"><i class="fas fa-calendar"></i>Date</div>
                            <div class="order-card-value">${orderDateShort}</div>
                        </div>
                    </div>
                    <div class="order-card-footer" style="flex-wrap: wrap; gap: 8px;">
                        <button class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9rem; background: #25d366;" data-action="whatsapp" data-id="${order.id}">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                        <button class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9rem; background: #f57c00;" data-action="email" data-id="${order.id}">
                            <i class="fas fa-envelope"></i> Email
                        </button>
                        <button class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9rem; background: #1976d2;" data-action="copy" data-id="${order.id}">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <button class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9rem;" data-action="edit" data-id="${order.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <?php if ($canDelete): ?>
                        <button class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.9rem; background: #dc3545; color: white;" data-action="delete" data-id="${order.id}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <?php endif; ?>
                    </div>
                `;
                ordersCards.appendChild(card);
            });
            
            // Add event listeners to action buttons (table)
            document.querySelectorAll('.whatsapp-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    sendWhatsApp(orderId);
                });
            });
            
            document.querySelectorAll('.email-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    sendEmail(orderId);
                });
            });
            
            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    copyOrderDetails(orderId);
                });
            });
            
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    openEditModal(orderId);
                });
            });
            
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    deleteOrder(orderId);
                });
            });
            
            // Add event listeners to action buttons (cards)
            document.querySelectorAll('.order-card button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    const orderId = this.getAttribute('data-id');
                    
                    if (action === 'whatsapp') {
                        sendWhatsApp(orderId);
                    } else if (action === 'email') {
                        sendEmail(orderId);
                    } else if (action === 'copy') {
                        copyOrderDetails(orderId);
                    } else if (action === 'edit') {
                        openEditModal(orderId);
                    } else if (action === 'delete') {
                        deleteOrder(orderId);
                    }
                });
            });
            
            // Update pagination info
            const total = apiData.pagination?.total || orders.length;
            const page = apiData.pagination?.current_page || currentPage;
            const perPage = apiData.pagination?.per_page || ordersPerPage;
            const startIndex = (page - 1) * perPage + 1;
            const endIndex = Math.min(page * perPage, total);
            
            paginationInfo.textContent = `Showing ${startIndex}-${endIndex} of ${total} orders`;
            
            // Render pagination controls
            const totalPages = apiData.pagination?.total_pages || 1;
            renderPaginationControls(totalPages);
        }

        // Render pagination controls
        function renderPaginationControls(totalPages) {
            paginationControls.innerHTML = '';
            
            if (totalPages <= 1) return;
            
            // Previous button
            const prevButton = document.createElement('button');
            prevButton.className = `pagination-btn ${currentPage === 1 ? 'disabled' : ''}`;
            prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    loadOrders();
                }
            });
            paginationControls.appendChild(prevButton);
            
            // Page buttons (show max 5 pages)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('button');
                pageButton.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageButton.textContent = i;
                pageButton.addEventListener('click', () => {
                    currentPage = i;
                    loadOrders();
                });
                paginationControls.appendChild(pageButton);
            }
            
            // Next button
            const nextButton = document.createElement('button');
            nextButton.className = `pagination-btn ${currentPage === totalPages ? 'disabled' : ''}`;
            nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadOrders();
                }
            });
            paginationControls.appendChild(nextButton);
        }

        // Get status text for display
        function getStatusText(status) {
            const statusMap = {
                'pending': 'Pending',
                'confirmed': 'Confirmed',
                'processing': 'Processing',
                'shipped': 'Shipped',
                'delivered': 'Delivered',
                'cancelled': 'Cancelled'
            };
            
            return statusMap[status] || status;
        }

        // Open edit modal with order data
        async function openEditModal(orderId) {
            const order = orders.find(o => o.id == orderId);
            
            if (order) {
                const packageNames = {
                    'starter': 'Starter Set',
                    'bundle': 'Learning Bundle',
                    'collection': 'Mastery Collection'
                };
                
                document.getElementById('edit-order-id').value = order.id;
                document.getElementById('edit-customer-name').value = order.fullname;
                document.getElementById('edit-package').value = packageNames[order.pack] || order.pack;
                document.getElementById('edit-order-date').value = order.created_at || '';
                document.getElementById('edit-confirmed-date').value = order.confirmed_at || 'Not confirmed';
                document.getElementById('edit-status').value = order.status || 'pending';
                document.getElementById('edit-notes').value = order.admin_notes || '';
                
                editOrderModal.style.display = 'flex';
            }
        }

        // Send WhatsApp message
        function sendWhatsApp(orderId) {
            const order = orders.find(o => o.id == orderId);
            
            if (!order) {
                alert('Order not found');
                return;
            }
            
            if (!order.phone) {
                alert('Customer phone number not available');
                return;
            }
            
            // Show message type selection
            showMessageTypeModal(orderId, 'whatsapp');
        }
        
        // Send WhatsApp with type
        function sendWhatsAppWithType(orderId, messageType) {
            const order = orders.find(o => o.id == orderId);
            
            if (!order) return;
            
            // Format the order details text with message type
            const orderText = formatOrderDetailsWithType(order, messageType);
            
            // Clean phone number (remove spaces, dashes, etc.)
            let phone = order.phone.replace(/[^0-9]/g, '');
            
            // Add country code if not present (assuming Nigeria +234)
            if (phone.startsWith('0')) {
                phone = '234' + phone.substring(1);
            } else if (!phone.startsWith('234')) {
                phone = '234' + phone;
            }
            
            // Create WhatsApp URL
            const whatsappURL = `https://wa.me/${phone}?text=${encodeURIComponent(orderText)}`;
            
            // Open WhatsApp in new window
            window.open(whatsappURL, '_blank');
        }
        
        // Send Email
        function sendEmail(orderId) {
            const order = orders.find(o => o.id == orderId);
            
            if (!order) {
                alert('Order not found');
                return;
            }
            
            if (!order.email) {
                alert('Customer email not available');
                return;
            }
            
            // Show message type selection
            showMessageTypeModal(orderId, 'email');
        }
        
        // Send Email with type via SMTP
        async function sendEmailWithType(orderId, messageType) {
            const order = orders.find(o => o.id == orderId);
            
            if (!order) return;
            
            if (!order.email) {
                alert('No email address for this customer');
                return;
            }
            
            // Format the order details text with message type
            const orderText = formatOrderDetailsWithType(order, messageType);
            
            // Create email subject based on message type
            let subject = '';
            if (messageType === 'confirmation') {
                subject = `Order Confirmation - #${order.id} - Sank Magic Copy Book`;
            } else if (messageType === 'reminder') {
                subject = `Order Reminder - #${order.id} - Sank Magic Copy Book`;
            } else if (messageType === 'not_picking') {
                subject = `URGENT: Unable to Reach You - Order #${order.id}`;
            } else {
                subject = `Message - Order #${order.id} - Sank Magic Copy Book`;
            }
            
            // Show loading state
            const loadingMessage = document.createElement('div');
            loadingMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #0a7c42; color: white; padding: 15px 25px; border-radius: 5px; z-index: 10000; box-shadow: 0 2px 10px rgba(0,0,0,0.2);';
            loadingMessage.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending email...';
            document.body.appendChild(loadingMessage);
            
            try {
                // Send via SMTP API
                const response = await fetch('php/send_email_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        to: order.email,
                        to_name: order.fullname,
                        subject: subject,
                        body: orderText,
                        is_html: false
                    })
                });
                
                const result = await response.json();
                
                // Remove loading message
                document.body.removeChild(loadingMessage);
                
                if (result.success) {
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 5px; z-index: 10000; box-shadow: 0 2px 10px rgba(0,0,0,0.2);';
                    successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Email sent successfully to ' + order.email;
                    document.body.appendChild(successMessage);
                    
                    setTimeout(() => {
                        document.body.removeChild(successMessage);
                    }, 3000);
                } else {
                    throw new Error(result.message || 'Failed to send email');
                }
                
            } catch (error) {
                // Remove loading message if it exists
                if (loadingMessage.parentNode) {
                    document.body.removeChild(loadingMessage);
                }
                
                // Show error message
                alert('Failed to send email: ' + error.message);
                console.error('Email error:', error);
            }
        }
        
        // Format order details with message type
        function formatOrderDetailsWithType(order, messageType) {
            const quantity = order.quantity || 1;
            
            // Normalize package name to lowercase for comparison
            const packLower = (order.pack || '').toLowerCase();
            
            // Define base sets per package type
            const baseSets = {
                'starter': 1,
                'bundle': 2,
                'collection': 3
            };
            
            // Calculate total sets based on package type and quantity
            const totalSets = (baseSets[packLower] || 1) * quantity;
            
            // Generate package details with quantities
            let packageDetails = '';
            if (packLower === 'starter') {
                packageDetails = `Starter Set (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook)`;
            } else if (packLower === 'bundle') {
                packageDetails = `Learning Bundle (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook, ${totalSets} gaming pad${totalSets > 1 ? 's' : ''}, ${totalSets} skipping rope${totalSets > 1 ? 's' : ''}, ${totalSets} U-shape Brush${totalSets > 1 ? 'es' : ''})`;
            } else if (packLower === 'collection') {
                packageDetails = `Mastery Collection (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook, ${totalSets} gaming pad${totalSets > 1 ? 's' : ''}, ${totalSets} skipping rope${totalSets > 1 ? 's' : ''}, ${totalSets} U-shape Brush${totalSets > 1 ? 'es' : ''}, premium learning materials)`;
            } else {
                packageDetails = order.pack;
            }
            
            // Calculate total amount
            const unitPrice = packLower === 'starter' ? 18000 : (packLower === 'bundle' ? 32000 : 45000);
            const totalAmount = '₦' + (unitPrice * quantity).toLocaleString();
            
            const orderDate = order.created_at ? new Date(order.created_at).toLocaleDateString('en-GB') : 'N/A';
            const status = getStatusText(order.status || 'pending');
            
            // Format LGA/Address
            const lgaAddress = [order.local_govt, order.address].filter(Boolean).join(', ');
            
            // Expected delivery date
            const expectedDelivery = order.expected_delivery_date ? new Date(order.expected_delivery_date).toLocaleDateString('en-GB') : '';
            
            // Message header based on type
            let messageHeader = '';
            let messageIntro = '';
            
            if (messageType === 'confirmation') {
                messageHeader = 'ORDER CONFIRMATION';
                messageIntro = `Dear ${order.fullname},\n\nThank you for your order! We are pleased to confirm your order details below:`;
            } else if (messageType === 'reminder') {
                messageHeader = 'ORDER REMINDER';
                messageIntro = `Dear ${order.fullname},\n\nThis is a friendly reminder about your order. Please find the details below:`;
            } else if (messageType === 'not_picking') {
                messageHeader = 'URGENT: UNABLE TO REACH YOU';
                messageIntro = `Dear ${order.fullname},\n\nWe've been trying to reach you regarding your order but couldn't get through.\n\nPlease contact us as soon as possible to confirm your delivery details.`;
            }
            
            // Build the complete message
            let message = `━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\nSANK MAGIC COPY BOOK - ${messageHeader}\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n`;
            message += `${messageIntro}\n\n`;
            message += `Order ID: #${order.id}\n`;
            message += `Status: ${status}\n`;
            message += `Order Date: ${orderDate}\n\n`;
            message += `👤 CUSTOMER INFORMATION\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n`;
            message += `Name: ${order.fullname}\n`;
            message += `Phone: ${order.phone}\n\n`;
            message += `📍 DELIVERY INFORMATION\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n`;
            message += `State: ${order.state}\n`;
            message += `LGA/Address: ${lgaAddress}\n\n`;
            message += `📦 ORDER DETAILS\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n`;
            message += `Package: ${packageDetails}\n`;
            message += `Quantity: ${quantity}\n`;
            message += `Amount: ${totalAmount}`;
            
            if (expectedDelivery) {
                message += `\nExpected Delivery: ${expectedDelivery}`;
            }
            
            // Add closing message based on type
            if (messageType === 'confirmation') {
                message += `\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\nYour order is being processed and will be delivered soon.\n\nWhen will you be available for delivery?\n\nThank you for choosing Sank Magic Copy Book!`;
            } else if (messageType === 'reminder') {
                message += `\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\nIf you have any questions or concerns about your order, please feel free to contact us.`;
            } else if (messageType === 'not_picking') {
                message += `\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n⚠️ IMPORTANT: Please call us back or reply to this message with your availability.\n\nContact Numbers:\n📞 09029026782\n📞 08102609396\n\nWe need to confirm your delivery details to proceed with your order.\n\nThank you for your cooperation!`;
            }
            
            return message;
        }
        
        // Format order details (shared function)
        function formatOrderDetails(order) {
            const quantity = order.quantity || 1;
            
            // Normalize package name to lowercase for comparison
            const packLower = (order.pack || '').toLowerCase();
            
            // Define base sets per package type
            const baseSets = {
                'starter': 1,
                'bundle': 2,
                'collection': 3
            };
            
            // Calculate total sets based on package type and quantity
            const totalSets = (baseSets[packLower] || 1) * quantity;
            
            // Generate package details with quantities
            // Free gifts quantity = total sets (1 set = 1 gift each, 2 sets = 2 gifts each, 3 sets = 3 gifts each)
            let packageDetails = '';
            if (packLower === 'starter') {
                packageDetails = `Starter Set (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook)`;
            } else if (packLower === 'bundle') {
                packageDetails = `Learning Bundle (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook, ${totalSets} gaming pad${totalSets > 1 ? 's' : ''}, ${totalSets} skipping rope${totalSets > 1 ? 's' : ''}, ${totalSets} U-shape Brush${totalSets > 1 ? 'es' : ''})`;
            } else if (packLower === 'collection') {
                packageDetails = `Mastery Collection (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook, ${totalSets} gaming pad${totalSets > 1 ? 's' : ''}, ${totalSets} skipping rope${totalSets > 1 ? 's' : ''}, ${totalSets} U-shape Brush${totalSets > 1 ? 'es' : ''}, premium learning materials)`;
            } else {
                packageDetails = order.pack;
            }
            
            // Calculate total amount
            const unitPrice = packLower === 'starter' ? 18000 : (packLower === 'bundle' ? 32000 : 45000);
            const totalAmount = '₦' + (unitPrice * quantity).toLocaleString();
            
            const orderDate = order.created_at ? new Date(order.created_at).toLocaleDateString('en-GB') : 'N/A';
            const status = getStatusText(order.status || 'pending');
            
            // Format LGA/Address
            let lgaAddress = '';
            if (order.local_govt && order.address) {
                lgaAddress = order.local_govt + ', ' + order.address;
            } else if (order.local_govt) {
                lgaAddress = order.local_govt;
            } else if (order.address) {
                lgaAddress = order.address;
            } else {
                lgaAddress = 'N/A';
            }
            
            return `
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
SANK MAGIC COPY BOOK - ORDER DETAILS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Status: ${status}
Order Date: ${orderDate}

👤 CUSTOMER INFORMATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Name: ${order.fullname}
Phone: ${order.phone || 'N/A'}


📍 DELIVERY INFORMATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
State: ${order.state || 'N/A'}
LGA/Address: ${lgaAddress}

📦 ORDER DETAILS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Package details: 
${packageDetails}
Quantity: ${quantity}
Amount: ${totalAmount}
Expected Delivery Date: ${order.expected_delivery_date ? new Date(order.expected_delivery_date).toLocaleDateString('en-GB') : ''}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            `.trim();
        }
        
        // Copy order details to clipboard
        function copyOrderDetails(orderId) {
            const order = orders.find(o => o.id == orderId);
            
            if (!order) {
                alert('Order not found');
                return;
            }
            
            const orderDetails = formatOrderDetails(order);
            
            // Copy to clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(orderDetails)
                    .then(() => {
                        showCopyNotification();
                    })
                    .catch(err => {
                        console.error('Failed to copy:', err);
                        fallbackCopy(orderDetails);
                    });
            } else {
                fallbackCopy(orderDetails);
            }
        }
        
        // Fallback copy method for older browsers
        function fallbackCopy(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                showCopyNotification();
            } catch (err) {
                console.error('Fallback copy failed:', err);
                alert('Failed to copy to clipboard. Please try again.');
            }
            
            document.body.removeChild(textarea);
        }
        
        // Show copy notification
        function showCopyNotification() {
            const notification = document.getElementById('copy-notification');
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
        
        // Close edit modal
        function closeEditModal() {
            editOrderModal.style.display = 'none';
        }

        // Save order changes
        async function saveOrderChanges() {
            const orderId = document.getElementById('edit-order-id').value;
            const newStatus = document.getElementById('edit-status').value;
            const notes = document.getElementById('edit-notes').value;
            
            try {
                const response = await fetch('api/orders.php?action=update_status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: newStatus,
                        notes: notes
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Order updated successfully!');
                    closeEditModal();
                    loadOrders();
                    loadStats();
                } else {
                    alert('Error updating order: ' + data.message);
                }
            } catch (error) {
                console.error('Error saving order:', error);
                alert('Error connecting to server. Please try again.');
            }
        }

        // Delete order
        async function deleteOrder(orderId) {
            if (!confirm(`Are you sure you want to delete order ${orderId}? This action cannot be undone.`)) {
                return;
            }
            
            try {
                const response = await fetch('api/orders.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: orderId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Order deleted successfully!');
                    loadOrders();
                    loadStats();
                } else {
                    alert('Error deleting order: ' + data.message);
                }
            } catch (error) {
                console.error('Error deleting order:', error);
                alert('Error connecting to server. Please try again.');
            }
        }

        // Refresh orders
        function refreshOrders() {
            currentPage = 1;
            loadOrders();
            loadStats();
        }

        // Export orders
        async function exportOrders() {
            try {
                const startDate = prompt('Enter start date (YYYY-MM-DD):', getDateWeeksAgo(4));
                const endDate = prompt('Enter end date (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
                
                if (!startDate || !endDate) return;
                
                const params = new URLSearchParams({
                    action: 'sales_report',
                    start_date: startDate,
                    end_date: endDate,
                    group_by: 'day'
                });
                
                const response = await fetch(`api/orders.php?${params}`);
                const data = await response.json();
                
                if (data.success) {
                    // Convert to CSV
                    let csv = 'Order ID,Customer,Package,State,Amount,Status,Order Date,Confirmed Date,Delivered Date,Agent\n';
                    
                    data.data.orders.forEach(order => {
                        const packageNames = {
                            'starter': 'Starter Set',
                            'bundle': 'Learning Bundle',
                            'collection': 'Mastery Collection'
                        };
                        const amounts = { 'starter': 18000, 'bundle': 32000, 'collection': 45000 };
                        
                        csv += `${order.id},"${order.fullname}","${packageNames[order.pack]}","${order.state}",${amounts[order.pack]},"${order.status}","${order.created_at}","${order.confirmed_at || ''}","${order.delivered_at || ''}","${order.agent_name || ''}"\n`;
                    });
                    
                    // Download CSV
                    const blob = new Blob([csv], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `orders_${startDate}_to_${endDate}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                } else {
                    alert('Error exporting orders: ' + data.message);
                }
            } catch (error) {
                console.error('Error exporting orders:', error);
                alert('Error exporting orders. Please try again.');
            }
        }

        // Helper function to get date weeks ago
        function getDateWeeksAgo(weeks) {
            const date = new Date();
            date.setDate(date.getDate() - (weeks * 7));
            return date.toISOString().split('T')[0];
        }
    </script>
</body>
</html>