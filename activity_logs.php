<?php
require_once 'php/auth.php';
requireAdmin(); // Only admins can view activity logs

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - Emerald Tech Hub</title>
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
        }

        header {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
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

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group select,
        .filter-group input {
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
            padding: 10px 20px;
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
        }

        .btn-success {
            background: #28a745;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .content-card h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-login {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .badge-logout {
            background: rgba(108, 117, 125, 0.2);
            color: #6c757d;
        }

        .badge-create {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
        }

        .badge-update, .badge-update_status {
            background: rgba(255, 193, 7, 0.2);
            color: #f57c00;
        }

        .badge-delete {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .entity-badge {
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 11px;
            background: #e9ecef;
            color: #495057;
        }

        .description {
            color: #666;
            max-width: 400px;
        }

        .timestamp {
            color: #999;
            font-size: 12px;
            white-space: nowrap;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }

        .pagination button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 5px;
        }

        .pagination button.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 15px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 11px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1><i class="fas fa-history"></i> Activity Logs</h1>
                <div class="breadcrumb">
                    <a href="admin_dashboard_crm.php">Dashboard</a> / <span>Activity Logs</span>
                </div>
            </div>
            <a href="admin_dashboard_crm.php" class="btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </header>

        <div class="filter-card">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>User</label>
                    <select id="filter-user">
                        <option value="">All Users</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Action</label>
                    <select id="filter-action">
                        <option value="">All Actions</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                        <option value="update_status">Update Status</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Entity Type</label>
                    <select id="filter-entity">
                        <option value="">All Types</option>
                        <option value="order">Order</option>
                        <option value="user">User</option>
                        <option value="agent">Agent</option>
                        <option value="stock">Stock</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Start Date</label>
                    <input type="date" id="filter-start-date">
                </div>

                <div class="filter-group">
                    <label>End Date</label>
                    <input type="date" id="filter-end-date">
                </div>

                <div class="filter-group" style="display: flex; align-items: end;">
                    <button class="btn" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h2><i class="fas fa-list"></i> System Activity</h2>

            <table id="logs-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #667eea;"></i>
                            <p>Loading activity logs...</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination" id="pagination"></div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let totalPages = 1;
        const perPage = 50;

        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
            setDefaultDates();
            loadLogs();
        });

        function setDefaultDates() {
            const today = new Date();
            const weekAgo = new Date(today);
            weekAgo.setDate(today.getDate() - 7);

            document.getElementById('filter-start-date').valueAsDate = weekAgo;
            document.getElementById('filter-end-date').valueAsDate = today;
        }

        async function loadUsers() {
            try {
                const response = await fetch('api/users.php?action=list');
                const data = await response.json();

                if (data.success) {
                    const userSelect = document.getElementById('filter-user');
                    data.data.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = `${user.full_name} (${user.username})`;
                        userSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }

        async function loadLogs() {
            try {
                const filters = getFilters();
                const queryString = new URLSearchParams({
                    ...filters,
                    page: currentPage,
                    per_page: perPage
                }).toString();

                const response = await fetch(`api/activity_logs.php?${queryString}`);
                const data = await response.json();

                if (data.success) {
                    renderLogs(data.data);
                    renderPagination(data.pagination);
                } else {
                    showError('Failed to load logs');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('An error occurred while loading logs');
            }
        }

        function getFilters() {
            return {
                user_id: document.getElementById('filter-user').value,
                action: document.getElementById('filter-action').value,
                entity_type: document.getElementById('filter-entity').value,
                start_date: document.getElementById('filter-start-date').value,
                end_date: document.getElementById('filter-end-date').value
            };
        }

        function renderLogs(logs) {
            const tbody = document.querySelector('#logs-table tbody');

            if (logs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">No activity logs found</td></tr>';
                return;
            }

            tbody.innerHTML = logs.map(log => `
                <tr>
                    <td class="timestamp">${formatDate(log.created_at)}</td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">${log.full_name ? log.full_name.charAt(0).toUpperCase() : 'U'}</div>
                            <div>
                                <div style="font-weight: 600;">${log.full_name || 'Unknown'}</div>
                                <div style="font-size: 11px; color: #999;">${log.username || 'N/A'}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-${log.action}">${log.action}</span></td>
                    <td><span class="entity-badge">${log.entity_type} #${log.entity_id || 'N/A'}</span></td>
                    <td class="description">${log.description}</td>
                    <td style="color: #999; font-size: 12px;">${log.ip_address || 'N/A'}</td>
                </tr>
            `).join('');
        }

        function renderPagination(pagination) {
            if (!pagination) return;

            totalPages = pagination.total_pages;
            currentPage = pagination.page;

            const paginationDiv = document.getElementById('pagination');
            let html = '';

            // Previous button
            html += `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left"></i>
                     </button>`;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `<button class="${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<button disabled>...</button>`;
                }
            }

            // Next button
            html += `<button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                        <i class="fas fa-chevron-right"></i>
                     </button>`;

            paginationDiv.innerHTML = html;
        }

        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            loadLogs();
        }

        function applyFilters() {
            currentPage = 1;
            loadLogs();
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function showError(message) {
            const tbody = document.querySelector('#logs-table tbody');
            tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; padding: 40px; color: #dc3545;">
                                <i class="fas fa-exclamation-triangle"></i> ${message}
                              </td></tr>`;
        }
    </script>
</body>
</html>
