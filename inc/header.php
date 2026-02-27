<?php
// Initialize session and load functions for cart count
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include functions only if available (on pages that haven't already loaded it)
if (!function_exists('getCartCount')) {
    $inc_dir = __DIR__;
    // Try relative from /inc/ folder or from root
    if (file_exists($inc_dir . '/../includes/functions.php')) {
        require_once $inc_dir . '/../includes/functions.php';
    } elseif (file_exists($inc_dir . '/includes/functions.php')) {
        require_once $inc_dir . '/includes/functions.php';
    }
}
$cart_count = function_exists('getCartCount') ? getCartCount() : 0;

// Build reliable site root URL using SITE_URL constant if available, else auto-detect
if (defined('SITE_URL')) {
    $site_root = rtrim(SITE_URL, '/') . '/';
} else {
    $proto     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // Find uniqueStudio folder in path
    $doc_root  = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $script    = str_replace('\\', '/', __DIR__ . '/../');
    $rel       = ltrim(str_replace($doc_root, '', realpath($script)), '/');
    $site_root = $proto . '://' . $host . '/' . $rel . '/';
}
$site_root = str_replace('//', '/', $site_root);

// Relative root offset for href links (relative from current file's directory)
$current_dir  = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
$project_path = '/uniqueStudio';   // adjust if project folder name differs
$depth        = substr_count(ltrim(str_replace($project_path, '', $current_dir), '/'), '/');
$root_offset  = $depth > 0 ? str_repeat('../', $depth) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page) ? htmlspecialchars($page) . ' - ' : '' ?>Unique Studio | Premium Printing Services</title>
    <meta name="description" content="Unique Studio - India's trusted printing partner. Business cards, custom mugs, t-shirts, banners, and more. Order online, pay via WhatsApp.">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="http://localhost/uniqueStudio/assets/css/style.css">

    <style>
        /* Cart Badge in Navbar */
        .cart-nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .cart-badge-count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: var(--orange);
            color: white;
            font-size: 0.68rem;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* WhatsApp Floating Button */
        .wa-floating-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .wa-floating-btn a.wa-main {
            width: 56px;
            height: 56px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
            transition: all 0.3s;
            animation: waPulse 2.5s infinite;
        }

        .wa-floating-btn a.wa-main:hover {
            transform: scale(1.1);
            background: #20ba58;
            animation: none;
        }

        @keyframes waPulse {

            0%,
            100% {
                box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
            }

            50% {
                box-shadow: 0 6px 30px rgba(37, 211, 102, 0.7), 0 0 0 12px rgba(37, 211, 102, 0.1);
            }
        }

        .wa-tooltip {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s;
            pointer-events: none;
        }

        .wa-floating-btn:hover .wa-tooltip {
            opacity: 1;
            transform: translateX(0);
        }
    </style>
</head>

<body>

    <!-- STICKY HEADER -->
    <section class="sticky-header">
        <div class="topbar py-50">
            <div class="container-fluid d-flex justify-content-between align-items-center ">

                <div class="phone-pill">
                    <i class="bi bi-telephone"></i>
                    WhatsApp Orders: <span>+91 98765-43210</span> &nbsp;|&nbsp; Email: <span>orders@uniquestudio.com</span>
                </div>

                <div class="nav_icons">
                    Follow On
                    <i class="bi bi-facebook ms-2"></i>
                    <i class="bi bi-twitter ms-2"></i>
                    <i class="bi bi-instagram ms-2"></i>
                    <i class="bi bi-dribbble ms-2"></i>
                </div>

            </div>
        </div>

        <!-- NAVBAR -->
        <div class="custom-navbar py-50">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-xl site-navbar p-0">
                    <a class="navbar-brand logo-wrapper me-3" href="<?= $root_offset ?>index.php">
                        <span class="logo-text">unique Studio</span>
                    </a>

                    <!-- Mobile: Cart + Toggler -->
                    <div class="d-flex align-items-center gap-2 d-xl-none ms-auto me-2">
                        <a href="<?= $root_offset ?>cart/view-cart.php" class="cart-nav-link" style="color:var(--green);font-size:1.4rem;">
                            <i class="bi bi-cart3"></i>
                            <?php if ($cart_count > 0): ?>
                                <span class="cart-badge-count"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                    </div>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainNavbar">
                        <ul class="navbar-nav mx-auto gap-xl-3">
                            <li class="nav-item">
                                <a class="nav-link <?php echo isset($page) && $page === 'Home' ? 'text-danger' : ''; ?>"
                                    href="<?= $root_offset ?>index.php">HOME</a>
                            </li>
                            <li class="nav-item dropdown modern-dropdown-parent">
                                <a class="nav-link dropdown-toggle custom-dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">CATEGORY</a>
                                <ul class="dropdown-menu modern-dropdown shadow-lg">
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>business-id-cards.php">Business &amp; ID Cards</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>office-stationery.php">Office Stationery</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>college-photo-frames.php">College Photo Frames</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>wall-display.php">Wall Display</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>phone-back-cover.php">Phone Back Cover</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>photo-frames.php">Photo Frames</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>cup-printing.php">Cup Printing</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>accessories-decoration.php">Accessories Decor</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>digital-printing.php">Digital Printing</a></li>
                                    <li>
                                        <hr class="modern-divider">
                                    </li>
                                    <li><a class="modern-dropdown-item dropdown-item text-orange fw-bold" href="<?= $root_offset ?>products/">Explore All Products <i class="bi bi-arrow-right ms-2"></i></a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo isset($page) && $page === 'Products' ? 'text-danger' : ''; ?>"
                                    href="<?= $root_offset ?>products/">SHOP</a>
                            </li>
                            <li class="nav-item dropdown modern-dropdown-parent">
                                <a class="nav-link dropdown-toggle custom-dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">PAGES</a>
                                <ul class="dropdown-menu modern-dropdown shadow-lg">
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>pages/about.php"><i class="bi bi-building me-2"></i>About Us</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>pages/how-it-works.php"><i class="bi bi-list-check me-2"></i>How It Works</a></li>
                                    <li><a class="modern-dropdown-item dropdown-item" href="<?= $root_offset ?>pages/faq.php"><i class="bi bi-question-circle me-2"></i>FAQ</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo isset($page) && $page === 'Contact' ? 'text-danger' : ''; ?>"
                                    href="<?= $root_offset ?>pages/contact.php">CONTACT</a>
                            </li>
                        </ul>

                        <div class="d-flex align-items-center gap-3 mt-3 mt-xl-0">
                            <!-- Search Icon -->
                            <button id="searchToggleBtn" type="button"
                                style="background:none;border:none;color:var(--green);font-size:1.3rem;cursor:pointer;padding:6px;">
                                <i class="bi bi-search"></i>
                            </button>
                            <!-- Desktop Cart Icon -->
                            <a href="<?= $root_offset ?>cart/view-cart.php" class="cart-nav-link d-none d-xl-flex" style="color:var(--green);font-size:1.4rem;">
                                <i class="bi bi-cart3"></i>
                                <?php if ($cart_count > 0): ?>
                                    <span class="cart-badge-count"><?= $cart_count ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="https://wa.me/<?= defined('WA_NUMBER') ? WA_NUMBER : '919876543210' ?>" target="_blank" class="quote-btn text-decoration-none">
                                <i class="bi bi-whatsapp me-1"></i>GET A QUOTE
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </section>

    <!-- Search Overlay -->
    <div id="searchOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:99999;align-items:flex-start;justify-content:center;padding-top:80px;">
        <div style="background:white;border-radius:20px;padding:28px;width:100%;max-width:600px;margin:0 16px;position:relative;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
            <button onclick="closeSearch()" style="position:absolute;top:14px;right:16px;border:none;background:none;font-size:1.3rem;color:#64748b;cursor:pointer;">&times;</button>
            <h5 style="font-weight:700;color:var(--green);margin-bottom:16px;font-size:1rem;">Search Products</h5>
            <form action="<?= $root_offset ?>products/search.php" method="GET">
                <div style="display:flex;gap:8px;">
                    <input id="searchInput" type="text" name="q" autocomplete="off"
                        placeholder="Search for mugs, t-shirts, business cards…"
                        style="flex:1;padding:13px 18px;border:2px solid #e2e8f0;border-radius:12px;font-size:1rem;outline:none;"
                        onfocus="this.style.borderColor='var(--orange)'" onblur="this.style.borderColor='#e2e8f0'">
                    <button type="submit" style="background:var(--orange);color:white;border:none;padding:13px 22px;border-radius:12px;font-weight:700;cursor:pointer;">Search</button>
                </div>
            </form>
            <div style="margin-top:16px;font-size:0.82rem;color:#94a3b8;">Popular: Business Cards · Custom Mugs · T-Shirts · Banners · Photo Frames</div>
        </div>
    </div>

    <script>
        document.getElementById('searchToggleBtn').addEventListener('click', function() {
            var overlay = document.getElementById('searchOverlay');
            overlay.style.display = 'flex';
            setTimeout(function() {
                document.getElementById('searchInput').focus();
            }, 100);
        });

        function closeSearch() {
            document.getElementById('searchOverlay').style.display = 'none';
        }
        document.getElementById('searchOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeSearch();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSearch();
        });
    </script>

    <!-- WhatsApp Floating Button -->
    <div class="wa-floating-btn">
        <div class="d-flex align-items-center gap-2">
            <span class="wa-tooltip">Order on WhatsApp</span>
            <a href="https://wa.me/919876543210?text=Hi!%20I%27m%20interested%20in%20your%20printing%20services." target="_blank" class="wa-main">
                <i class="bi bi-whatsapp"></i>
            </a>
        </div>
    </div>