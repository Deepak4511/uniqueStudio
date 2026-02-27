<?php
$page = 'Order Confirmation';
require_once '../includes/functions.php';

$order_number = sanitize($_GET['order'] ?? '');
$whatsapp_link = $_SESSION['whatsapp_link'] ?? '#';
$pdf_url       = $_SESSION['pdf_url'] ?? '';
$total         = $_SESSION['last_order_total'] ?? 0;

// Get order from DB
$order = null;
if (!empty($order_number)) {
    $order = getOrderByNumber($order_number);
}

// If no order found and no session, redirect
if (!$order && empty($order_number)) {
    header('Location: ../index.php');
    exit;
}

// Clear session data
unset($_SESSION['whatsapp_link'], $_SESSION['pdf_url'], $_SESSION['last_order_number'], $_SESSION['last_order_total']);

include('../inc/header.php');
?>

<style>
    .confirmation-page {
        background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 50%, #fff5f0 100%);
        min-height: 80vh;
        padding: 80px 0;
    }

    .success-card {
        background: white;
        border-radius: 28px;
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.1);
        max-width: 720px;
        margin: 0 auto;
        padding: 50px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--orange), var(--green));
    }

    .success-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55) forwards;
    }

    .success-icon i {
        font-size: 3rem;
        color: #16a34a;
    }

    @keyframes popIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .success-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 12px;
    }

    .success-subtitle {
        color: #64748b;
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 28px;
    }

    .order-num-badge {
        background: #fff5f0;
        border: 2px dashed var(--orange);
        border-radius: 14px;
        padding: 14px 28px;
        display: inline-block;
        margin-bottom: 32px;
    }

    .order-num-label {
        font-size: 0.82rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .order-num-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--orange);
        font-family: 'Space Grotesk', sans-serif;
    }

    /* Steps */
    .next-steps {
        background: #f8fafc;
        border-radius: 18px;
        padding: 28px;
        margin: 28px 0;
        text-align: left;
    }

    .step-row {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 18px;
    }

    .step-row:last-child {
        margin-bottom: 0;
    }

    .step-num {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--orange), #ff8c5f);
        color: white;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    .step-text strong {
        display: block;
        color: #1e293b;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 2px;
    }

    .step-text span {
        color: #64748b;
        font-size: 0.86rem;
    }

    /* Buttons */
    .wa-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #25d366;
        color: white;
        border: none;
        padding: 18px 36px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        animation: pulse 2s infinite;
    }

    .wa-btn:hover {
        background: #20ba58;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(37, 211, 102, 0.45);
        animation: none;
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.4);
        }

        50% {
            box-shadow: 0 0 0 12px rgba(37, 211, 102, 0);
        }
    }

    .pdf-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: white;
        color: var(--green);
        border: 2px solid #e2e8f0;
        padding: 16px 28px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
    }

    .pdf-btn:hover {
        border-color: var(--green);
        color: var(--green);
        transform: translateY(-2px);
        background: #f0fdf4;
    }

    /* Order details expandable */
    .order-details-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 24px;
        margin-top: 28px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.92rem;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0 0;
        font-size: 1.1rem;
        font-weight: 800;
    }

    .confetti-emoji {
        font-size: 2rem;
        display: inline-block;
        animation: bounce 0.8s infinite alternate;
    }

    @keyframes bounce {
        from {
            transform: translateY(0);
        }

        to {
            transform: translateY(-10px);
        }
    }
</style>

<section class="confirmation-page">
    <div class="container py-50">
        <div class="success-card">
            <!-- Confetti -->
            <div style="font-size:1.4rem;margin-bottom:16px;">🎉 🎊 🎉</div>

            <!-- Success Icon -->
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <!-- Title -->
            <h1 class="success-title">Order Placed Successfully!</h1>
            <p class="success-subtitle">
                Thank you, <?= htmlspecialchars($order['customer_name'] ?? 'valued customer') ?>!<br>
                Your order has been received and saved. Our team will contact you on WhatsApp shortly.
            </p>

            <!-- Order Number -->
            <div class="order-num-badge">
                <div class="order-num-label">Your Order Number</div>
                <div class="order-num-value">#<?= htmlspecialchars($order_number) ?></div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h5 style="font-weight:700;color:var(--green);margin-bottom:20px;font-family:'Space Grotesk',sans-serif;">
                    <i class="bi bi-list-check me-2" style="color:var(--orange);"></i>What Happens Next?
                </h5>
                <div class="step-row">
                    <div class="step-num">1</div>
                    <div class="step-text">
                        <strong>Click "Send to WhatsApp" below</strong>
                        <span>This will open WhatsApp with your complete order details pre-filled</span>
                    </div>
                </div>
                <div class="step-row">
                    <div class="step-num">2</div>
                    <div class="step-text">
                        <strong>Our team confirms your order</strong>
                        <span>We'll review requirements and send payment details within 1-2 hours</span>
                    </div>
                </div>
                <div class="step-row">
                    <div class="step-num">3</div>
                    <div class="step-text">
                        <strong>Make payment & share design</strong>
                        <span>Pay via UPI/bank transfer and share your design files on WhatsApp</span>
                    </div>
                </div>
                <div class="step-row">
                    <div class="step-num">4</div>
                    <div class="step-text">
                        <strong>Production & Delivery (5-7 days)</strong>
                        <span>We'll keep you updated throughout the process</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 justify-content-center flex-wrap mb-4">
                <a href="<?= htmlspecialchars($whatsapp_link) ?>" target="_blank" class="wa-btn">
                    <i class="bi bi-whatsapp fs-5"></i>
                    Send Order to WhatsApp
                </a>
                <?php if (!empty($pdf_url)): ?>
                    <a href="<?= htmlspecialchars($pdf_url) ?>" target="_blank" download class="pdf-btn">
                        <i class="bi bi-file-earmark-pdf fs-5"></i>
                        Download PDF
                    </a>
                <?php endif; ?>
            </div>

            <!-- Order Summary -->
            <?php if ($order): ?>
                <div class="order-details-section">
                    <h6 style="font-weight:700;color:var(--green);margin-bottom:16px;">
                        <i class="bi bi-receipt me-2"></i>Order Summary
                    </h6>
                    <div class="detail-row">
                        <span style="color:#64748b;">Customer</span>
                        <span style="font-weight:600;"><?= htmlspecialchars($order['customer_name']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span style="color:#64748b;">Phone</span>
                        <span style="font-weight:600;"><?= htmlspecialchars($order['customer_phone']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span style="color:#64748b;">Delivery To</span>
                        <span style="font-weight:600;text-align:right;max-width:60%;">
                            <?= htmlspecialchars($order['customer_city']) . ', ' . htmlspecialchars($order['customer_state']) ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span style="color:#64748b;">Subtotal</span>
                        <span><?= formatPrice($order['subtotal']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span style="color:#64748b;">GST</span>
                        <span><?= formatPrice($order['gst_amount']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span style="color:#64748b;">Delivery</span>
                        <span><?= $order['delivery_charge'] == 0 ? '<span style="color:#16a34a;font-weight:600;">FREE</span>' : formatPrice($order['delivery_charge']) ?></span>
                    </div>
                    <div class="total-row">
                        <span style="color:var(--green);">Total Amount</span>
                        <span style="color:var(--orange);"><?= formatPrice($order['total_amount']) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Payment Info -->
            <div class="mt-4 p-3 rounded-3" style="background:#fffbeb;border:1px dashed #fbbf24;">
                <p style="font-size:0.85rem;color:#92400e;margin:0;">
                    <i class="bi bi-credit-card me-1"></i>
                    <strong>No payment required yet.</strong>
                    Our team will send UPI/bank details on WhatsApp after reviewing your order.
                </p>
            </div>

            <!-- Continue Shopping -->
            <div class="mt-4">
                <a href="../index.php" class="text-decoration-none" style="color:#64748b;font-size:0.9rem;">
                    <i class="bi bi-arrow-left me-1"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>

<?php include('../inc/footer.php'); ?>