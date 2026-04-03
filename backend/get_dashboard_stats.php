<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

// Check admin access
$user = getLoggedInUser($conn);
if (!$user || !intval($user['is_admin'])) {
    handleError('Admin access required', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    handleError('Method not allowed', 405);
}

$stats = [];

// 1. Total Revenue (sum of all completed/shipped orders)
$revenueResult = $conn->query("
    SELECT COALESCE(SUM(total_amount), 0) as total_revenue 
    FROM orders 
    WHERE status IN ('Completed', 'Shipped')
");
$revenueRow = $revenueResult->fetch_assoc();
$stats['total_revenue'] = floatval($revenueRow['total_revenue'] ?? 0);

// 2. Active Orders (Pending status)
$activeOrdersResult = $conn->query("
    SELECT COUNT(*) as active_count 
    FROM orders 
    WHERE status = 'Pending'
");
$activeOrdersRow = $activeOrdersResult->fetch_assoc();
$stats['active_orders'] = intval($activeOrdersRow['active_count'] ?? 0);

// 3. Out of Stock Products (quantity 0 or less)
$lowStockResult = $conn->query("
    SELECT COUNT(*) as low_stock_count 
    FROM products 
    WHERE stock_quantity <= 0
");
$lowStockRow = $lowStockResult->fetch_assoc();
$stats['low_stock_count'] = intval($lowStockRow['low_stock_count'] ?? 0);

// 4. Total Products
$totalProductsResult = $conn->query("
    SELECT COUNT(*) as total_products 
    FROM products
");
$totalProductsRow = $totalProductsResult->fetch_assoc();
$stats['total_products'] = intval($totalProductsRow['total_products'] ?? 0);

// 5. Total Customers
$totalCustomersResult = $conn->query("
    SELECT COUNT(*) as total_customers 
    FROM users 
    WHERE is_admin = 0
");
$totalCustomersRow = $totalCustomersResult->fetch_assoc();
$stats['total_customers'] = intval($totalCustomersRow['total_customers'] ?? 0);

// 6. Recent orders (last 5)
$recentOrdersResult = $conn->query("
    SELECT 
        o.order_id,
        o.order_date,
        o.total_amount,
        o.status,
        u.first_name,
        u.last_name
    FROM orders o
    LEFT JOIN users u ON o.customer_id = u.customer_id
    ORDER BY o.order_date DESC
    LIMIT 5
");
$stats['recent_orders'] = [];
if ($recentOrdersResult) {
    while ($row = $recentOrdersResult->fetch_assoc()) {
        $stats['recent_orders'][] = [
            'order_id' => intval($row['order_id']),
            'customer_name' => trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
            'date' => $row['order_date'],
            'amount' => floatval($row['total_amount']),
            'status' => $row['status'] ?? 'Pending'
        ];
    }
}

jsonResponse([
    'success' => true,
    'stats' => $stats
]);
?>
