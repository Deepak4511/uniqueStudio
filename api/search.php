<?php

/**
 * UNIQUE STUDIO - Product Search API (AJAX)
 */

header('Content-Type: application/json');
require_once '../includes/functions.php';

$query = sanitize($_GET['q'] ?? '');

if (strlen($query) < 2) {
    echo json_encode(['success' => true, 'results' => []]);
    exit;
}

$products = searchProducts($query, 8);

$results = array_map(function ($p) {
    return [
        'id'    => $p['id'],
        'name'  => $p['name'],
        'slug'  => $p['slug'],
        'price' => formatPrice($p['base_price']),
        'image' => !empty($p['image_main']) ? $p['image_main'] : '',
        'category' => $p['category_name'],
        'url'   => SITE_URL . '/products/product-detail.php?slug=' . urlencode($p['slug']),
    ];
}, $products);

echo json_encode(['success' => true, 'results' => $results, 'count' => count($results)]);
exit;
