<?php
require_once 'php/auth.php';
requireAdmin(); // Only admins can manage users

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include 'php/content_protection.php'; ?>
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
        
        /* Main Content Wrapper */
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
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: var(--primary);
            color: white;
        }
        
        .btn-success:hover {
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
        
        .btn-danger {
            background: var(--secondary);
            color: white;
        }
        
        .btn-danger:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: #ffc107;
            color: #000;
        }
        
        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
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

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            text-transform: capitalize;
        }
        
        .badge-admin {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-subadmin {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-agent {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
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
        
        .modal.show {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title,
        .modal-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .close-btn,
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
            transition: var(--transition);
        }
        
        .close-btn:hover,
        .modal-close:hover {
            color: var(--secondary);
        }
        
        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
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
        
        .form-control,
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus,
        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(10, 124, 66, 0.1);
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }



        /* Responsive table wrapper with scroll indicators */
        .table-wrapper {
            position: relative;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .table-wrapper::before,
        .table-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 30px;
            pointer-events: none;
            z-index: 2;
            transition: opacity 0.3s ease;
        }

        .table-wrapper::before {
            left: 0;
            background: linear-gradient(to right, rgba(255,255,255,0.95), transparent);
            opacity: 0;
        }

        .table-wrapper::after {
            right: 0;
            background: linear-gradient(to left, rgba(255,255,255,0.95), transparent);
            opacity: 0;
        }

        .table-wrapper.scroll-left::before {
            opacity: 1;
        }

        .table-wrapper.scroll-right::after {
            opacity: 1;
        }

        .table-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: #5568d3;
        }

        #users-table {
            min-width: 1000px;
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
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .card-header h2 {
                font-size: 1.1rem;
            }
            
            .card-header .btn {
                width: 100%;
                justify-content: center;
            }
            
            .table-wrapper {
                margin: 0 -15px;
            }
            
            #users-table {
                min-width: 900px;
            }
            
            table th,
            table td {
                padding: 12px 8px;
                font-size: 0.85rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-buttons .btn {
                width: 100%;
                justify-content: center;
                font-size: 0.8rem;
            }
            
            .badge {
                font-size: 0.75rem;
                padding: 4px 8px;
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
            
            .modal-content {
                width: 95%;
            }
        }

        @media (max-width: 576px) {
            #users-table {
                min-width: 800px;
            }
            
            table th,
            table td {
                padding: 10px 5px;
                font-size: 0.75rem;
            }
            
            .btn {
                font-size: 0.8rem;
                padding: 8px 12px;
            }
            
            .action-buttons .btn i {
                margin-right: 0;
            }
            
            .action-buttons .btn span {
                display: none;
            }
            
            .modal-content {
                width: 98%;
            }
            
            .modal-header h3 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

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
                        <a href="customer_orderlist.php" class="sidebar-menu-link">
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
                        <a href="user_management.php" class="sidebar-menu-link active">
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
                            <span>User Management</span>
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
            <div class="content-card">
                <div class="card-header">
                    <h2>System Users</h2>
                    <button class="btn btn-success" onclick="openAddUserModal()">
                        <i class="fas fa-user-plus"></i> Add New User
                    </button>
                </div>

                <div id="alert-container"></div>

                <div class="table-responsive">
                    <table id="users-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #667eea;"></i>
                            <p>Loading users...</p>
                        </td>
                    </tr>
                </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Add/Edit User Modal -->
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Add New User</h3>
                <button class="close-btn" onclick="closeUserModal()">&times;</button>
            </div>

            <form id="user-form">
                <input type="hidden" id="user-id" name="user_id">

                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" id="full-name" name="full_name" required>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Role *</label>
                    <select id="role" name="role" required>
                        <option value="subadmin">Subadmin</option>
                        <option value="agent">Agent</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group" id="password-group">
                    <label>Password <span id="password-optional">(Leave blank to keep current)</span></label>
                    <input type="password" id="password" name="password">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Minimum 6 characters
                    </small>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success" id="submit-btn">
                        <i class="fas fa-save"></i> Save User
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeUserModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let users = [];
        let editingUserId = null;

        // Initialize table scroll indicators
        function initTableScroll() {
            const tableWrapper = document.querySelector('.table-wrapper');
            if (!tableWrapper) return;

            function updateScrollIndicators() {
                const scrollLeft = tableWrapper.scrollLeft;
                const scrollWidth = tableWrapper.scrollWidth;
                const clientWidth = tableWrapper.clientWidth;
                const maxScroll = scrollWidth - clientWidth;

                // Add/remove classes based on scroll position
                if (scrollLeft > 10) {
                    tableWrapper.classList.add('scroll-left');
                } else {
                    tableWrapper.classList.remove('scroll-left');
                }

                if (scrollLeft < maxScroll - 10) {
                    tableWrapper.classList.add('scroll-right');
                } else {
                    tableWrapper.classList.remove('scroll-right');
                }
            }

            // Update on scroll
            tableWrapper.addEventListener('scroll', updateScrollIndicators);
            
            // Update on window resize
            window.addEventListener('resize', updateScrollIndicators);
            
            // Initial check
            setTimeout(updateScrollIndicators, 100);
        }

        // Load users on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
            initTableScroll();
        });

        async function loadUsers() {
            try {
                const response = await fetch('api/users.php?action=list');
                const data = await response.json();

                if (data.success) {
                    users = data.data;
                    renderUsersTable();
                } else {
                    showAlert('Error loading users: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to load users', 'error');
            }
        }

        function renderUsersTable() {
            const tbody = document.querySelector('#users-table tbody');

            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px;">No users found</td></tr>';
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>${user.id}</td>
                    <td><strong>${user.username}</strong></td>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td><span class="badge badge-${user.role}">${user.role}</span></td>
                    <td><span class="badge badge-${user.status}">${user.status}</span></td>
                    <td>${user.last_login ? new Date(user.last_login).toLocaleString() : 'Never'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm" onclick="editUser(${user.id})" title="Edit user details">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            ${user.id !== <?= $currentUser['id'] ?> ? `
                                <button class="btn btn-${user.status === 'active' ? 'warning' : 'success'} btn-sm" 
                                        onclick="toggleUserStatus(${user.id}, '${user.username}', '${user.status}')"
                                        title="${user.status === 'active' ? 'Deactivate' : 'Activate'} account">
                                    <i class="fas fa-${user.status === 'active' ? 'ban' : 'check-circle'}"></i> 
                                    ${user.status === 'active' ? 'Deactivate' : 'Activate'}
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id}, '${user.username}')" title="Delete user">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            ` : '<span style="color: #999; font-size: 0.85rem;"><i class="fas fa-user-shield"></i> Current User</span>'}
                        </div>
                    </td>
                </tr>
            `).join('');

            // Reinitialize scroll indicators after table content changes
            setTimeout(() => {
                const tableWrapper = document.querySelector('.table-wrapper');
                if (tableWrapper) {
                    const event = new Event('scroll');
                    tableWrapper.dispatchEvent(event);
                }
            }, 50);
        }

        function openAddUserModal() {
            editingUserId = null;
            document.getElementById('modal-title').textContent = 'Add New User';
            document.getElementById('user-form').reset();
            document.getElementById('user-id').value = '';
            document.getElementById('password').required = true;
            document.getElementById('password-optional').style.display = 'none';
            document.getElementById('user-modal').classList.add('show');
        }

        function editUser(userId) {
            const user = users.find(u => u.id === userId);
            if (!user) return;

            editingUserId = userId;
            document.getElementById('modal-title').textContent = 'Edit User';
            document.getElementById('user-id').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('full-name').value = user.full_name;
            document.getElementById('email').value = user.email;
            document.getElementById('role').value = user.role;
            document.getElementById('status').value = user.status;
            document.getElementById('password').value = '';
            document.getElementById('password').required = false;
            document.getElementById('password-optional').style.display = 'inline';
            document.getElementById('user-modal').classList.add('show');
        }

        function closeUserModal() {
            document.getElementById('user-modal').classList.remove('show');
        }

        document.getElementById('user-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            try {
                const action = editingUserId ? 'update' : 'create';
                const response = await fetch(`api/users.php?action=${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    closeUserModal();
                    loadUsers();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to save user', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Save User';
            }
        });

        async function toggleUserStatus(userId, username, currentStatus) {
            const action = currentStatus === 'active' ? 'deactivate' : 'activate';
            const message = currentStatus === 'active' 
                ? `Are you sure you want to deactivate user "${username}"? They will not be able to log in.`
                : `Are you sure you want to activate user "${username}"? They will be able to log in.`;

            if (!confirm(message)) {
                return;
            }

            try {
                const response = await fetch(`api/users.php?action=toggle_status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    loadUsers();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to update user status', 'error');
            }
        }

        async function deleteUser(userId, username) {
            if (!confirm(`Are you sure you want to delete user "${username}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`api/users.php?action=delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    loadUsers();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to delete user', 'error');
            }
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

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
        
        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.sidebar-menu-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });
    </script>
        </div><!-- End main-wrapper -->
    </div><!-- End layout-wrapper -->
    
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
</body>
</html>
