<?php
require_once 'php/auth.php';
requireLogin(); // Require authentication

$currentUser = getCurrentUser();
$canManageStock = canPerform('add_expense'); // Subadmins and admins can manage stock
$isAdminUser = isAdmin(); // Full admin access
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
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

        h1 {
            color: var(--dark);
            margin-bottom: 10px;
            font-size: 28px;
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.primary {
            background: var(--primary-light);
            color: var(--primary);
        }

        .stat-icon.warning {
            background: #fff3cd;
            color: var(--warning);
        }

        .stat-icon.danger {
            background: #f8d7da;
            color: var(--danger);
        }

        .stat-icon.info {
            background: #d1ecf1;
            color: var(--info);
        }

        .stat-content h3 {
            font-size: 32px;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-content p {
            color: #666;
            font-size: 14px;
        }

        /* Content Card */
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
            color: var(--dark);
            font-size: 22px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
        }

        .tab {
            padding: 12px 24px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: #666;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: -2px;
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #085a30;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--primary);
            color: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            font-weight: 600;
            font-size: 14px;
        }

        tbody tr:hover {
            background: var(--light);
        }

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
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

        /* Responsive */
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

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 22px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
            }

            .tab {
                font-size: 13px;
                padding: 10px 16px;
                white-space: nowrap;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px 8px;
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
                        <a href="agent_management.php" class="sidebar-menu-link">
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
                    <li class="sidebar-menu-item">
                        <a href="stock_management.php" class="sidebar-menu-link active">
                            <i class="fas fa-boxes"></i>
                            <span>Stock Management</span>
                        </a>
                    </li>
                    
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

        <!-- Main Content -->
        <main class="main-wrapper">
            <div class="container">
                <header>
                    <h1><i class="fas fa-boxes"></i> Stock Management</h1>
                    <div class="page-breadcrumb">
                        <i class="fas fa-home"></i>
                        <a href="index.php">Dashboard</a>
                        <i class="fas fa-chevron-right"></i>
                        <span>Stock Management</span>
                    </div>
                </header>

                <!-- Alert Container -->
                <div id="alert-container"></div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-content">
                            <h3 id="total-items">0</h3>
                            <p>Total Items in Stock</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon info">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-content">
                            <h3 id="total-value">₦0</h3>
                            <p>Total Inventory Value</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 id="low-stock">0</h3>
                            <p>Low Stock Alerts</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon danger">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="stat-content">
                            <h3 id="out-of-stock">0</h3>
                            <p>Out of Stock</p>
                        </div>
                    </div>
                </div>

                <!-- Stock Content -->
                <div class="content-card">
                    <div class="card-header">
                        <h2>Inventory Overview</h2>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-primary" onclick="openAddStockModal()">
                                <i class="fas fa-plus"></i> Add Stock
                            </button>
                            <button class="btn btn-secondary" onclick="refreshStock()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="tabs">
                        <button class="tab active" onclick="switchTab('all')">All Stock</button>
                        <button class="tab" onclick="switchTab('low')">Low Stock</button>
                        <button class="tab" onclick="switchTab('movements')">Stock Movements</button>
                    </div>

                    <!-- All Stock Table -->
                    <div id="all-stock-content" class="tab-content">
                        <div class="table-wrapper">
                            <table id="stock-table">
                                <thead>
                                    <tr>
                                        <th>State</th>
                                        <th>Package Type</th>
                                        <th>Quantity</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: var(--primary);"></i>
                                            <p>Loading stock data...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Low Stock Table -->
                    <div id="low-stock-content" class="tab-content" style="display: none;">
                        <div class="table-wrapper">
                            <table id="low-stock-table">
                                <thead>
                                    <tr>
                                        <th>State</th>
                                        <th>Package Type</th>
                                        <th>Quantity</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: var(--primary);"></i>
                                            <p>Loading low stock data...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Stock Movements Table -->
                    <div id="movements-content" class="tab-content" style="display: none;">
                        <div class="table-wrapper">
                            <table id="movements-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>State</th>
                                        <th>Package</th>
                                        <th>Type</th>
                                        <th>Change</th>
                                        <th>Agent</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: var(--primary);"></i>
                                            <p>Loading movements...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Update Stock Modal -->
    <div id="stock-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Update Stock</h3>
                <button class="close-btn" onclick="closeStockModal()">&times;</button>
            </div>

            <form id="stock-form">
                <div class="form-group">
                    <label>State *</label>
                    <select id="state" name="state" required>
                        <option value="">Select State</option>
                        <!-- Will be populated dynamically -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Package Type *</label>
                    <select id="package-type" name="package_type" required>
                        <option value="">Select Package</option>
                        <option value="starter">Starter Package</option>
                        <option value="bundle">Bundle Package</option>
                        <option value="collection">Collection Package</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>New Quantity *</label>
                    <input type="number" id="quantity" name="quantity" min="0" required>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Add any notes about this stock update..."></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-save"></i> Update Stock
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeStockModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTab = 'all';
        let stockData = [];
        let lowStockData = [];
        let movementsData = [];

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            loadSummary();
            loadAllStock();
            loadLowStock();
            loadMovements();
            loadStates();
        });

        async function loadSummary() {
            try {
                const response = await fetch('api/stock.php?action=summary');
                const data = await response.json();

                if (data.success) {
                    const summary = data.data;
                    
                    // Calculate total items
                    let totalItems = 0;
                    summary.by_package.forEach(pkg => {
                        totalItems += parseInt(pkg.total_quantity);
                    });

                    document.getElementById('total-items').textContent = totalItems;
                    document.getElementById('total-value').textContent = summary.formatted_value;
                    document.getElementById('low-stock').textContent = summary.low_stock_alerts;
                    document.getElementById('out-of-stock').textContent = summary.out_of_stock;
                }
            } catch (error) {
                console.error('Error loading summary:', error);
            }
        }

        async function loadAllStock() {
            try {
                const response = await fetch('api/stock.php');
                const data = await response.json();

                if (data.success) {
                    stockData = data.data;
                    renderStockTable();
                }
            } catch (error) {
                console.error('Error loading stock:', error);
                showAlert('Failed to load stock data', 'error');
            }
        }

        async function loadLowStock() {
            try {
                const response = await fetch('api/stock.php?action=low_stock&threshold=10');
                const data = await response.json();

                if (data.success) {
                    lowStockData = data.data;
                    renderLowStockTable();
                }
            } catch (error) {
                console.error('Error loading low stock:', error);
            }
        }

        async function loadMovements() {
            try {
                const response = await fetch('api/stock.php?action=movements&limit=100');
                const data = await response.json();

                if (data.success) {
                    movementsData = data.data;
                    renderMovementsTable();
                }
            } catch (error) {
                console.error('Error loading movements:', error);
            }
        }

        function renderStockTable() {
            const tbody = document.querySelector('#stock-table tbody');

            if (stockData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;">No stock data found</td></tr>';
                return;
            }

            tbody.innerHTML = stockData.map(stock => {
                const status = stock.quantity === 0 ? 'Out of Stock' : stock.quantity <= 5 ? 'Low Stock' : 'In Stock';
                const badge = stock.quantity === 0 ? 'danger' : stock.quantity <= 5 ? 'warning' : 'success';
                const packageName = stock.package_type.charAt(0).toUpperCase() + stock.package_type.slice(1);

                return `
                    <tr>
                        <td><strong>${stock.state}</strong></td>
                        <td>${packageName}</td>
                        <td><strong>${stock.quantity}</strong></td>
                        <td>${stock.agent_name || 'N/A'}</td>
                        <td><span class="badge badge-${badge}">${status}</span></td>
                        <td>${stock.updated_at ? new Date(stock.updated_at).toLocaleDateString() : 'N/A'}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="editStock('${stock.state}', '${stock.package_type}', ${stock.quantity})">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderLowStockTable() {
            const tbody = document.querySelector('#low-stock-table tbody');

            if (lowStockData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">No low stock items</td></tr>';
                return;
            }

            tbody.innerHTML = lowStockData.map(stock => {
                const status = stock.quantity === 0 ? 'Out of Stock' : 'Low Stock';
                const badge = stock.quantity === 0 ? 'danger' : 'warning';
                const packageName = stock.package_type.charAt(0).toUpperCase() + stock.package_type.slice(1);

                return `
                    <tr>
                        <td><strong>${stock.state}</strong></td>
                        <td>${packageName}</td>
                        <td><strong>${stock.quantity}</strong></td>
                        <td>${stock.agent_name || 'N/A'}</td>
                        <td><span class="badge badge-${badge}">${status}</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="editStock('${stock.state}', '${stock.package_type}', ${stock.quantity})">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderMovementsTable() {
            const tbody = document.querySelector('#movements-table tbody');

            if (movementsData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;">No movements recorded</td></tr>';
                return;
            }

            tbody.innerHTML = movementsData.map(movement => {
                const changeIcon = movement.quantity_change > 0 ? '+' : '';
                const changeColor = movement.quantity_change > 0 ? 'green' : 'red';
                const packageName = movement.package_type.charAt(0).toUpperCase() + movement.package_type.slice(1);

                return `
                    <tr>
                        <td>${new Date(movement.created_at).toLocaleString()}</td>
                        <td>${movement.state}</td>
                        <td>${packageName}</td>
                        <td><span class="badge badge-${movement.movement_type === 'stock_in' ? 'success' : 'warning'}">${movement.movement_type}</span></td>
                        <td style="color: ${changeColor}; font-weight: bold;">${changeIcon}${movement.quantity_change}</td>
                        <td>${movement.agent_name || 'N/A'}</td>
                        <td>${movement.notes || '-'}</td>
                    </tr>
                `;
            }).join('');
        }

        function switchTab(tab) {
            currentTab = tab;

            // Update tab buttons
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');

            // Hide all content
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');

            // Show selected content
            if (tab === 'all') {
                document.getElementById('all-stock-content').style.display = 'block';
            } else if (tab === 'low') {
                document.getElementById('low-stock-content').style.display = 'block';
            } else if (tab === 'movements') {
                document.getElementById('movements-content').style.display = 'block';
            }
        }

        function openAddStockModal() {
            document.getElementById('modal-title').textContent = 'Add Stock';
            document.getElementById('stock-form').reset();
            document.getElementById('stock-modal').classList.add('show');
        }

        function editStock(state, packageType, currentQuantity) {
            document.getElementById('modal-title').textContent = 'Update Stock';
            document.getElementById('state').value = state;
            document.getElementById('package-type').value = packageType;
            document.getElementById('quantity').value = currentQuantity;
            document.getElementById('stock-modal').classList.add('show');
        }

        function closeStockModal() {
            document.getElementById('stock-modal').classList.remove('show');
        }

        document.getElementById('stock-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = {
                state: formData.get('state'),
                package_type: formData.get('package_type'),
                quantity: parseInt(formData.get('quantity')),
                updated_by: '<?= $currentUser['username'] ?>'
            };

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            try {
                const response = await fetch('api/stock.php?action=update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    closeStockModal();
                    refreshStock();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to update stock', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Stock';
            }
        });

        async function refreshStock() {
            await loadSummary();
            await loadAllStock();
            await loadLowStock();
            await loadMovements();
            showAlert('Stock data refreshed', 'success');
        }

        async function loadStates() {
            // Load Nigerian states for the dropdown
            const states = [
                'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River',
                'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano',
                'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun',
                'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
            ];

            const stateSelect = document.getElementById('state');
            states.forEach(state => {
                const option = document.createElement('option');
                option.value = state;
                option.textContent = state;
                stateSelect.appendChild(option);
            });
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
</body>
</html>
