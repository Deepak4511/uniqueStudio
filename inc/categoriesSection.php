<section class="printing-solution-section py-100 position-relative">
    <div class="container">
        <!-- Section Header -->
        <div class="row justify-content-center mb-1">
            <div class="col-lg-8 text-center">
                <h6 class="text-orange text-uppercase fw-bold mb-2 ls-2">
                    CATEGORIES
                </h6>
                <h2 class="display-4 fw-bold mb-3">The Complete Printing Solution</h2>
                <p class="text-muted">
                    There are many variations of passages of lorem but the majority.
                </p>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="position-relative px-4 px-md-5">
            <div class="swiper categoriesSwiper py-5">
                <div class="swiper-wrapper">
                    <!-- Card 1: Banners -->
                    <div class="swiper-slide">
                        <div class="solution-card h-100 text-center bg-white p-4">
                            <div class="card-content">
                                <h4 class="fw-bold mb-3">Banners</h4>
                                <p class="text-muted small mb-4">
                                    Take a trivial example, which of us ever...
                                </p>
                                <div class="img-circle-wrapper mx-auto mb-4">
                                    <img src="https://images.unsplash.com/photo-1568992687947-868a62a9f521?q=80&w=300&auto=format&fit=crop" alt="Banners" class="img-fluid rounded-circle">
                                </div>
                            </div>
                            <div class="card-divider my-3"></div>
                            <div class="card-footer-link">
                                <a href="#" class="read-more-link text-uppercase fw-bold">
                                    <i class="bi bi-arrow-right text-orange me-2"></i> Read More
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Booklets -->
                    <div class="swiper-slide">
                        <div class="solution-card h-100 text-center bg-white p-4">
                            <div class="card-content">
                                <h4 class="fw-bold mb-3">Booklets</h4>
                                <p class="text-muted small mb-4">
                                    But who has any right to find fault...
                                </p>
                                <div class="img-circle-wrapper mx-auto mb-4">
                                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=300&auto=format&fit=crop" alt="Booklets" class="img-fluid rounded-circle">
                                </div>
                            </div>
                            <div class="card-divider my-3"></div>
                            <div class="card-footer-link">
                                <a href="#" class="read-more-link text-uppercase fw-bold">
                                    <i class="bi bi-arrow-right text-orange me-2"></i> Read More
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Business Cards -->
                    <div class="swiper-slide">
                        <div class="solution-card h-100 text-center bg-white p-4">
                            <div class="card-content">
                                <h4 class="fw-bold mb-3">Business Cards</h4>
                                <p class="text-muted small mb-4">
                                    Nor again is there anyone who loves or...
                                </p>
                                <div class="img-circle-wrapper mx-auto mb-4">
                                    <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=300&auto=format&fit=crop" alt="Business Cards" class="img-fluid rounded-circle">
                                </div>
                            </div>
                            <div class="card-divider my-3"></div>
                            <div class="card-footer-link">
                                <a href="#" class="read-more-link text-uppercase fw-bold">
                                    <i class="bi bi-arrow-right text-orange me-2"></i> Read More
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Calendars -->
                    <div class="swiper-slide">
                        <div class="solution-card h-100 text-center bg-white p-4">
                            <div class="card-content">
                                <h4 class="fw-bold mb-3">Calendars</h4>
                                <p class="text-muted small mb-4">
                                    Which of us ever undertakes laborious physical exercise,...
                                </p>
                                <div class="img-circle-wrapper mx-auto mb-4">
                                    <img src="https://images.unsplash.com/photo-1506784983877-45594efa4cbe?q=80&w=300&auto=format&fit=crop" alt="Calendars" class="img-fluid rounded-circle">
                                </div>
                            </div>
                            <div class="card-divider my-3"></div>
                            <div class="card-footer-link">
                                <a href="#" class="read-more-link text-uppercase fw-bold">
                                    <i class="bi bi-arrow-right text-orange me-2"></i> Read More
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Business Cards -->
                    <div class="swiper-slide">
                        <div class="solution-card h-100 text-center bg-white p-4">
                            <div class="card-content">
                                <h4 class="fw-bold mb-3">Business Cards</h4>
                                <p class="text-muted small mb-4">
                                    Nor again is there anyone who loves or...
                                </p>
                                <div class="img-circle-wrapper mx-auto mb-4">
                                    <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=300&auto=format&fit=crop" alt="Business Cards" class="img-fluid rounded-circle">
                                </div>
                            </div>
                            <div class="card-divider my-3"></div>
                            <div class="card-footer-link">
                                <a href="#" class="read-more-link text-uppercase fw-bold">
                                    <i class="bi bi-arrow-right text-orange me-2"></i> Read More
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Custom Navigation Buttons -->
            <div class="swiper-button-prev custom-swiper-btn"></div>
            <div class="swiper-button-next custom-swiper-btn"></div>
        </div>

        <!-- Button -->
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="#" class="btn btn-orange rounded-pill px-5 py-3 fw-bold text-uppercase ls-1">More Categories</a>
            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper(".categoriesSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            // pagination: {
            //     el: ".swiper-pagination",
            //     clickable: true,
            // },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1440: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                },
            },
        });
    });
</script>