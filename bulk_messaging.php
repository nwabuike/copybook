<?php
require_once 'php/auth.php';
requireLogin();

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Messaging - Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --text: #374151;
            --text-light: #6b7280;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .container {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-header {
            background: white;
            padding: 30px 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .content-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.25rem;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--border);
        }

        .tab {
            padding: 12px 24px;
            background: none;
            border: none;
            color: var(--text-light);
            font-weight: 600;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: var(--transition);
        }

        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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

        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
            font-size: 0.9rem;
        }

        input, select, textarea {
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .filter-section {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item input[type="checkbox"] {
            width: auto;
        }

        .preview-section {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .preview-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .preview-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 0.85rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-box {
            background: var(--primary-light);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .message-template {
            background: white;
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: var(--transition);
        }

        .message-template:hover {
            border-color: var(--primary);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.1);
        }

        .template-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .template-preview {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .recipient-list {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 15px;
        }

        .recipient-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: white;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        /* Responsive */
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

            .breadcrumb {
                margin-left: 50px;
            }
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .tabs {
                overflow-x: auto;
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
                        <a href="customer_orderlist.php" class="sidebar-menu-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="bulk_messaging.php" class="sidebar-menu-link active">
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
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title">
                        <i class="fas fa-paper-plane"></i> Bulk Messaging
                    </h1>
                    <div class="breadcrumb">
                        <i class="fas fa-home"></i>
                        <a href="index.php">Home</a>
                        <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                        <span>Bulk Messaging</span>
                    </div>
                </div>
            </div>

            <div class="container" style="padding-bottom: 40px;">
                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab active" onclick="switchTab('bulk', event)">
                        <i class="fas fa-users"></i> Bulk Messaging
                    </button>
                    <button class="tab" onclick="switchTab('individual', event)">
                        <i class="fas fa-user"></i> Individual Message
                    </button>
                    <button class="tab" onclick="switchTab('templates', event)">
                        <i class="fas fa-file-alt"></i> Message Templates
                    </button>
                </div>

                <!-- Bulk Messaging Tab -->
                <div id="bulk-tab" class="tab-content active">
                    <div class="content-section">
                        <h2 class="section-title">
                            <i class="fas fa-filter"></i> Select Recipients
                        </h2>

                        <div class="filter-section">
                            <div class="filter-title">Filter by Order Status:</div>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="status-pending" value="pending" checked>
                                    <label for="status-pending" style="font-weight: normal; margin: 0;">Pending</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="status-confirmed" value="confirmed" checked>
                                    <label for="status-confirmed" style="font-weight: normal; margin: 0;">Confirmed</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="status-processing" value="processing">
                                    <label for="status-processing" style="font-weight: normal; margin: 0;">Processing</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="status-shipped" value="shipped">
                                    <label for="status-shipped" style="font-weight: normal; margin: 0;">Shipped</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="status-delivered" value="delivered">
                                    <label for="status-delivered" style="font-weight: normal; margin: 0;">Delivered</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="status-cancelled" value="cancelled">
                                    <label for="status-cancelled" style="font-weight: normal; margin: 0;">Cancelled</label>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section" style="margin-top: 20px;">
                            <div class="filter-title">Special Categories:</div>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="category-not-picking">
                                    <label for="category-not-picking" style="font-weight: normal; margin: 0; color: #dc3545;">
                                        <i class="fas fa-phone-slash"></i> Not Picking Calls
                                    </label>
                                </div>
                            </div>
                            <small style="display: block; margin-top: 8px; color: #666; font-style: italic;">
                                * Check this to filter orders where customers haven't been answering calls (requires admin notes indicating "not picking" or similar)
                            </small>
                        </div>

                        <div class="stats-row" id="recipient-stats">
                            <div class="stat-box">
                                <div class="stat-value" id="total-recipients">0</div>
                                <div class="stat-label">Total Recipients</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="whatsapp-recipients">0</div>
                                <div class="stat-label">With Phone Numbers</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="email-recipients">0</div>
                                <div class="stat-label">With Email Addresses</div>
                            </div>
                        </div>

                        <button class="btn btn-primary" onclick="loadRecipients()">
                            <i class="fas fa-sync"></i> Load Recipients
                        </button>

                        <div class="recipient-list" id="recipient-list" style="display: none;">
                            <!-- Recipients will be loaded here -->
                        </div>
                    </div>

                    <div class="content-section">
                        <h2 class="section-title">
                            <i class="fas fa-envelope"></i> Compose Message
                        </h2>

                        <div class="form-group full-width">
                            <label>Message Type:</label>
                            <select id="bulk-message-type">
                                <option value="confirmation">Order Confirmation</option>
                                <option value="reminder">Order Reminder</option>
                                <option value="not_picking">Not Picking Calls</option>
                                <option value="custom">Custom Message</option>
                            </select>
                        </div>

                        <div class="form-group full-width" id="custom-message-section" style="display: none;">
                            <label>Custom Message:</label>
                            <textarea id="bulk-custom-message" placeholder="Enter your message here..."></textarea>
                        </div>

                        <div class="preview-section">
                            <div class="preview-title">Message Preview:</div>
                            <div class="preview-content" id="bulk-message-preview">
                                Select recipients and message type to see preview...
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn btn-success" onclick="sendBulkWhatsApp()">
                                <i class="fab fa-whatsapp"></i> Send via WhatsApp
                            </button>
                            <button class="btn btn-primary" onclick="sendBulkEmail()">
                                <i class="fas fa-envelope"></i> Send via Email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Individual Messaging Tab -->
                <div id="individual-tab" class="tab-content">
                    <div class="content-section">
                        <h2 class="section-title">
                            <i class="fas fa-user"></i> Send Individual Message
                        </h2>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Order ID:</label>
                                <input type="text" id="individual-order-id" placeholder="Enter Order ID">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" onclick="loadIndividualOrder()" style="margin-top: 28px;">
                                    <i class="fas fa-search"></i> Load Order
                                </button>
                            </div>
                        </div>

                        <div id="individual-order-details" style="display: none;">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Customer Name:</label>
                                    <input type="text" id="individual-customer-name" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Phone:</label>
                                    <input type="text" id="individual-customer-phone" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="text" id="individual-customer-email" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Status:</label>
                                    <input type="text" id="individual-order-status" readonly>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label>Message Type:</label>
                                <select id="individual-message-type">
                                    <option value="confirmation">Order Confirmation</option>
                                    <option value="reminder">Order Reminder</option>
                                    <option value="not_picking">Not Picking Calls</option>
                                    <option value="custom">Custom Message</option>
                                </select>
                            </div>

                            <div class="form-group full-width" id="individual-custom-message-section" style="display: none;">
                                <label>Custom Message:</label>
                                <textarea id="individual-custom-message" placeholder="Enter your message here..."></textarea>
                            </div>

                            <div class="preview-section">
                                <div class="preview-title">Message Preview:</div>
                                <div class="preview-content" id="individual-message-preview">
                                    Message preview will appear here...
                                </div>
                            </div>

                            <div class="action-buttons">
                                <button class="btn btn-success" onclick="sendIndividualWhatsApp()">
                                    <i class="fab fa-whatsapp"></i> Send via WhatsApp
                                </button>
                                <button class="btn btn-primary" onclick="sendIndividualEmail()">
                                    <i class="fas fa-envelope"></i> Send via Email
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Templates Tab -->
                <div id="templates-tab" class="tab-content">
                    <div class="content-section">
                        <h2 class="section-title">
                            <i class="fas fa-file-alt"></i> Message Templates
                        </h2>

                        <div class="message-template" onclick="useTemplate('confirmation')">
                            <div class="template-name">
                                <i class="fas fa-check-circle"></i> Order Confirmation Template
                            </div>
                            <div class="template-preview">
                                Thank you for your order! We are pleased to confirm your order details...
                            </div>
                        </div>

                        <div class="message-template" onclick="useTemplate('reminder')">
                            <div class="template-name">
                                <i class="fas fa-bell"></i> Order Reminder Template
                            </div>
                            <div class="template-preview">
                                This is a friendly reminder about your order. Please find the details...
                            </div>
                        </div>

                        <div class="message-template" onclick="useTemplate('not_picking')">
                            <div class="template-name">
                                <i class="fas fa-phone-slash"></i> Not Picking Calls Template
                            </div>
                            <div class="template-preview">
                                We've been trying to reach you regarding your order. Please contact us...
                            </div>
                        </div>

                        <div class="message-template" onclick="useTemplate('delivery')">
                            <div class="template-name">
                                <i class="fas fa-truck"></i> Delivery Update Template
                            </div>
                            <div class="template-preview">
                                Your order is on its way! Expected delivery date...
                            </div>
                        </div>

                        <div class="message-template" onclick="useTemplate('followup')">
                            <div class="template-name">
                                <i class="fas fa-phone"></i> Follow-up Template
                            </div>
                            <div class="template-preview">
                                We hope you received your order. We'd love to hear your feedback...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>

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

        let selectedOrders = [];
        let individualOrder = null;

        // Tab switching
        function switchTab(tabName, event) {
            if (event) {
                event.preventDefault();
            }
            
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            if (event && event.target) {
                event.target.closest('.tab').classList.add('active');
            }
            document.getElementById(tabName + '-tab').classList.add('active');
        }

        // Message type change handlers
        document.getElementById('bulk-message-type').addEventListener('change', function() {
            const customSection = document.getElementById('custom-message-section');
            const messageType = this.value;
            
            // Always show the custom message section so users can edit
            customSection.style.display = 'block';
            
            // If not custom, load the template message into the textarea for editing
            if (messageType !== 'custom' && selectedOrders.length > 0) {
                const sampleOrder = selectedOrders[0];
                const templateMessage = formatOrderMessage(sampleOrder, messageType);
                document.getElementById('bulk-custom-message').value = templateMessage;
            } else if (messageType === 'custom') {
                // Clear the textarea for custom message
                document.getElementById('bulk-custom-message').value = '';
            }
            
            updateBulkPreview();
        });

        document.getElementById('individual-message-type').addEventListener('change', function() {
            const customSection = document.getElementById('individual-custom-message-section');
            const messageType = this.value;
            
            // Always show the custom message section so users can edit
            customSection.style.display = 'block';
            
            // If not custom, load the template message into the textarea for editing
            if (messageType !== 'custom' && individualOrder) {
                const templateMessage = formatOrderMessage(individualOrder, messageType);
                document.getElementById('individual-custom-message').value = templateMessage;
            } else if (messageType === 'custom') {
                // Clear the textarea for custom message
                document.getElementById('individual-custom-message').value = '';
            }
            
            updateIndividualPreview();
        });

        // Load recipients based on status
        async function loadRecipients() {
            const statusCheckboxes = document.querySelectorAll('[id^="status-"]:checked');
            const statuses = Array.from(statusCheckboxes).map(cb => cb.value);

            if (statuses.length === 0) {
                alert('Please select at least one order status');
                return;
            }

            try {
                const promises = statuses.map(status => 
                    fetch(`api/orders.php?action=list&status=${status}&per_page=1000`)
                );
                
                const responses = await Promise.all(promises);
                const dataPromises = responses.map(r => r.json());
                const results = await Promise.all(dataPromises);

                selectedOrders = [];
                results.forEach(result => {
                    if (result.success && result.data) {
                        selectedOrders.push(...result.data);
                    }
                });

                // Apply "Not Picking Calls" filter if checked
                const notPickingCheckbox = document.getElementById('category-not-picking');
                if (notPickingCheckbox && notPickingCheckbox.checked) {
                    selectedOrders = selectedOrders.filter(order => {
                        const notes = (order.admin_notes || '').toLowerCase();
                        return notes.includes('not picking') || 
                               notes.includes('no answer') || 
                               notes.includes('not answering') ||
                               notes.includes('unreachable') ||
                               notes.includes('not responding');
                    });
                }

                // Update stats
                const totalRecipients = selectedOrders.length;
                const whatsappRecipients = selectedOrders.filter(o => o.phone).length;
                const emailRecipients = selectedOrders.filter(o => o.email).length;

                document.getElementById('total-recipients').textContent = totalRecipients;
                document.getElementById('whatsapp-recipients').textContent = whatsappRecipients;
                document.getElementById('email-recipients').textContent = emailRecipients;

                // Show recipient list
                const recipientList = document.getElementById('recipient-list');
                recipientList.style.display = 'block';
                recipientList.innerHTML = selectedOrders.map(order => `
                    <div class="recipient-item">
                        <div>
                            <strong>${order.fullname}</strong><br>
                            <small>${order.phone || 'No phone'} | ${order.email || 'No email'}</small>
                        </div>
                        <div>
                            <span style="background: #e0e7ff; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem;">
                                ${order.status}
                            </span>
                        </div>
                    </div>
                `).join('');

                updateBulkPreview();
            } catch (error) {
                console.error('Error loading recipients:', error);
                alert('Failed to load recipients');
            }
        }

        // Update bulk message preview
        function updateBulkPreview() {
            const previewEl = document.getElementById('bulk-message-preview');

            if (selectedOrders.length === 0) {
                previewEl.textContent = 'Load recipients first...';
                return;
            }

            // Always use the custom message textarea value (which may contain template or custom message)
            const customMessage = document.getElementById('bulk-custom-message').value;
            previewEl.textContent = customMessage || 'Select a message type or enter custom message...';
        }

        // Load individual order
        async function loadIndividualOrder() {
            const orderId = document.getElementById('individual-order-id').value;

            if (!orderId) {
                alert('Please enter an order ID');
                return;
            }

            try {
                const response = await fetch(`api/orders.php?action=single&id=${orderId}`);
                const data = await response.json();

                if (data.success && data.data) {
                    individualOrder = data.data;
                    
                    document.getElementById('individual-customer-name').value = individualOrder.fullname;
                    document.getElementById('individual-customer-phone').value = individualOrder.phone || 'N/A';
                    document.getElementById('individual-customer-email').value = individualOrder.email || 'N/A';
                    document.getElementById('individual-order-status').value = individualOrder.status;
                    
                    document.getElementById('individual-order-details').style.display = 'block';
                    updateIndividualPreview();
                } else {
                    alert('Order not found');
                }
            } catch (error) {
                console.error('Error loading order:', error);
                alert('Failed to load order');
            }
        }

        // Update individual message preview
        function updateIndividualPreview() {
            if (!individualOrder) return;

            const previewEl = document.getElementById('individual-message-preview');

            // Always use the custom message textarea value (which may contain template or custom message)
            const customMessage = document.getElementById('individual-custom-message').value;
            previewEl.textContent = customMessage || 'Select a message type or enter custom message...';
        }

        // Format order message
        function formatOrderMessage(order, messageType) {
            const quantity = order.quantity || 1;
            const baseSets = { 'starter': 1, 'bundle': 2, 'collection': 3 };
            const totalSets = (baseSets[order.pack] || 1) * quantity;

            let packageDetails = '';
            if (order.pack === 'starter') {
                packageDetails = `Starter Set (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook)`;
            } else if (order.pack === 'bundle') {
                packageDetails = `Learning Bundle (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook, ${totalSets} gaming pad${totalSets > 1 ? 's' : ''}, ${totalSets} skipping rope${totalSets > 1 ? 's' : ''}, ${totalSets} U-shape Brush${totalSets > 1 ? 'es' : ''})`;
            } else {
                packageDetails = `Mastery Collection (${totalSets} set${totalSets > 1 ? 's' : ''} of copybook, ${totalSets} gaming pad${totalSets > 1 ? 's' : ''}, ${totalSets} skipping rope${totalSets > 1 ? 's' : ''}, ${totalSets} U-shape Brush${totalSets > 1 ? 'es' : ''})`;
            }

            const unitPrice = order.pack === 'starter' ? 18000 : (order.pack === 'bundle' ? 32000 : 45000);
            const totalAmount = '₦' + (unitPrice * quantity).toLocaleString();
            const orderDate = order.created_at ? new Date(order.created_at).toLocaleDateString('en-GB') : 'N/A';
            const lgaAddress = [order.local_govt, order.address].filter(Boolean).join(', ');

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

            let message = `━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n`;
            message += `SANK MAGIC COPY BOOK - ${messageHeader}\n`;
            message += `━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n`;
            message += `${messageIntro}\n\n`;
            message += `Order ID: #${order.id}\n`;
            message += `Status: ${order.status}\n`;
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
            message += `Amount: ${totalAmount}\n`;
            
            if (messageType === 'confirmation') {
                message += `\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\nYour order is being processed and will be delivered soon.\n\nWhen will you be available for delivery?\n\nThank you for choosing Sank Magic Copy Book!`;
            } else if (messageType === 'not_picking') {
                message += `\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n⚠️ IMPORTANT: Please call us back or reply to this message with your availability.\n\nContact Numbers:\n📞 09029026782\n📞 08102609396\n\nWe need to confirm your delivery details to proceed with your order.\n\nThank you for your cooperation!`;
            } else {
                message += `\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\nIf you have questions, please contact us.`;
            }

            return message;
        }

        // Send bulk WhatsApp
        function sendBulkWhatsApp() {
            if (selectedOrders.length === 0) {
                alert('Please load recipients first');
                return;
            }

            const ordersWithPhone = selectedOrders.filter(o => o.phone);
            
            if (ordersWithPhone.length === 0) {
                alert('No recipients with phone numbers');
                return;
            }

            if (!confirm(`Send WhatsApp message to ${ordersWithPhone.length} customers?`)) {
                return;
            }

            const messageType = document.getElementById('bulk-message-type').value;
            
            ordersWithPhone.forEach((order, index) => {
                setTimeout(() => {
                    // Always use the custom message textarea (contains template or custom message)
                    const message = document.getElementById('bulk-custom-message').value;
                    
                    let phone = order.phone.replace(/[^0-9]/g, '');
                    if (phone.startsWith('0')) {
                        phone = '234' + phone.substring(1);
                    } else if (!phone.startsWith('234')) {
                        phone = '234' + phone;
                    }
                    
                    const whatsappURL = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                    window.open(whatsappURL, '_blank');
                }, index * 2000); // 2 second delay between each
            });

            alert('Opening WhatsApp for each recipient. Please note: There will be a 2-second delay between each window.');
        }

        // Send bulk Email
        async function sendBulkEmail() {
            if (selectedOrders.length === 0) {
                alert('Please load recipients first');
                return;
            }

            const ordersWithEmail = selectedOrders.filter(o => o.email);
            
            if (ordersWithEmail.length === 0) {
                alert('No recipients with email addresses');
                return;
            }

            if (!confirm(`Send emails to ${ordersWithEmail.length} recipient(s)?`)) {
                return;
            }

            const messageType = document.getElementById('bulk-message-type').value;
            
            // Always use the custom message textarea (contains template or custom message)
            const message = document.getElementById('bulk-custom-message').value;
            
            const subject = messageType === 'confirmation' 
                ? 'Order Confirmation - Sank Magic Copy Book'
                : messageType === 'reminder'
                ? 'Order Reminder - Sank Magic Copy Book'
                : messageType === 'not_picking'
                ? 'URGENT: Unable to Reach You - Sank Magic Copy Book'
                : 'Message from Sank Magic Copy Book';
            
            // Show loading overlay
            const loadingOverlay = document.createElement('div');
            loadingOverlay.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 10000; display: flex; align-items: center; justify-content: center;';
            loadingOverlay.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 10px; text-align: center; min-width: 300px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #0a7c42; margin-bottom: 20px;"></i>
                    <h3>Sending Emails...</h3>
                    <p>Please wait while we send emails to ${ordersWithEmail.length} recipient(s)</p>
                    <p id="email-progress">0 / ${ordersWithEmail.length}</p>
                </div>
            `;
            document.body.appendChild(loadingOverlay);
            
            try {
                let successCount = 0;
                let failCount = 0;
                
                // Send emails in batches to avoid overwhelming the server
                const batchSize = 5;
                for (let i = 0; i < ordersWithEmail.length; i += batchSize) {
                    const batch = ordersWithEmail.slice(i, i + batchSize);
                    const batchEmails = batch.map(o => o.email).join(',');
                    
                    const response = await fetch('php/send_email_api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            to: batchEmails,
                            to_name: 'Customer',
                            subject: subject,
                            body: message,
                            is_html: false
                        })
                    });
                    
                    const result = await response.json();
                    successCount += result.success_count || 0;
                    failCount += result.fail_count || 0;
                    
                    // Update progress
                    document.getElementById('email-progress').textContent = `${successCount + failCount} / ${ordersWithEmail.length}`;
                    
                    // Small delay between batches
                    if (i + batchSize < ordersWithEmail.length) {
                        await new Promise(resolve => setTimeout(resolve, 1000));
                    }
                }
                
                // Remove loading overlay
                document.body.removeChild(loadingOverlay);
                
                // Show results
                if (successCount > 0) {
                    alert(`✅ Successfully sent ${successCount} email(s)` + (failCount > 0 ? `\n⚠️ ${failCount} failed` : ''));
                } else {
                    alert(`❌ Failed to send all emails. Please check your SMTP configuration.`);
                }
                
            } catch (error) {
                // Remove loading overlay
                if (loadingOverlay.parentNode) {
                    document.body.removeChild(loadingOverlay);
                }
                
                alert('Error sending emails: ' + error.message);
                console.error('Email error:', error);
            }
        }

        // Send individual WhatsApp
        function sendIndividualWhatsApp() {
            if (!individualOrder || !individualOrder.phone) {
                alert('Customer phone number not available');
                return;
            }

            // Always use the custom message textarea (contains template or custom message)
            const message = document.getElementById('individual-custom-message').value;
            
            let phone = individualOrder.phone.replace(/[^0-9]/g, '');
            if (phone.startsWith('0')) {
                phone = '234' + phone.substring(1);
            } else if (!phone.startsWith('234')) {
                phone = '234' + phone;
            }
            
            const whatsappURL = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            window.open(whatsappURL, '_blank');
        }

        // Send individual Email
        async function sendIndividualEmail() {
            if (!individualOrder || !individualOrder.email) {
                alert('Customer email not available');
                return;
            }

            const messageType = document.getElementById('individual-message-type').value;
            
            // Always use the custom message textarea (contains template or custom message)
            const message = document.getElementById('individual-custom-message').value;
            
            const subject = messageType === 'confirmation'
                ? `Order Confirmation - #${individualOrder.id} - Sank Magic Copy Book`
                : messageType === 'reminder'
                ? `Order Reminder - #${individualOrder.id} - Sank Magic Copy Book`
                : messageType === 'not_picking'
                ? `URGENT: Unable to Reach You - Order #${individualOrder.id}`
                : `Message - Order #${individualOrder.id} - Sank Magic Copy Book`;
            
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
                        to: individualOrder.email,
                        to_name: individualOrder.fullname,
                        subject: subject,
                        body: message,
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
                    successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Email sent successfully to ' + individualOrder.email;
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
                
                alert('Failed to send email: ' + error.message);
                console.error('Email error:', error);
            }
        }

        // Use template
        function useTemplate(templateType) {
            // Switch to bulk tab first
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            document.querySelector('.tab').classList.add('active');
            document.getElementById('bulk-tab').classList.add('active');
            
            // Set message type based on template
            setTimeout(() => {
                const messageTypeSelect = document.getElementById('bulk-message-type');
                if (templateType === 'confirmation' || templateType === 'reminder' || templateType === 'not_picking') {
                    messageTypeSelect.value = templateType;
                    document.getElementById('custom-message-section').style.display = 'none';
                } else if (templateType === 'delivery') {
                    messageTypeSelect.value = 'custom';
                    document.getElementById('bulk-custom-message').value = 'Dear Customer,\n\nYour order is on its way! It will be delivered soon.\n\nThank you for your patience.';
                    document.getElementById('custom-message-section').style.display = 'block';
                } else if (templateType === 'followup') {
                    messageTypeSelect.value = 'custom';
                    document.getElementById('bulk-custom-message').value = 'Dear Customer,\n\nWe hope you received your Sank Magic Copy Book order.\n\nWe\'d love to hear your feedback! How is your child enjoying the product?\n\nThank you for choosing us!';
                    document.getElementById('custom-message-section').style.display = 'block';
                }
                updateBulkPreview();
            }, 100);
        }

        // Update custom message preview on input
        document.getElementById('bulk-custom-message').addEventListener('input', updateBulkPreview);
        document.getElementById('individual-custom-message').addEventListener('input', updateIndividualPreview);
    </script>
</body>
</html>
