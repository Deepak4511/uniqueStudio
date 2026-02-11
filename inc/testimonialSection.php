<section class="testimonial-section py-80 position-relative">
    <div class="container">
        <!-- Section Header -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center text-white">
                <h6 class="text-orange text-uppercase subtitle mb-2 ls-2">
                    FEEDBACK
                </h6>
                <h2 class="display-4 sec_title mb-3">Customers about Our Service</h2>
                <p class="text-secondary">
                    There are many variations of passages of lorem but the majority.
                </p>
            </div>
        </div>

        <!-- Testimonial Swiper -->
        <div class="swiper testimonialSwiper pb-5">
            <div class="swiper-wrapper">

                <!-- Testimonial 1 -->
                <div class="swiper-slide">
                    <div class="testimonial-card p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-quote fs-1 text-orange me-3"></i>
                            <h4 class="text-white fw-bold mb-0">Exceed My Expectations!</h4>
                        </div>
                        <p class="text-secondary mb-5">
                            Over the years, Printpark has been a reliable partner for our custom printing needs. When we have custom prints jobs, we turn to Printpark for value, careful attention, and...
                        </p>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="author-img-wrapper me-3">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=150&auto=format&fit=crop" alt="Author" class="img-fluid rounded-circle">
                                </div>
                                <div>
                                    <h5 class="text-white fw-bold mb-1">Nora Penelope</h5>
                                    <p class="text-orange small mb-0">GM_Blackcats Security Service</p>
                                </div>
                            </div>

                            <div class="rating-pill">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="swiper-slide">
                    <div class="testimonial-card p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-quote fs-1 text-orange me-3"></i>
                            <h4 class="text-white fw-bold mb-0">Pleasure to do Business With!</h4>
                        </div>
                        <p class="text-secondary mb-5">
                            Printpark Printing does an outstanding job printing anything andeverything we send them. But the biggest reason I go to them time and again is the customer pleasure of the moment...
                        </p>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="author-img-wrapper me-3">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=150&auto=format&fit=crop" alt="Author" class="img-fluid rounded-circle">
                                </div>
                                <div>
                                    <h5 class="text-white fw-bold mb-1">Nathan Felix</h5>
                                    <p class="text-orange small mb-0">Manager_Creote Corporate</p>
                                </div>
                            </div>

                            <div class="rating-pill">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-light-gray-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="swiper-slide">
                    <div class="testimonial-card p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-quote fs-1 text-orange me-3"></i>
                            <h4 class="text-white fw-bold mb-0">Top Notch Quality!</h4>
                        </div>
                        <p class="text-secondary mb-5">
                            I was amazed by the quality of the brochures. The colors were vibrant and the paper quality was excellent. Definitely recommending Unique Studio to everyone I know.
                        </p>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="author-img-wrapper me-3">
                                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=150&auto=format&fit=crop" alt="Author" class="img-fluid rounded-circle">
                                </div>
                                <div>
                                    <h5 class="text-white fw-bold mb-1">Sarah Jenkins</h5>
                                    <p class="text-orange small mb-0">Marketing Director</p>
                                </div>
                            </div>

                            <div class="rating-pill">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Custom Navigation -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button class="testi-nav-btn testi-prev"><i class="bi bi-arrow-left"></i></button>
            <button class="testi-nav-btn testi-next"><i class="bi bi-arrow-right"></i></button>
        </div>

    </div>
</section>

<!-- Initialize Swiper -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper(".testimonialSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            navigation: {
                nextEl: ".testi-next",
                prevEl: ".testi-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
            },
        });
    });
</script>