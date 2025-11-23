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
    <title>Analytics & Accounting - Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include 'php/content_protection.php'; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            color: #667eea;
            margin-bottom: 5px;
        }

        .breadcrumb {
            color: #666;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        .date-filter {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group input, .filter-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
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
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.revenue {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .stat-icon.orders {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .stat-icon.average {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .stat-icon.profit {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-change {
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-change.positive {
            color: #28a745;
        }

        .stat-change.negative {
            color: #dc3545;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-chart-line"></i> Analytics & Accounting Dashboard</h1>
            <div class="breadcrumb">
                <a href="index.php">Home</a> / <a href="admin_dashboard_crm.php">Dashboard</a> / <span>Analytics</span>
            </div>
        </header>

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
            <h2><i class="fas fa-user-tie"></i> Agent Performance</h2>
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
    </script>
</body>
</html>
