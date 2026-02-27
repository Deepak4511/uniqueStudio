<?php
$page = 'Product';
require_once '../includes/functions.php';

$slug = sanitize($_GET['slug'] ?? '');
if (empty($slug)) {
    header('Location: index.php');
    exit;
}

$product = getProductBySlug($slug);
if (!$product) {
    header('Location: index.php');
    exit;
}

$options       = getProductOptions($product['id']);
$related       = getProductsByCategory($product['category_id'], 4, 0);
$gallery       = !empty($product['images_gallery']) ? json_decode($product['images_gallery'], true) : [];

// Add main image to gallery if not empty
$all_images = [];
if (!empty($product['image_main'])) array_unshift($gallery, $product['image_main']);
$all_images = $gallery ?: [];

include('../inc/header.php');
?>

<style>
    .product-detail-page {
        padding: 60px 0;
        background: #f8fafc;
    }

    .product-main-img {
        width: 100%;
        border-radius: 20px;
        object-fit: cover;
        max-height: 450px;
        cursor: pointer;
    }

    .thumbnail-row {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .thumb-img {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.2s;
    }

    .thumb-img:hover,
    .thumb-img.active {
        border-color: var(--orange);
        transform: scale(1.05);
    }

    .product-detail-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        padding: 36px;
    }

    .product-badge-category {
        background: var(--orange-50);
        color: var(--orange);
        font-size: 0.82rem;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 20px;
        border: 1px solid var(--orange);
    }

    .product-main-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--green);
        line-height: 1.3;
        margin: 16px 0 10px;
    }

    .product-short-desc {
        color: #64748b;
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 24px;
    }

    .price-display {
        background: #fff5f0;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .price-from {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .price-amount {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--orange);
        font-family: 'Space Grotesk', sans-serif;
    }

    .price-per-unit {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Options */
    .option-group {
        margin-bottom: 20px;
    }

    .option-label {
        font-weight: 700;
        color: #374151;
        font-size: 0.9rem;
        margin-bottom: 10px;
        display: block;
    }

    .option-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .option-pill {
        padding: 8px 16px;
        border-radius: 25px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #475569;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
    }

    .option-pill:hover,
    .option-pill.selected {
        border-color: var(--orange);
        background: #fff5f0;
        color: var(--orange);
        font-weight: 700;
    }

    /* Quantity */
    .qty-section {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .qty-label {
        font-weight: 700;
        color: #374151;
        font-size: 0.9rem;
    }

    .qty-control-lg {
        display: flex;
        align-items: center;
        gap: 0;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .qty-btn-lg {
        width: 44px;
        height: 44px;
        border: none;
        background: #f8fafc;
        color: #374151;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .qty-btn-lg:hover {
        background: var(--orange);
        color: white;
    }

    .qty-input-lg {
        width: 64px;
        height: 44px;
        border: none;
        border-left: 2px solid #e2e8f0;
        border-right: 2px solid #e2e8f0;
        text-align: center;
        font-size: 1rem;
        font-weight: 700;
        outline: none;
        color: #1e293b;
    }

    /* Buttons */
    .btn-add-cart {
        background: linear-gradient(135deg, #ff6b35, #ff8c5f);
        color: white;
        border: none;
        padding: 16px 36px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4);
    }

    .btn-whatsapp {
        background: #25d366;
        color: white;
        border: none;
        padding: 16px 28px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-whatsapp:hover {
        background: #20ba58;
        transform: translateY(-2px);
    }

    /* Features */
    .features-row {
        border-top: 1px solid #f1f5f9;
        padding-top: 24px;
        margin-top: 24px;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 10px;
    }

    .feature-icon {
        width: 32px;
        height: 32px;
        background: #fff5f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange);
        flex-shrink: 0;
    }

    /* Tabs */
    .detail-tabs {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        margin-top: 40px;
        overflow: hidden;
    }

    .nav-tabs {
        background: #f8fafc;
        border-bottom: 2px solid #f1f5f9;
        padding: 0 24px;
    }

    .nav-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 14px 20px;
        border: none;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link.active {
        color: var(--orange);
        border-bottom-color: var(--orange);
        background: none;
    }

    .tab-content {
        padding: 28px;
    }

    /* Related Products */
    .related-section {
        margin-top: 60px;
    }

    .related-heading {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--green);
        margin-bottom: 30px;
    }

    .related-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .related-card:hover {
        transform: translateY(-5px);
        border-color: var(--orange);
    }

    .related-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .related-body {
        padding: 16px;
    }

    .related-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
        margin-bottom: 6px;
    }

    .related-price {
        color: var(--orange);
        font-weight: 800;
    }
</style>

<!-- Breadcrumb -->
<div style="background: var(--green); padding: 16px 0;">
    <div class="container py-50">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background:none;padding:0;margin:0;">
                <li class="breadcrumb-item"><a href="../index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php" style="color:rgba(255,255,255,0.7);text-decoration:none;">Products</a></li>
                <li class="breadcrumb-item"><a href="index.php?category=<?= $product['category_id'] ?>" style="color:rgba(255,255,255,0.7);text-decoration:none;"><?= htmlspecialchars($product['category_name']) ?></a></li>
                <li class="breadcrumb-item active" style="color:white;"><?= htmlspecialchars($product['name']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<section class="product-detail-page">
    <div class="container py-50">
        <div class="row g-5">

            <!-- Product Images -->
            <div class="col-lg-5">
                <div style="position:sticky;top:90px;">
                    <img id="mainProductImg"
                        src="<?= !empty($all_images[0]) ? '../' . htmlspecialchars($all_images[0]) : 'https://via.placeholder.com/600x450/ff6b35/ffffff?text=' . urlencode($product['name']) ?>"
                        class="product-main-img"
                        alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php if (count($all_images) > 1): ?>
                        <div class="thumbnail-row">
                            <?php foreach ($all_images as $i => $img): ?>
                                <img src="../<?= htmlspecialchars($img) ?>"
                                    class="thumb-img <?= $i === 0 ? 'active' : '' ?>"
                                    alt="Product image <?= $i + 1 ?>"
                                    onclick="switchImg(this, '../<?= htmlspecialchars($img) ?>')">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-7">
                <div class="product-detail-card">
                    <span class="product-badge-category">
                        <i class="bi bi-tag me-1"></i><?= htmlspecialchars($product['category_name']) ?>
                    </span>

                    <h1 class="product-main-title"><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="product-short-desc"><?= htmlspecialchars($product['short_description']) ?></p>

                    <!-- Price Display -->
                    <div class="price-display">
                        <div class="price-from">Starting from</div>
                        <div class="price-amount" id="currentPrice"><?= formatPrice($product['base_price']) ?></div>
                        <?php if ($product['min_quantity'] > 1): ?>
                            <div class="price-per-unit">Minimum order: <?= $product['min_quantity'] ?> units</div>
                        <?php endif; ?>
                    </div>

                    <!-- Options -->
                    <?php foreach ($options as $option): ?>
                        <div class="option-group">
                            <label class="option-label"><?= htmlspecialchars($option['option_name']) ?></label>
                            <div class="option-pills">
                                <?php
                                $values = array_map('trim', explode(',', $option['option_values']));
                                foreach ($values as $i => $val): ?>
                                    <button type="button"
                                        class="option-pill <?= $i === 0 ? 'selected' : '' ?>"
                                        data-option-type="<?= htmlspecialchars($option['option_type']) ?>"
                                        data-option-value="<?= htmlspecialchars($val) ?>"
                                        data-price-modifier="<?= $option['price_modifier'] ?>"
                                        onclick="selectOption(this)">
                                        <?= htmlspecialchars($val) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Quantity -->
                    <div class="qty-section">
                        <span class="qty-label">Quantity:</span>
                        <div class="qty-control-lg">
                            <button class="qty-btn-lg" onclick="changeQty(-1)">−</button>
                            <input type="number" id="productQty" class="qty-input-lg"
                                value="<?= $product['min_quantity'] ?>"
                                min="<?= $product['min_quantity'] ?>"
                                onchange="updatePrice()">
                            <button class="qty-btn-lg" onclick="changeQty(1)">+</button>
                        </div>
                        <?php if ($product['customizable'] == 'yes'): ?>
                            <span class="badge" style="background:#dcfce7;color:#166534;padding:6px 12px;border-radius:20px;font-size:0.8rem;">
                                <i class="bi bi-pencil me-1"></i>Customizable
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Upload Design (if customizable) -->
                    <?php if ($product['customizable'] == 'yes'): ?>
                        <div class="mb-4 p-3" style="background:#f0f9ff;border-radius:12px;border:1px dashed #93c5fd;">
                            <label class="d-block mb-2" style="font-weight:600;color:#1e40af;font-size:0.9rem;">
                                <i class="bi bi-cloud-upload me-2"></i>Upload Your Design (Optional)
                            </label>
                            <input type="file" id="designFile" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf,.ai,.psd">
                            <div style="font-size:0.78rem;color:#64748b;margin-top:6px;">
                                Accepted: JPG, PNG, PDF, AI, PSD. Max 5MB. You can also share via WhatsApp.
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 flex-wrap mb-4">
                        <button class="btn-add-cart" onclick="addToCart()">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <a href="https://wa.me/<?= WA_NUMBER ?>?text=<?= urlencode("Hi! I'm interested in: " . $product['name'] . ". Please share more details.") ?>"
                            target="_blank"
                            class="btn-whatsapp">
                            <i class="bi bi-whatsapp me-2"></i>Order via WhatsApp
                        </a>
                    </div>

                    <!-- Features -->
                    <div class="features-row">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="bi bi-truck"></i></div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.85rem;color:#1e293b;">Fast Delivery</div>
                                        <div style="font-size:0.78rem;color:#64748b;">5-7 business days</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.85rem;color:#1e293b;">Quality Guaranteed</div>
                                        <div style="font-size:0.78rem;color:#64748b;">100% satisfaction</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="bi bi-palette"></i></div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.85rem;color:#1e293b;">Premium Print</div>
                                        <div style="font-size:0.78rem;color:#64748b;">Vibrant colors</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="bi bi-whatsapp"></i></div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.85rem;color:#1e293b;">WhatsApp Support</div>
                                        <div style="font-size:0.78rem;color:#64748b;">Real-time updates</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Toast notification -->
                <div id="cartToast" class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;display:none;">
                    <div class="d-flex align-items-center gap-2 p-3" style="background:white;border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,0.15);border-left:4px solid var(--orange);">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        <span style="font-weight:600;color:#1e293b;">Added to cart!</span>
                        <a href="../cart/view-cart.php" class="btn btn-sm btn-orange ms-2" style="border-radius:20px;">View Cart</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description Tabs -->
        <div class="detail-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#description">Description</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#howToOrder">How to Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#shipping">Shipping & Delivery</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="description">
                    <?php if (!empty($product['long_description'])): ?>
                        <div style="color:#475569;line-height:1.8;"><?= nl2br(htmlspecialchars($product['long_description'])) ?></div>
                    <?php else: ?>
                        <p style="color:#475569;"><?= htmlspecialchars($product['short_description']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="tab-pane" id="howToOrder">
                    <ol style="color:#475569;line-height:2;">
                        <li>Select your product options (size, finish, color, etc.)</li>
                        <li>Set the quantity you need</li>
                        <li>Upload your design file (or share via WhatsApp after ordering)</li>
                        <li>Click "Add to Cart" and proceed to checkout</li>
                        <li>Fill in your delivery details and submit the order</li>
                        <li>Our team will WhatsApp you with payment details within 1-2 hours</li>
                        <li>After payment, production begins immediately!</li>
                    </ol>
                </div>
                <div class="tab-pane" id="shipping">
                    <div class="row g-3" style="color:#475569;">
                        <div class="col-md-6">
                            <h6 style="color:var(--green);font-weight:700;">Delivery Timeline</h6>
                            <ul style="line-height:2;">
                                <li>Standard delivery: 5-7 business days</li>
                                <li>Express delivery: 2-3 business days (extra charge)</li>
                                <li>Local (Indore): 1-2 business days</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color:var(--green);font-weight:700;">Shipping Charges</h6>
                            <ul style="line-height:2;">
                                <li>Free delivery on orders above ₹2,000</li>
                                <li>Flat ₹100 delivery for orders below ₹2,000</li>
                                <li>Pan India delivery available</li>
                                <li>International orders on request</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related)): ?>
            <div class="related-section">
                <h2 class="related-heading">Related Products</h2>
                <div class="row g-4">
                    <?php foreach ($related as $r): if ($r['id'] == $product['id']) continue; ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="product-detail.php?slug=<?= urlencode($r['slug']) ?>" class="text-decoration-none">
                                <div class="related-card">
                                    <img src="<?= !empty($r['image_main']) ? '../' . htmlspecialchars($r['image_main']) : 'https://via.placeholder.com/300x180/004e7c/ffffff?text=' . urlencode(substr($r['name'], 0, 10)) ?>"
                                        class="related-img" alt="<?= htmlspecialchars($r['name']) ?>">
                                    <div class="related-body">
                                        <div class="related-name"><?= htmlspecialchars($r['name']) ?></div>
                                        <div class="related-price"><?= formatPrice($r['base_price']) ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    const basePrice = <?= $product['base_price'] ?>;
    const productId = <?= $product['id'] ?>;
    const productName = "<?= addslashes(htmlspecialchars($product['name'])) ?>";
    let selectedOptions = {};
    let priceModifier = 0;

    function selectOption(el) {
        // Deselect siblings
        const siblings = el.parentElement.querySelectorAll('.option-pill');
        siblings.forEach(s => s.classList.remove('selected'));
        el.classList.add('selected');

        const optionType = el.dataset.optionType;
        const optionValue = el.dataset.optionValue;
        selectedOptions[optionType] = optionValue;

        // Recalculate price modifier
        priceModifier = 0;
        document.querySelectorAll('.option-pill.selected').forEach(pill => {
            priceModifier += parseFloat(pill.dataset.priceModifier || 0);
        });

        updatePrice();
    }

    function changeQty(delta) {
        const input = document.getElementById('productQty');
        const minQty = <?= $product['min_quantity'] ?>;
        const newVal = Math.max(minQty, parseInt(input.value) + delta);
        input.value = newVal;
        updatePrice();
    }

    function updatePrice() {
        const qty = parseInt(document.getElementById('productQty').value);
        const total = (basePrice + priceModifier) * qty;
        document.getElementById('currentPrice').textContent = '₹' + total.toLocaleString('en-IN', {
            minimumFractionDigits: 2
        });
    }

    function addToCart() {
        const qty = document.getElementById('productQty').value;
        const unitPrice = (basePrice + priceModifier).toFixed(2);

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', qty);
        formData.append('unit_price', unitPrice);
        formData.append('product_name', productName);
        formData.append('options', JSON.stringify(selectedOptions));

        fetch('../cart/add-to-cart.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const toast = document.getElementById('cartToast');
                    toast.style.display = 'block';
                    setTimeout(() => toast.style.display = 'none', 4000);
                } else {
                    alert(data.message || 'Could not add to cart');
                }
            })
            .catch(() => alert('Something went wrong. Try again.'));
    }

    function switchImg(el, src) {
        document.getElementById('mainProductImg').src = src;
        document.querySelectorAll('.thumb-img').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    // Initialize with first option selected per group
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.option-group').forEach(group => {
            const first = group.querySelector('.option-pill.selected');
            if (first) {
                selectedOptions[first.dataset.optionType] = first.dataset.optionValue;
                priceModifier += parseFloat(first.dataset.priceModifier || 0);
            }
        });
        updatePrice();
    });
</script>

<?php include('../inc/footer.php'); ?>