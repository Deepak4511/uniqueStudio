<?php
$page = 'About';
require_once '../includes/functions.php';
include('../inc/header.php');
?>

<style>
    .about-hero {
        background: linear-gradient(135deg, var(--green) 0%, #003d63 100%);
        padding: 80px 0;
        color: white;
        text-align: center;
    }

    .about-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.8rem;
        font-weight: 700;
    }

    .about-hero p {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        margin: 0 auto;
    }

    .about-section {
        padding: 80px 0;
    }

    .about-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        padding: 40px;
        height: 100%;
        transition: transform 0.3s;
    }

    .about-card:hover {
        transform: translateY(-8px);
    }

    .about-icon {
        width: 70px;
        height: 70px;
        background: #fff5f0;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--orange);
        margin-bottom: 20px;
    }

    .about-heading {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 14px;
    }

    .stat-box {
        background: linear-gradient(135deg, var(--orange), #ff8c5f);
        color: white;
        border-radius: 18px;
        padding: 30px;
        text-align: center;
    }

    .stat-num {
        font-size: 2.8rem;
        font-weight: 800;
        font-family: 'Space Grotesk', sans-serif;
    }

    .stat-text {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .value-item {
        display: flex;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .value-icon {
        width: 48px;
        height: 48px;
        background: #fff5f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange);
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .value-text h6 {
        font-weight: 700;
        color: var(--green);
        margin-bottom: 4px;
    }

    .value-text p {
        font-size: 0.88rem;
        color: #64748b;
        margin: 0;
    }
</style>

<!-- Hero -->
<section class="about-hero py-50">
    <div class="container-fluid">
        <nav class="mb-4" style="opacity:0.7;font-size:0.88rem;">
            <a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a>
            <span class="mx-2">/</span>
            <span>About Us</span>
        </nav>
        <h1>About Unique Studio</h1>
        <p class="mt-3">India's trusted printing partner — bringing your ideas to life with premium quality and fast turnaround</p>
    </div>
</section>

<!-- Our Story -->
<section class="about-section" style="background:#f8fafc;">
    <div class="container py-50">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="d-inline-block px-3 py-1 rounded-pill mb-3" style="background:#fff5f0;color:var(--orange);font-size:0.85rem;font-weight:700;">OUR STORY</span>
                <h2 class="about-heading" style="font-size:2.2rem;">We Turn Your Vision<br>Into Reality</h2>
                <p style="color:#64748b;line-height:1.8;margin-bottom:20px;">
                    Unique Studio was born from a simple idea: every business deserves access to high-quality, affordable printing without the hassle. Founded in Indore, we've been helping businesses, creators, and individuals bring their ideas to life through exceptional printing services.
                </p>
                <p style="color:#64748b;line-height:1.8;margin-bottom:28px;">
                    From a single photo mug to thousands of business cards — we handle every order with the same passion and attention to detail. Our WhatsApp-first approach ensures you always have a real person to talk to, not a chatbot.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="stat-num">500+</div>
                            <div class="stat-text">Happy Clients</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box" style="background:linear-gradient(135deg, var(--green), #003d63);">
                            <div class="stat-num">5K+</div>
                            <div class="stat-text">Orders Completed</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box" style="background:linear-gradient(135deg, #8b5cf6, #6d28d9);">
                            <div class="stat-num">50+</div>
                            <div class="stat-text">Product Types</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box" style="background:linear-gradient(135deg, #16a34a, #15803d);">
                            <div class="stat-num">99%</div>
                            <div class="stat-text">Satisfaction Rate</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div style="background:linear-gradient(135deg,#fff5f0,#f8fafc);border-radius:28px;padding:40px;">
                    <h4 style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--green);margin-bottom:30px;">Our Core Values</h4>
                    <?php
                    $values = [
                        ['bi-gem', 'Premium Quality', 'We never compromise on print quality. Every product meets our strict standards before delivery.'],
                        ['bi-lightning-charge', 'Fast Turnaround', 'Most orders completed within 5-7 business days. Express options available.'],
                        ['bi-headset', 'Personal Service', 'Real humans via WhatsApp — not bots. We\'re with you from order to delivery.'],
                        ['bi-shield-check', 'Satisfaction Guaranteed', 'Not happy? We\'ll redo it or refund it. Your satisfaction is our promise.'],
                    ];
                    foreach ($values as $v): ?>
                        <div class="value-item">
                            <div class="value-icon"><i class="bi <?= $v[0] ?>"></i></div>
                            <div class="value-text">
                                <h6><?= $v[1] ?></h6>
                                <p><?= $v[2] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do -->
<section class="about-section" style="background:white;">
    <div class="container py-50">
        <div class="text-center mb-50">
            <span class="d-inline-block px-3 py-1 rounded-pill mb-3" style="background:#fff5f0;color:var(--orange);font-size:0.85rem;font-weight:700;">WHAT WE DO</span>
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;color:var(--green);">Complete Printing Solutions</h2>
        </div>
        <div class="row g-4">
            <?php
            $services = [
                ['bi-card-text', 'Business Cards', 'Premium business cards that make a lasting first impression. Standard, premium, transparent, and specialty options.'],
                ['bi-gift', 'Photo Gifts', 'Personalized photo gifts for every occasion — mugs, cushions, canvas prints, frames, and much more.'],
                ['bi-bag', 'Custom Apparel', 'T-shirts, hoodies, caps, and more with your custom design or logo. Perfect for teams, events, and promotions.'],
                ['bi-megaphone', 'Marketing Materials', 'Flyers, brochures, banners, and stickers that make your brand stand out from the competition.'],
                ['bi-journal-text', 'Stationery', 'Professional letterheads, notepads, calendars, and other office stationery for your business.'],
                ['bi-image', 'Wall Displays', 'Canvas prints, acrylic prints, and wall art that transform your photos into stunning displays.'],
            ];
            foreach ($services as $s): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="about-card">
                        <div class="about-icon"><i class="bi <?= $s[0] ?>"></i></div>
                        <h5 class="about-heading" style="font-size:1.2rem;"><?= $s[1] ?></h5>
                        <p style="color:#64748b;line-height:1.7;margin:0;"><?= $s[2] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="background:linear-gradient(135deg,var(--green),#003d63);padding:80px 0;text-align:center;color:white;">
    <div class="container py-50">
        <h2 style="font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;margin-bottom:16px;">Ready to Start Your Project?</h2>
        <p style="color:rgba(255,255,255,0.8);font-size:1.05rem;margin-bottom:32px;">Chat with us on WhatsApp for a free quote and instant assistance</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank"
                class="d-inline-flex align-items-center gap-2"
                style="background:#25d366;color:white;padding:16px 32px;border-radius:50px;font-weight:700;text-decoration:none;transition:all 0.3s;">
                <i class="bi bi-whatsapp fs-5"></i> Chat on WhatsApp
            </a>
            <a href="../products/"
                style="background:rgba(255,255,255,0.15);color:white;padding:16px 32px;border-radius:50px;font-weight:700;text-decoration:none;border:2px solid rgba(255,255,255,0.3);">
                <i class="bi bi-grid me-2"></i>Browse Products
            </a>
        </div>
    </div>
</section>

<?php include('../inc/footer.php'); ?>