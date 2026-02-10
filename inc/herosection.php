<section class="position-relative w-100 px-4 pt-2  py-50">
    <div class="hero-bannerherosection position-relative container-fluid overflow-hidden px-0">

        <!-- Controls (Custom Vertical Pagination kept for visuals, but now controls horizontal slide) -->
        <!-- Hidden on smaller screens via CSS d-none d-lg-flex -->
        <div class="position-absolute vertical-pagination top-50 translate-middle-y d-none d-lg-flex flex-column align-items-center gap-3">
            <span class="vertical-text text-muted rotate-180">Prev</span>
            <div class="pagination-line"></div>
            <button class="circle-btn" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m18 15-6-6-6 6" />
                </svg>
            </button>
            <div class="pagination-line"></div>
            <button class="circle-btn" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
            <div class="pagination-line"></div>
            <span class="vertical-text text-muted">Next</span>
        </div>

        <!-- Carousel Wrapper -->
        <div id="heroCarousel" class="carousel slide h-100 w-100" data-bs-ride="carousel" data-bs-interval="100000">
            <div class="carousel-inner h-100">

                <!-- Slide 1: Photo Frames -->
                <div class="carousel-item active h-100">
                    <div class="row">
                        <!-- Left Text Side -->
                        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center position-relative">
                            <!-- Background Spiral Decoration -->
                            <div class="position-absolute spiral-decoration">
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-100 h-100 spiral-svg">
                                    <g fill="none" stroke="#FF6B35" stroke-opacity="0.2" stroke-width="0.4">
                                        <?php for ($i = 0; $i < 24; $i++): ?>
                                            <path d="M100 100 Q 150 50 200 100" transform="rotate(<?php echo $i * 15; ?> 100 100)" />
                                        <?php endfor; ?>
                                    </g>
                                </svg>
                            </div>

                            <div class="position-relative hero_content_sec text-center text-lg-start z-1">
                                <h1 class="hero-headline">
                                    <span class="text-highlight">
                                        Photo Frames
                                        <svg class="highlight-underline" height="15" viewBox="0 0 200 15" fill="none">
                                            <path d="M5 10C50 2 150 2 195 10" stroke="#FF6B35" stroke-width="5" stroke-linecap="round" />
                                        </svg>
                                    </span>
                                    That Turn Moments into Art
                                </h1>
                                <p class="mt-4 hero-subtext mx-auto mx-lg-0">
                                    Elegant designs and premium finishes to showcase your most cherished family memories.
                                </p>
                                <button class="mt-4 btn-hero-start">Start Your Project</button>
                            </div>
                        </div>

                        <!-- Right Image Side -->
                        <div class="col-lg-6 col-12 position-relative">
                            <img src="assets/img/HeroFrameImg.png" class="w-100 h-100 object-fit-cover float-end" alt="hero" />

                            <div class="blob blob-orange-sm"></div>
                            <div class="blob blob-orange-md"></div>
                            <div class="blob blob-green-lg-mapped"></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Canvas Prints -->
                <div class="carousel-item h-100">
                    <div class="row">
                        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center position-relative">
                            <div class="position-absolute spiral-decoration">
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-100 h-100 spiral-svg">
                                    <g fill="none" stroke="#FF6B35" stroke-opacity="0.2" stroke-width="0.4">
                                        <?php for ($i = 0; $i < 24; $i++): ?>
                                            <path d="M100 100 Q 150 50 200 100" transform="rotate(<?php echo $i * 15; ?> 100 100)" />
                                        <?php endfor; ?>
                                    </g>
                                </svg>
                            </div>
                            <div class="position-relative ps-lg-5 ms-lg-5 text-center text-lg-start z-1">
                                <h1 class="hero-headline">
                                    <span class="text-highlight">
                                        Canvas Prints
                                        <svg class="highlight-underline" height="15" viewBox="0 0 200 15" fill="none">
                                            <path d="M5 10C50 2 150 2 195 10" stroke="#FF6B35" stroke-width="5" stroke-linecap="round" />
                                        </svg>
                                    </span>
                                    Bring Your Walls to Life
                                </h1>
                                <p class="mt-4 hero-subtext mx-auto mx-lg-0">
                                    Transform your favorite photos into stunning gallery-quality canvas art.
                                </p>
                                <button class="mt-4 btn-hero-start">Explore Canvas</button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 position-relative">
                            <img src="assets/img/HeroFrameImg.png" class="w-100 h-100 object-fit-cover float-end" alt="hero" />
                            <div class="blob blob-orange-sm"></div>
                            <div class="blob blob-orange-md"></div>
                            <div class="blob blob-green-lg-mapped"></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: Custom Gifts -->
                <div class="carousel-item h-100">
                    <div class="row">
                        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center position-relative">
                            <div class="position-absolute spiral-decoration">
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-100 h-100 spiral-svg">
                                    <g fill="none" stroke="#FF6B35" stroke-opacity="0.2" stroke-width="0.4">
                                        <?php for ($i = 0; $i < 24; $i++): ?>
                                            <path d="M100 100 Q 150 50 200 100" transform="rotate(<?php echo $i * 15; ?> 100 100)" />
                                        <?php endfor; ?>
                                    </g>
                                </svg>
                            </div>
                            <div class="position-relative ps-lg-5 ms-lg-5 text-center text-lg-start z-1">
                                <h1 class="hero-headline">
                                    <span class="text-highlight">
                                        Custom Gifts
                                        <svg class="highlight-underline" height="15" viewBox="0 0 200 15" fill="none">
                                            <path d="M5 10C50 2 150 2 195 10" stroke="#FF6B35" stroke-width="5" stroke-linecap="round" />
                                        </svg>
                                    </span>
                                    For Every Occasion
                                </h1>
                                <p class="mt-4 hero-subtext mx-auto mx-lg-0">
                                    Personalized mugs, t-shirts, and keepsakes that make the perfect present.
                                </p>
                                <button class="mt-4 btn-hero-start">Create a Gift</button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 position-relative">
                            <img src="assets/img/HeroFrameImg.png" class="w-100 h-100 object-fit-cover float-end" alt="hero" />
                            <div class="blob blob-orange-sm"></div>
                            <div class="blob blob-orange-md"></div>
                            <div class="blob blob-green-lg-mapped"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>