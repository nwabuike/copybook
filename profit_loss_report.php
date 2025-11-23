<?php
require_once 'php/auth.php';
requireAdmin(); // Only admins can view profit/loss reports

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit & Loss Report | Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
            min-height: 100vh;
        }
        
        .container {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .header {
            background: white;
            padding: 30px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .header h1 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 1rem;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .back-link:hover {
            color: var(--primary-dark);
            transform: translateX(-5px);
        }
        
        .back-link i {
            margin-right: 8px;
        }
        
        .controls {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .controls select,
        .controls input {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            min-width: 150px;
        }
        
        .controls select:focus,
        .controls input:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: var(--transition);
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .summary-card h3 {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .summary-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--dark);
        }
        
        .summary-card.revenue .value {
            color: #2196F3;
        }
        
        .summary-card.expenses .value {
            color: var(--secondary);
        }
        
        .summary-card.profit .value {
            color: var(--primary);
        }
        
        .summary-card .icon {
            font-size: 2.5rem;
            opacity: 0.2;
            float: right;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .chart-container h2 {
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .orders-table {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .orders-table h2 {
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table th,
        table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        table th {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }
        
        table tr:hover {
            background: #f8f9fa;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .controls {
                flex-direction: column;
            }
            
            .controls select,
            .controls input {
                width: 100%;
            }
            
            table {
                font-size: 0.9rem;
            }
            
            table th,
            table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <a href="customer_orderlist.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <h1><i class="fas fa-chart-line"></i> Profit & Loss Report</h1>
            <p>Analyze revenue, expenses, and profitability over time</p>
        </div>
    </div>
    
    <div class="container">
        <div class="controls">
            <select id="period-select">
                <option value="week">Last 7 Days</option>
                <option value="month" selected>This Month</option>
                <option value="year">This Year</option>
                <option value="custom">Custom Range</option>
            </select>
            
            <input type="date" id="start-date" style="display: none;">
            <input type="date" id="end-date" style="display: none;">
            
            <button class="btn btn-primary" onclick="loadReport()">
                <i class="fas fa-sync"></i> Update Report
            </button>
        </div>
        
        <div id="alert-warning" class="alert alert-warning" style="display: none;"></div>
        
        <div class="summary-grid" id="summary-grid">
            <!-- Summary cards will be loaded here -->
        </div>
        
        <div class="chart-container">
            <h2><i class="fas fa-chart-bar"></i> Profit Margin</h2>
            <canvas id="profitChart" height="100"></canvas>
        </div>
        
        <div class="orders-table">
            <h2><i class="fas fa-list"></i> Delivered Orders</h2>
            <table id="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Delivered Date</th>
                        <th>Revenue</th>
                        <th>Expenses</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Orders will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        let profitChart = null;
        
        // Handle period selection
        document.getElementById('period-select').addEventListener('change', function() {
            const customDateInputs = document.querySelectorAll('#start-date, #end-date');
            if (this.value === 'custom') {
                customDateInputs.forEach(input => input.style.display = 'block');
            } else {
                customDateInputs.forEach(input => input.style.display = 'none');
            }
        });
        
        // Load report data
        async function loadReport() {
            try {
                const period = document.getElementById('period-select').value;
                let url = `api/expenses.php?action=profit_loss_report&period=${period}`;
                
                if (period === 'custom') {
                    const startDate = document.getElementById('start-date').value;
                    const endDate = document.getElementById('end-date').value;
                    
                    if (!startDate || !endDate) {
                        alert('Please select start and end dates');
                        return;
                    }
                    
                    url += `&start_date=${startDate}&end_date=${endDate}`;
                }
                
                const response = await fetch(url);
                const result = await response.json();
                
                if (result.success) {
                    displayReport(result.data);
                } else {
                    alert('Error loading report: ' + result.message);
                }
            } catch (error) {
                alert('Error loading report: ' + error.message);
            }
        }
        
        // Display report data
        function displayReport(data) {
            const summary = data.summary;
            const orders = data.orders;
            
            // Show warning if there are orders without expenses
            if (summary.orders_without_expenses > 0) {
                const warningDiv = document.getElementById('alert-warning');
                warningDiv.textContent = `⚠️ ${summary.orders_without_expenses} delivered order(s) don't have expenses recorded. Add expenses to get accurate profit calculations.`;
                warningDiv.style.display = 'block';
            } else {
                document.getElementById('alert-warning').style.display = 'none';
            }
            
            // Display summary cards
            displaySummary(summary);
            
            // Display chart
            displayChart(summary);
            
            // Display orders table
            displayOrdersTable(orders);
        }
        
        // Display summary cards
        function displaySummary(summary) {
            const grid = document.getElementById('summary-grid');
            grid.innerHTML = `
                <div class="summary-card revenue">
                    <i class="fas fa-dollar-sign icon"></i>
                    <h3>Total Revenue</h3>
                    <div class="value">${summary.formatted_revenue}</div>
                    <small>${summary.total_orders} orders</small>
                </div>
                
                <div class="summary-card expenses">
                    <i class="fas fa-receipt icon"></i>
                    <h3>Total Expenses</h3>
                    <div class="value">${summary.formatted_expenses}</div>
                    <small>${summary.orders_with_expenses} orders tracked</small>
                </div>
                
                <div class="summary-card profit">
                    <i class="fas fa-chart-line icon"></i>
                    <h3>Net Profit</h3>
                    <div class="value">${summary.formatted_profit}</div>
                    <small>${summary.profit_margin}% margin</small>
                </div>
            `;
        }
        
        // Display profit chart
        function displayChart(summary) {
            const ctx = document.getElementById('profitChart').getContext('2d');
            
            if (profitChart) {
                profitChart.destroy();
            }
            
            profitChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Revenue', 'Expenses', 'Profit'],
                    datasets: [{
                        label: 'Amount (₦)',
                        data: [
                            summary.total_revenue,
                            summary.total_expenses,
                            summary.total_profit
                        ],
                        backgroundColor: [
                            'rgba(33, 150, 243, 0.7)',
                            'rgba(255, 107, 107, 0.7)',
                            'rgba(10, 124, 66, 0.7)'
                        ],
                        borderColor: [
                            'rgba(33, 150, 243, 1)',
                            'rgba(255, 107, 107, 1)',
                            'rgba(10, 124, 66, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₦' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₦' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Display orders table
        function displayOrdersTable(orders) {
            const tbody = document.getElementById('orders-table').querySelector('tbody');
            tbody.innerHTML = '';
            
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px; color: #999;">No delivered orders found for this period</td></tr>';
                return;
            }
            
            orders.forEach(order => {
                const row = document.createElement('tr');
                const hasExpenses = order.expenses > 0;
                
                row.innerHTML = `
                    <td>#${order.id}</td>
                    <td>${order.fullname}</td>
                    <td style="text-transform: capitalize;">${order.pack}</td>
                    <td>${new Date(order.delivered_at).toLocaleDateString()}</td>
                    <td style="font-weight: 600; color: #2196F3;">${order.formatted_revenue}</td>
                    <td style="font-weight: 600; color: ${hasExpenses ? '#ff6b6b' : '#999'};">
                        ${order.formatted_expenses}
                        ${!hasExpenses ? '<i class="fas fa-exclamation-triangle" style="margin-left: 5px;" title="No expenses recorded"></i>' : ''}
                    </td>
                    <td style="font-weight: 600; color: ${order.profit >= 0 ? 'var(--primary)' : '#ff6b6b'};">
                        ${order.formatted_profit}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadReport();
        });
    </script>
</body>
</html>
