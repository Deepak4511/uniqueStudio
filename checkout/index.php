<?php
$page = 'Checkout';
require_once '../includes/functions.php';

$cart = getCart();
if (empty($cart)) {
    header('Location: ../cart/view-cart.php');
    exit;
}

$totals = calculateCartTotals();
include('../inc/header.php');
?>

<style>
    .checkout-page {
        background: #f8fafc;
        padding: 60px 0;
        min-height: 80vh;
    }

    .checkout-heading {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 8px;
    }

    .checkout-subtitle {
        color: #64748b;
        margin-bottom: 40px;
    }

    /* Steps Indicator */
    .checkout-steps {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 40px;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .step-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .step-circle.active {
        background: var(--orange);
        color: white;
        box-shadow: 0 4px 14px rgba(255, 107, 53, 0.4);
    }

    .step-circle.done {
        background: #16a34a;
        color: white;
    }

    .step-circle.inactive {
        background: #e2e8f0;
        color: #94a3b8;
    }

    .step-label {
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 6px;
        color: #64748b;
    }

    .step-line {
        flex: 1;
        height: 2px;
        background: #e2e8f0;
        margin-bottom: 20px;
    }

    .step-line.done {
        background: var(--orange);
    }

    /* Form */
    .checkout-form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        padding: 32px;
        margin-bottom: 24px;
    }

    .section-title-sm {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title-sm::before {
        content: '';
        width: 4px;
        height: 22px;
        background: var(--orange);
        border-radius: 2px;
        display: block;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.88rem;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 11px 16px;
        font-size: 0.95rem;
        color: #1e293b;
        transition: border-color 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.12);
    }

    .form-control.error {
        border-color: #ef4444;
    }

    /* Order Summary */
    .order-summary-sidebar {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        padding: 28px;
        position: sticky;
        top: 90px;
    }

    .summary-heading {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 20px;
    }

    .cart-mini-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .cart-mini-img {
        width: 55px;
        height: 55px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #f1f5f9;
        flex-shrink: 0;
    }

    .cart-mini-name {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
    }

    .cart-mini-qty {
        font-size: 0.78rem;
        color: #64748b;
    }

    .cart-mini-price {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--orange);
        margin-left: auto;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 0.92rem;
    }

    .summary-total-line {
        display: flex;
        justify-content: space-between;
        padding: 14px 0 0;
        border-top: 2px solid #f1f5f9;
        margin-top: 6px;
    }

    .summary-total-label {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--green);
    }

    .summary-total-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--orange);
    }

    /* Submit */
    .submit-order-btn {
        background: linear-gradient(135deg, #ff6b35, #ff8c5f);
        color: white;
        border: none;
        padding: 18px 36px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.05rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 20px;
    }

    .submit-order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(255, 107, 53, 0.45);
    }

    .submit-order-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* Secure badge */
    .secure-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        font-size: 0.82rem;
        margin-top: 12px;
    }
</style>

<div style="background: var(--green); padding: 16px 0;">
    <div class="container py-50">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background:none;padding:0;margin:0;">
                <li class="breadcrumb-item"><a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a></li>
                <li class="breadcrumb-item"><a href="../cart/view-cart.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Cart</a></li>
                <li class="breadcrumb-item active" style="color:white;">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<section class="checkout-page">
    <div class="container py-50">
        <h1 class="checkout-heading">Secure Checkout</h1>
        <p class="checkout-subtitle">Fill in your details and we'll process your order via WhatsApp</p>

        <form id="checkoutForm" action="process-order.php" method="POST" enctype="multipart/form-data" novalidate>
            <div class="row g-4">

                <!-- Left - Checkout Form -->
                <div class="col-lg-7">

                    <!-- Customer Information -->
                    <div class="checkout-form-card">
                        <h4 class="section-title-sm">Customer Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Your full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="customer_email" class="form-control" placeholder="email@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WhatsApp / Phone Number *</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius:10px 0 0 10px;border:1.5px solid #e2e8f0;border-right:none;background:#f8fafc;color:#475569;">+91</span>
                                    <input type="tel" name="customer_phone" class="form-control" placeholder="9876543210" style="border-radius:0 10px 10px 0;" required>
                                </div>
                                <small style="color:#64748b;font-size:0.78rem;">Order will be confirmed on this WhatsApp number</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Company Name (Optional)</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Your company name">
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="checkout-form-card">
                        <h4 class="section-title-sm">Delivery Address</h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Street Address *</label>
                                <input type="text" name="customer_address" class="form-control" placeholder="House/Flat No., Street Name, Area" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City *</label>
                                <input type="text" name="customer_city" class="form-control" placeholder="City" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State *</label>
                                <select name="customer_state" class="form-select" required>
                                    <option value="">Select State</option>
                                    <option>Madhya Pradesh</option>
                                    <option>Maharashtra</option>
                                    <option>Delhi</option>
                                    <option>Gujarat</option>
                                    <option>Rajasthan</option>
                                    <option>Uttar Pradesh</option>
                                    <option>Karnataka</option>
                                    <option>Tamil Nadu</option>
                                    <option>West Bengal</option>
                                    <option>Andhra Pradesh</option>
                                    <option>Telangana</option>
                                    <option>Kerala</option>
                                    <option>Punjab</option>
                                    <option>Haryana</option>
                                    <option>Bihar</option>
                                    <option>Odisha</option>
                                    <option>Jharkhand</option>
                                    <option>Chhattisgarh</option>
                                    <option>Assam</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PIN Code *</label>
                                <input type="text" name="customer_pincode" class="form-control" placeholder="452001" maxlength="6" required>
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div class="checkout-form-card">
                        <h4 class="section-title-sm">Special Instructions</h4>
                        <div class="mb-3">
                            <label class="form-label">Design Notes / Special Requirements (Optional)</label>
                            <textarea name="special_instructions" class="form-control" rows="4"
                                placeholder="Any special design requirements, color preferences, delivery notes..."></textarea>
                        </div>
                        <?php
                        $has_customizable = false;
                        foreach ($cart as $item) {
                            // Check if any item has designs to upload
                            $has_customizable = true;
                            break;
                        }
                        ?>
                        <?php if ($has_customizable): ?>
                            <div class="p-3 rounded-3" style="background:#f0f9ff;border:1px dashed #93c5fd;">
                                <label class="form-label" style="color:#1e40af;">
                                    <i class="bi bi-cloud-upload me-1"></i>Upload Design Files (Optional)
                                </label>
                                <input type="file" name="design_files[]" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.ai,.psd" multiple>
                                <small style="color:#64748b;font-size:0.78rem;display:block;margin-top:6px;">
                                    You can also share designs via WhatsApp after placing the order.
                                    Max 5MB per file. Accepted: JPG, PNG, PDF, AI, PSD
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Right - Order Summary -->
                <div class="col-lg-5">
                    <div class="order-summary-sidebar">
                        <h4 class="summary-heading">
                            <i class="bi bi-receipt me-2" style="color:var(--orange);"></i>Order Summary
                        </h4>

                        <!-- Cart Items -->
                        <?php foreach ($cart as $item): ?>
                            <div class="cart-mini-item">
                                <img src="<?= !empty($item['image']) ? '../' . htmlspecialchars($item['image']) : 'https://via.placeholder.com/60x60/ff6b35/ffffff?text=' . urlencode(substr($item['name'], 0, 2)) ?>"
                                    class="cart-mini-img" alt="<?= htmlspecialchars($item['name']) ?>">
                                <div class="flex-grow-1">
                                    <div class="cart-mini-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="cart-mini-qty">
                                        Qty: <?= $item['quantity'] ?>
                                        <?php if (!empty($item['options_str'])): ?>
                                            | <?= htmlspecialchars($item['options_str']) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="cart-mini-price"><?= formatPrice($item['total_price']) ?></div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Totals -->
                        <div class="mt-3">
                            <div class="summary-line">
                                <span>Subtotal</span>
                                <span><?= formatPrice($totals['subtotal']) ?></span>
                            </div>
                            <div class="summary-line">
                                <span>GST (<?= GST_RATE ?>%)</span>
                                <span><?= formatPrice($totals['gst']) ?></span>
                            </div>
                            <div class="summary-line">
                                <span>Delivery</span>
                                <span>
                                    <?= $totals['delivery'] == 0
                                        ? '<span style="color:#16a34a;font-weight:600;">FREE</span>'
                                        : formatPrice($totals['delivery']) ?>
                                </span>
                            </div>
                            <div class="summary-total-line">
                                <span class="summary-total-label">Total</span>
                                <span class="summary-total-value"><?= formatPrice($totals['total']) ?></span>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="submit-order-btn" id="submitBtn">
                            <i class="bi bi-send me-2"></i>Place Order & Send to WhatsApp
                        </button>

                        <!-- Edit Cart -->
                        <a href="../cart/view-cart.php" class="d-block text-center mt-3 text-decoration-none" style="color:#64748b;font-size:0.87rem;">
                            <i class="bi bi-pencil me-1"></i>Edit Cart
                        </a>

                        <!-- Secure Notice -->
                        <div class="secure-badge justify-content-center mt-4">
                            <i class="bi bi-shield-lock" style="color:#16a34a;"></i>
                            Secure order form. Your data is protected.
                        </div>

                        <!-- Payment Info -->
                        <div class="mt-4 p-3 rounded-3 text-center" style="background:#fffbeb;border:1px dashed #fbbf24;">
                            <i class="bi bi-info-circle" style="color:#d97706;margin-bottom:6px;display:block;"></i>
                            <p style="font-size:0.82rem;color:#92400e;margin:0;">
                                <strong>No payment required now.</strong><br>
                                Our team will WhatsApp you payment details after reviewing your order.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        let valid = true;
        const required = this.querySelectorAll('[required]');

        required.forEach(field => {
            field.classList.remove('error');
            if (!field.value.trim()) {
                field.classList.add('error');
                valid = false;
            }
        });

        // Email validation
        const emailField = this.querySelector('[name="customer_email"]');
        if (emailField && emailField.value && !/\S+@\S+\.\S+/.test(emailField.value)) {
            emailField.classList.add('error');
            valid = false;
        }

        // Phone validation
        const phoneField = this.querySelector('[name="customer_phone"]');
        if (phoneField && phoneField.value && !/^[6-9]\d{9}$/.test(phoneField.value.replace(/\s/g, ''))) {
            phoneField.classList.add('error');
            valid = false;
        }

        if (!valid) {
            const firstError = this.querySelector('.error');
            if (firstError) firstError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Show error message
            const existingMsg = document.getElementById('validationMsg');
            if (existingMsg) existingMsg.remove();

            const msg = document.createElement('div');
            msg.id = 'validationMsg';
            msg.className = 'alert alert-danger rounded-3 mt-3';
            msg.style.cssText = 'border-radius:12px;border:none;background:#fef2f2;color:#dc2626;';
            msg.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Please fill in all required fields correctly.';
            this.prepend(msg);
            return;
        }

        // Disable button and submit
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        this.submit();
    });
</script>

<?php include('../inc/footer.php'); ?>