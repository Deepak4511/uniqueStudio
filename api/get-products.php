<?php

/**
 * UNIQUE STUDIO - Get Products API (AJAX)
 */

header('Content-Type: application/json');
require_once '../includes/functions.php';

$category_id = (int)($_GET['category'] ?? 0);
$limit       = min((int)($_GET['limit'] ?? 8), 50);
$offset      = (int)($_GET['offset'] ?? 0);
$featured    = $_GET['featured'] ?? '';

if ($featured === 'yes') {
    $products = getFeaturedProducts($limit);
} elseif ($category_id > 0) {
    $products = getProductsByCategory($category_id, $limit, $offset);
} else {
    $products = getAllProducts($limit, $offset);
}

// Format for JSON response
$formatted = array_map(function ($p) {
    return [
        'id'          => (int)$p['id'],
        'name'        => $p['name'],
        'slug'        => $p['slug'],
        'description' => $p['short_description'],
        'price'       => (float)$p['base_price'],
        'price_fmt'   => formatPrice($p['base_price']),
        'image'       => $p['image_main'] ?? '',
        'category'    => $p['category_name'],
        'featured'    => $p['featured'],
        'url'         => 'products/product-detail.php?slug=' . urlencode($p['slug']),
    ];
}, $products);

echo json_encode(['success' => true, 'products' => $formatted, 'count' => count($formatted)]);
exit;
