<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Emerald Tech Hub</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            min-height: 500px;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-left h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .login-left p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .login-left .feature-list {
            margin-top: 30px;
        }

        .login-left .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .login-left .feature-item i {
            font-size: 24px;
        }

        .login-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .logo h1 {
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-forgot label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-forgot a {
            color: #667eea;
            text-decoration: none;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .alert.show {
            display: block;
        }

        .test-accounts {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
        }

        .test-accounts h4 {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .test-accounts .account-item {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .test-accounts .account-item strong {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-left {
                padding: 40px 30px;
            }

            .login-right {
                padding: 40px 30px;
            }

            .logo h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h2>Welcome to Emerald Tech Hub</h2>
            <p>Manage your orders, track inventory, monitor sales performance, and grow your business with our comprehensive management system.</p>
            
            <div class="feature-list">
                <div class="feature-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Order Management</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Sales Analytics</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-box"></i>
                    <span>Inventory Tracking</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-users"></i>
                    <span>Agent Management</span>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="logo">
                <i class="fas fa-gem"></i>
                <h1>Login to Your Account</h1>
            </div>

            <div id="alert" class="alert"></div>

            <form id="login-form">
                <div class="form-group">
                    <label>Username or Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required placeholder="Enter your username or email">
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required placeholder="Enter your password">
                    </div>
                </div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="forgot-password.php">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login" id="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <!-- <div class="test-accounts">
                <h4><i class="fas fa-info-circle"></i> Test Accounts</h4>
                <div class="account-item">
                    <strong>Admin:</strong> admin / admin123
                </div>
                <div class="account-item">
                    <strong>Subadmin:</strong> subadmin / subadmin123
                </div>
                <div class="account-item">
                    <strong>Agent:</strong> agent001 / agent123
                </div>
            </div> -->
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const loginBtn = document.getElementById('login-btn');
        const alertBox = document.getElementById('alert');

        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Disable button and show loading
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            
            try {
                const response = await fetch('api/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('Login successful! Redirecting...', 'success');
                    
                    // Get redirect URL from query string or default to customer_orderlist.php
                    const urlParams = new URLSearchParams(window.location.search);
                    const redirect = urlParams.get('redirect') || 'customer_orderlist.php';
                    
                    setTimeout(() => {
                        window.location.href = redirect;
                    }, 1000);
                } else {
                    showAlert(data.message || 'Login failed. Please try again.', 'error');
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
                }
            } catch (error) {
                console.error('Login error:', error);
                showAlert('An error occurred. Please try again.', 'error');
                loginBtn.disabled = false;
                loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
            }
        });

        function showAlert(message, type) {
            alertBox.textContent = message;
            alertBox.className = 'alert alert-' + type + ' show';
        }
    </script>
</body>
</html>
