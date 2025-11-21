<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard - Notifications</title>
    <link rel="stylesheet" href="css/style.css">
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

        /* Mobile Responsive */
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
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1><i class="fas fa-bell"></i> Sales Rep Dashboard</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <a href="customer_orderlist.php">Orders</a> / <span>Notifications</span>
                </div>
            </div>
            <div class="notification-toggle">
                <div class="notification-status" id="notification-status">
                    <i class="fas fa-bell-slash"></i>
                    <span>Notifications Disabled</span>
                </div>
                <button class="btn" id="enable-notifications-btn">
                    <i class="fas fa-bell"></i> Enable Notifications
                </button>
            </div>
        </header>

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
                alert('This browser does not support notifications');
                return false;
            }

            const permission = await Notification.requestPermission();
            notificationPermission = permission === 'granted';
            
            updateNotificationStatus();
            
            if (notificationPermission) {
                startAutoCheck();
                checkAlerts();
            }

            return notificationPermission;
        }

        // Update notification status UI
        function updateNotificationStatus() {
            const statusEl = document.getElementById('notification-status');
            const btnEl = document.getElementById('enable-notifications-btn');

            if (notificationPermission) {
                statusEl.className = 'notification-status enabled';
                statusEl.innerHTML = '<i class="fas fa-bell"></i><span>Notifications Enabled</span>';
                btnEl.innerHTML = '<i class="fas fa-bell-slash"></i> Disable Notifications';
                btnEl.className = 'btn btn-danger';
            } else {
                statusEl.className = 'notification-status disabled';
                statusEl.innerHTML = '<i class="fas fa-bell-slash"></i><span>Notifications Disabled</span>';
                btnEl.innerHTML = '<i class="fas fa-bell"></i> Enable Notifications';
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
            const response = await fetch('api/orders.php?action=list&status=pending');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                const now = new Date();
                const thresholdMs = settings.pendingThreshold * 60 * 1000;
                let alertCount = 0;

                data.data.forEach(order => {
                    const orderTime = new Date(order.created_at);
                    const timeDiff = now - orderTime;

                    if (timeDiff > thresholdMs) {
                        alertCount++;
                        if (alertCount === 1) { // Only notify once per check
                            showNotification(
                                'Pending Orders Need Attention',
                                `${data.data.length} order(s) have been pending for over ${settings.pendingThreshold} minutes`,
                                'urgent'
                            );
                        }
                    }
                });

                document.getElementById('pending-count').textContent = data.data.length;
            } else {
                document.getElementById('pending-count').textContent = '0';
            }
        }

        // Check low stock
        async function checkLowStock() {
            const response = await fetch(`api/stock.php?action=low_stock&threshold=${settings.stockThreshold}`);
            const data = await response.json();

            if (data.success) {
                const lowStockCount = data.data.filter(item => item.quantity > 0 && item.quantity <= settings.stockThreshold).length;
                const outOfStockCount = data.data.filter(item => item.quantity === 0 || item.quantity === '0').length;

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
            }
        }

        // Check follow-ups
        async function checkFollowUps() {
            const response = await fetch('api/orders.php?action=list&status=confirmed');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
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
        }

        // Show notification
        function showNotification(title, body, type = 'info') {
            if (!notificationPermission) return;

            // Browser notification
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

            // Add to alerts list
            addAlertToList(title, body, type);

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
            
            // Check if notifications were previously enabled
            if (Notification.permission === 'granted') {
                notificationPermission = true;
                updateNotificationStatus();
                startAutoCheck();
                checkAlerts(); // Initial check
            }

            // Event listeners
            document.getElementById('enable-notifications-btn').addEventListener('click', function() {
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
    </script>
</body>
</html>
