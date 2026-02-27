<?php
$page = 'How It Works';
require_once '../includes/functions.php';
include('../inc/header.php');
?>

<style>
    .hiw-hero {
        background: linear-gradient(135deg, var(--green), #003d63);
        padding: 80px 0;
        color: white;
    }

    .hiw-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .hiw-section {
        padding: 80px 0;
        background: #f8fafc;
    }

    /* Timeline Steps */
    .steps-timeline {
        position: relative;
    }

    .step-block {
        display: flex;
        gap: 40px;
        align-items: flex-start;
        margin-bottom: 60px;
        position: relative;
    }

    .step-block:last-child {
        margin-bottom: 0;
    }

    .step-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
    }

    .step-number {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--orange), #ff8c5f);
        color: white;
        font-size: 1.6rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Space Grotesk', sans-serif;
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
        position: relative;
        z-index: 2;
    }

    .step-line {
        width: 2px;
        height: calc(100% + 20px);
        background: linear-gradient(to bottom, var(--orange), #e2e8f0);
        position: absolute;
        top: 70px;
        left: 34px;
        z-index: 1;
    }

    .step-block:last-child .step-line {
        display: none;
    }

    .step-content {
        flex: 1;
        background: white;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        border-left: 4px solid var(--orange);
        transition: transform 0.3s;
    }

    .step-content:hover {
        transform: translateX(8px);
    }

    .step-heading {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 10px;
    }

    .step-desc {
        color: #475569;
        line-height: 1.8;
        margin-bottom: 16px;
    }

    .step-icon-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff5f0;
        color: var(--orange);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* WhatsApp Flow */
    .wa-flow {
        background: linear-gradient(135deg, #dcfce7, #f0fdf4);
        border-radius: 24px;
        padding: 40px;
        margin: 60px 0;
        border: 2px dashed #86efac;
    }

    .wa-flow h3 {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        color: #166534;
        margin-bottom: 24px;
        font-size: 1.6rem;
    }

    .wa-step {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
        padding: 16px 20px;
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .wa-step-num {
        width: 36px;
        height: 36px;
        background: #25d366;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .wa-step-text {
        color: #166534;
        font-weight: 500;
        font-size: 0.95rem;
    }

    /* Payment methods */
    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }

    .payment-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
        transition: all 0.3s;
    }

    .payment-card:hover {
        border-color: var(--orange);
        transform: translateY(-4px);
    }

    .payment-icon {
        font-size: 2rem;
        margin-bottom: 8px;
    }

    .payment-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }
</style>

<!-- Hero -->
<section class="hiw-hero py-50">
    <div class="container-fluid">
        <nav class="mb-4" style="font-size:0.88rem;">
            <a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a>
            <span class="mx-2" style="color:rgba(255,255,255,0.5);">/</span>
            <span style="color:white;">How It Works</span>
        </nav>
        <h1>How Unique Studio Works</h1>
        <p style="color:rgba(255,255,255,0.8);margin-top:12px;font-size:1.05rem;max-width:600px;">
            Ordering your custom prints is simple, fast, and backed by our satisfaction guarantee.
            Here's the complete process from browse to delivery.
        </p>
    </div>
</section>

<section class="hiw-section">
    <div class="container py-50">

        <!-- Order Process Timeline -->
        <div class="text-center mb-60">
            <span class="d-inline-block px-3 py-1 rounded-pill mb-3" style="background:#fff5f0;color:var(--orange);font-size:0.85rem;font-weight:700;">THE PROCESS</span>
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;color:var(--green);">From Idea to Doorstep</h2>
            <p style="color:#64748b;max-width:500px;margin:10px auto 0;">Simple steps to get your custom prints delivered</p>
        </div>

        <div class="row g-5 align-items-start">
            <div class="col-lg-7">
                <div class="steps-timeline">
                    <?php
                    $steps = [
                        ['bi-grid', 'Browse & Select', 'Explore our wide range of printing products — from business cards and t-shirts to mugs and banners. Use filters to find exactly what you need.', 'Catalog Available'],
                        ['bi-sliders', 'Customize Your Order', 'Choose your options: size, paper type, finish, color, and quantity. Our price calculator updates instantly so you know the exact cost before ordering.', 'Instant Price Updates'],
                        ['bi-cart-plus', 'Add to Cart & Checkout', 'Add products to cart and proceed to checkout. Fill in your delivery details and upload your design file (or share via WhatsApp later). No payment required yet.', 'Secure Form'],
                        ['bi-whatsapp', 'WhatsApp Confirmation', 'After submitting, click "Send to WhatsApp" to send your complete order to our team. Our team reviews your order within 1-2 hours and confirms everything.', 'Quick Response'],
                        ['bi-credit-card', 'Make Payment', 'Our team sends payment details via WhatsApp. Pay conveniently via UPI (Google Pay, PhonePe, Paytm), bank transfer, or cash for local orders.', 'Multiple Options'],
                        ['bi-palette2', 'Production Begins', 'Once payment is confirmed, we start printing your order immediately with premium quality materials and processes. You\'ll receive production updates.', '5-7 Day Timeline'],
                        ['bi-truck', 'Receive Your Order', 'Your beautifully printed products are packaged carefully and shipped to your doorstep. We share tracking details on WhatsApp. 🎉', 'Pan India Delivery'],
                    ];
                    foreach ($steps as $i => $step): ?>
                        <div class="step-block">
                            <div class="step-left" style="position:relative;">
                                <div class="step-number"><?= $i + 1 ?></div>
                                <?php if ($i < count($steps) - 1): ?>
                                    <div class="step-line"></div>
                                <?php endif; ?>
                            </div>
                            <div class="step-content">
                                <h4 class="step-heading">
                                    <i class="bi <?= $step[0] ?> me-2" style="color:var(--orange);"></i>
                                    <?= $step[1] ?>
                                </h4>
                                <p class="step-desc"><?= $step[2] ?></p>
                                <span class="step-icon-chip">
                                    <i class="bi bi-check-circle"></i><?= $step[3] ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-5">
                <!-- WhatsApp Flow -->
                <div class="wa-flow">
                    <h3><i class="bi bi-whatsapp me-2"></i>Our WhatsApp Order System</h3>
                    <p style="color:#166534;margin-bottom:24px;font-size:0.95rem;">
                        We use WhatsApp as our primary communication channel — it's faster, more personal, and you always talk to a real person.
                    </p>
                    <?php
                    $wa_steps = [
                        ['Place order on website', '→ Order saved in our system'],
                        ['Click "Send to WhatsApp"', '→ Full order details auto-sent'],
                        ['Our team responds', '→ Within 1-2 hours (usually faster!)'],
                        ['We send payment link', '→ UPI/bank transfer details'],
                        ['You pay & share design', '→ WhatsApp your design file'],
                        ['Production starts', '→ Regular updates via WhatsApp'],
                        ['Order delivered!', '→ Tracking shared on WhatsApp'],
                    ];
                    foreach ($wa_steps as $i => $step): ?>
                        <div class="wa-step">
                            <div class="wa-step-num"><?= $i + 1 ?></div>
                            <div class="wa-step-text">
                                <strong><?= $step[0] ?></strong> <span style="color:#16a34a;"><?= $step[1] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank"
                        class="d-block w-100 text-center mt-4 p-3 rounded-3 fw-700 text-decoration-none"
                        style="background:#25d366;color:white;border-radius:14px;font-weight:700;">
                        <i class="bi bi-whatsapp me-2"></i>Open WhatsApp Now
                    </a>
                </div>

                <!-- Payment Methods -->
                <div class="p-4 mt-4" style="background:white;border-radius:20px;box-shadow:0 4px 16px rgba(0,0,0,0.07);">
                    <h5 style="font-weight:700;color:var(--green);margin-bottom:20px;font-family:'Space Grotesk',sans-serif;">
                        <i class="bi bi-credit-card me-2" style="color:var(--orange);"></i>Payment Methods
                    </h5>
                    <div class="payment-grid">
                        <?php
                        $payments = [
                            ['💳', 'UPI (GPay)'],
                            ['📱', 'PhonePe'],
                            ['🏦', 'Paytm'],
                            ['🏧', 'Bank Transfer'],
                            ['💵', 'Cash (Local)'],
                        ];
                        foreach ($payments as $p): ?>
                            <div class="payment-card">
                                <div class="payment-icon"><?= $p[0] ?></div>
                                <div class="payment-name"><?= $p[1] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-3 mb-0" style="font-size:0.82rem;color:#94a3b8;text-align:center;">
                        No payment gateway fees. You pay only for your order.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="mt-5 p-5 rounded-4 text-center" style="background:linear-gradient(135deg,var(--green),#003d63);color:white;">
            <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.8rem;margin-bottom:16px;">Ready to Place Your First Order?</h3>
            <p style="color:rgba(255,255,255,0.85);font-size:1rem;margin-bottom:28px;max-width:500px;margin-left:auto;margin-right:auto;">
                Browse our products or chat with us on WhatsApp for a free consultation and quote.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="../products/" style="background:var(--orange);color:white;padding:14px 32px;border-radius:50px;font-weight:700;text-decoration:none;">
                    <i class="bi bi-grid me-2"></i>Browse Products
                </a>
                <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank"
                    style="background:#25d366;color:white;padding:14px 32px;border-radius:50px;font-weight:700;text-decoration:none;">
                    <i class="bi bi-whatsapp me-2"></i>Get Free Quote
                </a>
            </div>
        </div>

    </div>
</section>

<?php include('../inc/footer.php'); ?>