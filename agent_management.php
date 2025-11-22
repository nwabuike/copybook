<?php
require_once 'php/auth.php';
requireLogin();

$currentUser = getCurrentUser();
$canDelete = canPerform('delete_agent');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Management - Emerald Tech Hub</title>
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

        h1 {
            color: #667eea;
            margin-bottom: 10px;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-info h3 {
            font-size: 32px;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 14px;
        }

        .stat-icon {
            font-size: 40px;
            color: #667eea;
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

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            background: none;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .edit-btn {
            color: #667eea;
            background: #e7e9fc;
        }

        .edit-btn:hover {
            background: #667eea;
            color: white;
        }

        .delete-btn {
            color: #dc3545;
            background: #f8d7da;
        }

        .delete-btn:hover {
            background: #dc3545;
            color: white;
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

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-header h2 {
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
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
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input, select, textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .states-selection {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            max-height: 200px;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .state-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .state-checkbox input {
            width: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
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
                        <a href="bulk_messaging.php" class="sidebar-menu-link">
                            <i class="fas fa-paper-plane"></i>
                            <span>Bulk Messaging</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="agent_management.php" class="sidebar-menu-link active">
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
                            <span>Agent Management</span>
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

    <div class="container">

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="total-agents">0</h3>
                    <p>Total Agents</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="active-agents">0</h3>
                    <p>Active Agents</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="total-states">0</h3>
                    <p>States Covered</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h2>All Delivery Agents</h2>
                <button class="btn" id="add-agent-btn">
                    <i class="fas fa-plus"></i> Add New Agent
                </button>
            </div>

            <div class="table-container">
                <table id="agents-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>States Covered</th>
                            <th>Total Orders</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="agents-tbody">
                        <tr>
                            <td colspan="8" class="loading">
                                <i class="fas fa-spinner fa-spin"></i> Loading agents...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Agent Modal -->
    <div class="modal" id="agent-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Add New Agent</h2>
                <button class="close-btn" id="close-modal">&times;</button>
            </div>

            <form id="agent-form">
                <input type="hidden" id="agent-id">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="agent-name">Full Name *</label>
                        <input type="text" id="agent-name" required>
                    </div>

                    <div class="form-group">
                        <label for="agent-email">Email</label>
                        <input type="email" id="agent-email">
                    </div>

                    <div class="form-group">
                        <label for="agent-phone">Phone *</label>
                        <input type="tel" id="agent-phone" required>
                    </div>

                    <div class="form-group">
                        <label for="agent-alt-phone">Alternative Phone</label>
                        <input type="tel" id="agent-alt-phone">
                    </div>

                    <div class="form-group full-width">
                        <label for="agent-address">Address</label>
                        <textarea id="agent-address" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="agent-status">Status</label>
                        <select id="agent-status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>States to Cover *</label>
                        <div class="states-selection" id="states-selection">
                            <!-- States checkboxes will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" style="background: #6c757d" id="cancel-btn">Cancel</button>
                    <button type="submit" class="btn">Save Agent</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Nigerian states
        const nigerianStates = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe', 'Imo',
            'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa',
            'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba',
            'Yobe', 'Zamfara'
        ];

        let agents = [];
        let currentEditingAgentId = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadAgents();
            setupEventListeners();
            populateStatesCheckboxes();
        });

        function setupEventListeners() {
            document.getElementById('add-agent-btn').addEventListener('click', () => openModal());
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('cancel-btn').addEventListener('click', closeModal);
            document.getElementById('agent-form').addEventListener('submit', saveAgent);
            
            document.getElementById('agent-modal').addEventListener('click', (e) => {
                if (e.target.id === 'agent-modal') closeModal();
            });
        }

        function populateStatesCheckboxes() {
            const container = document.getElementById('states-selection');
            container.innerHTML = nigerianStates.map(state => `
                <div class="state-checkbox">
                    <input type="checkbox" id="state-${state}" value="${state}">
                    <label for="state-${state}" style="font-weight: normal; margin: 0;">${state}</label>
                </div>
            `).join('');
        }

        async function loadAgents() {
            try {
                const response = await fetch('api/agents.php?action=list');
                const data = await response.json();

                if (data.success) {
                    agents = data.data;
                    renderAgentsTable();
                    updateStats();
                } else {
                    console.error('Failed to load agents:', data.message);
                }
            } catch (error) {
                console.error('Error loading agents:', error);
                document.getElementById('agents-tbody').innerHTML = `
                    <tr><td colspan="8" class="empty-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading agents. Please refresh the page.</p>
                    </td></tr>
                `;
            }
        }

        function updateStats() {
            const totalAgents = agents.length;
            const activeAgents = agents.filter(a => a.status === 'active').length;
            const statesCovered = new Set();
            agents.forEach(agent => {
                if (agent.states) {
                    agent.states.split(', ').forEach(state => statesCovered.add(state.trim()));
                }
            });

            document.getElementById('total-agents').textContent = totalAgents;
            document.getElementById('active-agents').textContent = activeAgents;
            document.getElementById('total-states').textContent = statesCovered.size;
        }

        function renderAgentsTable() {
            const tbody = document.getElementById('agents-tbody');

            if (agents.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="8" class="empty-state">
                        <i class="fas fa-users"></i>
                        <p>No agents found. Click "Add New Agent" to get started.</p>
                    </td></tr>
                `;
                return;
            }

            tbody.innerHTML = agents.map(agent => `
                <tr>
                    <td>${agent.id}</td>
                    <td>${agent.name}</td>
                    <td>${agent.email || 'N/A'}</td>
                    <td>${agent.phone}</td>
                    <td>${agent.states || 'None'} (${agent.state_count || 0})</td>
                    <td>${agent.total_orders || 0}</td>
                    <td><span class="status-badge status-${agent.status}">${agent.status.toUpperCase()}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit-btn" onclick="editAgent(${agent.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete-btn" onclick="deleteAgent(${agent.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openModal(agentId = null) {
            const modal = document.getElementById('agent-modal');
            const title = document.getElementById('modal-title');
            
            if (agentId) {
                title.textContent = 'Edit Agent';
                currentEditingAgentId = agentId;
                loadAgentDetails(agentId);
            } else {
                title.textContent = 'Add New Agent';
                currentEditingAgentId = null;
                document.getElementById('agent-form').reset();
                document.querySelectorAll('.state-checkbox input').forEach(cb => cb.checked = false);
            }

            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('agent-modal').style.display = 'none';
        }

        async function loadAgentDetails(agentId) {
            try {
                const response = await fetch(`api/agents.php?action=single&id=${agentId}`);
                const data = await response.json();

                if (data.success) {
                    const agent = data.data;
                    document.getElementById('agent-id').value = agent.id;
                    document.getElementById('agent-name').value = agent.name;
                    document.getElementById('agent-email').value = agent.email || '';
                    document.getElementById('agent-phone').value = agent.phone;
                    document.getElementById('agent-alt-phone').value = agent.alt_phone || '';
                    document.getElementById('agent-address').value = agent.address || '';
                    document.getElementById('agent-status').value = agent.status;

                    // Check states
                    document.querySelectorAll('.state-checkbox input').forEach(cb => cb.checked = false);
                    if (agent.states) {
                        agent.states.forEach(state => {
                            const checkbox = document.getElementById(`state-${state}`);
                            if (checkbox) checkbox.checked = true;
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading agent details:', error);
                alert('Error loading agent details');
            }
        }

        async function saveAgent(e) {
            e.preventDefault();

            const selectedStates = Array.from(document.querySelectorAll('.state-checkbox input:checked'))
                .map(cb => cb.value);

            if (selectedStates.length === 0) {
                alert('Please select at least one state');
                return;
            }

            const agentData = {
                name: document.getElementById('agent-name').value,
                email: document.getElementById('agent-email').value,
                phone: document.getElementById('agent-phone').value,
                alt_phone: document.getElementById('agent-alt-phone').value,
                address: document.getElementById('agent-address').value,
                status: document.getElementById('agent-status').value,
                states: selectedStates
            };

            try {
                let url = 'api/agents.php';
                let method = 'POST';

                if (currentEditingAgentId) {
                    agentData.id = currentEditingAgentId;
                    method = 'PUT';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(agentData)
                });

                const data = await response.json();

                if (data.success) {
                    alert(currentEditingAgentId ? 'Agent updated successfully!' : 'Agent created successfully!');
                    closeModal();
                    loadAgents();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error saving agent:', error);
                alert('Error saving agent. Please try again.');
            }
        }

        function editAgent(agentId) {
            openModal(agentId);
        }

        async function deleteAgent(agentId) {
            if (!confirm('Are you sure you want to delete this agent? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch('api/agents.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: agentId })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Agent deleted successfully!');
                    loadAgents();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error deleting agent:', error);
                alert('Error deleting agent. Please try again.');
            }
        }
    </script>
        </div>
    </div>
</body>
</html>
