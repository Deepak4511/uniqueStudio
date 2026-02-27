<?php
$page = 'Products';
require_once '../includes/functions.php';

// Get category by slug
$slug = sanitize($_GET['slug'] ?? '');
$category = null;

if (!empty($slug)) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ? AND status = 'active'");
        $stmt->execute([$slug]);
        $category = $stmt->fetch();
    } catch (Exception $e) {
        $category = null;
    }
}

if (!$category) {
    // Redirect to all products
    header('Location: index.php');
    exit;
}

// Pagination
$current_page = (int)($_GET['page'] ?? 1);
$per_page = 12;
$offset = ($current_page - 1) * $per_page;

$products = getProductsByCategory($category['id'], $per_page, $offset);

// Count products in this category
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ? AND status = 'active'");
    $stmt->execute([$category['id']]);
    $total = (int)$stmt->fetchColumn();
} catch (Exception $e) {
    $total = 0;
}

$total_pages = ceil($total / $per_page);
$categories = getCategories();

include('../inc/header.php');
?>

<style>
    .category-hero {
        padding: 70px 0;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .category-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0, 78, 124, 0.92), rgba(0, 30, 60, 0.95));
        z-index: 1;
    }

    .category-hero-content {
        position: relative;
        z-index: 2;
    }

    .category-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.4rem;
        font-weight: 700;
    }

    .products-grid-section {
        padding: 60px 0;
        background: #f8fafc;
    }

    .product-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12);
        border-color: var(--orange);
    }

    .product-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .product-card:hover .product-img {
        transform: scale(1.05);
    }

    .product-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .product-desc {
        font-size: 0.85rem;
        color: #64748b;
        flex: 1;
        margin-bottom: 14px;
        line-height: 1.5;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--orange);
    }

    .btn-view {
        background: var(--green);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 10px;
    }

    .btn-view:hover {
        background: var(--orange);
        color: white;
    }

    .sidebar-cat-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        padding: 22px;
        margin-bottom: 20px;
    }

    .sidebar-cat-link {
        display: block;
        padding: 8px 12px;
        border-radius: 8px;
        color: #475569;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 2px;
    }

    .sidebar-cat-link:hover,
    .sidebar-cat-link.active {
        background: #fff5f0;
        color: var(--orange);
        font-weight: 600;
    }

    .page-btn {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #475569;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        margin: 0 2px;
    }

    .page-btn:hover,
    .page-btn.active {
        background: var(--orange);
        border-color: var(--orange);
        color: white;
    }
</style>

<!-- Hero -->
<section class="category-hero py-50" style="background-image: url('https://images.unsplash.com/photo-1562887009-b707ed5baa21?w=1400&q=80'); background-size: cover; background-position: center;">
    <div class="container-fluid category-hero-content">
        <nav class="mb-4" style="font-size:0.88rem;">
            <a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a>
            <span class="mx-2" style="color:rgba(255,255,255,0.5);">/</span>
            <a href="index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Products</a>
            <span class="mx-2" style="color:rgba(255,255,255,0.5);">/</span>
            <span style="color:white;"><?= htmlspecialchars($category['name']) ?></span>
        </nav>
        <h1><?= htmlspecialchars($category['name']) ?></h1>
        <?php if (!empty($category['description'])): ?>
            <p style="color:rgba(255,255,255,0.85);max-width:600px;margin-top:12px;font-size:1rem;line-height:1.7;">
                <?= htmlspecialchars($category['description']) ?>
            </p>
        <?php endif; ?>
        <div class="mt-3">
            <span style="background:rgba(255,107,53,0.9);color:white;padding:6px 16px;border-radius:20px;font-size:0.85rem;font-weight:600;">
                <?= $total ?> product<?= $total !== 1 ? 's' : '' ?> available
            </span>
        </div>
    </div>
</section>

<!-- Products -->
<section class="products-grid-section">
    <div class="container-fluid py-50">
        <div class="row g-4">

            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-2">
                <div class="sidebar-cat-card">
                    <h5 style="font-weight:700;color:var(--green);margin-bottom:16px;font-size:1rem;">All Categories</h5>
                    <a href="index.php" class="sidebar-cat-link">
                        <i class="bi bi-grid me-2" style="color:var(--orange);"></i>All Products
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="category.php?slug=<?= urlencode($cat['slug']) ?>"
                            class="sidebar-cat-link <?= $cat['id'] == $category['id'] ? 'active' : '' ?>">
                            <i class="bi bi-chevron-right me-2" style="color:var(--orange);font-size:0.7rem;"></i>
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- WhatsApp Promo -->
                <div class="sidebar-cat-card" style="background:linear-gradient(135deg,#dcfce7,#f0fdf4);border:1px dashed #86efac;">
                    <div class="text-center">
                        <i class="bi bi-whatsapp" style="color:#25d366;font-size:2rem;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:0.82rem;color:#166534;margin-bottom:12px;font-weight:500;">
                            Need bulk pricing or custom quotation?
                        </p>
                        <a href="https://wa.me/919876543210" target="_blank"
                            class="btn btn-sm w-100" style="background:#25d366;color:white;border-radius:20px;">
                            Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9 col-xl-10">
                <?php if (empty($products)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size:3rem;color:#e2e8f0;display:block;margin-bottom:16px;"></i>
                        <h4 style="color:#475569;">No products in this category yet</h4>
                        <p style="color:#94a3b8;">Check back soon or browse other categories</p>
                        <a href="index.php" class="btn btn-orange rounded-pill px-4 mt-2">Browse All Products</a>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-4">
                        <?php foreach ($products as $product): ?>
                            <div class="col">
                                <div class="product-card">
                                    <div style="overflow:hidden;position:relative;">
                                        <img src="<?= !empty($product['image_main']) ? '../' . htmlspecialchars($product['image_main']) : 'https://via.placeholder.com/400x220/ff6b35/ffffff?text=' . urlencode(substr($product['name'], 0, 14)) ?>"
                                            class="product-img"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                        <?php if ($product['featured'] == 'yes'): ?>
                                            <span style="position:absolute;top:12px;left:12px;background:var(--orange);color:white;font-size:0.72rem;font-weight:700;padding:4px 10px;border-radius:20px;">⭐ Featured</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-body">
                                        <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                        <p class="product-desc"><?= htmlspecialchars(truncate($product['short_description'], 80)) ?></p>
                                        <div>
                                            <span style="font-size:0.78rem;color:#94a3b8;">Starting from</span>
                                            <div class="product-price"><?= formatPrice($product['base_price']) ?></div>
                                        </div>
                                        <a href="product-detail.php?slug=<?= urlencode($product['slug']) ?>" class="btn-view">
                                            <i class="bi bi-eye me-1"></i>View & Order
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="d-flex justify-content-center align-items-center flex-wrap gap-1 mt-5">
                            <?php if ($current_page > 1): ?>
                                <a href="?slug=<?= urlencode($slug) ?>&page=<?= $current_page - 1 ?>" class="page-btn">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            <?php endif; ?>
                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <a href="?slug=<?= urlencode($slug) ?>&page=<?= $p ?>"
                                    class="page-btn <?= $p === $current_page ? 'active' : '' ?>"><?= $p ?></a>
                            <?php endfor; ?>
                            <?php if ($current_page < $total_pages): ?>
                                <a href="?slug=<?= urlencode($slug) ?>&page=<?= $current_page + 1 ?>" class="page-btn">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('../inc/footer.php'); ?>