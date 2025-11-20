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
                    <a href="index.html"><i class="fas fa-home"></i> Home</a>
                    <a href="#" class="active"><i class="fas fa-shopping-cart"></i> Orders</a>
                    <a href="#"><i class="fas fa-users"></i> Customers</a>
                    <a href="#"><i class="fas fa-chart-bar"></i> Analytics</a>
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
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>Confirmed Date</th>
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
        // Sample order data
        let orders = [
            {
                id: 'ETH2023110452',
                customer: 'Adeola Johnson',
                package: 'Learning Bundle',
                amount: '₦32,000',
                status: 'confirmed',
                orderDate: '2023-11-04 14:30',
                confirmedDate: '2023-11-04 15:45',
                notes: 'Customer confirmed via WhatsApp'
            },
            {
                id: 'ETH2023110389',
                customer: 'Chinedu Okoro',
                package: 'Mastery Collection',
                amount: '₦45,000',
                status: 'shipped',
                orderDate: '2023-11-03 10:15',
                confirmedDate: '2023-11-03 11:20',
                notes: 'Shipped via dispatch rider'
            },
            {
                id: 'ETH2023110276',
                customer: 'Fatima Bello',
                package: 'Starter Set',
                amount: '₦18,000',
                status: 'delivered',
                orderDate: '2023-11-02 09:45',
                confirmedDate: '2023-11-02 10:30',
                notes: 'Delivered successfully'
            },
            {
                id: 'ETH2023110154',
                customer: 'Emeka Nwosu',
                package: 'Learning Bundle',
                amount: '₦32,000',
                status: 'processing',
                orderDate: '2023-11-01 16:20',
                confirmedDate: '2023-11-01 17:05',
                notes: 'Preparing for shipment'
            },
            {
                id: 'ETH2023102987',
                customer: 'Bisi Adekunle',
                package: 'Starter Set',
                amount: '₦18,000',
                status: 'pending',
                orderDate: '2023-10-29 13:10',
                confirmedDate: '',
                notes: 'Awaiting customer confirmation'
            },
            {
                id: 'ETH2023102765',
                customer: 'Tunde Lawal',
                package: 'Mastery Collection',
                amount: '₦45,000',
                status: 'delivered',
                orderDate: '2023-10-27 11:30',
                confirmedDate: '2023-10-27 12:15',
                notes: 'Customer satisfied with products'
            },
            {
                id: 'ETH2023102543',
                customer: 'Grace Okafor',
                package: 'Learning Bundle',
                amount: '₦32,000',
                status: 'cancelled',
                orderDate: '2023-10-25 15:40',
                confirmedDate: '2023-10-25 16:20',
                notes: 'Customer requested cancellation'
            },
            {
                id: 'ETH2023102312',
                customer: 'Samuel Adeyemi',
                package: 'Starter Set',
                amount: '₦18,000',
                status: 'delivered',
                orderDate: '2023-10-23 08:50',
                confirmedDate: '2023-10-23 09:35',
                notes: 'Delivered to office address'
            }
        ];

        // Pagination variables
        let currentPage = 1;
        const ordersPerPage = 5;
        let filteredOrders = [...orders];

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
            updateStats();
            renderOrdersTable();
            setupEventListeners();
        });

        // Set up event listeners
        function setupEventListeners() {
            searchInput.addEventListener('input', filterOrders);
            statusFilter.addEventListener('change', filterOrders);
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

        // Update statistics cards
        function updateStats() {
            const totalOrders = orders.length;
            const confirmedOrders = orders.filter(order => order.status === 'confirmed' || order.status === 'processing' || order.status === 'shipped' || order.status === 'delivered').length;
            const shippedOrders = orders.filter(order => order.status === 'shipped').length;
            const deliveredOrders = orders.filter(order => order.status === 'delivered').length;
            
            document.getElementById('total-orders').textContent = totalOrders;
            document.getElementById('confirmed-orders').textContent = confirmedOrders;
            document.getElementById('shipped-orders').textContent = shippedOrders;
            document.getElementById('delivered-orders').textContent = deliveredOrders;
        }

        // Filter orders based on search and status
        function filterOrders() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusFilterValue = statusFilter.value;
            
            filteredOrders = orders.filter(order => {
                const matchesSearch = 
                    order.id.toLowerCase().includes(searchTerm) ||
                    order.customer.toLowerCase().includes(searchTerm) ||
                    order.package.toLowerCase().includes(searchTerm);
                
                const matchesStatus = statusFilterValue === '' || order.status === statusFilterValue;
                
                return matchesSearch && matchesStatus;
            });
            
            currentPage = 1;
            renderOrdersTable();
        }

        // Render orders table with pagination
        function renderOrdersTable() {
            // Clear current table
            ordersTbody.innerHTML = '';
            
            // Calculate pagination
            const totalPages = Math.ceil(filteredOrders.length / ordersPerPage);
            const startIndex = (currentPage - 1) * ordersPerPage;
            const endIndex = Math.min(startIndex + ordersPerPage, filteredOrders.length);
            const currentOrders = filteredOrders.slice(startIndex, endIndex);
            
            // Populate table
            currentOrders.forEach(order => {
                const row = document.createElement('tr');
                
                // Format confirmed date
                const confirmedDate = order.confirmedDate ? order.confirmedDate : 'Not confirmed';
                
                row.innerHTML = `
                    <td class="order-id">${order.id}</td>
                    <td class="customer-name">${order.customer}</td>
                    <td>${order.package}</td>
                    <td>${order.amount}</td>
                    <td><span class="status-badge status-${order.status}">${getStatusText(order.status)}</span></td>
                    <td>${order.orderDate}</td>
                    <td>${confirmedDate}</td>
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
            paginationInfo.textContent = `Showing ${startIndex + 1}-${endIndex} of ${filteredOrders.length} orders`;
            
            // Render pagination controls
            renderPaginationControls(totalPages);
        }

        // Render pagination controls
        function renderPaginationControls(totalPages) {
            paginationControls.innerHTML = '';
            
            // Previous button
            const prevButton = document.createElement('button');
            prevButton.className = `pagination-btn ${currentPage === 1 ? 'disabled' : ''}`;
            prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderOrdersTable();
                }
            });
            paginationControls.appendChild(prevButton);
            
            // Page buttons
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageButton.textContent = i;
                pageButton.addEventListener('click', () => {
                    currentPage = i;
                    renderOrdersTable();
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
                    renderOrdersTable();
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
        function openEditModal(orderId) {
            const order = orders.find(o => o.id === orderId);
            
            if (order) {
                document.getElementById('edit-order-id').value = order.id;
                document.getElementById('edit-customer-name').value = order.customer;
                document.getElementById('edit-package').value = order.package;
                document.getElementById('edit-order-date').value = order.orderDate;
                document.getElementById('edit-confirmed-date').value = order.confirmedDate || 'Not confirmed';
                document.getElementById('edit-status').value = order.status;
                document.getElementById('edit-notes').value = order.notes || '';
                
                editOrderModal.style.display = 'flex';
            }
        }

        // Close edit modal
        function closeEditModal() {
            editOrderModal.style.display = 'none';
        }

        // Save order changes
        function saveOrderChanges() {
            const orderId = document.getElementById('edit-order-id').value;
            const newStatus = document.getElementById('edit-status').value;
            const notes = document.getElementById('edit-notes').value;
            
            const orderIndex = orders.findIndex(o => o.id === orderId);
            
            if (orderIndex !== -1) {
                // Update order status
                orders[orderIndex].status = newStatus;
                orders[orderIndex].notes = notes;
                
                // If confirming for the first time, set confirmed date
                if (newStatus === 'confirmed' && !orders[orderIndex].confirmedDate) {
                    const now = new Date();
                    orders[orderIndex].confirmedDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
                }
                
                // Update stats and table
                updateStats();
                filterOrders(); // This will also re-render the table
                
                // Close modal
                closeEditModal();
                
                // Show success message
                alert(`Order ${orderId} status updated successfully!`);
            }
        }

        // Delete order
        function deleteOrder(orderId) {
            if (confirm(`Are you sure you want to delete order ${orderId}? This action cannot be undone.`)) {
                orders = orders.filter(o => o.id !== orderId);
                updateStats();
                filterOrders(); // This will also re-render the table
                alert(`Order ${orderId} has been deleted.`);
            }
        }

        // Refresh orders
        function refreshOrders() {
            // In a real application, this would fetch fresh data from the server
            // For now, we'll just re-render the table
            filterOrders();
            alert('Orders refreshed successfully!');
        }

        // Export orders
        function exportOrders() {
            // In a real application, this would generate a CSV or Excel file
            // For now, we'll just show an alert
            alert('Export functionality would generate a CSV file with all order data in a real application.');
        }
    </script>
</body>
</html>