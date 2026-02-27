<?php

/**
 * UNIQUE STUDIO - Helper Functions
 * Common utility functions used across the application
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/whatsapp-config.php';


// ============================================================
// CART FUNCTIONS
// ============================================================

/**
 * Initialize cart in session
 */
function initCart()
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

/**
 * Get cart items from session
 * @return array
 */
function getCart()
{
    initCart();
    return $_SESSION['cart'];
}

/**
 * Get cart total count (number of unique items)
 * @return int
 */
function getCartCount()
{
    return count(getCart());
}

/**
 * Get total quantity of all items in cart
 * @return int
 */
function getCartTotalQty()
{
    $total = 0;
    foreach (getCart() as $item) {
        $total += $item['quantity'];
    }
    return $total;
}

/**
 * Add item to cart
 * @param int $product_id
 * @param int $quantity
 * @param array $options Selected options
 * @param float $unit_price Price per unit
 * @param string $product_name Product name
 * @param string $product_image Image path
 * @return array result
 */
function addToCart($product_id, $quantity, $options = [], $unit_price = 0, $product_name = '', $product_image = '')
{
    initCart();

    // Create unique cart key based on product_id + options
    $cart_key = $product_id . '_' . md5(json_encode($options));

    if (isset($_SESSION['cart'][$cart_key])) {
        // Update quantity if same product+options combo exists
        $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$cart_key] = [
            'product_id'    => $product_id,
            'name'          => $product_name,
            'image'         => $product_image,
            'quantity'      => $quantity,
            'unit_price'    => $unit_price,
            'options'       => $options,
            'options_str'   => implode(', ', array_map(function ($k, $v) {
                return $k . ': ' . $v;
            }, array_keys($options), $options)),
            'total_price'   => $unit_price * $quantity,
        ];
    }

    // Recalculate total price
    $_SESSION['cart'][$cart_key]['total_price'] = $_SESSION['cart'][$cart_key]['unit_price'] * $_SESSION['cart'][$cart_key]['quantity'];

    return ['success' => true, 'message' => 'Item added to cart', 'cart_count' => getCartCount()];
}

/**
 * Update cart item quantity
 * @param string $cart_key
 * @param int $quantity
 * @return array result
 */
function updateCartItem($cart_key, $quantity)
{
    initCart();

    if (!isset($_SESSION['cart'][$cart_key])) {
        return ['success' => false, 'message' => 'Item not found in cart'];
    }

    if ($quantity <= 0) {
        return removeFromCart($cart_key);
    }

    $_SESSION['cart'][$cart_key]['quantity'] = $quantity;
    $_SESSION['cart'][$cart_key]['total_price'] = $_SESSION['cart'][$cart_key]['unit_price'] * $quantity;

    return ['success' => true, 'message' => 'Cart updated', 'cart_count' => getCartCount()];
}

/**
 * Remove item from cart
 * @param string $cart_key
 * @return array result
 */
function removeFromCart($cart_key)
{
    initCart();

    if (!isset($_SESSION['cart'][$cart_key])) {
        return ['success' => false, 'message' => 'Item not found in cart'];
    }

    unset($_SESSION['cart'][$cart_key]);

    return ['success' => true, 'message' => 'Item removed from cart', 'cart_count' => getCartCount()];
}

/**
 * Clear entire cart
 */
function clearCart()
{
    $_SESSION['cart'] = [];
}

/**
 * Calculate cart totals
 * @return array [subtotal, gst, delivery, total]
 */
function calculateCartTotals()
{
    $cart = getCart();
    $subtotal = 0;

    foreach ($cart as $item) {
        $subtotal += $item['total_price'];
    }

    $gst = round($subtotal * (GST_RATE / 100), 2);
    $delivery = ($subtotal >= FREE_DELIVERY_ABOVE) ? 0 : DELIVERY_CHARGE;
    $total = $subtotal + $gst + $delivery;

    return [
        'subtotal' => $subtotal,
        'gst'      => $gst,
        'delivery' => $delivery,
        'total'    => $total,
    ];
}

// ============================================================
// PRODUCT FUNCTIONS
// ============================================================

/**
 * Get all active categories
 * @return array
 */
function getCategories()
{
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY display_order ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("getCategories error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get featured products
 * @param int $limit
 * @return array
 */
function getFeaturedProducts($limit = 8)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              WHERE p.featured = 'yes' AND p.status = 'active' 
                              ORDER BY p.created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("getFeaturedProducts error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get products by category
 * @param int $category_id
 * @param int $limit
 * @param int $offset
 * @return array
 */
function getProductsByCategory($category_id, $limit = 12, $offset = 0)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              WHERE p.category_id = ? AND p.status = 'active' 
                              ORDER BY p.created_at DESC LIMIT ? OFFSET ?");
        $stmt->execute([$category_id, $limit, $offset]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("getProductsByCategory error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get single product by slug
 * @param string $slug
 * @return array|null
 */
function getProductBySlug($slug)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug 
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              WHERE p.slug = ? AND p.status = 'active'");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("getProductBySlug error: " . $e->getMessage());
        return null;
    }
}

/**
 * Get product options
 * @param int $product_id
 * @return array
 */
function getProductOptions($product_id)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM product_options WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("getProductOptions error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get price for given quantity of a product
 * @param int $product_id
 * @param int $quantity
 * @param float $base_price
 * @return float
 */
function getPriceForQuantity($product_id, $quantity, $base_price)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT price_per_unit FROM product_pricing 
                              WHERE product_id = ? AND quantity_from <= ? AND quantity_to >= ?
                              ORDER BY quantity_from DESC LIMIT 1");
        $stmt->execute([$product_id, $quantity, $quantity]);
        $pricing = $stmt->fetch();
        return $pricing ? (float)$pricing['price_per_unit'] : (float)$base_price;
    } catch (Exception $e) {
        return (float)$base_price;
    }
}

/**
 * Search products
 * @param string $query
 * @param int $limit
 * @return array
 */
function searchProducts($query, $limit = 20)
{
    try {
        $db = getDB();
        $search = '%' . $query . '%';
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              WHERE (p.name LIKE ? OR p.short_description LIKE ?) AND p.status = 'active' 
                              LIMIT ?");
        $stmt->execute([$search, $search, $limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("searchProducts error: " . $e->getMessage());
        return [];
    }
}

// ============================================================
// ORDER FUNCTIONS
// ============================================================

/**
 * Generate unique order number
 * @return string
 */
function generateOrderNumber()
{
    $prefix = ORDER_PREFIX;
    $year = date('Y');

    try {
        $db = getDB();
        $stmt = $db->query("SELECT COUNT(*) as count FROM orders WHERE YEAR(created_at) = YEAR(NOW())");
        $result = $stmt->fetch();
        $count = $result['count'] + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    } catch (Exception $e) {
        return $prefix . $year . rand(1000, 9999);
    }
}

/**
 * Save order to database
 * @param array $data
 * @return int|false Order ID or false on failure
 */
function saveOrder($data)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO orders 
            (order_number, customer_name, customer_email, customer_phone, customer_address, 
             customer_city, customer_state, customer_pincode, order_items, 
             subtotal, gst_amount, delivery_charge, total_amount, special_instructions)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['order_number'],
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['customer_address'],
            $data['customer_city'],
            $data['customer_state'],
            $data['customer_pincode'],
            $data['order_items'],
            $data['subtotal'],
            $data['gst_amount'],
            $data['delivery_charge'],
            $data['total_amount'],
            $data['special_instructions'],
        ]);

        return $db->lastInsertId();
    } catch (Exception $e) {
        error_log("saveOrder error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get order by order number
 * @param string $order_number
 * @return array|null
 */
function getOrderByNumber($order_number)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ?");
        $stmt->execute([$order_number]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Update order's PDF path
 * @param string $order_number
 * @param string $pdf_path
 */
function updateOrderPdfPath($order_number, $pdf_path)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE orders SET pdf_path = ?, whatsapp_sent = 'yes' WHERE order_number = ?");
        $stmt->execute([$pdf_path, $order_number]);
    } catch (Exception $e) {
        error_log("updateOrderPdfPath error: " . $e->getMessage());
    }
}

// ============================================================
// UTILITY FUNCTIONS
// ============================================================

/**
 * Format price with currency symbol
 * @param float $amount
 * @return string
 */
function formatPrice($amount)
{
    return CURRENCY_SYMBOL . number_format((float)$amount, 2);
}

/**
 * Generate SEO-friendly slug from string
 * @param string $string
 * @return string
 */
function makeSlug($string)
{
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Sanitize user input
 * @param string $input
 * @return string
 */
function sanitize($input)
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 * @param string $email
 * @return bool
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Indian phone number
 * @param string $phone
 * @return bool
 */
function isValidPhone($phone)
{
    $phone = preg_replace('/[\s\-\(\)\+]/', '', $phone);
    return preg_match('/^[6-9]\d{9}$/', $phone) || preg_match('/^91[6-9]\d{9}$/', $phone);
}

/**
 * Get placeholder image URL
 * @param int $width
 * @param int $height
 * @param string $text
 * @return string
 */
function getPlaceholderImg($width = 400, $height = 300, $text = 'Product')
{
    return "https://via.placeholder.com/{$width}x{$height}/ff6b35/ffffff?text=" . urlencode($text);
}

/**
 * Truncate text to specified length
 * @param string $text
 * @param int $length
 * @param string $suffix
 * @return string
 */
function truncate($text, $length = 150, $suffix = '...')
{
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . $suffix;
}

/**
 * Save contact inquiry
 * @param array $data
 * @return bool
 */
function saveContactInquiry($data)
{
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO contact_inquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? '',
            $data['subject'] ?? 'General Inquiry',
            $data['message'],
        ]);
        return true;
    } catch (Exception $e) {
        error_log("saveContactInquiry error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all products with pagination
 * @param int $limit
 * @param int $offset
 * @param string $search
 * @param int $category_id
 * @return array
 */
function getAllProducts($limit = 12, $offset = 0, $search = '', $category_id = 0)
{
    try {
        $db = getDB();
        $where = "WHERE p.status = 'active'";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (p.name LIKE ? OR p.short_description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        if ($category_id > 0) {
            $where .= " AND p.category_id = ?";
            $params[] = $category_id;
        }

        $params[] = $limit;
        $params[] = $offset;

        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              {$where} ORDER BY p.featured DESC, p.created_at DESC 
                              LIMIT ? OFFSET ?");
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("getAllProducts error: " . $e->getMessage());
        return [];
    }
}

/**
 * Count all products (for pagination)
 * @param string $search
 * @param int $category_id
 * @return int
 */
function countAllProducts($search = '', $category_id = 0)
{
    try {
        $db = getDB();
        $where = "WHERE p.status = 'active'";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (p.name LIKE ? OR p.short_description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        if ($category_id > 0) {
            $where .= " AND p.category_id = ?";
            $params[] = $category_id;
        }

        $stmt = $db->prepare("SELECT COUNT(*) FROM products p {$where}");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}
