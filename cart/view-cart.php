<?php
$page = 'Cart';
require_once '../includes/functions.php';
$cart = getCart();
$totals = calculateCartTotals();
include('../inc/header.php');
?>

<style>
    .cart-page {
        background: #f8fafc;
        min-height: 70vh;
        padding: 60px 0;
    }

    .cart-section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 8px;
        font-family: 'Space Grotesk', sans-serif;
    }

    .cart-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 40px;
    }

    .cart-table-wrapper {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .cart-table {
        margin: 0;
    }

    .cart-table thead {
        background: linear-gradient(135deg, var(--green), #003d63);
        color: white;
    }

    .cart-table thead th {
        padding: 16px 20px;
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .cart-table tbody td {
        padding: 20px;
        vertical-align: middle;
        border-color: #f1f5f9;
    }

    .cart-product-img {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
        background: #f8fafc;
        border: 2px solid #f1f5f9;
    }

    .cart-product-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
    }

    .cart-product-options {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 4px;
    }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #334155;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .qty-btn:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: white;
    }

    .qty-input {
        width: 55px;
        height: 32px;
        text-align: center;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
    }

    .remove-btn {
        color: #ef4444;
        background: #fef2f2;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .remove-btn:hover {
        background: #ef4444;
        color: white;
    }

    .cart-price {
        font-weight: 700;
        color: var(--green);
        font-size: 1rem;
    }

    .order-summary-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        padding: 28px;
    }

    .summary-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 20px;
        font-family: 'Space Grotesk', sans-serif;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-total {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--green);
    }

    .summary-total-val {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--orange);
    }

    .checkout-btn {
        background: linear-gradient(135deg, #ff6b35, #ff8c5f);
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        width: 100%;
        margin-top: 20px;
        cursor: pointer;
        transition: all 0.3s;
        letter-spacing: 0.05em;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4);
    }

    .continue-link {
        display: block;
        text-align: center;
        margin-top: 14px;
        color: #64748b;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .continue-link:hover {
        color: var(--orange);
    }

    .empty-cart {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }

    .empty-cart-icon {
        font-size: 5rem;
        color: #e2e8f0;
        margin-bottom: 24px;
    }

    .badge-free-delivery {
        background: #dcfce7;
        color: #16a34a;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }
</style>

<section class="cart-page">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-2" style="font-size:0.9rem;color:#64748b;">
            <a href="../index.php" class="text-decoration-none" style="color:#64748b;">Home</a>
            <i class="bi bi-chevron-right" style="font-size:0.7rem;"></i>
            <span style="color:var(--orange);">Shopping Cart</span>
        </div>
        <h1 class="cart-section-title">Your Shopping Cart</h1>
        <p class="cart-subtitle">
            <?= count($cart) ?> item(s) in your cart
            <?php if ($totals['delivery'] == 0 && count($cart) > 0): ?>
                <span class="badge-free-delivery ms-2"><i class="bi bi-truck"></i> Free Delivery Applied!</span>
            <?php elseif (count($cart) > 0): ?>
                <span class="ms-2" style="font-size:0.85rem;color:#16a34a;">
                    Add <?= formatPrice(FREE_DELIVERY_ABOVE - $totals['subtotal']) ?> more for FREE delivery!
                </span>
            <?php endif; ?>
        </p>

        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon"><i class="bi bi-cart-x"></i></div>
                <h3 style="color:#1e293b;margin-bottom:12px;">Your cart is empty!</h3>
                <p style="color:#64748b;margin-bottom:28px;">Looks like you haven't added anything yet. Explore our products and find something you love!</p>
                <a href="../products/" class="btn btn-orange rounded-pill px-5 py-3 fw-bold">
                    <i class="bi bi-grid me-2"></i>Browse Products
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-table-wrapper">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <?php foreach ($cart as $cart_key => $item): ?>
                                    <tr id="row-<?= htmlspecialchars($cart_key) ?>">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?= !empty($item['image']) ? '../' . htmlspecialchars($item['image']) : 'https://via.placeholder.com/80x80/ff6b35/ffffff?text=' . urlencode(substr($item['name'], 0, 2)) ?>"
                                                    class="cart-product-img"
                                                    alt="<?= htmlspecialchars($item['name']) ?>">
                                                <div>
                                                    <div class="cart-product-name"><?= htmlspecialchars($item['name']) ?></div>
                                                    <?php if (!empty($item['options_str'])): ?>
                                                        <div class="cart-product-options">
                                                            <i class="bi bi-gear me-1"></i><?= htmlspecialchars($item['options_str']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="cart-price"><?= formatPrice($item['unit_price']) ?></span>
                                        </td>
                                        <td>
                                            <div class="qty-control">
                                                <button class="qty-btn" onclick="updateQty('<?= $cart_key ?>', -1)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number"
                                                    class="qty-input"
                                                    id="qty-<?= $cart_key ?>"
                                                    value="<?= $item['quantity'] ?>"
                                                    min="1"
                                                    onchange="setQty('<?= $cart_key ?>', this.value)">
                                                <button class="qty-btn" onclick="updateQty('<?= $cart_key ?>', 1)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="cart-price" id="total-<?= $cart_key ?>">
                                                <?= formatPrice($item['total_price']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="remove-btn" onclick="removeItem('<?= $cart_key ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Continue Shopping -->
                    <a href="../products/" class="d-inline-flex align-items-center gap-2 mt-3 text-decoration-none" style="color:#64748b;">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="order-summary-card">
                        <h4 class="summary-title">Order Summary</h4>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span class="fw-600" id="summary-subtotal"><?= formatPrice($totals['subtotal']) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>GST (<?= GST_RATE ?>%)</span>
                            <span id="summary-gst"><?= formatPrice($totals['gst']) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Charge</span>
                            <span id="summary-delivery">
                                <?= $totals['delivery'] == 0 ? '<span style="color:#16a34a;font-weight:600;">FREE</span>' : formatPrice($totals['delivery']) ?>
                            </span>
                        </div>
                        <div class="summary-row mt-2">
                            <span class="summary-total">Total Amount</span>
                            <span class="summary-total-val" id="summary-total"><?= formatPrice($totals['total']) ?></span>
                        </div>

                        <form action="../checkout/" method="GET">
                            <button type="submit" class="checkout-btn">
                                <i class="bi bi-lock me-2"></i>Proceed to Checkout
                            </button>
                        </form>
                        <a href="../index.php" class="continue-link">
                            <i class="bi bi-house me-1"></i>Back to Home
                        </a>

                        <!-- WhatsApp Quick Order -->
                        <div class="mt-4 p-3 rounded-3" style="background:#f0fdf4;border:1px dashed #86efac;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-whatsapp" style="color:#25d366;font-size:1.2rem;"></i>
                                <span style="font-weight:600;color:#166534;font-size:0.9rem;">Need Help?</span>
                            </div>
                            <p style="font-size:0.82rem;color:#166534;margin-bottom:8px;">
                                Have questions? Chat with us on WhatsApp for instant help!
                            </p>
                            <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank"
                                class="btn btn-sm" style="background:#25d366;color:white;border-radius:20px;font-size:0.82rem;">
                                <i class="bi bi-whatsapp me-1"></i>Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    const CURRENCY = '<?= CURRENCY_SYMBOL ?>';

    function formatPrice(amount) {
        return CURRENCY + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function updateQty(cartKey, delta) {
        const input = document.getElementById('qty-' + cartKey);
        const newQty = Math.max(1, parseInt(input.value) + delta);
        input.value = newQty;
        setQty(cartKey, newQty);
    }

    function setQty(cartKey, qty) {
        qty = Math.max(1, parseInt(qty));
        const input = document.getElementById('qty-' + cartKey);
        input.value = qty;

        fetch('update-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cart_key=${encodeURIComponent(cartKey)}&quantity=${qty}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Reload to recalculate totals
                    location.reload();
                }
            });
    }

    function removeItem(cartKey) {
        if (!confirm('Remove this item from cart?')) return;

        fetch('remove-from-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cart_key=${encodeURIComponent(cartKey)}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
    }
</script>

<?php include('../inc/footer.php'); ?>