<?php
$page = 'Contact';
require_once '../includes/functions.php';

$success = false;
$error   = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = sanitize($_POST['email'] ?? '');
    $phone   = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!isValidEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        $result = saveContactInquiry(['name' => $name, 'email' => $email, 'phone' => $phone, 'subject' => $subject, 'message' => $message]);
        if ($result) {
            $success = true;
        } else {
            $error = 'Something went wrong. Please try again or contact us directly via WhatsApp.';
        }
    }
}

include('../inc/header.php');
?>

<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--green) 0%, #003d63 100%);
        padding: 80px 0;
        color: white;
    }

    .contact-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .contact-section {
        padding: 80px 0;
        background: #f8fafc;
    }

    .contact-form-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
        padding: 40px;
    }

    .contact-info-card {
        background: linear-gradient(135deg, var(--green), #003d63);
        border-radius: 24px;
        padding: 40px;
        color: white;
        height: 100%;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 28px;
    }

    .info-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .info-text h6 {
        font-weight: 700;
        margin-bottom: 4px;
        color: white;
    }

    .info-text p {
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        font-size: 0.9rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.88rem;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 11px 16px;
        font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.12);
    }

    .submit-btn {
        background: linear-gradient(135deg, var(--orange), #ff8c5f);
        color: white;
        border: none;
        padding: 14px 36px;
        border-radius: 50px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4);
    }

    .wa-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }

    .wa-float a {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #25d366;
        color: white;
        padding: 14px 22px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
        transition: all 0.3s;
    }

    .wa-float a:hover {
        background: #20ba58;
        transform: scale(1.05);
        color: white;
    }
</style>

<!-- Hero -->
<section class="contact-hero py-50">
    <div class="container-fluid">
        <nav class="mb-4" style="font-size:0.88rem;">
            <a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a>
            <span class="mx-2" style="color:rgba(255,255,255,0.5);">/</span>
            <span style="color:white;">Contact Us</span>
        </nav>
        <h1>Get In Touch</h1>
        <p style="color:rgba(255,255,255,0.8);margin-top:12px;font-size:1.05rem;">
            Have questions? We'd love to hear from you. Send us a message or chat on WhatsApp!
        </p>
    </div>
</section>

<section class="contact-section">
    <div class="container py-50">
        <div class="row g-5">

            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.6rem;margin-bottom:32px;">Contact Information</h3>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-whatsapp"></i></div>
                        <div class="info-text">
                            <h6>WhatsApp (Preferred)</h6>
                            <p>+91 <?= WA_NUMBER ?><br><small>Mon-Sat, 9am - 8pm</small></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-telephone"></i></div>
                        <div class="info-text">
                            <h6>Phone</h6>
                            <p>+91 <?= WA_NUMBER ?><br><small>For urgent queries</small></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-envelope"></i></div>
                        <div class="info-text">
                            <h6>Email</h6>
                            <p><?= SITE_EMAIL ?><br><small>We reply within 24 hours</small></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
                        <div class="info-text">
                            <h6>Located In</h6>
                            <p>Indore, Madhya Pradesh<br>India - 452001</p>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.2);">
                        <p style="font-size:0.88rem;color:rgba(255,255,255,0.7);">Follow Us:</p>
                        <div class="d-flex gap-3">
                            <?php foreach (['instagram', 'facebook', 'twitter', 'youtube'] as $social): ?>
                                <a href="#" style="width:40px;height:40px;background:rgba(255,255,255,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;text-decoration:none;transition:all 0.2s;border:1px solid rgba(255,255,255,0.2);"
                                    onmouseover="this.style.background='rgba(255,107,53,0.5)'"
                                    onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                                    <i class="bi bi-<?= $social ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--green);margin-bottom:8px;">Send Us a Message</h3>
                    <p style="color:#64748b;margin-bottom:28px;">Fill out the form and we'll get back to you within 24 hours.</p>

                    <?php if ($success): ?>
                        <div class="alert" style="background:#dcfce7;color:#166534;border:none;border-radius:12px;padding:16px 20px;margin-bottom:20px;">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Message sent successfully!</strong> We'll get back to you within 24 hours.
                            <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank" style="color:#166534;margin-left:10px;">Or chat on WhatsApp for faster response →</a>
                        </div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert" style="background:#fef2f2;color:#dc2626;border:none;border-radius:12px;padding:16px 20px;margin-bottom:20px;">
                            <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Your Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+91 9876543210" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <select name="subject" class="form-select">
                                    <option value="">Select Subject</option>
                                    <option>General Inquiry</option>
                                    <option>Product Information</option>
                                    <option>Order Status</option>
                                    <option>Custom Printing Request</option>
                                    <option>Pricing Query</option>
                                    <option>Complaint / Feedback</option>
                                    <option>Partnership Inquiry</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message *</label>
                                <textarea name="message" class="form-control" rows="5"
                                    placeholder="Tell us about your printing requirements, order details, or any questions..."
                                    required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <div class="d-flex gap-3 align-items-center flex-wrap">
                                    <button type="submit" class="submit-btn">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                    <span style="color:#94a3b8;font-size:0.85rem;">or</span>
                                    <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank"
                                        class="d-inline-flex align-items-center gap-2"
                                        style="background:#25d366;color:white;padding:14px 24px;border-radius:50px;font-weight:700;text-decoration:none;">
                                        <i class="bi bi-whatsapp"></i>WhatsApp Us
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Map placeholder -->
                <div class="contact-form-card mt-4 p-0" style="overflow:hidden;">
                    <div style="background:linear-gradient(135deg,#f1f5f9,#e2e8f0);height:250px;display:flex;align-items:center;justify-content:center;border-radius:24px;">
                        <div class="text-center">
                            <i class="bi bi-map" style="font-size:3rem;color:#94a3b8;margin-bottom:12px;display:block;"></i>
                            <p style="color:#64748b;margin:0;">Indore, Madhya Pradesh, India</p>
                            <a href="https://maps.google.com/?q=Indore,Madhya+Pradesh,India" target="_blank"
                                class="btn btn-sm mt-2" style="background:var(--orange);color:white;border-radius:20px;">
                                <i class="bi bi-geo-alt me-1"></i>View on Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating WhatsApp Button -->
<div class="wa-float">
    <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank">
        <i class="bi bi-whatsapp fs-4"></i>
        <span>Chat Now</span>
    </a>
</div>

<?php include('../inc/footer.php'); ?>