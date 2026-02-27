<?php

/**
 * UNIQUE STUDIO - Add to Cart (AJAX Handler)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$product_id   = (int)($_POST['product_id'] ?? 0);
$quantity     = (int)($_POST['quantity'] ?? 1);
$unit_price   = (float)($_POST['unit_price'] ?? 0);
$product_name = sanitize($_POST['product_name'] ?? '');
$product_image = sanitize($_POST['product_image'] ?? '');

// Parse options if sent as JSON or as individual fields
$options = [];
if (!empty($_POST['options'])) {
    if (is_string($_POST['options'])) {
        $decoded = json_decode($_POST['options'], true);
        $options = is_array($decoded) ? $decoded : [];
    } elseif (is_array($_POST['options'])) {
        $options = $_POST['options'];
    }
}

// Validation
if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

if ($quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1']);
    exit;
}

// If price not sent, fetch from database
if ($unit_price <= 0) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT base_price, name, image_main FROM products WHERE id = ? AND status = 'active'");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        $unit_price = getPriceForQuantity($product_id, $quantity, $product['base_price']);
        if (empty($product_name)) $product_name = $product['name'];
        if (empty($product_image)) $product_image = $product['image_main'];
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Could not fetch product details']);
        exit;
    }
}

$result = addToCart($product_id, $quantity, $options, $unit_price, $product_name, $product_image);

echo json_encode($result);
exit;
