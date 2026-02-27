<?php
$page = 'Products';
require_once '../includes/functions.php';

// Filters
$search      = sanitize($_GET['search'] ?? '');
$category_id = (int)($_GET['category'] ?? 0);
$current_page = (int)($_GET['page'] ?? 1);
$per_page    = 12;
$offset      = ($current_page - 1) * $per_page;

// Get data
$products    = getAllProducts($per_page, $offset, $search, $category_id);
$total       = countAllProducts($search, $category_id);
$categories  = getCategories();
$total_pages = ceil($total / $per_page);

// Current category name
$current_category_name = 'All Products';
if ($category_id > 0) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $category_id) {
            $current_category_name = $cat['name'];
            break;
        }
    }
}

include('../inc/header.php');
?>

<style>
    .products-hero {
        background: linear-gradient(135deg, var(--green) 0%, #003d63 100%);
        padding: 60px 0;
        color: white;
    }

    .products-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .search-bar-wrapper {
        background: white;
        border-radius: 50px;
        padding: 6px 6px 6px 20px;
        display: flex;
        align-items: center;
        max-width: 500px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .search-bar-input {
        border: none;
        outline: none;
        flex: 1;
        font-size: 0.95rem;
        background: transparent;
        color: #1e293b;
    }

    .search-bar-btn {
        background: var(--orange);
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
    }

    .products-main {
        padding: 60px 0;
        background: #f8fafc;
    }

    /* Filter Sidebar */
    .filter-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        padding: 24px;
        margin-bottom: 20px;
    }

    .filter-heading {
        font-size: 1rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-filter-btn {
        display: block;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        color: #475569;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 2px;
    }

    .cat-filter-btn:hover,
    .cat-filter-btn.active {
        background: #fff5f0;
        color: var(--orange);
        font-weight: 600;
    }

    /* Product Cards */
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

    .product-img-wrapper {
        position: relative;
        overflow: hidden;
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

    .product-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: var(--orange);
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .product-category-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 78, 124, 0.85);
        color: white;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
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
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .product-desc {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.5;
        flex: 1;
        margin-bottom: 14px;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--orange);
    }

    .product-price-label {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 400;
    }

    .btn-view-product {
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
        margin-top: 10px;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .btn-view-product:hover {
        background: var(--orange);
        color: white;
        transform: translateY(-1px);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 40px;
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

    /* No products */
    .no-products {
        text-align: center;
        padding: 60px;
        background: white;
        border-radius: 16px;
    }
</style>

<!-- Hero -->
<section class="products-hero py-50">
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none;padding:0;">
                <li class="breadcrumb-item"><a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a></li>
                <li class="breadcrumb-item active" style="color:white;"><?= htmlspecialchars($current_category_name) ?></li>
            </ol>
        </nav>
        <h1><?= htmlspecialchars($current_category_name) ?></h1>
        <p style="color:rgba(255,255,255,0.8);margin-bottom:24px;">
            <?= $total ?> product<?= $total !== 1 ? 's' : '' ?> found
        </p>
        <!-- Search bar -->
        <form method="GET" action="">
            <?php if ($category_id > 0): ?>
                <input type="hidden" name="category" value="<?= $category_id ?>">
            <?php endif; ?>
            <div class="search-bar-wrapper">
                <i class="bi bi-search me-2" style="color:#94a3b8;"></i>
                <input type="text"
                    name="search"
                    class="search-bar-input"
                    placeholder="Search products..."
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="search-bar-btn">Search</button>
            </div>
        </form>
    </div>
</section>

<!-- Main Products -->
<section class="products-main">
    <div class="container-fluid py-50">
        <div class="row g-4">

            <!-- Sidebar Filters -->
            <div class="col-lg-3 col-xl-2">
                <!-- Categories Filter -->
                <div class="filter-card">
                    <h5 class="filter-heading"><i class="bi bi-grid-3x3-gap"></i> Categories</h5>
                    <a href="?" class="cat-filter-btn <?= $category_id === 0 ? 'active' : '' ?>">
                        <i class="bi bi-circle-fill me-2" style="font-size:0.5rem;"></i>All Products
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="?category=<?= $cat['id'] ?>"
                            class="cat-filter-btn <?= $category_id == $cat['id'] ? 'active' : '' ?>">
                            <i class="bi bi-circle-fill me-2" style="font-size:0.5rem;"></i>
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- WhatsApp Helper -->
                <div class="filter-card" style="background:linear-gradient(135deg,#dcfce7,#f0fdf4);border:1px dashed #86efac;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-whatsapp" style="color:#25d366;font-size:1.4rem;"></i>
                        <span style="font-weight:700;color:#166534;">Need Help?</span>
                    </div>
                    <p style="font-size:0.82rem;color:#166534;margin-bottom:10px;">
                        Can't find what you need? Chat with us!
                    </p>
                    <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank"
                        class="btn btn-sm w-100" style="background:#25d366;color:white;border-radius:20px;">
                        <i class="bi bi-whatsapp me-1"></i>WhatsApp Us
                    </a>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9 col-xl-10">
                <!-- Active Filters Bar -->
                <?php if (!empty($search) || $category_id > 0): ?>
                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                        <span style="color:#64748b;font-size:0.9rem;">Active filters:</span>
                        <?php if (!empty($search)): ?>
                            <span class="badge" style="background:#fff5f0;color:var(--orange);padding:6px 12px;border-radius:20px;font-size:0.82rem;border:1px solid var(--orange);">
                                Search: <?= htmlspecialchars($search) ?>
                                <a href="?<?= $category_id > 0 ? 'category=' . $category_id : '' ?>" style="color:var(--orange);margin-left:6px;text-decoration:none;">✕</a>
                            </span>
                        <?php endif; ?>
                        <?php if ($category_id > 0): ?>
                            <span class="badge" style="background:#fff5f0;color:var(--orange);padding:6px 12px;border-radius:20px;font-size:0.82rem;border:1px solid var(--orange);">
                                <?= htmlspecialchars($current_category_name) ?>
                                <a href="?<?= !empty($search) ? 'search=' . urlencode($search) : '' ?>" style="color:var(--orange);margin-left:6px;text-decoration:none;">✕</a>
                            </span>
                        <?php endif; ?>
                        <a href="?" style="color:#94a3b8;font-size:0.85rem;text-decoration:none;">Clear all</a>
                    </div>
                <?php endif; ?>

                <?php if (empty($products)): ?>
                    <div class="no-products">
                        <i class="bi bi-search" style="font-size:3rem;color:#e2e8f0;display:block;margin-bottom:16px;"></i>
                        <h4 style="color:#475569;">No products found</h4>
                        <p style="color:#94a3b8;">Try adjusting your search filter or <a href="?" style="color:var(--orange);">browse all products</a></p>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-4">
                        <?php foreach ($products as $product): ?>
                            <div class="col">
                                <div class="product-card">
                                    <div class="product-img-wrapper">
                                        <img src="<?= !empty($product['image_main']) ? '../' . htmlspecialchars($product['image_main']) : 'https://via.placeholder.com/400x220/ff6b35/ffffff?text=' . urlencode(substr($product['name'], 0, 15)) ?>"
                                            class="product-img"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                        <?php if ($product['featured'] == 'yes'): ?>
                                            <span class="product-badge">⭐ Featured</span>
                                        <?php endif; ?>
                                        <span class="product-category-badge"><?= htmlspecialchars($product['category_name']) ?></span>
                                    </div>
                                    <div class="product-body">
                                        <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                        <p class="product-desc"><?= htmlspecialchars(truncate($product['short_description'], 80)) ?></p>
                                        <div>
                                            <span class="product-price-label">Starting from </span>
                                            <div class="product-price"><?= formatPrice($product['base_price']) ?></div>
                                        </div>
                                        <a href="../products/product-detail.php?slug=<?= urlencode($product['slug']) ?>"
                                            class="btn-view-product">
                                            <i class="bi bi-eye me-1"></i>View Product
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination-wrapper d-flex justify-content-center align-items-center flex-wrap gap-1">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=<?= $current_page - 1 ?>&<?= http_build_query(array_filter(['search' => $search, 'category' => $category_id ?: null])) ?>" class="page-btn">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <a href="?page=<?= $p ?>&<?= http_build_query(array_filter(['search' => $search, 'category' => $category_id ?: null])) ?>"
                                    class="page-btn <?= $p === $current_page ? 'active' : '' ?>">
                                    <?= $p ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?= $current_page + 1 ?>&<?= http_build_query(array_filter(['search' => $search, 'category' => $category_id ?: null])) ?>" class="page-btn">
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