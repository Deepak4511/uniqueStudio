<?php
$page = 'Search Results';
require_once '../includes/functions.php';

$query   = sanitize($_GET['q'] ?? '');
$results = [];
$count   = 0;

if (strlen($query) >= 2) {
    $results = searchProducts($query, 24);
    $count   = count($results);
}

include('../inc/header.php');
?>

<style>
    .search-hero {
        background: linear-gradient(135deg, var(--green), #003d63);
        padding: 50px 0;
        color: white;
    }

    .search-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2rem;
        font-weight: 700;
    }

    .search-box-big {
        display: flex;
        gap: 0;
        border-radius: 50px;
        overflow: hidden;
        max-width: 600px;
        margin-top: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .search-box-big input {
        flex: 1;
        padding: 16px 24px;
        border: none;
        font-size: 1rem;
        outline: none;
    }

    .search-box-big button {
        background: var(--orange);
        color: white;
        border: none;
        padding: 16px 28px;
        font-weight: 700;
        cursor: pointer;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .search-box-big button:hover {
        background: #e05a27;
    }

    .results-section {
        padding: 60px 0;
        background: #f8fafc;
        min-height: 400px;
    }

    .product-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s;
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
        height: 200px;
        object-fit: cover;
    }

    .product-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .product-cat {
        font-size: 0.75rem;
        color: var(--orange);
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .product-desc {
        font-size: 0.82rem;
        color: #64748b;
        flex: 1;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--orange);
    }

    .btn-view {
        background: var(--green);
        color: white;
        border: none;
        padding: 9px 18px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.8rem;
        width: 100%;
        cursor: pointer;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 10px;
    }

    .btn-view:hover {
        background: var(--orange);
        color: white;
    }

    .tag {
        background: #fff5f0;
        color: var(--orange);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-block;
        margin: 2px;
    }
</style>

<!-- Search Hero -->
<section class="search-hero">
    <div class="container-fluid">
        <nav class="mb-4" style="font-size:0.88rem;">
            <a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a>
            <span class="mx-2" style="color:rgba(255,255,255,0.5);">/</span>
            <span style="color:white;">Search</span>
        </nav>
        <h1>
            <?php if (!empty($query)): ?>
                <?= $count ?> result<?= $count !== 1 ? 's' : '' ?> for "<span style="color:var(--orange);"><?= htmlspecialchars($query) ?></span>"
            <?php else: ?>
                Search Products
            <?php endif; ?>
        </h1>

        <!-- Search Form -->
        <form action="search.php" method="GET">
            <div class="search-box-big">
                <input type="text" name="q" value="<?= htmlspecialchars($query) ?>"
                    placeholder="Search for mugs, t-shirts, business cards…" autofocus>
                <button type="submit"><i class="bi bi-search me-1"></i> Search</button>
            </div>
        </form>

        <!-- Popular Searches -->
        <div class="mt-3" style="opacity:0.85;">
            <span style="font-size:0.82rem;margin-right:8px;">Popular:</span>
            <?php
            $popular = ['business cards', 'custom mug', 't-shirt', 'photo frame', 'banner', 'sticker'];
            foreach ($popular as $term): ?>
                <a href="search.php?q=<?= urlencode($term) ?>" class="tag text-decoration-none">
                    <?= htmlspecialchars($term) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Results -->
<section class="results-section">
    <div class="container-fluid py-50">
        <?php if (empty($query)): ?>
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size:3rem;color:#e2e8f0;display:block;margin-bottom:16px;"></i>
                <h4 style="color:#475569;">Enter a keyword to search products</h4>
                <p style="color:#94a3b8;">Try searching for: business cards, mug, photo frame, banner…</p>
                <a href="../products/" class="btn btn-orange rounded-pill px-4 mt-3">Browse All Products</a>
            </div>
        <?php elseif ($count === 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown" style="font-size:3rem;color:#e2e8f0;display:block;margin-bottom:16px;"></i>
                <h4 style="color:#475569;">No products found for "<?= htmlspecialchars($query) ?>"</h4>
                <p style="color:#94a3b8;max-width:400px;margin:10px auto;">Try a different keyword or browse our full catalog.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap mt-3">
                    <a href="../products/" class="btn btn-orange rounded-pill px-4">Browse All Products</a>
                    <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank"
                        class="btn rounded-pill px-4" style="background:#25d366;color:white;font-weight:600;">
                        <i class="bi bi-whatsapp me-1"></i>Ask on WhatsApp
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <p style="color:#475569;margin:0;">
                    Found <strong><?= $count ?></strong> product<?= $count !== 1 ? 's' : '' ?> matching
                    "<strong><?= htmlspecialchars($query) ?></strong>"
                </p>
                <a href="../products/" style="color:var(--orange);text-decoration:none;font-weight:600;font-size:0.88rem;">
                    Browse all products <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-4">
                <?php foreach ($results as $product): ?>
                    <div class="col">
                        <div class="product-card">
                            <div style="overflow:hidden;">
                                <img src="<?= !empty($product['image_main']) ? '../' . htmlspecialchars($product['image_main']) : 'https://via.placeholder.com/400x200/ff6b35/ffffff?text=' . urlencode(substr($product['name'], 0, 14)) ?>"
                                    class="product-img"
                                    alt="<?= htmlspecialchars($product['name']) ?>"
                                    loading="lazy">
                            </div>
                            <div class="product-body">
                                <div class="product-cat"><?= htmlspecialchars($product['category_name']) ?></div>
                                <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-desc"><?= htmlspecialchars(truncate($product['short_description'], 70)) ?></p>
                                <div>
                                    <span style="font-size:0.75rem;color:#94a3b8;">From</span>
                                    <div class="product-price"><?= formatPrice($product['base_price']) ?></div>
                                </div>
                                <a href="../products/product-detail.php?slug=<?= urlencode($product['slug']) ?>"
                                    class="btn-view">
                                    <i class="bi bi-eye me-1"></i>View & Order
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('../inc/footer.php'); ?>