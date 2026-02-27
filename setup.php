<?php

/**
 * UNIQUE STUDIO - Installation / Test Script
 * Run this once to verify everything is configured correctly.
 * DELETE THIS FILE after setup is complete.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';

$results = [];

// ── 1. Database Connection ────────────────────────────────────────────────
echo '<h2>1. Database Connection</h2>';
try {
    $db = getDB();
    $db->query('SELECT 1');
    echo '<span style="color:green;">✅ PDO Connection OK</span><br>';
    $results['db'] = true;
} catch (Exception $e) {
    echo '<span style="color:red;">❌ DB Error: ' . $e->getMessage() . '</span><br>';
    $results['db'] = false;
}

// ── 2. Tables Check ───────────────────────────────────────────────────────
echo '<h2>2. Database Tables</h2>';
$required_tables = ['categories', 'products', 'product_options', 'product_pricing', 'orders', 'contact_inquiries', 'site_settings'];
if (!empty($results['db'])) {
    foreach ($required_tables as $table) {
        try {
            $db->query("SELECT 1 FROM `$table` LIMIT 1");
            echo "<span style='color:green;'>✅ Table <b>$table</b> exists</span><br>";
        } catch (Exception $e) {
            echo "<span style='color:red;'>❌ Table <b>$table</b> MISSING — run database/schema.sql</span><br>";
        }
    }
}

// ── 3. Data Seeding Check ─────────────────────────────────────────────────
echo '<h2>3. Seed Data</h2>';
if (!empty($results['db'])) {
    try {
        $cats = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        $prods = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        echo "<span style='color:" . ($cats > 0 ? 'green' : 'orange') . ";'>" . ($cats > 0 ? '✅' : '⚠️') . " Categories: $cats</span><br>";
        echo "<span style='color:" . ($prods > 0 ? 'green' : 'orange') . ";'>" . ($prods > 0 ? '✅' : '⚠️') . " Products: $prods</span><br>";
        if ($cats == 0 || $prods == 0) {
            echo "<span style='color:orange;'>⚠️ Run database/schema.sql to seed data</span><br>";
        }
    } catch (Exception $e) {
    }
}

// ── 4. Sessions ───────────────────────────────────────────────────────────
echo '<h2>4. PHP Sessions</h2>';
session_start();
$_SESSION['test'] = 'working';
if (isset($_SESSION['test'])) {
    echo '<span style="color:green;">✅ Sessions working</span><br>';
} else {
    echo '<span style="color:red;">❌ Sessions not working</span><br>';
}

// ── 5. Required Directories ───────────────────────────────────────────────
echo '<h2>5. Upload Directories</h2>';
$dirs = [
    'uploads/customer-designs/',
    'uploads/order-pdfs/',
    'vendor/fpdf/',
];
foreach ($dirs as $dir) {
    $full = __DIR__ . '/' . $dir;
    if (!is_dir($full)) {
        mkdir($full, 0755, true);
    }
    $writable = is_writable($full);
    echo "<span style='color:" . ($writable ? 'green' : 'red') . ";'>" . ($writable ? '✅' : '❌') . " $dir " . ($writable ? '(writable)' : '(NOT writable — check permissions!)') . "</span><br>";
}

// ── 6. FPDF Library ───────────────────────────────────────────────────────
echo '<h2>6. FPDF Library (PDF Generation)</h2>';
$fpdf_path = __DIR__ . '/vendor/fpdf/fpdf.php';
if (file_exists($fpdf_path)) {
    require_once $fpdf_path;
    if (class_exists('FPDF')) {
        // Test if it's the real one
        $fpdf = new FPDF();
        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 12);
        $tmpFile = sys_get_temp_dir() . '/fpdf_test.pdf';
        try {
            $fpdf->Output('F', $tmpFile);
            if (file_exists($tmpFile) && filesize($tmpFile) > 100) {
                echo '<span style="color:green;">✅ Real FPDF installed — PDF generation works!</span><br>';
                unlink($tmpFile);
            } else {
                echo '<span style="color:orange;">⚠️ FPDF stub found — replace with real FPDF for PDF generation</span><br>';
                echo '<span style="color:#666;">Download: https://github.com/Setasign/FPDF</span><br>';
            }
        } catch (Exception $e) {
            echo '<span style="color:orange;">⚠️ FPDF stub (orders work, but no PDF generated)</span><br>';
        }
    }
} else {
    echo '<span style="color:red;">❌ fpdf.php not found at vendor/fpdf/fpdf.php</span><br>';
    echo '<span style="color:#666;">Download from: https://github.com/Setasign/FPDF/archive/refs/heads/master.zip</span><br>';
    echo '<span style="color:#666;">Extract and copy fpdf.php to vendor/fpdf/fpdf.php</span><br>';
}

// ── 7. PHP Extensions ─────────────────────────────────────────────────────
echo '<h2>7. PHP Extensions</h2>';
$extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'gd', 'fileinfo'];
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<span style='color:" . ($loaded ? 'green' : 'orange') . ";'>" . ($loaded ? '✅' : '⚠️') . " $ext " . ($loaded ? 'loaded' : 'NOT loaded (may cause issues)') . "</span><br>";
}

// ── 8. Configuration ──────────────────────────────────────────────────────
echo '<h2>8. Site Configuration</h2>';
echo '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">';
$config = [
    'SITE_URL' => SITE_URL,
    'SITE_NAME' => SITE_NAME,
    'SITE_EMAIL' => SITE_EMAIL,
    'WHATSAPP_NUMBER' => WHATSAPP_NUMBER,
    'GST_RATE' => GST_RATE . '%',
    'DELIVERY_CHARGE' => '₹' . DELIVERY_CHARGE,
    'FREE_DELIVERY_ABOVE' => '₹' . FREE_DELIVERY_ABOVE,
    'ORDER_PREFIX' => ORDER_PREFIX,
];
foreach ($config as $k => $v) {
    echo "<tr><td><b>$k</b></td><td>$v</td></tr>";
}
echo '</table>';

// ── 9. Quick Links ────────────────────────────────────────────────────────
echo '<h2>9. Quick Links to Test</h2>';
$base = 'http://localhost/uniqueStudio/';
$links = [
    'Homepage' => $base,
    'Products Listing' => $base . 'products/',
    'Cart' => $base . 'cart/view-cart.php',
    'Checkout' => $base . 'checkout/',
    'About' => $base . 'pages/about.php',
    'Contact' => $base . 'pages/contact.php',
    'FAQ' => $base . 'pages/faq.php',
    'How It Works' => $base . 'pages/how-it-works.php',
];
echo '<ul>';
foreach ($links as $label => $url) {
    echo "<li><a href='$url' target='_blank'>$label</a></li>";
}
echo '</ul>';

?>
<!DOCTYPE html>
<html>

<head>
    <title>Unique Studio - Installation Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        h1 {
            color: #004e7c;
            border-bottom: 3px solid #ff6b35;
            padding-bottom: 10px;
        }

        h2 {
            color: #ff6b35;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <h1>🚀 Unique Studio - Installation Check</h1>
    <p style="background:#fff5f0;padding:12px;border-radius:8px;border-left:4px solid #ff6b35;">
        <strong>⚠️ Security Note:</strong> Delete this file (<code>setup.php</code>) after verifying the installation!
    </p>
</body>

</html>