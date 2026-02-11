<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unique Studio</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

    <!-- STICKY HEADER -->
    <section class="sticky-header">
        <div class="topbar py-50">
            <div class="container-fluid d-flex justify-content-between align-items-center ">

                <div class="d-flex gap-4">
                    <span><i class="bi bi-cloud-upload text-danger"></i> Upload Your File</span>
                    <span><i class="bi bi-image text-danger"></i> Request a Sample</span>
                </div>

                <div class="phone-pill">
                    <i class="bi bi-telephone"></i>
                    Printing Solutions Sales: (+41)-888-56-7890, Service: 1800.123.4567
                </div>

                <div>
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

                <div class="d-flex align-items-center justify-content-between">

                    <!-- LOGO -->
                    <div class="logo-wrapper">
                        <span class="logo-text">unique Studio</span>
                    </div>

                    <!-- MENU -->
                    <ul class="nav d-flex gap-3">
                        <li class="nav-item"><a class="nav-link text-danger" href="#">HOME</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">SERVICES</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">SHOP</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">BLOG</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">PAGES</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">CONTACT</a></li>
                    </ul>

                    <!-- RIGHT SIDE -->
                    <div class="d-flex align-items-center gap-3">


                        <!-- Button -->
                        <button class="quote-btn">
                            GET A QUOTE
                        </button>


                        <!-- Grid Icon -->
                        <div class="icon-circle">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        const header = document.querySelector('.header-custom');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>