<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders | Emerald Tech Hub</title>
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
        }
        
        .container {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 20px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            font-size: 28px;
            color: white;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: white;
        }
        
        .admin-nav {
            display: flex;
            gap: 20px;
        }
        
        .admin-nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: 4px;
        }
        
        .admin-nav a:hover, .admin-nav a.active {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* Main Content */
        .main-content {
            padding: 40px 0;
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
            margin-top: 50px;
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
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .pagination {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <div class="logo-text">Emerald Tech Hub</div>
                </div>
                <nav class="admin-nav">
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="customer_orderlist.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a>
                    <a href="agent_management.php"><i class="fas fa-user-tie"></i> Agents</a>
                    <a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a>
                    <a href="sales_notifications.php"><i class="fas fa-bell"></i> Alerts</a>
                </nav>
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
                    <table id="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Package</th>
                                <th>Amount</th>
                                <th>State</th>
                                <th>Agent</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orders-tbody">
                            <!-- Orders will be populated by JavaScript -->
                        </tbody>
                    </table>
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
                        <li><i class="fas fa-phone"></i> 08163778265</li>
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

    <script>
        // State variables
        let orders = [];
        let allOrders = [];
        let currentPage = 1;
        const ordersPerPage = 10;
        let filteredOrders = [];
        let currentSearch = '';
        let currentStatusFilter = '';
        let statsData = {};

        // DOM Elements
        const ordersTbody = document.getElementById('orders-tbody');
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
            // Clear current table
            ordersTbody.innerHTML = '';
            
            if (!orders || orders.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="8" style="text-align: center; padding: 20px;">No orders found</td>';
                ordersTbody.appendChild(row);
                paginationInfo.textContent = 'Showing 0 orders';
                paginationControls.innerHTML = '';
                return;
            }
            
            // Populate table
            orders.forEach(order => {
                const row = document.createElement('tr');
                
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
                const confirmedDate = order.confirmed_at ? new Date(order.confirmed_at).toLocaleString() : 'Not confirmed';
                const status = order.status || 'pending';
                const agentName = order.agent_name || '<span style="color: #999;">Not assigned</span>';
                
                row.innerHTML = `
                    <td class="order-id">${order.id}</td>
                    <td class="customer-name">${order.fullname}</td>
                    <td>${packageNames[order.pack] || order.pack}</td>
                    <td>${amounts[order.pack] || 'N/A'}</td>
                    <td>${order.state || 'N/A'}</td>
                    <td>${agentName}</td>
                    <td><span class="status-badge status-${status}">${getStatusText(status)}</span></td>
                    <td>${orderDate}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit-btn" data-id="${order.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete-btn" data-id="${order.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                ordersTbody.appendChild(row);
            });
            
            // Add event listeners to action buttons
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