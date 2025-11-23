<?php
// api/traffic_sources.php - Get analytics data for traffic sources
require_once '../php/auth.php';
requireAdmin();
require_once '../php/db.php';

header('Content-Type: application/json');

try {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
    
    // Package prices
    $prices = [
        'Starter' => 18000,
        'Bundle' => 32000,
        'Collection' => 45000
    ];
    
    // Get Facebook stats
    $fbSql = "SELECT 
                COUNT(*) as total_orders,
                GROUP_CONCAT(pack) as packages
              FROM orders 
              WHERE source = 'facebook' 
              AND DATE(created_at) BETWEEN ? AND ?";
    
    $stmt = $conn->prepare($fbSql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $fbResult = $stmt->get_result()->fetch_assoc();
    
    // Calculate Facebook revenue
    $fbRevenue = 0;
    if ($fbResult['packages']) {
        $packages = explode(',', $fbResult['packages']);
        foreach ($packages as $pack) {
            $fbRevenue += $prices[$pack] ?? 0;
        }
    }
    
    // Get TikTok stats
    $ttSql = "SELECT 
                COUNT(*) as total_orders,
                GROUP_CONCAT(pack) as packages
              FROM orders 
              WHERE source = 'tiktok' 
              AND DATE(created_at) BETWEEN ? AND ?";
    
    $stmt = $conn->prepare($ttSql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $ttResult = $stmt->get_result()->fetch_assoc();
    
    // Calculate TikTok revenue
    $ttRevenue = 0;
    if ($ttResult['packages']) {
        $packages = explode(',', $ttResult['packages']);
        foreach ($packages as $pack) {
            $ttRevenue += $prices[$pack] ?? 0;
        }
    }
    
    // Get recent orders
    $ordersSql = "SELECT id, fullname, pack, source, created_at 
                  FROM orders 
                  WHERE DATE(created_at) BETWEEN ? AND ?
                  ORDER BY created_at DESC 
                  LIMIT 50";
    
    $stmt = $conn->prepare($ordersSql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $ordersResult = $stmt->get_result();
    
    $orders = [];
    while ($row = $ordersResult->fetch_assoc()) {
        $row['price'] = $prices[$row['pack']] ?? 0;
        $orders[] = $row;
    }
    
    $response = [
        'success' => true,
        'data' => [
            'facebook' => [
                'orders' => (int)$fbResult['total_orders'],
                'revenue' => $fbRevenue,
                'avg_order' => $fbResult['total_orders'] > 0 ? round($fbRevenue / $fbResult['total_orders'], 2) : 0,
                'conversion_rate' => 0 // Placeholder - needs page view tracking
            ],
            'tiktok' => [
                'orders' => (int)$ttResult['total_orders'],
                'revenue' => $ttRevenue,
                'avg_order' => $ttResult['total_orders'] > 0 ? round($ttRevenue / $ttResult['total_orders'], 2) : 0,
                'conversion_rate' => 0 // Placeholder - needs page view tracking
            ]
        ],
        'orders' => $orders
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
