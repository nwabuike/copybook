<?php
require_once 'php/auth.php';
requireLogin(); // All logged-in users can view notifications

$currentUser = getCurrentUser();
$userRole = $currentUser['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard - Notifications</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="css/style.css">
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

        .notification-toggle {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-status {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification-status.enabled {
            background: #d4edda;
            color: #155724;
        }

        .notification-status.disabled {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

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
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .content-card h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .notification-settings {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .setting-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9fa;
        }

        .setting-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .setting-header h3 {
            color: #333;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-switch {
            position: relative;
            width: 50px;
            height: 26px;
        }

        .toggle-switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 26px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #667eea;
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }

        .setting-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .setting-input {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .setting-input label {
            font-size: 14px;
            color: #333;
        }

        .setting-input input[type="number"] {
            width: 80px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .alerts-list {
            margin-top: 20px;
        }

        .alert-item {
            background: white;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-item.urgent {
            border-left-color: #dc3545;
        }

        .alert-item.warning {
            border-left-color: #ffc107;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .alert-time {
            font-size: 12px;
            color: #666;
        }

        .test-btn {
            background: #28a745;
        }

        .test-btn:hover {
            background: #218838;
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

        /* Mobile Responsive */
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
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .notification-toggle {
                width: 100%;
                flex-direction: column;
            }

            .notification-status {
                width: 100%;
                justify-content: center;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .notification-settings {
                grid-template-columns: 1fr;
            }

            .setting-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .alert-item {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 24px;
            }

            h1 {
                font-size: 20px;
            }

            h2 {
                font-size: 18px;
            }
        }
    </style>
    <?php include 'php/content_protection.php'; ?>
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
                        <a href="sales_notifications.php" class="sidebar-menu-link active">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    
                    <?php if (isAdmin()): ?>
                    <div class="sidebar-divider"></div>
                    <li class="sidebar-menu-item">
                        <a href="user_management.php" class="sidebar-menu-link">
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
                            <span>Notifications</span>
                        </div>
                        <div class="header-actions">
                            <div class="notification-toggle">
                                <div class="notification-status" id="notification-status">
                                    <i class="fas fa-bell-slash"></i>
                                    <span>Notifications Disabled</span>
                                </div>
                                <button class="btn" id="enable-notifications-btn">
                                    <i class="fas fa-bell"></i> Enable
                                </button>
                            </div>
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

        <div class="stats-grid">
            <div class="stat-card">
                <h3><i class="fas fa-clock"></i> Pending Orders</h3>
                <div class="stat-value" id="pending-count">0</div>
                <div class="stat-label">Require follow-up</div>
            </div>

            <div class="stat-card">
                <h3><i class="fas fa-exclamation-triangle"></i> Low Stock Items</h3>
                <div class="stat-value" id="low-stock-count">0</div>
                <div class="stat-label">Below threshold</div>
            </div>

            <div class="stat-card">
                <h3><i class="fas fa-box-open"></i> Out of Stock</h3>
                <div class="stat-value" id="out-stock-count">0</div>
                <div class="stat-label">Need restocking</div>
            </div>

            <div class="stat-card">
                <h3><i class="fas fa-history"></i> Last Check</h3>
                <div class="stat-value" style="font-size: 16px;" id="last-check">Never</div>
                <div class="stat-label">Auto-check every 5 minutes</div>
            </div>
        </div>

        <div class="content-card">
            <h2><i class="fas fa-cog"></i> Notification Settings</h2>
            
            <div class="notification-settings">
                <div class="setting-item">
                    <div class="setting-header">
                        <h3><i class="fas fa-shopping-cart"></i> Pending Orders Alert</h3>
                        <label class="toggle-switch">
                            <input type="checkbox" id="pending-orders-toggle" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="setting-description">Get notified about orders pending for more than set time</p>
                    <div class="setting-input">
                        <label>Alert after:</label>
                        <input type="number" id="pending-threshold" value="30" min="5" max="1440">
                        <span>minutes</span>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-header">
                        <h3><i class="fas fa-box"></i> Low Stock Alert</h3>
                        <label class="toggle-switch">
                            <input type="checkbox" id="low-stock-toggle" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="setting-description">Get notified when stock falls below threshold</p>
                    <div class="setting-input">
                        <label>Threshold:</label>
                        <input type="number" id="stock-threshold" value="5" min="1" max="50">
                        <span>units</span>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-header">
                        <h3><i class="fas fa-phone"></i> Follow-up Reminder</h3>
                        <label class="toggle-switch">
                            <input type="checkbox" id="followup-toggle" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="setting-description">Remind to follow up on confirmed orders</p>
                    <div class="setting-input">
                        <label>Remind after:</label>
                        <input type="number" id="followup-threshold" value="60" min="10" max="2880">
                        <span>minutes</span>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-header">
                        <h3><i class="fas fa-clock"></i> Check Interval</h3>
                        <label class="toggle-switch">
                            <input type="checkbox" id="auto-check-toggle" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="setting-description">How often to check for new alerts</p>
                    <div class="setting-input">
                        <label>Check every:</label>
                        <input type="number" id="check-interval" value="5" min="1" max="60">
                        <span>minutes</span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button class="btn" id="save-settings-btn">
                    <i class="fas fa-save"></i> Save Settings
                </button>
                <button class="btn test-btn" id="test-notification-btn">
                    <i class="fas fa-flask"></i> Test Notification
                </button>
            </div>
        </div>

        <div class="content-card">
            <h2><i class="fas fa-list"></i> Recent Alerts</h2>
            <div class="alerts-list" id="alerts-list">
                <p style="color: #666; text-align: center; padding: 40px;">No alerts yet. Enable notifications to start monitoring.</p>
            </div>
        </div>
    </div>

    <script>
        let notificationPermission = false;
        let checkInterval = null;
        let settings = {
            pendingOrders: true,
            pendingThreshold: 30,
            lowStock: true,
            stockThreshold: 5,
            followUp: true,
            followUpThreshold: 60,
            autoCheck: true,
            checkInterval: 5
        };

        // Load settings from localStorage
        function loadSettings() {
            const saved = localStorage.getItem('notificationSettings');
            if (saved) {
                settings = JSON.parse(saved);
                applySettings();
            }
        }

        // Apply settings to UI
        function applySettings() {
            document.getElementById('pending-orders-toggle').checked = settings.pendingOrders;
            document.getElementById('pending-threshold').value = settings.pendingThreshold;
            document.getElementById('low-stock-toggle').checked = settings.lowStock;
            document.getElementById('stock-threshold').value = settings.stockThreshold;
            document.getElementById('followup-toggle').checked = settings.followUp;
            document.getElementById('followup-threshold').value = settings.followUpThreshold;
            document.getElementById('auto-check-toggle').checked = settings.autoCheck;
            document.getElementById('check-interval').value = settings.checkInterval;
        }

        // Save settings
        function saveSettings() {
            settings = {
                pendingOrders: document.getElementById('pending-orders-toggle').checked,
                pendingThreshold: parseInt(document.getElementById('pending-threshold').value),
                lowStock: document.getElementById('low-stock-toggle').checked,
                stockThreshold: parseInt(document.getElementById('stock-threshold').value),
                followUp: document.getElementById('followup-toggle').checked,
                followUpThreshold: parseInt(document.getElementById('followup-threshold').value),
                autoCheck: document.getElementById('auto-check-toggle').checked,
                checkInterval: parseInt(document.getElementById('check-interval').value)
            };

            localStorage.setItem('notificationSettings', JSON.stringify(settings));
            
            // Restart interval with new settings
            if (notificationPermission && settings.autoCheck) {
                startAutoCheck();
            }

            alert('Settings saved successfully!');
        }

        // Request notification permission
        async function requestNotificationPermission() {
            if (!('Notification' in window)) {
                alert('This browser does not support desktop notifications. You can still use the alert list below.');
                // Still allow page to work without notifications
                notificationPermission = false;
                updateNotificationStatus();
                startAutoCheck();
                checkAlerts();
                return false;
            }

            try {
                // Handle both old and new notification API syntax for mobile compatibility
                let permission;
                if (Notification.requestPermission) {
                    permission = await Notification.requestPermission();
                } else {
                    // Fallback for older browsers
                    permission = await new Promise((resolve) => {
                        Notification.requestPermission(resolve);
                    });
                }
                
                notificationPermission = permission === 'granted';
                
                updateNotificationStatus();
                
                if (notificationPermission) {
                    startAutoCheck();
                    checkAlerts();
                    alert('Notifications enabled successfully!');
                } else if (permission === 'denied') {
                    alert('Notifications were blocked. Please enable them in your browser settings. You can still use the alert list below.');
                } else {
                    alert('Notification permission was not granted. You can still use the alert list below.');
                }
                
                // Even if notifications denied, still run checks for alert list
                if (!notificationPermission) {
                    startAutoCheck();
                    checkAlerts();
                }

                return notificationPermission;
            } catch (error) {
                console.error('Error requesting notification permission:', error);
                alert('Could not enable browser notifications. You can still use the alert list below.');
                notificationPermission = false;
                updateNotificationStatus();
                // Still start checks for alert list
                startAutoCheck();
                checkAlerts();
                return false;
            }
        }

        // Update notification status UI
        function updateNotificationStatus() {
            const statusEl = document.getElementById('notification-status');
            const btnEl = document.getElementById('enable-notifications-btn');

            if (notificationPermission) {
                statusEl.className = 'notification-status enabled';
                statusEl.innerHTML = '<i class="fas fa-bell"></i><span>Browser Notifications Enabled</span>';
                btnEl.innerHTML = '<i class="fas fa-bell-slash"></i> Disable Browser Alerts';
                btnEl.className = 'btn btn-danger';
            } else {
                statusEl.className = 'notification-status disabled';
                statusEl.innerHTML = '<i class="fas fa-info-circle"></i><span>Monitoring Active (Alert List Only)</span>';
                btnEl.innerHTML = '<i class="fas fa-bell"></i> Enable Browser Notifications';
                btnEl.className = 'btn';
            }
        }

        // Start auto-check interval
        function startAutoCheck() {
            if (checkInterval) {
                clearInterval(checkInterval);
            }

            checkInterval = setInterval(() => {
                if (settings.autoCheck && notificationPermission) {
                    checkAlerts();
                }
            }, settings.checkInterval * 60 * 1000);
        }

        // Check for alerts
        async function checkAlerts() {
            try {
                // Update last check time
                document.getElementById('last-check').textContent = new Date().toLocaleTimeString();

                // Check pending orders
                if (settings.pendingOrders) {
                    await checkPendingOrders();
                }

                // Check low stock
                if (settings.lowStock) {
                    await checkLowStock();
                }

                // Check follow-ups
                if (settings.followUp) {
                    await checkFollowUps();
                }

            } catch (error) {
                console.error('Error checking alerts:', error);
            }
        }

        // Check pending orders
        async function checkPendingOrders() {
            try {
                const response = await fetch('api/orders.php?action=list&status=pending');
                
                if (!response.ok) {
                    console.error('Orders API error:', response.status);
                    return;
                }
                
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    const now = new Date();
                    const thresholdMs = settings.pendingThreshold * 60 * 1000;
                    let alertCount = 0;

                    data.data.forEach(order => {
                        const orderTime = new Date(order.created_at);
                        const timeDiff = now - orderTime;

                        if (timeDiff > thresholdMs) {
                            alertCount++;
                        }
                    });

                    document.getElementById('pending-count').textContent = data.data.length;
                    
                    // Only notify once per check if there are old pending orders
                    if (alertCount > 0) {
                        showNotification(
                            'Pending Orders Need Attention',
                            `${alertCount} order(s) have been pending for over ${settings.pendingThreshold} minutes`,
                            'urgent'
                        );
                    }
                } else {
                    document.getElementById('pending-count').textContent = '0';
                }
            } catch (error) {
                console.error('Error checking pending orders:', error);
                document.getElementById('pending-count').textContent = '0';
            }
        }

        // Check low stock
        async function checkLowStock() {
            try {
                const response = await fetch(`api/stock.php?action=low_stock&threshold=${settings.stockThreshold}`);
                
                if (!response.ok) {
                    console.error('Stock API error:', response.status);
                    return;
                }
                
                const data = await response.json();

                if (data.success && data.data) {
                    const lowStockCount = data.data.filter(item => {
                        const qty = parseInt(item.quantity);
                        return qty > 0 && qty <= settings.stockThreshold;
                    }).length;
                    
                    const outOfStockCount = data.data.filter(item => {
                        const qty = parseInt(item.quantity);
                        return qty === 0;
                    }).length;

                    document.getElementById('low-stock-count').textContent = lowStockCount;
                    document.getElementById('out-stock-count').textContent = outOfStockCount;

                    if (outOfStockCount > 0) {
                        showNotification(
                            'Out of Stock Alert',
                            `${outOfStockCount} item(s) are out of stock`,
                            'urgent'
                        );
                    } else if (lowStockCount > 0) {
                        showNotification(
                            'Low Stock Alert',
                            `${lowStockCount} item(s) are running low`,
                            'warning'
                        );
                    }
                } else {
                    console.error('Stock data error:', data.message || 'Unknown error');
                    document.getElementById('low-stock-count').textContent = '0';
                    document.getElementById('out-stock-count').textContent = '0';
                }
            } catch (error) {
                console.error('Error checking stock:', error);
                document.getElementById('low-stock-count').textContent = '0';
                document.getElementById('out-stock-count').textContent = '0';
            }
        }

        // Check follow-ups
        async function checkFollowUps() {
            try {
                const response = await fetch('api/orders.php?action=list&status=confirmed');
                
                if (!response.ok) {
                    console.error('Orders API error:', response.status);
                    return;
                }
                
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    const now = new Date();
                    const thresholdMs = settings.followUpThreshold * 60 * 1000;
                    let alertCount = 0;

                    data.data.forEach(order => {
                        const confirmedTime = order.confirmed_at ? new Date(order.confirmed_at) : new Date(order.created_at);
                        const timeDiff = now - confirmedTime;

                        if (timeDiff > thresholdMs) {
                            alertCount++;
                        }
                    });

                    if (alertCount > 0) {
                        showNotification(
                            'Follow-up Required',
                            `${alertCount} confirmed order(s) need follow-up`,
                            'warning'
                        );
                    }
                }
            } catch (error) {
                console.error('Error checking follow-ups:', error);
            }
        }

        // Show notification
        function showNotification(title, body, type = 'info') {
            // Always add to alerts list regardless of browser notification permission
            addAlertToList(title, body, type);
            
            // Try to show browser notification if permission granted
            if (notificationPermission && 'Notification' in window) {
                try {
                    const notification = new Notification(title, {
                        body: body,
                        icon: 'images/logo.png',
                        badge: 'images/logo.png',
                        tag: type,
                        requireInteraction: type === 'urgent',
                        vibrate: [200, 100, 200]
                    });

                    notification.onclick = function() {
                        window.focus();
                        notification.close();
                    };
                } catch (error) {
                    console.error('Error showing notification:', error);
                }
            }

            // Play sound for urgent alerts
            if (type === 'urgent') {
                playAlertSound();
            }
        }

        // Add alert to list
        function addAlertToList(title, body, type) {
            const alertsList = document.getElementById('alerts-list');
            
            // Clear placeholder if exists
            if (alertsList.querySelector('p')) {
                alertsList.innerHTML = '';
            }

            const alertItem = document.createElement('div');
            alertItem.className = `alert-item ${type}`;
            alertItem.innerHTML = `
                <div class="alert-content">
                    <div class="alert-title">${title}</div>
                    <div class="alert-time">${body} - ${new Date().toLocaleTimeString()}</div>
                </div>
                <button class="btn" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            alertsList.insertBefore(alertItem, alertsList.firstChild);

            // Keep only last 10 alerts
            while (alertsList.children.length > 10) {
                alertsList.removeChild(alertsList.lastChild);
            }
        }

        // Play alert sound
        function playAlertSound() {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGa47OabTgwOUKXh8LVkHAU7k9jzzn0xBSR2xe/+');
            audio.play().catch(() => {}); // Ignore if sound fails
        }

        // Test notification
        function testNotification() {
            if (!notificationPermission) {
                alert('Please enable notifications first');
                return;
            }

            showNotification(
                'Test Notification',
                'This is a test notification. Your alerts are working!',
                'info'
            );
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
            
            // Check notification support and permission
            if ('Notification' in window) {
                if (Notification.permission === 'granted') {
                    notificationPermission = true;
                    updateNotificationStatus();
                    startAutoCheck();
                    checkAlerts(); // Initial check
                } else if (Notification.permission === 'denied') {
                    // Permission was denied, but still show alerts list
                    notificationPermission = false;
                    updateNotificationStatus();
                    startAutoCheck();
                    checkAlerts();
                } else {
                    // Permission not yet requested (default)
                    // Auto-request permission on every page load until granted or denied
                    requestNotificationPermission();
                }
            } else {
                // Browser doesn't support notifications, just use alert list
                notificationPermission = false;
                updateNotificationStatus();
                startAutoCheck();
                checkAlerts();
            }

            // Event listeners
            document.getElementById('enable-notifications-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if (notificationPermission) {
                    notificationPermission = false;
                    if (checkInterval) clearInterval(checkInterval);
                    updateNotificationStatus();
                } else {
                    requestNotificationPermission();
                }
            });

            document.getElementById('save-settings-btn').addEventListener('click', saveSettings);
            document.getElementById('test-notification-btn').addEventListener('click', testNotification);

            // Auto-save on input change
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('change', saveSettings);
            });
        });

        // Service Worker registration for persistent notifications (optional)
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw-notifications.js').catch(() => {
                console.log('Service worker registration skipped');
            });
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
        </div>
    </div>
</body>
</html>
