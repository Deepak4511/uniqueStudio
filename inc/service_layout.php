<?php
if (!isset($serviceId) || !isset($servicesData[$serviceId])) {
    echo "<div class='container py-5 text-center'><h2>Service not found.</h2></div>";
    return;
}

$service = $servicesData[$serviceId];
?>

<section class="service-hero-banner" style="background-image: url('<?php echo $service['hero_image']; ?>');">
    <div class="service-hero-overlay"></div>
    <div class="container service-hero-content">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <span class="service-kicker">UNIQUE STUDIO SERVICE</span>
                <h1 class="service-hero-title"><?php echo $service['hero_title']; ?></h1>
                <p class="service-hero-subtitle"><?php echo $service['hero_subtitle']; ?></p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#categories" class="service-hero-btn">View Categories</a>
                    <a href="#products" class="service-hero-btn service-hero-btn-outline">Browse Products</a>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="service-metrics-card">
                    <h4>Popular in <?php echo $service['title']; ?></h4>
                    <ul class="mb-0">
                        <li><?php echo count($service['categories']); ?> Categories</li>
                        <li><?php echo count($service['products']); ?> Product Types</li>
                        <li>Custom Design Support Included</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="categories" class="service-categories-section py-5">
    <div class="container py-4">
        <div class="service-section-head text-center mb-5">
            <p class="service-mini-title">STEP 1</p>
            <h2>Choose A Category First</h2>
            <p><?php echo isset($service['category_intro']) ? $service['category_intro'] : 'Pick the category that best matches your requirement, then choose products and customize.'; ?></p>
        </div>
        <div class="row g-4">
            <?php foreach ($service['categories'] as $category): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="service-category-card h-100">
                        <div class="service-category-icon">
                            <i class="bi <?php echo $category['icon']; ?>"></i>
                        </div>
                        <h3><?php echo $category['name']; ?></h3>
                        <p><?php echo $category['description']; ?></p>
                        <a href="#products" class="service-inline-link">Explore Products <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="products" class="service-products-section py-5">
    <div class="container py-4">
        <div class="service-section-head text-center mb-5">
            <p class="service-mini-title">STEP 2</p>
            <h2>Select Your Product</h2>
            <p>Responsive product cards with quick price visibility and clear call-to-action.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($service['products'] as $product): ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="service-product-card h-100">
                        <div class="service-product-media">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <span class="service-product-badge">Custom</span>
                        </div>
                        <div class="service-product-body">
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="service-product-price"><?php echo $product['price']; ?></p>
                            <a href="#" class="service-card-btn">Order / Enquire</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="service-features-section py-5">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <img src="<?php echo $service['hero_image']; ?>" class="img-fluid service-feature-image" alt="<?php echo $service['title']; ?>">
            </div>
            <div class="col-lg-6">
                <div class="service-section-head mb-4">
                    <p class="service-mini-title">STEP 3</p>
                    <h2>Why Clients Choose Us</h2>
                </div>
                <ul class="service-feature-list">
                    <?php foreach ($service['features'] as $feature): ?>
                        <li><i class="bi bi-check-circle-fill"></i> <?php echo $feature; ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="#" class="service-hero-btn mt-3">Request Custom Quote</a>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($service['process'])): ?>
    <section class="service-process-section py-5">
        <div class="container py-4">
            <div class="service-section-head text-center mb-5">
                <p class="service-mini-title">WORKFLOW</p>
                <h2>How We Complete Your Order</h2>
            </div>
            <div class="row g-4">
                <?php foreach ($service['process'] as $index => $step): ?>
                    <div class="col-md-4">
                        <div class="service-process-card h-100">
                            <span class="service-process-num"><?php echo $index + 1; ?></span>
                            <h3><?php echo $step['title']; ?></h3>
                            <p><?php echo $step['description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($service['faq'])): ?>
    <section class="service-faq-section py-5">
        <div class="container py-4">
            <div class="service-section-head text-center mb-5">
                <p class="service-mini-title">FAQ</p>
                <h2>Need More Details?</h2>
            </div>
            <div class="accordion service-faq-accordion" id="serviceFaqAccordion">
                <?php foreach ($service['faq'] as $index => $item): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $index !== 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#serviceFaq<?php echo $index; ?>">
                                <?php echo $item['question']; ?>
                            </button>
                        </h2>
                        <div id="serviceFaq<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#serviceFaqAccordion">
                            <div class="accordion-body">
                                <?php echo $item['answer']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
