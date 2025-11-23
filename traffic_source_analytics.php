<?php
// traffic_source_analytics.php - Compare Facebook vs TikTok Performance
require_once 'php/auth.php';
requireAdmin();
require_once 'php/db.php';

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Source Analytics - Emerald Tech Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #0a7c42;
            --primary-dark: #066633;
            --primary-light: #e8f5ed;
            --facebook: #1877f2;
            --tiktok: #000000;
            --tiktok-accent: #fe2c55;
            --text: #333;
            --border: #e0e0e0;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: var(--text);
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
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
        }

        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            min-height: 100vh;
            padding: 30px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Source Comparison Cards */
        .source-comparison {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .source-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .source-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .source-card.facebook::before {
            background: var(--facebook);
        }

        .source-card.tiktok::before {
            background: linear-gradient(90deg, var(--tiktok) 0%, var(--tiktok-accent) 100%);
        }

        .source-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .source-name {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 600;
        }

        .source-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .facebook .source-icon {
            background: var(--facebook);
        }

        .tiktok .source-icon {
            background: var(--tiktok);
        }

        .source-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .metric {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .metric-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
        }

        .metric-change {
            font-size: 12px;
            margin-top: 5px;
        }

        .metric-change.positive {
            color: #10b981;
        }

        .metric-change.negative {
            color: #ef4444;
        }

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        /* Date Filter */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .filter-row {
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
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text);
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        /* Detailed Table */
        .data-table {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            background: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.facebook {
            background: #e7f3ff;
            color: var(--facebook);
        }

        .badge.tiktok {
            background: #ffe7f0;
            color: var(--tiktok-accent);
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .main-wrapper {
                padding: 15px;
            }

            .source-comparison {
                grid-template-columns: 1fr;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .source-metrics {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
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
                        <a href="admin_dashboard_crm.php" class="sidebar-menu-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="analytics.php" class="sidebar-menu-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="traffic_source_analytics.php" class="sidebar-menu-link active">
                            <i class="fas fa-chart-line"></i>
                            <span>Traffic Sources</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="logout.php" class="sidebar-menu-link">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-chart-line"></i>
                    Traffic Source Analytics
                </h1>
            </div>

            <!-- Date Filter -->
            <div class="filter-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Start Date</label>
                        <input type="date" id="startDate">
                    </div>
                    <div class="filter-group">
                        <label>End Date</label>
                        <input type="date" id="endDate">
                    </div>
                    <button class="btn btn-primary" onclick="loadData()">
                        <i class="fas fa-sync"></i> Update
                    </button>
                </div>
            </div>

            <!-- Source Comparison Cards -->
            <div class="source-comparison" id="sourceComparison">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Charts -->
            <div class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">Orders by Source</h3>
                    <div class="chart-container">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Revenue by Source</h3>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Table -->
            <div class="data-table">
                <h3 class="chart-title">Recent Orders by Source</h3>
                <table id="ordersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>Source</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                                <p>Loading orders...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let ordersChart, revenueChart;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeDates();
            loadData();
        });

        function initializeDates() {
            const today = new Date();
            const weekAgo = new Date(today);
            weekAgo.setDate(today.getDate() - 30);

            document.getElementById('startDate').valueAsDate = weekAgo;
            document.getElementById('endDate').valueAsDate = today;
        }

        async function loadData() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            try {
                const response = await fetch(`api/traffic_sources.php?start_date=${startDate}&end_date=${endDate}`);
                const data = await response.json();

                if (data.success) {
                    renderSourceCards(data.data);
                    renderCharts(data.data);
                    renderOrdersTable(data.orders);
                }
            } catch (error) {
                console.error('Error loading data:', error);
            }
        }

        function renderSourceCards(data) {
            const container = document.getElementById('sourceComparison');
            const sources = ['facebook', 'tiktok'];
            
            container.innerHTML = sources.map(source => {
                const sourceData = data[source] || { orders: 0, revenue: 0, avg_order: 0, conversion_rate: 0 };
                
                return `
                    <div class="source-card ${source}">
                        <div class="source-header">
                            <div class="source-name">
                                <div class="source-icon">
                                    <i class="fab fa-${source}"></i>
                                </div>
                                ${source.charAt(0).toUpperCase() + source.slice(1)}
                            </div>
                        </div>
                        <div class="source-metrics">
                            <div class="metric">
                                <div class="metric-label">Total Orders</div>
                                <div class="metric-value">${sourceData.orders}</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Revenue</div>
                                <div class="metric-value">₦${Number(sourceData.revenue).toLocaleString()}</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Avg Order Value</div>
                                <div class="metric-value">₦${Number(sourceData.avg_order).toLocaleString()}</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Conversion Rate</div>
                                <div class="metric-value">${sourceData.conversion_rate}%</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderCharts(data) {
            const sources = ['Facebook', 'TikTok'];
            const orders = [data.facebook?.orders || 0, data.tiktok?.orders || 0];
            const revenue = [data.facebook?.revenue || 0, data.tiktok?.revenue || 0];

            // Orders Chart
            if (ordersChart) ordersChart.destroy();
            const ordersCtx = document.getElementById('ordersChart').getContext('2d');
            ordersChart = new Chart(ordersCtx, {
                type: 'doughnut',
                data: {
                    labels: sources,
                    datasets: [{
                        data: orders,
                        backgroundColor: ['#1877f2', '#fe2c55'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Revenue Chart
            if (revenueChart) revenueChart.destroy();
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: sources,
                    datasets: [{
                        label: 'Revenue (₦)',
                        data: revenue,
                        backgroundColor: ['#1877f2', '#fe2c55'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₦' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderOrdersTable(orders) {
            const tbody = document.getElementById('ordersTableBody');
            
            if (!orders || orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No orders found</td></tr>';
                return;
            }

            tbody.innerHTML = orders.map(order => `
                <tr>
                    <td>#${order.id}</td>
                    <td>${order.fullname}</td>
                    <td>${order.pack}</td>
                    <td><span class="badge ${order.source}">${order.source}</span></td>
                    <td>₦${Number(order.price).toLocaleString()}</td>
                    <td>${order.created_at}</td>
                </tr>
            `).join('');
        }
    </script>
</body>
</html>
