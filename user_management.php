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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a7c42;
            --primary-light: #e8f5e9;
            --secondary: #ff6b35;
            --dark: #2c3e50;
            --light: #f8f9fa;
            --accent: #ffd700;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* Layout with Sidebar */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
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
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--primary);
        }

        .sidebar-logo-icon {
            font-size: 1.5rem;
        }

        .sidebar-logo-text {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 15px 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu-item {
            margin: 0;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #666;
            text-decoration: none;
            transition: var(--transition);
        }

        .sidebar-menu-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-menu-link.active {
            background: var(--primary);
            color: white;
            font-weight: 600;
        }

        .sidebar-menu-link i {
            width: 20px;
            font-size: 1.1rem;
        }

        .sidebar-divider {
            height: 1px;
            background: #eee;
            margin: 15px 20px;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #eee;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .sidebar-user-info {
            flex: 1;
        }

        .sidebar-user-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .sidebar-user-role {
            font-size: 0.75rem;
            color: #999;
            text-transform: capitalize;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: white;
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-breadcrumb {
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
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
            gap: 12px;
        }

        .header-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .header-user-info {
            text-align: right;
        }

        .header-user-name {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .header-user-role {
            font-size: 12px;
            color: #666;
            text-transform: capitalize;
        }

        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-warning {
            background: #ffc107;
            color: #000;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .card-header h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-admin {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
        }

        .badge-subadmin {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .badge-agent {
            background: rgba(255, 193, 7, 0.2);
            color: #f57c00;
        }

        .badge-active {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .badge-inactive {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
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

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h3 {
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--primary);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: var(--transition);
        }

        .mobile-menu-toggle:hover {
            background: var(--dark);
        }

        .mobile-menu-toggle i {
            font-size: 20px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
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

        @media (max-width: 968px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-wrapper {
                margin-left: 0;
                padding: 80px 15px 15px;
            }

            .container {
                padding: 0;
            }

            header {
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .page-breadcrumb {
                font-size: 12px;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .card-header h2 {
                font-size: 18px;
            }

            .card-header .btn {
                width: 100%;
                justify-content: center;
            }

            table {
                font-size: 12px;
            }

            table thead th {
                font-size: 11px;
                padding: 10px 8px;
            }

            table tbody td {
                padding: 10px 8px;
                font-size: 11px;
            }

            .table-wrapper {
                margin: 15px -15px;
                border-radius: 0;
            }

            #users-table {
                min-width: 900px;
            }

            table thead,
            table tbody,
            table tr {
                display: table;
                width: 100%;
                table-layout: fixed;
            }

            table th,
            table td {
                padding: 8px 4px;
                font-size: 10px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .action-buttons .btn {
                font-size: 10px;
                padding: 4px 8px;
                width: 100%;
            }

            .badge {
                font-size: 9px;
                padding: 3px 6px;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
                max-height: 85vh;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                font-size: 13px;
            }

            .form-group input,
            .form-group select {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
                top: 10px;
                left: 10px;
            }

            .main-wrapper {
                padding: 70px 10px 10px;
            }

            table {
                font-size: 10px;
            }

            table th,
            table td {
                padding: 8px 5px;
            }

            #users-table {
                min-width: 800px;
            }

            .table-wrapper {
                box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            }

            .btn {
                font-size: 11px;
                padding: 6px 10px;
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
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

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
                        <a href="stock_management.php" class="sidebar-menu-link">
                            <i class="fas fa-boxes"></i>
                            <span>Stock Management</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="pricing_management.php" class="sidebar-menu-link">
                            <i class="fas fa-tags"></i>
                            <span>Pricing</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="user_management.php" class="sidebar-menu-link active">
                            <i class="fas fa-users-cog"></i>
                            <span>Users</span>
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

    <div class="container">

        <div class="content-card">
            <div class="card-header">
                <h2>System Users</h2>
                <button class="btn btn-success" onclick="openAddUserModal()">
                    <i class="fas fa-user-plus"></i> Add New User
                </button>
            </div>

            <div id="alert-container"></div>

            <div class="table-wrapper">
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

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.getElementById('mobile-menu-toggle');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Change icon
            const icon = toggle.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }

        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.sidebar-menu-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 968) {
                    toggleSidebar();
                }
            });
        });

        // Close sidebar on window resize if desktop
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.getElementById('mobile-menu-toggle');
            
            if (window.innerWidth > 968) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                const icon = toggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    </script>
        </div>
    </div>
</body>
</html>
