<?php
require_once 'php/auth.php';
requireAdmin(); // Only admins can manage users

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Emerald Tech Hub</title>
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
            max-width: 1400px;
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #333;
        }

        .user-role {
            font-size: 12px;
            color: #666;
            text-transform: capitalize;
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
            text-decoration: none;
        }

        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

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
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-admin {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
        }

        .badge-subadmin {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .badge-agent {
            background: rgba(255, 193, 7, 0.2);
            color: #f57c00;
        }

        .badge-active {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .badge-inactive {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

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
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
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

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 15px;
            }

            .card-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            table {
                font-size: 12px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1><i class="fas fa-users-cog"></i> User Management</h1>
                <div class="breadcrumb">
                    <a href="customer_orderlist.php">Dashboard</a> / <span>Users</span>
                </div>
            </div>
            <div class="user-info">
                <div class="user-avatar"><?= strtoupper(substr($currentUser['full_name'], 0, 1)) ?></div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars($currentUser['full_name']) ?></div>
                    <div class="user-role"><?= htmlspecialchars($currentUser['role']) ?></div>
                </div>
                <a href="logout.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </header>

        <div class="content-card">
            <div class="card-header">
                <h2>System Users</h2>
                <button class="btn btn-success" onclick="openAddUserModal()">
                    <i class="fas fa-user-plus"></i> Add New User
                </button>
            </div>

            <div id="alert-container"></div>

            <table id="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #667eea;"></i>
                            <p>Loading users...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Add New User</h3>
                <button class="close-btn" onclick="closeUserModal()">&times;</button>
            </div>

            <form id="user-form">
                <input type="hidden" id="user-id" name="user_id">

                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" id="full-name" name="full_name" required>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Role *</label>
                    <select id="role" name="role" required>
                        <option value="subadmin">Subadmin</option>
                        <option value="agent">Agent</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group" id="password-group">
                    <label>Password <span id="password-optional">(Leave blank to keep current)</span></label>
                    <input type="password" id="password" name="password">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Minimum 6 characters
                    </small>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success" id="submit-btn">
                        <i class="fas fa-save"></i> Save User
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeUserModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let users = [];
        let editingUserId = null;

        // Load users on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
        });

        async function loadUsers() {
            try {
                const response = await fetch('api/users.php?action=list');
                const data = await response.json();

                if (data.success) {
                    users = data.data;
                    renderUsersTable();
                } else {
                    showAlert('Error loading users: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to load users', 'error');
            }
        }

        function renderUsersTable() {
            const tbody = document.querySelector('#users-table tbody');

            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px;">No users found</td></tr>';
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>${user.id}</td>
                    <td><strong>${user.username}</strong></td>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td><span class="badge badge-${user.role}">${user.role}</span></td>
                    <td><span class="badge badge-${user.status}">${user.status}</span></td>
                    <td>${user.last_login ? new Date(user.last_login).toLocaleString() : 'Never'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm" onclick="editUser(${user.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            ${user.id !== <?= $currentUser['id'] ?> ? `
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id}, '${user.username}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openAddUserModal() {
            editingUserId = null;
            document.getElementById('modal-title').textContent = 'Add New User';
            document.getElementById('user-form').reset();
            document.getElementById('user-id').value = '';
            document.getElementById('password').required = true;
            document.getElementById('password-optional').style.display = 'none';
            document.getElementById('user-modal').classList.add('show');
        }

        function editUser(userId) {
            const user = users.find(u => u.id === userId);
            if (!user) return;

            editingUserId = userId;
            document.getElementById('modal-title').textContent = 'Edit User';
            document.getElementById('user-id').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('full-name').value = user.full_name;
            document.getElementById('email').value = user.email;
            document.getElementById('role').value = user.role;
            document.getElementById('status').value = user.status;
            document.getElementById('password').value = '';
            document.getElementById('password').required = false;
            document.getElementById('password-optional').style.display = 'inline';
            document.getElementById('user-modal').classList.add('show');
        }

        function closeUserModal() {
            document.getElementById('user-modal').classList.remove('show');
        }

        document.getElementById('user-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            try {
                const action = editingUserId ? 'update' : 'create';
                const response = await fetch(`api/users.php?action=${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    closeUserModal();
                    loadUsers();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to save user', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Save User';
            }
        });

        async function deleteUser(userId, username) {
            if (!confirm(`Are you sure you want to delete user "${username}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`api/users.php?action=delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    loadUsers();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Failed to delete user', 'error');
            }
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
    </script>
</body>
</html>
