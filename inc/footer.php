<?php
// Determine base path for footer links
$dir_parts = explode('/', trim(dirname($_SERVER['PHP_SELF']), '/'));
$root_offset = '';
if (count($dir_parts) > 1) $root_offset = '../';
if (count($dir_parts) > 2) $root_offset = '../../';
?>
<footer class="footer">
    <div class="container">

        <!-- TOP -->
        <div class="footer-top d-flex justify-content-between align-items-center flex-wrap">

            <div>
                <div class="logo">Unique Studio</div>
                <div class="mt-2">
                    Copyrights © <?= date('Y') ?> <span style="color:#ff6a3d;">Unique Studio.</span>
                    All Rights Reserved.
                </div>
                <div class="mt-2" style="font-size:0.85rem;color:#8a8a8a;">
                    Premium Printing Services | Indore, India
                </div>
            </div>

            <div class="text-end">
                <div class="mb-2">Follow On</div>

                <span class="social-circle"><i class="bi bi-facebook"></i></span>
                <span class="social-circle"><i class="bi bi-twitter"></i></span>
                <span class="social-circle"><i class="bi bi-instagram"></i></span>
                <span class="social-circle"><i class="bi bi-youtube"></i></span>
                <span class="social-circle"><i class="bi bi-whatsapp"></i></span>

                <div class="mt-3">
                    <a href="<?= $root_offset ?>pages/about.php">About</a> •
                    <a href="<?= $root_offset ?>pages/contact.php">Contact</a> •
                    <a href="<?= $root_offset ?>pages/faq.php">FAQ</a> •
                    <a href="<?= $root_offset ?>pages/how-it-works.php">How It Works</a>
                </div>
            </div>

        </div>


        <!-- MAIN GRID -->
        <div class="row">

            <!-- Newsletter -->
            <div class="col-lg-4 mb-4">
                <div class="news-icon">
                    <i class="bi bi-envelope"></i>
                </div>

                <h5>Newsletter</h5>

                <p>
                    Subscribe to get our latest offers, new product launches,
                    and printing tips delivered to your inbox.
                </p>

                <div style="display:flex;gap:8px;margin-top:12px;">
                    <input type="email" placeholder="your@email.com"
                        style="flex:1;padding:10px 14px;border-radius:25px;border:1px solid rgba(255,255,255,0.15);background:rgba(255,255,255,0.08);color:white;font-size:0.88rem;outline:none;">
                    <button style="background:var(--orange);color:white;border:none;padding:10px 18px;border-radius:25px;font-weight:600;cursor:pointer;">
                        Subscribe
                    </button>
                </div>

                <div class="text-white mt-2" style="font-size:0.78rem;color:rgba(255,255,255,0.5)!important;">
                    No spam, ever. Unsubscribe anytime.
                </div>
            </div>


            <!-- Company -->
            <div class="col-lg-2 mb-4">
                <h5>Company</h5>

                <ul class="footer-list p-0">
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>pages/about.php" style="color:#bdbdbd;text-decoration:none;">About Us</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>pages/how-it-works.php" style="color:#bdbdbd;text-decoration:none;">How It Works</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>pages/faq.php" style="color:#bdbdbd;text-decoration:none;">FAQ</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>pages/contact.php" style="color:#bdbdbd;text-decoration:none;">Contact Us</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/" style="color:#bdbdbd;text-decoration:none;">Shop Products</a></li>
                </ul>
            </div>


            <!-- Services / Products -->
            <div class="col-lg-3 mb-4">
                <h5>Our Products</h5>

                <ul class="footer-list p-0">
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/index.php?category=1" style="color:#bdbdbd;text-decoration:none;">Business Cards</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/index.php?category=2" style="color:#bdbdbd;text-decoration:none;">Photo Gifts</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/index.php?category=3" style="color:#bdbdbd;text-decoration:none;">Custom Apparel</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/index.php?category=4" style="color:#bdbdbd;text-decoration:none;">Marketing Materials</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/index.php?category=7" style="color:#bdbdbd;text-decoration:none;">Cup Printing</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/index.php?category=8" style="color:#bdbdbd;text-decoration:none;">Wall Display / Canvas</a></li>
                    <li><i class="bi bi-check-square"></i><a href="<?= $root_offset ?>products/" style="color:#ff6a3d;text-decoration:none;font-weight:600;">View All Products →</a></li>
                </ul>
            </div>


            <!-- Promotion / Contact Card -->
            <div class="col-lg-3 mb-4">
                <div class="promo-card">
                    <div style="font-size:13px;font-weight:600;">ORDER VIA WHATSAPP</div>

                    <h3 class="mt-2">
                        Get Your Print Order in 5-7 Business Days!
                    </h3>

                    <p style="font-size:0.88rem;opacity:0.9;">Fast delivery across India. Order online and we'll confirm via WhatsApp.</p>

                    <a href="https://wa.me/919876543210" target="_blank" class="promo-btn text-decoration-none d-inline-flex align-items-center gap-2">
                        <i class="bi bi-whatsapp"></i>ORDER NOW
                    </a>
                </div>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div style="border-top:1px dashed rgba(255,255,255,0.08);padding-top:20px;margin-top:20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:0.82rem;color:#8a8a8a;">
            <span>Made with <span style="color:#ff6a3d;">♥</span> in Indore, India</span>
            <span>
                <a href="#" style="color:#8a8a8a;text-decoration:none;margin:0 10px;">Privacy Policy</a> |
                <a href="#" style="color:#8a8a8a;text-decoration:none;margin:0 10px;">Terms & Conditions</a>
            </span>
        </div>
    </div>
</footer>
</body>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</html>