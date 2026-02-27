<?php

/**
 * UNIQUE STUDIO - Admin Orders Panel
 * Simple order management dashboard
 * 
 * SECURITY: Add password protection in production!
 */

// Basic password protection
$admin_password = 'admin123'; // CHANGE THIS!
session_start();

if ($_POST['admin_pass'] ?? '' === $admin_password) {
    $_SESSION['admin_logged_in'] = true;
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header('Location: orders.php');
    exit;
}

// Show login if not authenticated
if (empty($_SESSION['admin_logged_in'])) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login - Unique Studio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#004e7c,#001e3c);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:\'Inter\',sans-serif;}
.login-card{background:white;border-radius:20px;padding:48px;max-width:420px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,0.3);}
.login-logo{font-size:1.8rem;font-weight:800;color:#004e7c;margin-bottom:8px;}
.form-control{border-radius:10px;border:1.5px solid #e2e8f0;padding:12px 16px;}
.form-control:focus{border-color:#ff6b35;box-shadow:0 0 0 3px rgba(255,107,53,0.12);}
.btn-login{background:linear-gradient(135deg,#ff6b35,#ff8c5f);color:white;border:none;padding:13px;border-radius:10px;font-weight:700;width:100%;font-size:1rem;}
</style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">🎨 Unique Studio</div>
    <p style="color:#64748b;margin-bottom:28px;">Admin Panel — Orders Management</p>
    <form method="POST">
        <div class="mb-3"><label class="form-label fw-600">Password</label>
        <input type="password" name="admin_pass" class="form-control" placeholder="Enter admin password" required autofocus></div>
        <button type="submit" class="btn-login">Login to Admin</button>
    </form>
    <div class="mt-3 text-center" style="font-size:0.78rem;color:#94a3b8;">Default password: <code>admin123</code> — change in admin/orders.php</div>
</div>
</body></html>';
    exit;
}

// Load functions
require_once '../includes/functions.php';

// Handle status update
if ($_POST['action'] ?? '' === 'update_status') {
    $order_num = sanitize($_POST['order_number'] ?? '');
    $status    = sanitize($_POST['status'] ?? '');
    $allowed_statuses = ['pending', 'confirmed', 'in_production', 'shipped', 'delivered', 'cancelled'];
    if (!empty($order_num) && in_array($status, $allowed_statuses)) {
        try {
            $db = getDB();
            $stmt = $db->prepare("UPDATE orders SET status = ? WHERE order_number = ?");
            $stmt->execute([$status, $order_num]);
            $msg = "Status updated for $order_num";
        } catch (Exception $e) {
            $msg_error = "Error updating status";
        }
    }
}

// Fetch orders
$page_num = max(1, (int)($_GET['page'] ?? 1));
$per_page = 20;
$offset   = ($page_num - 1) * $per_page;
$filter   = sanitize($_GET['status'] ?? '');
$search   = sanitize($_GET['search'] ?? '');

try {
    $db = getDB();
    $where = '1=1';
    $params = [];
    if (!empty($filter)) {
        $where .= " AND status = ?";
        $params[] = $filter;
    }
    if (!empty($search)) {
        $where .= " AND (order_number LIKE ? OR customer_name LIKE ? OR customer_phone LIKE ?)";
        $s = '%' . $search . '%';
        $params = array_merge($params, [$s, $s, $s]);
    }

    $total_orders = $db->prepare("SELECT COUNT(*) FROM orders WHERE $where");
    $total_orders->execute($params);
    $total = (int)$total_orders->fetchColumn();
    $total_pages = ceil($total / $per_page);

    $stmt = $db->prepare("SELECT * FROM orders WHERE $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
    $stmt->execute($params);
    $orders = $stmt->fetchAll();

    // Stats
    $stats = $db->query("SELECT 
        COUNT(*) as total,
        SUM(total_amount) as revenue,
        COUNT(CASE WHEN status='pending' THEN 1 END) as pending,
        COUNT(CASE WHEN status='delivered' THEN 1 END) as delivered,
        COUNT(CASE WHEN DATE(created_at)=CURDATE() THEN 1 END) as today
        FROM orders")->fetch();
} catch (Exception $e) {
    $orders = [];
    $stats  = [];
    $total  = 0;
    $total_pages = 1;
    $db_error = $e->getMessage();
}

$status_badges = [
    'pending'      => ['bg' => '#fff5f0', 'color' => '#ff6b35', 'label' => 'Pending'],
    'confirmed'    => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'Confirmed'],
    'in_production' => ['bg' => '#f0f9ff', 'color' => '#0284c7', 'label' => 'In Production'],
    'shipped'      => ['bg' => '#f5f3ff', 'color' => '#7c3aed', 'label' => 'Shipped'],
    'delivered'    => ['bg' => '#dcfce7', 'color' => '#16a34a', 'label' => 'Delivered'],
    'cancelled'    => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Cancelled'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Orders - Unique Studio Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --orange: #ff6b35;
            --green: #004e7c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f1f5f9;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
        }

        .sidebar {
            width: 240px;
            background: var(--green);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 24px 0;
            z-index: 100;
        }

        .sidebar-logo {
            color: white;
            font-size: 1.3rem;
            font-weight: 800;
            padding: 0 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 16px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.75);
            padding: 10px 20px;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.88rem;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 107, 53, 0.2);
            color: white;
            border-left: 3px solid var(--orange);
        }

        .sidebar-link i {
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: 240px;
            padding: 28px;
        }

        .topbar {
            background: white;
            border-radius: 14px;
            padding: 16px 24px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .topbar-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--green);
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--orange);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--green);
            font-family: 'Space Grotesk', sans-serif;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.82rem;
            margin-top: 4px;
        }

        .orders-table-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .orders-table-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .orders-table th {
            background: #f8fafc;
            color: #374151;
            font-weight: 700;
            font-size: 0.8rem;
            padding: 12px 16px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
        }

        .orders-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .orders-table tr:hover {
            background: #fafafa;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .order-num {
            font-weight: 700;
            color: var(--green);
        }

        .customer-name {
            font-weight: 600;
            color: #1e293b;
        }

        .customer-phone {
            color: #64748b;
            font-size: 0.82rem;
        }

        .filter-btn {
            padding: 6px 14px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            color: #475569;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .filter-btn:hover,
        .filter-btn.active {
            border-color: var(--orange);
            background: #fff5f0;
            color: var(--orange);
        }

        .search-input {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 0.88rem;
            outline: none;
            width: 220px;
        }

        .search-input:focus {
            border-color: var(--orange);
        }

        .page-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .page-btn:hover,
        .page-btn.active {
            background: var(--orange);
            border-color: var(--orange);
            color: white;
        }

        .amount {
            font-weight: 700;
            color: var(--orange);
        }

        select.status-select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.8rem;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">🎨 Unique Studio</div>
        <a href="orders.php" class="sidebar-link active"><i class="bi bi-receipt"></i> Orders</a>
        <a href="../index.php" target="_blank" class="sidebar-link"><i class="bi bi-house"></i> View Website</a>
        <a href="../products/" target="_blank" class="sidebar-link"><i class="bi bi-grid"></i> Products</a>
        <a href="../setup.php" target="_blank" class="sidebar-link"><i class="bi bi-tools"></i> Setup Check</a>
        <a href="orders.php?logout=1" class="sidebar-link" style="position:absolute;bottom:20px;width:100%;color:rgba(255,100,100,0.8);">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </aside>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="topbar">
            <div>
                <div class="topbar-title">📦 Order Management</div>
                <div style="color:#64748b;font-size:0.82rem;margin-top:2px;"><?= date('l, d F Y') ?></div>
            </div>
            <a href="https://wa.me/919876543210" target="_blank"
                style="background:#25d366;color:white;padding:10px 20px;border-radius:20px;text-decoration:none;font-weight:700;font-size:0.85rem;">
                <i class="bi bi-whatsapp me-1"></i>Open WhatsApp
            </a>
        </div>

        <?php if (!empty($msg)): ?>
            <div style="background:#dcfce7;color:#166534;padding:12px 20px;border-radius:10px;margin-bottom:20px;">
                <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($db_error)): ?>
            <div style="background:#fef2f2;color:#dc2626;padding:12px 20px;border-radius:10px;margin-bottom:20px;">
                <i class="bi bi-exclamation-circle me-2"></i>Database Error: <?= htmlspecialchars($db_error) ?>
                <br><small>Make sure the database is set up by running <code>database/schema.sql</code></small>
            </div>
        <?php endif; ?>

        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <?php
            $stat_items = [
                ['bi-receipt', 'Total Orders', $stats['total'] ?? 0, '#ff6b35'],
                ['bi-currency-rupee', 'Total Revenue', '₹' . number_format($stats['revenue'] ?? 0, 0), '#16a34a'],
                ['bi-clock', 'Pending', $stats['pending'] ?? 0, '#d97706'],
                ['bi-truck', 'Delivered', $stats['delivered'] ?? 0, '#7c3aed'],
                ['bi-calendar-day', 'Today', $stats['today'] ?? 0, '#0284c7'],
            ];
            foreach ($stat_items as $s): ?>
                <div class="col-6 col-md-4 col-lg">
                    <div class="stat-card" style="border-left-color:<?= $s[3] ?>;">
                        <div><i class="bi <?= $s[0] ?>" style="color:<?= $s[3] ?>;font-size:1.4rem;"></i></div>
                        <div class="stat-value" style="font-size:1.5rem;"><?= $s[2] ?></div>
                        <div class="stat-label"><?= $s[1] ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Orders Table Card -->
        <div class="orders-table-card">
            <div class="orders-table-header">
                <div style="font-weight:700;color:var(--green);">All Orders (<?= $total ?>)</div>

                <!-- Filters -->
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <form method="GET" style="display:contents;">
                        <input type="text" name="search" class="search-input"
                            placeholder="Search order / name / phone..." value="<?= htmlspecialchars($search) ?>">
                        <?php if (!empty($filter)): ?>
                            <input type="hidden" name="status" value="<?= htmlspecialchars($filter) ?>">
                        <?php endif; ?>
                        <button type="submit" style="background:var(--orange);color:white;border:none;padding:8px 16px;border-radius:8px;font-weight:600;cursor:pointer;">
                            Search
                        </button>
                    </form>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="orders.php" class="filter-btn <?= empty($filter) ? 'active' : '' ?>">All</a>
                    <?php foreach ($status_badges as $key => $badge): ?>
                        <a href="?status=<?= $key ?>" class="filter-btn <?= $filter === $key ? 'active' : '' ?>">
                            <?= $badge['label'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table orders-table mb-0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color:#94a3b8;">
                                    <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
                                    No orders found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $o):
                                $items = json_decode($o['order_items'], true) ?: [];
                                $badge = $status_badges[$o['status']] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => ucfirst($o['status'])];
                            ?>
                                <tr>
                                    <td>
                                        <div class="order-num">#<?= htmlspecialchars($o['order_number']) ?></div>
                                    </td>
                                    <td>
                                        <div class="customer-name"><?= htmlspecialchars($o['customer_name']) ?></div>
                                        <div class="customer-phone">
                                            <a href="https://wa.me/91<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $o['customer_phone'])) ?>"
                                                target="_blank" style="color:#25d366;text-decoration:none;">
                                                <i class="bi bi-whatsapp"></i> <?= htmlspecialchars($o['customer_phone']) ?>
                                            </a>
                                        </div>
                                        <div style="color:#94a3b8;font-size:0.78rem;"><?= htmlspecialchars($o['customer_city']) ?></div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;color:#1e293b;"><?= date('d M Y', strtotime($o['created_at'])) ?></div>
                                        <div style="color:#94a3b8;font-size:0.78rem;"><?= date('h:i A', strtotime($o['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <?php foreach (array_slice($items, 0, 2) as $item): ?>
                                            <div style="font-size:0.82rem;color:#374151;">
                                                <?= htmlspecialchars(substr($item['name'], 0, 28)) ?> ×<?= $item['quantity'] ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (count($items) > 2): ?>
                                            <div style="color:#94a3b8;font-size:0.78rem;">+<?= count($items) - 2 ?> more</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="amount">₹<?= number_format($o['total_amount'], 0) ?></span></td>
                                    <td>
                                        <span class="status-badge" style="background:<?= $badge['bg'] ?>;color:<?= $badge['color'] ?>;">
                                            <?= $badge['label'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 align-items-center flex-wrap">
                                            <!-- Update Status -->
                                            <form method="POST" class="d-flex gap-1">
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="order_number" value="<?= htmlspecialchars($o['order_number']) ?>">
                                                <select name="status" class="status-select" onchange="this.form.submit()">
                                                    <?php foreach ($status_badges as $sk => $sv): ?>
                                                        <option value="<?= $sk ?>" <?= $o['status'] === $sk ? 'selected' : '' ?>>
                                                            <?= $sv['label'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </form>

                                            <!-- WhatsApp -->
                                            <a href="https://wa.me/91<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $o['customer_phone'])) ?>?text=<?= urlencode("Hi " . $o['customer_name'] . "! Your order #" . $o['order_number'] . " — ") ?>"
                                                target="_blank"
                                                title="WhatsApp Customer"
                                                style="width:32px;height:32px;background:#dcfce7;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#16a34a;text-decoration:none;">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>

                                            <!-- PDF -->
                                            <?php if (!empty($o['pdf_path'])): ?>
                                                <a href="../<?= htmlspecialchars($o['pdf_path']) ?>" target="_blank"
                                                    title="Download PDF"
                                                    style="width:32px;height:32px;background:#fef2f2;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#dc2626;text-decoration:none;">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="d-flex justify-content-center gap-1 p-4">
                    <?php if ($page_num > 1): ?>
                        <a href="?page=<?= $page_num - 1 ?>&status=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>" class="page-btn">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                        <a href="?page=<?= $p ?>&status=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>"
                            class="page-btn <?= $p === $page_num ? 'active' : '' ?>"><?= $p ?></a>
                    <?php endfor; ?>
                    <?php if ($page_num < $total_pages): ?>
                        <a href="?page=<?= $page_num + 1 ?>&status=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>" class="page-btn">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div style="text-align:center;color:#94a3b8;font-size:0.78rem;margin-top:20px;">
            Unique Studio Admin Panel • <?= date('Y') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>