<?php
require_once 'php/auth.php';
requireAdmin(); // Only admins can access pricing management

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Management | Emerald Tech Hub</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include 'php/content_protection.php'; ?>
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
            max-width: 1200px;
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
        
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .pricing-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: var(--transition);
        }
        
        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .pricing-card h3 {
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-transform: capitalize;
        }
        
        .price-display {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--dark);
            margin: 20px 0;
        }
        
        .price-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1.1rem;
            margin: 10px 0;
            transition: var(--transition);
        }
        
        .price-input:focus {
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
            width: 100%;
            margin-top: 15px;
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
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .history-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .history-section h2 {
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .history-table th,
        .history-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .history-table th {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }
        
        .history-table tr:hover {
            background: #f8f9fa;
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
        
        .notes-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            margin: 10px 0;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }
        
        .notes-input:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .pricing-grid {
                grid-template-columns: 1fr;
            }
            
            .history-table {
                font-size: 0.9rem;
            }
            
            .history-table th,
            .history-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <a href="admin_dashboard_crm.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <h1><i class="fas fa-tags"></i> Pricing Management</h1>
            <p>Manage package pricing and view price change history</p>
        </div>
    </div>
    
    <div class="container">
        <div id="alertContainer"></div>
        
        <div class="pricing-grid" id="pricingGrid">
            <!-- Pricing cards will be loaded here -->
        </div>
        
        <div class="history-section">
            <h2><i class="fas fa-history"></i> Price Change History</h2>
            <table class="history-table" id="historyTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Package</th>
                        <th>Old Price</th>
                        <th>New Price</th>
                        <th>Changed By</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- History rows will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Load pricing data
        async function loadPricing() {
            try {
                const response = await fetch('api/expenses.php?action=get_pricing');
                const result = await response.json();
                
                if (result.success) {
                    displayPricing(result.data);
                } else {
                    showAlert('Error loading pricing: ' + result.message, 'error');
                }
            } catch (error) {
                showAlert('Error loading pricing: ' + error.message, 'error');
            }
        }
        
        // Display pricing cards
        function displayPricing(pricing) {
            const grid = document.getElementById('pricingGrid');
            grid.innerHTML = '';
            
            pricing.forEach(item => {
                const card = document.createElement('div');
                card.className = 'pricing-card';
                card.innerHTML = `
                    <h3>${item.package_type}</h3>
                    <div class="price-display">₦${parseInt(item.price).toLocaleString()}</div>
                    <div style="color: #666; margin-bottom: 20px;">
                        <small>Last updated: ${new Date(item.updated_at).toLocaleDateString()}</small>
                    </div>
                    <form onsubmit="updatePrice(event, '${item.package_type}')">
                        <input 
                            type="number" 
                            class="price-input" 
                            id="price_${item.package_type}" 
                            placeholder="New Price (₦)"
                            step="100"
                            required
                        >
                        <input 
                            type="number" 
                            class="price-input" 
                            id="cost_${item.package_type}" 
                            placeholder="Cost Per Unit (₦) - Optional"
                            step="100"
                        >
                        <textarea 
                            class="notes-input" 
                            id="notes_${item.package_type}" 
                            placeholder="Reason for price change (optional)"
                        ></textarea>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Price
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm('${item.package_type}')">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </form>
                `;
                grid.appendChild(card);
            });
        }
        
        // Update price
        async function updatePrice(event, packageType) {
            event.preventDefault();
            
            const priceInput = document.getElementById(`price_${packageType}`);
            const costInput = document.getElementById(`cost_${packageType}`);
            const notesInput = document.getElementById(`notes_${packageType}`);
            
            const newPrice = parseFloat(priceInput.value);
            const newCost = costInput.value ? parseFloat(costInput.value) : null;
            const notes = notesInput.value;
            
            if (!newPrice || newPrice <= 0) {
                showAlert('Please enter a valid price', 'error');
                return;
            }
            
            if (!confirm(`Are you sure you want to change ${packageType} price to ₦${newPrice.toLocaleString()}?`)) {
                return;
            }
            
            try {
                const response = await fetch('api/expenses.php?action=update_pricing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        package_type: packageType,
                        price: newPrice,
                        cost: newCost,
                        notes: notes
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert(`${packageType} price updated successfully!`, 'success');
                    resetForm(packageType);
                    loadPricing();
                    loadHistory();
                } else {
                    showAlert('Error updating price: ' + result.message, 'error');
                }
            } catch (error) {
                showAlert('Error updating price: ' + error.message, 'error');
            }
        }
        
        // Reset form
        function resetForm(packageType) {
            document.getElementById(`price_${packageType}`).value = '';
            document.getElementById(`cost_${packageType}`).value = '';
            document.getElementById(`notes_${packageType}`).value = '';
        }
        
        // Load pricing history
        async function loadHistory() {
            try {
                const response = await fetch('php/get_pricing_history.php');
                const result = await response.json();
                
                if (result.success) {
                    displayHistory(result.data);
                }
            } catch (error) {
                console.error('Error loading history:', error);
            }
        }
        
        // Display pricing history
        function displayHistory(history) {
            const tbody = document.getElementById('historyTable').querySelector('tbody');
            tbody.innerHTML = '';
            
            if (history.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #999;">No price changes yet</td></tr>';
                return;
            }
            
            history.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${new Date(item.changed_at).toLocaleString()}</td>
                    <td style="text-transform: capitalize;">${item.package_type}</td>
                    <td>₦${parseInt(item.old_price).toLocaleString()}</td>
                    <td style="font-weight: 600; color: var(--primary);">₦${parseInt(item.new_price).toLocaleString()}</td>
                    <td>${item.changed_by_name || 'N/A'}</td>
                    <td>${item.notes || '-'}</td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Show alert
        function showAlert(message, type) {
            const container = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alert.style.display = 'block';
            
            container.innerHTML = '';
            container.appendChild(alert);
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadPricing();
            loadHistory();
        });
    </script>
</body>
</html>
