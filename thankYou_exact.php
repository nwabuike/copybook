<?php
require_once 'php/auth.php';
requireLogin();
require_once 'php/db.php';

function esc($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = null;
if ($order_id > 0) {
    $sql = "SELECT * FROM orders WHERE id = " . $order_id . " LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows) {
        $order = $res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You | Emerald Tech Hub</title>
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
            font-family: 'Comic Neue', cursive, Arial, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
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
        
        /* Thank You Section */
        .thank-you-section {
            flex: 1;
            padding: 80px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .thank-you-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 800px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .thank-you-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: var(--primary);
            font-size: 3rem;
            animation: bounce 1s ease infinite alternate;
        }
        
        @keyframes bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }
        
        .thank-you-card h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .thank-you-card p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #666;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .order-details {
            background: var(--primary-light);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: left;
        }
        
        .order-details h3 {
            margin-bottom: 20px;
            color: var(--dark);
            text-align: center;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(10, 124, 66, 0.2);
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--dark);
        }
        
        .detail-value {
            color: var(--primary);
            font-weight: 600;
        }
        
        /* Referral Section */
        .referral-section {
            background: linear-gradient(135deg, var(--dark) 0%, #3a3e5c 100%);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            color: white;
            text-align: center;
        }
        
        .referral-section h3 {
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .referral-code {
            background: rgba(255, 255, 255, 0.1);
            border: 2px dashed var(--accent);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 3px;
            color: var(--accent);
        }
        
        .referral-instructions {
            margin-top: 20px;
            font-size: 1rem;
            color: #ccc;
        }
        
        /* WhatsApp Section */
        .whatsapp-section {
            margin: 30px 0;
            text-align: center;
        }
        
        .whatsapp-section h3 {
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .whatsapp-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .whatsapp-button {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #25D366;
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }
        
        .whatsapp-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(37, 211, 102, 0.4);
        }
        
        .whatsapp-button i {
            font-size: 1.5rem;
        }
        
        .whatsapp-note {
            margin-top: 20px;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Free Gifts Section */
        .free-gifts-section {
            margin: 40px 0;
        }
        
        .free-gifts-section h3 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--dark);
        }
        
        .gifts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .gift-item {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            border: 2px solid var(--primary-light);
        }
        
        .gift-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .gift-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .gift-item h4 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .gift-item p {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .action-button {
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .primary-button {
            background: var(--primary);
            color: white;
        }
        
        .primary-button:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(10, 124, 66, 0.3);
        }
        
        .secondary-button {
            background: var(--accent);
            color: var(--dark);
        }
        
        .secondary-button:hover {
            background: #ffc145;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 209, 102, 0.3);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            padding: 40px 0 20px;
            color: white;
            margin-top: auto;
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
            .thank-you-card {
                padding: 30px 20px;
            }
            
            .thank-you-card h1 {
                font-size: 2rem;
            }
            
            .referral-code {
                font-size: 1.5rem;
            }
            
            .whatsapp-buttons {
                flex-direction: column;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .action-button {
                width: 100%;
                justify-content: center;
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
                    <div class="logo-text">Smartkids Edu</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Thank You Section -->
    <section class="thank-you-section">
        <div class="container">
            <div class="thank-you-card">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                
                <h1>Thank You For Your Order!</h1>
                <p>Your order has been received successfully. We're preparing your copybooks and free gifts for delivery.</p>
                
                <div class="order-details">
                    <h3>Order Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Order ID:</span>
                        <span class="detail-value" id="order-id"><?php echo $order ? esc('#'.$order['id']) : 'ETH2023110452'; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Package:</span>
                        <span class="detail-value" id="package-name"><?php
                            if ($order) {
                                $map = ['starter'=>'Starter Set','bundle'=>'Learning Bundle','collection'=>'Mastery Collection'];
                                echo esc(isset($map[$order['pack']]) ? $map[$order['pack']] : $order['pack']);
                            } else { echo 'Learning Bundle'; }
                        ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Delivery To:</span>
                        <span class="detail-value" id="delivery-state"><?php echo $order ? esc($order['state']) : 'Lagos State'; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value">Pay on Delivery</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Delivery Status:</span>
                        <span class="detail-value">Processing</span>
                    </div>
                </div>
                
                <div class="referral-section">
                    <h3>Your Personal Referral Code</h3>
                    <div class="referral-code" id="referral-code"><?php echo $order ? esc($order['referral_code']) : 'REF7X9K2M'; ?></div>
                    <p>Share this code with friends and earn ₦10,000 cashback for each referral!</p>
                    <div class="referral-instructions">
                        <p><strong>How it works:</strong> Share your code with up to 3 friends. When they make a purchase using your code, you'll receive ₦10,000 cashback per referral.</p>
                    </div>
                </div>
                
                <div class="whatsapp-section">
                    <h3>Confirm Your Order via WhatsApp</h3>
                    <p>Click below to send us a quick confirmation message on WhatsApp:</p>
                    
                    <div class="whatsapp-buttons">
                        <a href="<?php echo $order ? 'https://wa.me/2348163778265?text='.urlencode('Hello Emerald Tech Hub! I would like to confirm my order:\n\nOrder ID: #'.$order['id'].'\nPackage: '.($order['pack'])).'' : '#'; ?>" class="whatsapp-button" id="whatsapp-confirm">
                            <i class="fab fa-whatsapp"></i>
                            <span>Confirm Order on WhatsApp</span>
                        </a>
                        <a href="<?php echo $order ? 'https://wa.me/2348102609396?text='.urlencode('Hello Emerald Tech Hub! I would like to track my order:\n\nOrder ID: #'.$order['id']).'' : '#'; ?>" class="whatsapp-button" id="whatsapp-track">
                            <i class="fab fa-whatsapp"></i>
                            <span>Track My Order</span>
                        </a>
                    </div>
                    
                    <p class="whatsapp-note">Our team will respond within 1 hour to confirm your order details and delivery timeline.</p>
                </div>
                
                <div class="free-gifts-section">
                    <h3>Your Free Gifts Included</h3>
                    <div class="gifts-grid">
                        <div class="gift-item">
                            <div class="gift-icon">
                                <i class="fas fa-paint-brush"></i>
                            </div>
                            <h4>Magic Water Drawing Pad</h4>
                            <p>Reusable water drawing pad for endless creative fun</p>
                        </div>
                        
                        <div class="gift-item">
                            <div class="gift-icon">
                                <i class="fas fa-tooth"></i>
                            </div>
                            <h4>U-Shaped Toothbrush</h4>
                            <p>Kid-friendly toothbrush that makes brushing fun</p>
                        </div>
                        
                        <div class="gift-item">
                            <div class="gift-icon">
                                <i class="fas fa-skipping-rope"></i>
                            </div>
                            <h4>Adjustable Skipping Rope</h4>
                            <p>High-quality NBR rope for active play and exercise</p>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="index.php" class="action-button primary-button">
                        <i class="fas fa-home"></i>
                        Back to Home
                    </a>
                    <a href="#" class="action-button secondary-button" id="share-referral">
                        <i class="fas fa-share-alt"></i>
                        Share Referral Code
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>Smartkids Edu</h4>
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
                        <li><i class="fas fa-phone"></i> 09029026782</li>
                        <li><i class="fas fa-phone"></i> 08102609396</li>
                        <li><i class="fas fa-envelope"></i> info@emeraldtechhub.com</li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#">Track Order</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Returns</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2023 Emerald Tech Hub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // If no server-driven order is present, fall back to generating values client-side (keeps original demo behaviour)
        (function(){
            var hasOrder = <?php echo $order ? 'true' : 'false'; ?>;
            if(hasOrder) return; // server rendered values already present

            // Generate random order ID and referral code
            function genOrderId(){
                const now = new Date();
                return 'ETH' + now.getFullYear() + ('0'+(now.getMonth()+1)).slice(-2) + ('0'+now.getDate()).slice(-2) + Math.floor(100 + Math.random()*900);
            }

            function genReferral(){
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let ref = 'REF';
                for(let i=0;i<6;i++) ref += chars.charAt(Math.floor(Math.random()*chars.length));
                return ref;
            }

            document.addEventListener('DOMContentLoaded', function(){
                const orderId = genOrderId();
                const referral = genReferral();
                document.getElementById('order-id').textContent = orderId;
                document.getElementById('referral-code').textContent = referral;
                // package and state already defaulted in HTML

                // WhatsApp links
                var confirm = document.getElementById('whatsapp-confirm');
                var track = document.getElementById('whatsapp-track');
                if(confirm) confirm.href = 'https://wa.me/2348163778265?text=' + encodeURIComponent('Hello Emerald Tech Hub! I would like to confirm my order:\n\nOrder ID: '+orderId+'\nPackage: Learning Bundle\nDelivery State: Lagos State');
                if(track) track.href = 'https://wa.me/2348102609396?text=' + encodeURIComponent('Hello Emerald Tech Hub! I would like to track my order:\n\nOrder ID: '+orderId+'\nPackage: Learning Bundle');

                // Share referral
                document.getElementById('share-referral').addEventListener('click', function(e){
                    e.preventDefault();
                    const shareText = `I just ordered educational copybooks! Use my referral code ${referral} to get a discount.`;
                    if(navigator.share){
                        navigator.share({title:'Smartkids Edu - Referral', text: shareText, url: window.location.origin});
                    } else {
                        window.open('https://wa.me/?text=' + encodeURIComponent(shareText), '_blank');
                    }
                });
            });
        })();
    </script>
</body>
</html>
