<?php

/**
 * UNIQUE STUDIO - Update Cart (AJAX Handler)
 */

header('Content-Type: application/json');

require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$cart_key = sanitize($_POST['cart_key'] ?? '');
$quantity  = (int)($_POST['quantity'] ?? 0);

if (empty($cart_key)) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart key']);
    exit;
}

$result = updateCartItem($cart_key, $quantity);
$totals = calculateCartTotals();

$result['cart_count'] = getCartCount();
$result['totals']     = $totals;

echo json_encode($result);
exit;
