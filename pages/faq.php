<?php
$page = 'FAQ';
require_once '../includes/functions.php';
include('../inc/header.php');
?>

<style>
    .faq-hero {
        background: linear-gradient(135deg, var(--green), #003d63);
        padding: 80px 0;
        color: white;
    }

    .faq-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .faq-section {
        padding: 80px 0;
        background: #f8fafc;
    }

    .faq-category-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--green);
        margin: 40px 0 20px;
        font-family: 'Space Grotesk', sans-serif;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .faq-category-title::before {
        content: '';
        width: 4px;
        height: 26px;
        background: var(--orange);
        border-radius: 2px;
        display: block;
    }

    .accordion-button {
        font-weight: 600;
        color: var(--green) !important;
        background: white !important;
        border: none;
        font-size: 1rem;
    }

    .accordion-button:not(.collapsed) {
        color: var(--orange) !important;
        box-shadow: none;
        background: #fff5f0 !important;
    }

    .accordion-button::after {
        filter: none;
    }

    .accordion-item {
        border: 1.5px solid #f1f5f9;
        border-radius: 12px !important;
        margin-bottom: 10px;
        overflow: hidden;
    }

    .accordion-button:focus {
        box-shadow: none;
    }

    .accordion-body {
        color: #475569;
        line-height: 1.8;
        font-size: 0.95rem;
    }

    .faq-sidebar-card {
        background: linear-gradient(135deg, var(--orange), #ff8c5f);
        border-radius: 20px;
        padding: 32px;
        color: white;
        text-align: center;
        position: sticky;
        top: 90px;
    }

    .faq-sidebar-card h4 {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
    }

    .faq-wa-btn {
        background: #25d366;
        color: white;
        padding: 14px 28px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        margin-top: 16px;
        transition: all 0.3s;
    }

    .faq-wa-btn:hover {
        background: #20ba58;
        color: white;
        transform: scale(1.05);
    }
</style>

<section class="faq-hero py-50">
    <div class="container-fluid">
        <nav class="mb-4" style="font-size:0.88rem;">
            <a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a>
            <span class="mx-2" style="color:rgba(255,255,255,0.5);">/</span>
            <span style="color:white;">FAQ</span>
        </nav>
        <h1>Frequently Asked Questions</h1>
        <p style="color:rgba(255,255,255,0.8);margin-top:12px;font-size:1.05rem;max-width:600px;">
            Got questions? We've got answers. Can't find what you need? Just WhatsApp us!
        </p>
    </div>
</section>

<section class="faq-section">
    <div class="container py-50">
        <div class="row g-5">
            <!-- FAQs -->
            <div class="col-lg-8">

                <!-- How to Order -->
                <h3 class="faq-category-title"><i class="bi bi-cart" style="color:var(--orange);"></i> How to Order</h3>
                <div class="accordion" id="orderFaq">
                    <?php
                    $order_faqs = [
                        ['How do I place an order?', 'Browse our products, select your options, add to cart, and proceed to checkout. Fill in your details and submit — we\'ll WhatsApp you for payment and confirmation.'],
                        ['Do I need to create an account?', 'No account needed! Simply browse, add to cart, and checkout as a guest. We keep it simple.'],
                        ['Can I place a bulk order?', 'Absolutely! We specialize in bulk printing. Contact us on WhatsApp for special bulk pricing on orders of 500+ units.'],
                        ['How do I share my design file?', 'You can upload your design during checkout or share it via WhatsApp after placing your order. We accept JPG, PNG, PDF, AI, and PSD formats.'],
                        ['What if I don\'t have a design?', 'No problem! Our design team can create a design for you. Just describe what you need on WhatsApp and we\'ll quote a design fee.'],
                    ];
                    foreach ($order_faqs as $i => $faq): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#orderFaq<?= $i ?>">
                                    <?= $faq[0] ?>
                                </button>
                            </h2>
                            <div id="orderFaq<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                                data-bs-parent="#orderFaq">
                                <div class="accordion-body"><?= $faq[1] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Payment -->
                <h3 class="faq-category-title mt-4"><i class="bi bi-credit-card" style="color:var(--orange);"></i> Payment</h3>
                <div class="accordion" id="paymentFaq">
                    <?php
                    $payment_faqs = [
                        ['How do I pay for my order?', 'After order review, our team will send payment details via WhatsApp. We accept UPI (Google Pay, PhonePe, Paytm), bank transfer, and cash on delivery for local orders.'],
                        ['Is there a payment gateway on the website?', 'No. We process payments manually via WhatsApp. This keeps things simple and allows us to offer competitive pricing without gateway fees.'],
                        ['When do I need to pay?', 'After our team confirms your order and sends payment details. No payment is required when placing the order online.'],
                        ['What if I\'m not satisfied and want a refund?', 'We offer a satisfaction guarantee. If there\'s a quality issue, we\'ll redo the order or refund depending on the situation. Contact us within 3 days of delivery.'],
                    ];
                    foreach ($payment_faqs as $i => $faq): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#payFaq<?= $i ?>">
                                    <?= $faq[0] ?>
                                </button>
                            </h2>
                            <div id="payFaq<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#paymentFaq">
                                <div class="accordion-body"><?= $faq[1] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Delivery -->
                <h3 class="faq-category-title mt-4"><i class="bi bi-truck" style="color:var(--orange);"></i> Delivery & Shipping</h3>
                <div class="accordion" id="deliveryFaq">
                    <?php
                    $delivery_faqs = [
                        ['How long does delivery take?', 'Most orders are delivered within 5-7 business days after payment confirmation. Local Indore orders may arrive in 1-2 days.'],
                        ['Do you deliver pan India?', 'Yes! We deliver across India through trusted courier services. International orders are available on request.'],
                        ['What are the delivery charges?', 'Free delivery on orders above ₹2,000. Standard delivery charge of ₹100 for orders below this amount.'],
                        ['Can I track my order?', 'Yes! Once dispatched, we\'ll share tracking details on WhatsApp so you can monitor your delivery.'],
                        ['What if my order arrives damaged?', 'Please photograph the damage immediately and share it on WhatsApp. We\'ll arrange for a replacement or refund promptly.'],
                    ];
                    foreach ($delivery_faqs as $i => $faq): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#delFaq<?= $i ?>">
                                    <?= $faq[0] ?>
                                </button>
                            </h2>
                            <div id="delFaq<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#deliveryFaq">
                                <div class="accordion-body"><?= $faq[1] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Print Quality -->
                <h3 class="faq-category-title mt-4"><i class="bi bi-palette" style="color:var(--orange);"></i> Print Quality</h3>
                <div class="accordion" id="qualityFaq">
                    <?php
                    $quality_faqs = [
                        ['What file format should I use?', 'For best results, provide files in PDF, AI, or PSD format at 300 DPI or higher. CMYK color mode is preferred. JPG and PNG also work but quality may vary.'],
                        ['What DPI should my design be?', 'Minimum 300 DPI for crisp printing. Lower DPI files may appear blurry on the final print.'],
                        ['Do colors look the same as on screen?', 'Printed colors may vary slightly from screen colors as monitors use RGB and printers use CMYK. We recommend ordering a sample for color-critical jobs.'],
                        ['Can I request a proof before mass printing?', 'Yes! For large orders, we can provide a digital proof. Physical samples may have an additional charge.'],
                    ];
                    foreach ($quality_faqs as $i => $faq): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#qualFaq<?= $i ?>">
                                    <?= $faq[0] ?>
                                </button>
                            </h2>
                            <div id="qualFaq<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#qualityFaq">
                                <div class="accordion-body"><?= $faq[1] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="faq-sidebar-card">
                    <i class="bi bi-whatsapp fs-1 mb-3 d-block"></i>
                    <h4>Still Have Questions?</h4>
                    <p style="opacity:0.9;font-size:0.92rem;line-height:1.7;margin-top:10px;">
                        Our team is available on WhatsApp to answer any question instantly. Mon-Sat, 9 AM to 8 PM.
                    </p>
                    <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank" class="faq-wa-btn">
                        <i class="bi bi-whatsapp me-2"></i>Chat on WhatsApp
                    </a>
                    <a href="contact.php" class="d-block mt-3" style="color:rgba(255,255,255,0.8);font-size:0.88rem;">
                        Or send us an email →
                    </a>
                </div>

                <!-- Quick Links -->
                <div class="mt-4 p-4" style="background:white;border-radius:20px;box-shadow:0 4px 16px rgba(0,0,0,0.06);">
                    <h5 style="font-weight:700;color:var(--green);margin-bottom:16px;">Quick Links</h5>
                    <?php
                    $links = [
                        ['bi-grid', 'Browse All Products', '../products/'],
                        ['bi-cart', 'View Cart', '../cart/view-cart.php'],
                        ['bi-info-circle', 'How It Works', 'how-it-works.php'],
                        ['bi-telephone', 'Contact Us', 'contact.php'],
                    ];
                    foreach ($links as $link): ?>
                        <a href="<?= $link[2] ?>" class="d-flex align-items-center gap-3 p-2 text-decoration-none mb-2 rounded-3"
                            style="color:#475569;transition:all 0.2s;"
                            onmouseover="this.style.background='#fff5f0';this.style.color='var(--orange)'"
                            onmouseout="this.style.background='transparent';this.style.color='#475569'">
                            <i class="bi <?= $link[0] ?>" style="color:var(--orange);font-size:1.1rem;"></i>
                            <span style="font-weight:500;"><?= $link[1] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('../inc/footer.php'); ?>