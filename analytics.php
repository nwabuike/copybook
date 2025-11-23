<?php
require_once 'php/auth.php';
requireAdmin(); // Only admins can view analytics

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics & Reports | Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            min-height: 100vh;
        }
        
        /* Header */
        header {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666;
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
            font-size: 0.85rem;
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
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 10px;
        }

        .date-filter {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            color: var(--text);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group input, .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
        }

        .filter-group input:focus, .filter-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(10, 124, 66, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.revenue {
            background: rgba(10, 124, 66, 0.1);
            color: var(--primary);
        }

        .stat-icon.orders {
            background: rgba(255, 107, 107, 0.1);
            color: var(--secondary);
        }

        .stat-icon.average {
            background: rgba(255, 209, 102, 0.1);
            color: var(--accent);
        }

        .stat-icon.profit {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .stat-change {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }

        .stat-change.positive {
            color: #28a745;
        }

        .stat-change.negative {
            color: #dc3545;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .content-card h2 {
            color: var(--text);
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-container {
            position: relative;
            height: 350px;
            margin-top: 20px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: var(--light);
            color: var(--text);
            font-weight: 600;
            font-size: 14px;
        }

        td {
            font-size: 14px;
            color: #555;
        }

        tbody tr:hover {
            background: var(--primary-light);
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

        .amount {
            font-weight: 600;
            color: #28a745;
        }

        .quick-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .quick-filter-btn {
            padding: 8px 16px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .quick-filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .quick-filter-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-data i {
            font-size: 60px;
            margin-bottom: 20px;
            color: #ddd;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #f57c00;
        }

        .status-confirmed {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
        }

        .status-processing {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
        }

        .status-shipped {
            background: rgba(111, 66, 193, 0.2);
            color: #6f42c1;
        }

        .status-delivered {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-cancelled {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
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
            }
            
            .main-wrapper {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .date-filter {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }

            .chart-container {
                height: 250px;
            }

            .header-user-info {
                display: none;
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
                        <a href="stock_management.php" class="sidebar-menu-link">
                            <i class="fas fa-boxes"></i>
                            <span>Stock Management</span>
                        </a>
                    </li>
                    
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
                        <a href="analytics.php" class="sidebar-menu-link active">
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
                            <a href="admin_dashboard_crm.php">Dashboard</a>
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                            <span>Analytics & Reports</span>
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
                        <h1 class="page-title"><i class="fas fa-chart-bar"></i> Analytics & Reports</h1>
                    </div>

        <!-- Date Filter -->
        <div class="date-filter">
            <div class="quick-filters" style="flex-basis: 100%;">
                <button class="quick-filter-btn" data-range="today">Today</button>
                <button class="quick-filter-btn active" data-range="week">This Week</button>
                <button class="quick-filter-btn" data-range="month">This Month</button>
                <button class="quick-filter-btn" data-range="last-month">Last Month</button>
                <button class="quick-filter-btn" data-range="year">This Year</button>
                <button class="quick-filter-btn" data-range="custom">Custom Range</button>
            </div>
            
            <div class="filter-group">
                <label>Start Date</label>
                <input type="date" id="start-date">
            </div>

            <div class="filter-group">
                <label>End Date</label>
                <input type="date" id="end-date">
            </div>

            <div class="filter-group">
                <label>Group By</label>
                <select id="group-by">
                    <option value="day">Daily</option>
                    <option value="week" selected>Weekly</option>
                    <option value="month">Monthly</option>
                </select>
            </div>

            <div class="filter-group">
                <button class="btn" id="apply-filter-btn">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
            </div>

            <div class="filter-group">
                <button class="btn btn-success" id="export-btn">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value" id="total-revenue">₦0</div>
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-change positive" id="revenue-change">
                            <i class="fas fa-arrow-up"></i> 0% from previous period
                        </div>
                    </div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-naira-sign"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value" id="total-orders">0</div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-change positive" id="orders-change">
                            <i class="fas fa-arrow-up"></i> 0% from previous period
                        </div>
                    </div>
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value" id="avg-order-value">₦0</div>
                        <div class="stat-label">Average Order Value</div>
                        <div class="stat-change" id="avg-change">
                            <i class="fas fa-minus"></i> No change
                        </div>
                    </div>
                    <div class="stat-icon average">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value" id="net-profit">₦0</div>
                        <div class="stat-label">Net Profit</div>
                        <div class="stat-change positive" id="profit-change">
                            <i class="fas fa-arrow-up"></i> Total profit earned
                        </div>
                    </div>
                    <div class="stat-icon profit">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value" id="delivered-orders">0</div>
                        <div class="stat-label">Delivered Orders</div>
                        <div class="stat-change positive" id="delivered-change">
                            <i class="fas fa-check-circle"></i> Successfully delivered
                        </div>
                    </div>
                    <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value" id="failed-orders">0</div>
                        <div class="stat-label">Failed Orders</div>
                        <div class="stat-change negative" id="failed-change">
                            <i class="fas fa-times-circle"></i> Cancelled
                        </div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid-2">
            <div class="content-card">
                <h2><i class="fas fa-chart-line"></i> Revenue Over Time</h2>
                <div class="chart-container">
                    <canvas id="revenue-chart"></canvas>
                </div>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-chart-pie"></i> Revenue by Package Type</h2>
                <div class="chart-container">
                    <canvas id="package-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top States by Revenue -->
        <div class="content-card">
            <h2><i class="fas fa-map-marked-alt"></i> Top 10 States by Revenue</h2>
            <div class="table-responsive">
                <table id="states-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>State</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="loading">
                                <i class="fas fa-spinner fa-spin"></i> Loading data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="content-card">
            <h2><i class="fas fa-chart-bar"></i> Order Status Breakdown</h2>
            <div class="chart-container">
                <canvas id="status-chart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="content-card">
            <h2><i class="fas fa-receipt"></i> Recent Transactions</h2>
            <div class="table-responsive">
                <table id="transactions-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>State</th>
                            <th>Amount</th>
                            <th>Cost</th>
                            <th>Net Profit</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" class="loading">
                                <i class="fas fa-spinner fa-spin"></i> Loading transactions...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Agent Performance -->
        <div class="content-card">
            <div class="card-header">
                <h2><i class="fas fa-user-tie"></i> Agent Performance</h2>
            </div>
            <div class="table-responsive">
                <table id="agents-table">
                    <thead>
                        <tr>
                            <th>Agent Name</th>
                            <th>States Covered</th>
                            <th>Total Orders</th>
                            <th>Delivered</th>
                            <th>Revenue Generated</th>
                            <th>Success Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="loading">
                                <i class="fas fa-spinner fa-spin"></i> Loading agent data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
                </div>
            </section>
        </div><!-- End main-wrapper -->
    </div><!-- End layout-wrapper -->
    
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        let revenueChart, packageChart, statusChart;
        let currentStartDate, currentEndDate, currentGroupBy = 'week';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeDates();
            setupEventListeners();
            loadAnalytics();
        });

        function initializeDates() {
            const today = new Date();
            const weekAgo = new Date(today);
            weekAgo.setDate(today.getDate() - 7);

            document.getElementById('start-date').valueAsDate = weekAgo;
            document.getElementById('end-date').valueAsDate = today;

            currentStartDate = formatDate(weekAgo);
            currentEndDate = formatDate(today);
        }

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function setupEventListeners() {
            // Quick filter buttons
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.quick-filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    applyQuickFilter(this.dataset.range);
                });
            });

            document.getElementById('apply-filter-btn').addEventListener('click', applyCustomFilter);
            document.getElementById('export-btn').addEventListener('click', exportToExcel);
        }

        function applyQuickFilter(range) {
            const today = new Date();
            let startDate = new Date();

            switch(range) {
                case 'today':
                    startDate = new Date(today);
                    break;
                case 'week':
                    startDate.setDate(today.getDate() - 7);
                    break;
                case 'month':
                    startDate.setMonth(today.getMonth() - 1);
                    break;
                case 'last-month':
                    startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    today.setDate(0); // Last day of previous month
                    break;
                case 'year':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    break;
                case 'custom':
                    return; // Let user input custom dates
            }

            document.getElementById('start-date').valueAsDate = startDate;
            document.getElementById('end-date').valueAsDate = today;

            currentStartDate = formatDate(startDate);
            currentEndDate = formatDate(today);

            loadAnalytics();
        }

        function applyCustomFilter() {
            currentStartDate = document.getElementById('start-date').value;
            currentEndDate = document.getElementById('end-date').value;
            currentGroupBy = document.getElementById('group-by').value;

            if (!currentStartDate || !currentEndDate) {
                alert('Please select both start and end dates');
                return;
            }

            loadAnalytics();
        }

        async function loadAnalytics() {
            try {
                // Load sales report
                const response = await fetch(`api/orders.php?action=sales_report&start_date=${currentStartDate}&end_date=${currentEndDate}&group_by=${currentGroupBy}`);
                const data = await response.json();

                if (data.success) {
                    updateSummaryStats(data.data);
                    updateRevenueChart(data.data);
                    updatePackageChart(data.data);
                    updateStatesTable(data.data);
                    updateTransactionsTable(data.data);
                }

                // Load order status
                await loadOrderStatus();

                // Load agent performance
                await loadAgentPerformance();

            } catch (error) {
                console.error('Error loading analytics:', error);
                alert('Error loading analytics data');
            }
        }

        function updateSummaryStats(data) {
            const summary = data.summary;
            
            // Calculate net profit ONLY from delivered and cancelled orders (where expenses are tracked)
            let totalProfit = 0;
            let profitableOrdersCount = 0;
            
            data.orders.forEach(order => {
                // Only calculate profit for delivered or cancelled orders
                if (order.status === 'delivered' || order.status === 'cancelled') {
                    const revenue = getAmount(order.pack);
                    const costPrice = parseFloat(order.cost_price) || 0;
                    const expenses = parseFloat(order.expenses) || 0;
                    const profit = revenue - costPrice - expenses;
                    totalProfit += profit;
                    profitableOrdersCount++;
                }
            });

            document.getElementById('total-revenue').textContent = '₦' + formatNumber(summary.total_revenue);
            document.getElementById('total-orders').textContent = summary.total_orders;
            document.getElementById('avg-order-value').textContent = '₦' + formatNumber(summary.average_order_value);
            document.getElementById('net-profit').textContent = '₦' + formatNumber(totalProfit);
            document.getElementById('delivered-orders').textContent = summary.delivered_orders || 0;
            document.getElementById('failed-orders').textContent = summary.failed_orders || 0;
            
            // Update profit change indicator
            const profitChange = document.getElementById('profit-change');
            if (totalProfit > 0) {
                profitChange.className = 'stat-change positive';
                profitChange.innerHTML = `<i class="fas fa-arrow-up"></i> From ${profitableOrdersCount} completed orders`;
            } else if (totalProfit < 0) {
                profitChange.className = 'stat-change negative';
                profitChange.innerHTML = `<i class="fas fa-arrow-down"></i> Loss from ${profitableOrdersCount} orders`;
            } else {
                profitChange.className = 'stat-change';
                profitChange.innerHTML = `<i class="fas fa-minus"></i> ${profitableOrdersCount} orders tracked`;
            }
        }

        function updateRevenueChart(data) {
            const ctx = document.getElementById('revenue-chart').getContext('2d');

            // Group orders by date - only count delivered orders for revenue
            const revenueByDate = {};
            data.orders.forEach(order => {
                if (order.status === 'delivered') {
                    const date = order.created_at.split(' ')[0];
                    const amount = getAmount(order.pack);
                    revenueByDate[date] = (revenueByDate[date] || 0) + amount;
                }
            });

            const labels = Object.keys(revenueByDate).sort();
            const values = labels.map(date => revenueByDate[date]);

            if (revenueChart) revenueChart.destroy();

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (₦)',
                        data: values,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₦' + formatNumber(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function updatePackageChart(data) {
            const ctx = document.getElementById('package-chart').getContext('2d');

            const packages = data.summary.revenue_by_package;
            const labels = Object.keys(packages).map(p => p.charAt(0).toUpperCase() + p.slice(1));
            const values = Object.values(packages);

            if (packageChart) packageChart.destroy();

            packageChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(255, 193, 7, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ₦' + formatNumber(context.parsed);
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateStatesTable(data) {
            const tbody = document.querySelector('#states-table tbody');
            const topStates = data.summary.top_states.slice(0, 10);

            if (topStates.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="no-data"><i class="fas fa-chart-bar"></i><br>No data available</td></tr>';
                return;
            }

            const totalRevenue = data.summary.total_revenue;

            tbody.innerHTML = topStates.map((state, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${state.state}</strong></td>
                    <td>${state.order_count}</td>
                    <td class="amount">₦${formatNumber(state.revenue)}</td>
                    <td>${((state.revenue / totalRevenue) * 100).toFixed(1)}%</td>
                </tr>
            `).join('');
        }

        function updateTransactionsTable(data) {
            const tbody = document.querySelector('#transactions-table tbody');
            const recentOrders = data.orders.slice(0, 20);

            if (recentOrders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="no-data"><i class="fas fa-receipt"></i><br>No transactions found</td></tr>';
                return;
            }

            const packageNames = {
                'starter': 'Starter Set',
                'bundle': 'Learning Bundle',
                'collection': 'Mastery Collection'
            };

            tbody.innerHTML = recentOrders.map(order => {
                const revenue = getAmount(order.pack);
                const costPrice = parseFloat(order.cost_price) || 0;
                const expenses = parseFloat(order.expenses) || 0;
                const totalCost = costPrice + expenses;
                
                // Only calculate profit for delivered or cancelled orders
                let profitDisplay = '-';
                let costDisplay = '-';
                
                if (order.status === 'delivered' || order.status === 'cancelled') {
                    const profit = revenue - costPrice - expenses;
                    profitDisplay = `₦${formatNumber(profit)}`;
                    costDisplay = `₦${formatNumber(totalCost)}`;
                    
                    return `
                        <tr>
                            <td><strong>${order.id}</strong></td>
                            <td>${order.fullname}</td>
                            <td>${packageNames[order.pack] || order.pack}</td>
                            <td>${order.state}</td>
                            <td class="amount">₦${formatNumber(revenue)}</td>
                            <td style="color: #dc3545;">${costDisplay}</td>
                            <td class="amount" style="color: ${profit >= 0 ? '#28a745' : '#dc3545'};">${profitDisplay}</td>
                            <td><span class="status-badge status-${order.status}">${order.status}</span></td>
                            <td>${new Date(order.created_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                } else {
                    // For pending/processing/shipped orders, show dashes for cost and profit
                    return `
                        <tr>
                            <td><strong>${order.id}</strong></td>
                            <td>${order.fullname}</td>
                            <td>${packageNames[order.pack] || order.pack}</td>
                            <td>${order.state}</td>
                            <td class="amount">₦${formatNumber(revenue)}</td>
                            <td style="color: #999;">-</td>
                            <td style="color: #999;">-</td>
                            <td><span class="status-badge status-${order.status}">${order.status}</span></td>
                            <td>${new Date(order.created_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                }
            }).join('');
        }

        async function loadOrderStatus() {
            const response = await fetch('api/orders.php?action=stats');
            const data = await response.json();

            if (data.success) {
                const ctx = document.getElementById('status-chart').getContext('2d');
                
                if (statusChart) statusChart.destroy();

                statusChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
                        datasets: [{
                            label: 'Orders',
                            data: [
                                data.data.pending,
                                data.data.confirmed,
                                data.data.processing,
                                data.data.shipped,
                                data.data.delivered,
                                data.data.cancelled
                            ],
                            backgroundColor: [
                                'rgba(255, 193, 7, 0.8)',
                                'rgba(102, 126, 234, 0.8)',
                                'rgba(23, 162, 184, 0.8)',
                                'rgba(111, 66, 193, 0.8)',
                                'rgba(40, 167, 69, 0.8)',
                                'rgba(220, 53, 69, 0.8)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        }

        async function loadAgentPerformance() {
            try {
                const agentsResponse = await fetch('api/agents.php?action=list');
                const agentsData = await agentsResponse.json();

                if (!agentsData.success) {
                    throw new Error('Failed to load agents');
                }

                const tbody = document.querySelector('#agents-table tbody');

                if (agentsData.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="no-data"><i class="fas fa-user-tie"></i><br>No agents found</td></tr>';
                    return;
                }

                // Get all orders
                const ordersResponse = await fetch('api/orders.php?action=list&per_page=1000');
                const ordersData = await ordersResponse.json();

                const ordersByAgent = {};
                
                if (ordersData.success && ordersData.data) {
                    ordersData.data.forEach(order => {
                        if (order.agent_id) {
                            if (!ordersByAgent[order.agent_id]) {
                                ordersByAgent[order.agent_id] = {
                                    totalRevenue: 0,
                                    totalOrders: 0,
                                    deliveredCount: 0
                                };
                            }
                            
                            ordersByAgent[order.agent_id].totalRevenue += getAmount(order.pack);
                            ordersByAgent[order.agent_id].totalOrders++;
                            if (order.status === 'delivered') {
                                ordersByAgent[order.agent_id].deliveredCount++;
                            }
                        }
                    });
                }

                const agentsWithStats = agentsData.data.map(agent => {
                    const stats = ordersByAgent[agent.id] || {
                        totalRevenue: 0,
                        totalOrders: 0,
                        deliveredCount: 0
                    };

                    const successRate = stats.totalOrders > 0 
                        ? ((stats.deliveredCount / stats.totalOrders) * 100).toFixed(1) 
                        : 0;

                    return {
                        ...agent,
                        totalRevenue: stats.totalRevenue,
                        totalOrders: stats.totalOrders,
                        deliveredCount: stats.deliveredCount,
                        successRate
                    };
                });

                tbody.innerHTML = agentsWithStats.map(agent => `
                    <tr>
                        <td><strong>${agent.name}</strong></td>
                        <td>${agent.states || 'None'}</td>
                        <td>${agent.totalOrders}</td>
                        <td>${agent.deliveredCount}</td>
                        <td class="amount">₦${formatNumber(agent.totalRevenue)}</td>
                        <td>${agent.successRate}%</td>
                    </tr>
                `).join('');
            } catch (error) {
                console.error('Error loading agent performance:', error);
                const tbody = document.querySelector('#agents-table tbody');
                tbody.innerHTML = '<tr><td colspan="6" class="no-data"><i class="fas fa-exclamation-triangle"></i><br>Error loading agent data</td></tr>';
            }
        }

        function getAmount(pack) {
            const amounts = {
                'starter': 18000,
                'bundle': 32000,
                'collection': 45000
            };
            return amounts[pack.toLowerCase()] || 0;
        }

        function formatNumber(num) {
            return parseInt(num).toLocaleString();
        }

        async function exportToExcel() {
            try {
                const response = await fetch(`api/orders.php?action=sales_report&start_date=${currentStartDate}&end_date=${currentEndDate}&group_by=${currentGroupBy}`);
                const data = await response.json();

                if (data.success) {
                    let csv = 'ACCOUNTING REPORT\n';
                    csv += `Period: ${currentStartDate} to ${currentEndDate}\n\n`;
                    
                    csv += 'SUMMARY\n';
                    csv += `Total Revenue,₦${formatNumber(data.data.summary.total_revenue)}\n`;
                    csv += `Total Orders,${data.data.summary.total_orders}\n`;
                    csv += `Average Order Value,₦${formatNumber(data.data.summary.average_order_value)}\n\n`;
                    
                    csv += 'REVENUE BY PACKAGE\n';
                    Object.entries(data.data.summary.revenue_by_package).forEach(([pkg, amount]) => {
                        csv += `${pkg.charAt(0).toUpperCase() + pkg.slice(1)},₦${formatNumber(amount)}\n`;
                    });
                    
                    csv += '\nTRANSACTIONS\n';
                    csv += 'Order ID,Customer,Package,State,Amount,Status,Date\n';
                    
                    const packageNames = {
                        'starter': 'Starter Set',
                        'bundle': 'Learning Bundle',
                        'collection': 'Mastery Collection'
                    };
                    
                    data.data.orders.forEach(order => {
                        csv += `${order.id},"${order.fullname}","${packageNames[order.pack]}","${order.state}",₦${formatNumber(getAmount(order.pack))},${order.status},${order.created_at}\n`;
                    });

                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `accounting_report_${currentStartDate}_to_${currentEndDate}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }
            } catch (error) {
                console.error('Error exporting:', error);
                alert('Error exporting data');
            }
        }

        // Load Agent Performance Data
        async function loadAgentPerformance() {
            try {
                const response = await fetch('api/agents.php?action=performance');
                const data = await response.json();

                if (data.success) {
                    updateAgentsTable(data.data);
                }
            } catch (error) {
                console.error('Error loading agent performance:', error);
                document.querySelector('#agents-table tbody').innerHTML = 
                    '<tr><td colspan="6" class="no-data"><i class="fas fa-exclamation-circle"></i><br>Error loading agent data</td></tr>';
            }
        }

        function updateAgentsTable(agents) {
            const tbody = document.querySelector('#agents-table tbody');

            if (!agents || agents.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="no-data"><i class="fas fa-user-tie"></i><br>No agent data available</td></tr>';
                return;
            }

            tbody.innerHTML = agents.map(agent => {
                const successRate = agent.total_orders > 0 
                    ? ((agent.delivered / agent.total_orders) * 100).toFixed(1) 
                    : '0.0';
                
                return `
                    <tr>
                        <td><strong>${agent.name}</strong></td>
                        <td>${agent.states_covered || 0}</td>
                        <td>${agent.total_orders || 0}</td>
                        <td>${agent.delivered || 0}</td>
                        <td class="amount">₦${formatNumber(agent.revenue || 0)}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-weight: 600; color: ${successRate >= 80 ? '#28a745' : successRate >= 50 ? '#ffc107' : '#dc3545'};">
                                    ${successRate}%
                                </span>
                                <div style="flex: 1; height: 8px; background: #eee; border-radius: 4px; overflow: hidden;">
                                    <div style="width: ${successRate}%; height: 100%; background: ${successRate >= 80 ? '#28a745' : successRate >= 50 ? '#ffc107' : '#dc3545'}; transition: width 0.3s;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
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
    </script>
</body>
</html>
