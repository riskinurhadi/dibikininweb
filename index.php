<!--**-->
<!--* Coded with coffee and passion by Riski Nurhadi.-->
<!--* For inquiries, drop a line to rizkibinmangtrisno@gmail.com-->
<!--* Visit my Portofolio on:  riskinurhadi.my.id-->
<!--**-->

<?php
// Sertakan file koneksi
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jasa Pembuatan Website & Web Development Profesional | dibikininweb</title>
    <meta name="description" content="Jasa pembuatan website profesional dan jasa web development terpercaya. Bikin website berkualitas dengan harga terjangkau. Solusi pembuatan web untuk bisnis Anda.">
    <meta name="keywords" content="jasa pembuatan website, jasa web development, bikin website, pembuatan web, jasa website, web development, pembuatan website profesional">
    <meta name="author" content="dibikininweb">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>



    <header class="header-desktop d-none d-lg-block">
        <div class="container">
          <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <a class="navbar-brand" style="color:var(--primary-color);" href="#">
                <img src="assets/img/dibikininweb.png" alt="dibikininweb" style="height: 40px;" />
            </a> 
            <div class="collapse navbar-collapse">
              <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                  <a class="nav-link active" href="#">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#about-us">Tentang Kami</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#Layanan">Layanan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#Portofolio">Portofolio</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#Pricing">Pricing</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#Kontak">Kontak</a>
                </li>
              </ul>
              <a href="https://wa.me/6282371869118" class="btn btn-primary-custom text-light text-end" style="height: 50px;">Hubungi Kami</a>
            </div>
          </nav>
        </div>
    </header>

<nav class="mobile-bottom-navbar d-lg-none">
    <a href="#hero" class="mobile-nav-item active">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
    </a>
    <a href="#about-us" class="mobile-nav-item">
        <i class="bi bi-building-fill"></i> <span>About</span>
    </a>
    <a href="#Layanan" class="mobile-nav-item">
        <i class="bi bi-file-earmark-check-fill"></i> <span>Service</span>
    </a>
    <a href="#Pricing" class="mobile-nav-item">
        <i class="bi bi-box2-fill"></i> <span>Package</span>
    </a>
    <a href="#kontak" class="mobile-nav-item">
        <i class="bi bi-envelope-fill"></i>
        <span>Contact</span>
    </a>
</nav>
    

<div class="overflow-hidden">
    <section class="hero-section">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 text-center order-1 order-lg-2 mb-4 mb-lg-0">
                <img src="img/hero.png" alt="Ilustrasi Tim sedang menganalisis data" class="img-fluid hero-illustration text-end">
            </div>

            <div class="col-lg-6 text-center text-lg-start order-2 order-lg-1">
                <p class="badge-pill-custom">
                    <i class="bi bi-lightbulb-fill me-2"></i> Solusi Jitu
                </p>
                <h1 class="display-6 fw-bold mb-4">Jasa Pembuatan Website Profesional untuk Percepat Pertumbuhan Bisnis Anda</h1>
                <p class="lead mb-4">
                    Kami adalah penyedia jasa web development terpercaya yang siap membantu Anda bikin website berkualitas tinggi. Dengan layanan pembuatan web profesional, kami mengubah visi bisnis Anda menjadi website yang memikat dan handal untuk meningkatkan keunggulan kompetitif di dunia digital.
                </p>
                <a href="#Statistik" class="btn btn-primary-custom btn-lg text-light">Selengkapnya</a>
            </div>
            
        </div>
    </div>
</section>
    
    <section id="Statistik" class="stats-section-cards py-5">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-code-square"></i>
                        </div>
                        <h2 class="card-title fw-bold text-primary mb-1">10+</h2>
                        <p class="card-text text-muted">Proyek Website</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-palette-fill"></i>
                        </div>
                        <h2 class="card-title fw-bold text-primary mb-1">100+</h2>
                        <p class="card-text text-muted">Proyek Desain</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h2 class="card-title fw-bold text-primary mb-1">50+</h2>
                        <p class="card-text text-muted">Klien</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-building-fill"></i>
                        </div>
                        <h2 class="card-title fw-bold text-primary mb-1">5+</h2>
                        <p class="card-text text-muted">Instansi Percaya</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="about-us" class="about-us-section py-5">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <div class="about-us-content">
                    <h6 class="text-primary text-uppercase fw-bold">Tentang Kami</h6>
                    <h2 class="display-6 fw-bold mb-3">Jasa Web Development yang Profesional dan Handal</h2>
                    <p class="text-muted mb-4">
                         Kami adalah partner digital terpercaya yang menyediakan jasa pembuatan website profesional atau jasa web development berkualitas tinggi. Dengan pengalaman dalam pembuatan web yang inovatif, kami membantu meningkatkan citra brand dan jangkauan pasar Anda melalui strategi digital yang tepat sasaran.
                    </p>
                    
                    <div class="checklist mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="checklist-icon me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <span>Pendekatan kolaboratif untuk memahami visi dan tujuan Anda secara mendalam.</span>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <div class="checklist-icon me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <span>Eksekusi desain yang presisi dan web development yang sesuai standar terkini untuk pembuatan web berkualitas.</span>
                        </div>
                         <div class="d-flex align-items-start">
                            <div class="checklist-icon me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <span>Dukungan penuh dan komitmen untuk memastikan kepuasan dan kesuksesan jangka panjang Anda.</span>
                        </div>
                    </div>

                    <a href="#Proses" class="btn btn-primary-custom text-light">
                        Selengkapnya <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row g-3 align-items-center">
                    <div class="col-7">
                        <img src="https://images.pexels.com/photos/3184418/pexels-photo-3184418.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" 
                             alt="Tim sedang berdiskusi di kantor" class="img-fluid rounded-3 w-100">
                    </div>
                    <div class="col-5">
                        <img src="https://images.pexels.com/photos/3184306/pexels-photo-3184306.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" 
                             alt="Diskusi tim kreatif" class="img-fluid rounded-3 mb-3 w-100">
                        <img src="https://images.pexels.com/photos/3861964/pexels-photo-3861964.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" 
                             alt="Desainer sedang bekerja dengan laptop" class="img-fluid rounded-3 w-100">
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="Proses" class="how-we-work-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold">Proses Kerja Kami</h2>
                <div class="title-divider mx-auto"></div>
                <p class="lead text-muted mt-2">
                    Dalam setiap pembuatan website yang kami tawarkan, kami mengikuti alur kerja yang terstruktur dan transparan untuk memastikan proses pembuatan web berjalan lancar dan memberikan hasil terbaik tepat waktu.
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="process-item" data-step="01">
                    <div class="d-flex align-items-center">
                        <div class="process-icon me-4">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div class="process-text">
                            <h4 class="fw-bold mb-1">Perencanaan Proyek</h4>
                            <p class="text-muted mb-0">Memahami tujuan, menentukan lingkup, dan menyusun roadmap proyek yang detail.</p>
                        </div>
                    </div>
                </div>
                <div class="process-item" data-step="02">
                    <div class="d-flex align-items-center">
                        <div class="process-icon me-4">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <div class="process-text">
                            <h4 class="fw-bold mb-1">Fase Pengembangan</h4>
                            <p class="text-muted mb-0">Proses web development dimulai dari desain UI/UX hingga penulisan kode (coding) untuk pembuatan web sesuai dengan rencana yang telah disepakati.</p>
                        </div>
                    </div>
                </div>
                <div class="process-item" data-step="03">
                    <div class="d-flex align-items-center">
                        <div class="process-icon me-4">
                            <i class="bi bi-search"></i>
                        </div>
                        <div class="process-text">
                            <h4 class="fw-bold mb-1">Pengujian & QA</h4>
                            <p class="text-muted mb-0">Memastikan semua fitur berfungsi dengan baik, responsif, dan tanpa bug.</p>
                        </div>
                    </div>
                </div>
                <div class="process-item" data-step="04">
                    <div class="d-flex align-items-center">
                        <div class="process-icon me-4">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                        </div>
                        <div class="process-text">
                            <h4 class="fw-bold mb-1">Peluncuran & Dukungan</h4>
                            <p class="text-muted mb-0">Menayangkan website ke publik dan menyediakan dukungan pasca-peluncuran.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="Layanan" class="services-section py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold">Layanan Kami</h2>
                <div class="title-divider mx-auto"></div>
                <p class="lead text-muted mt-2">
                    Sebagai penyedia jasa pembuatan website terpercaya, kami menyediakan solusi web development yang komprehensif untuk membantu brand Anda tumbuh dan bersinar di dunia online.
                </p>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="service-item d-flex">
                    <div class="service-icon me-4">
                        <i class="bi bi-code-slash"></i>
                    </div>
                    <div class="service-content">
                        <h4 class="fw-bold mb-2">Jasa Web Development</h4>
                        <p class="text-muted">Jasa pembuatan website profesional dari nol dengan performa tinggi, aman, dan disesuaikan penuh dengan kebutuhan bisnis unik Anda. Kami siap membantu Anda bikin website berkualitas.</p>
                        <a href="#" class="learn-more-link">
                            Pelajari Lebih Lanjut <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="service-item d-flex">
                    <div class="service-icon me-4">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="service-content">
                        <h4 class="fw-bold mb-2">Mobile App Solutions</h4>
                        <p class="text-muted">Solusi aplikasi mobile inovatif untuk platform iOS dan Android yang memberikan pengalaman pengguna terbaik.</p>
                        <a  class="learn-more-link">
                            Coming Soon <i class="
                            <!--bi bi-arrow-right-->
                            "></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="service-item d-flex">
                    <div class="service-icon me-4">
                        <i class="bi bi-palette2"></i>
                    </div>
                    <div class="service-content">
                        <h4 class="fw-bold mb-2">UI/UX Design</h4>
                        <p class="text-muted">Merancang antarmuka yang tidak hanya indah secara visual tetapi juga intuitif dan mudah digunakan oleh target audiens Anda.</p>
                        <a href="#" class="learn-more-link">
                            Pelajari Lebih Lanjut <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="service-item d-flex">
                    <div class="service-icon me-4">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="service-content">
                        <h4 class="fw-bold mb-2">Digital Marketing</h4>
                        <p class="text-muted">Meningkatkan visibilitas online dan menjangkau lebih banyak pelanggan melalui strategi SEO, SEM, dan media sosial.</p>
                        <a  class="learn-more-link">
                            Coming Soon <i class="
                            <!--bi bi-arrow-right-->
                            "></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="server-quality" class="server-quality-section py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <!-- Left: Rocket Illustration -->
            <div class="col-lg-5 text-center">
                <div class="rocket-illustration-wrapper position-relative">
                    <div class="rocket-icon">
                        <i class="bi bi-rocket-takeoff-fill"></i>
                    </div>
                    <div class="rocket-trail"></div>
                    <div class="speed-lines">
                        <div class="speed-line"></div>
                        <div class="speed-line"></div>
                        <div class="speed-line"></div>
                    </div>
                </div>
            </div>

            <!-- Right: Content -->
            <div class="col-lg-7">
                <h2 class="display-6 fw-bold mb-4" style="color: #32353a;">
                    Server Kualitas Terbaik, Load Website Cepat
                </h2>
                
                <!-- Metrics -->
                <div class="row g-4 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="metric-box">
                            <div class="metric-value">99,9%</div>
                            <div class="metric-label">UPTIME</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="metric-box">
                            <div class="metric-value">4.9</div>
                            <div class="metric-label">RATINGS</div>
                        </div>
                    </div>
                </div>

                <!-- Feature Badge -->
                <div class="loading-badge mb-4">
                    <i class="bi bi-lightning-charge-fill me-2"></i>
                    <span>Loading Tercepat</span>
                </div>

                <!-- Description -->
                <p class="text-muted mb-0" style="line-height: 1.8;">
                    Kami menggunakan server dengan kualitas terbaik, sehingga load website menjadi cepat. Dukungan teknis yang terampil, infrastruktur yang kuat, dan pemantauan proaktif dibikininweb akan memastikan bahwa website Anda tetap online tanpa gangguan.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="Portofolio" class="portfolio-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-3">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold">Portfolio</h2>
                <div class="title-divider mx-auto"></div>
                <p class="lead text-muted mt-3">
                    Berikut adalah beberapa hasil web development yang telah kami selesaikan dengan bangga untuk para klien hebat kami. Setiap pembuatan web yang kami kerjakan dirancang khusus sesuai kebutuhan bisnis.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                    <div class="portfolio-img-wrapper">
                        <img src="img/tampingan.png" class="card-img-top" alt="Proyek Web Design">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Web Development</p>
                        <h5 class="card-title fw-bold">Sistem Informasi Desa Tampingan</h5>
                        <p class="card-text text-muted small">Develop Sistem Informasi Desa Tampingan, Kecamatan Boja, Kabupaten Kendal, Jawa Tengah.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                    <div class="portfolio-img-wrapper">
                        <img src="img/waylaga.png" class="card-img-top" alt="Proyek Web Design">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Web Development</p>
                        <h5 class="card-title fw-bold">Sistem Informasi SD Negeri 4 Way Laga</h5>
                        <p class="card-text text-muted small">Develop Sistem Informasi SD Negeri 4 Way Laga Butuah, Bandar Lampung, Lampung.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                    <div class="portfolio-img-wrapper">
                        <img src="img/mataneka.png" class="card-img-top" alt="Proyek Web Design">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Web Development</p>
                        <h5 class="card-title fw-bold">Lulusku by Mataneka</h5>
                        <p class="card-text text-muted small">Develop Aplikasi Portal Kelulusan Madrasah Tsanawiyah Negeri 1 Way Kanan Lampung, berbasis Web.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                     <div class="portfolio-img-wrapper">
                        <img src="img/kemusuk.png" class="card-img-top" alt="Proyek Graphics">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Web Development</p>
                        <h5 class="card-title fw-bold">Sistem Informasi Dusun</h5>
                        <p class="card-text text-muted small">Develop Sistem Informasi Dusun Kemusuk Kidul, Yogyakarta.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                     <div class="portfolio-img-wrapper">
                        <img src="img/rm.png" class="card-img-top" alt="Proyek Branding">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Web Development</p>
                        <h5 class="card-title fw-bold">Portal Kelulusan</h5>
                        <p class="card-text text-muted small">Develop Portal Kelulusan Raudlatul Muta'allimin, Lampung..</p>
                    </div>
                </div>
            </div>
             <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                     <div class="portfolio-img-wrapper">
                        <img src="img/ikram.png" class="card-img-top" alt="Proyek Web App">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Graphics Design</p>
                        <h5 class="card-title fw-bold">Logo IKRAM</h5>
                        <p class="card-text text-muted small">Desain Logo Ikatan Keluarga Raudlatul Muta'allimin, Lampung.</p>
                    </div>
                </div>
            </div>
             <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                     <div class="portfolio-img-wrapper">
                        <img src="img/bannerskr.png" class="card-img-top" alt="Proyek UI/UX">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Graphics Design</p>
                        <h5 class="card-title fw-bold">Banner Kit</h5>
                        <p class="card-text text-muted small">Desain Banner Kit Ulang Tahun Sri Karang Rejo, Sumatera Selatan.</p>
                    </div>
                </div>
            </div>
             <div class="col-md-6 col-lg-4">
                <div class="card portfolio-card h-100">
                     <div class="portfolio-img-wrapper">
                        <img src="img/figmahimatik.png" class="card-img-top" alt="Proyek Motion Graphics">
                    </div>
                    <div class="card-body">
                        <p class="portfolio-category">Ui Design</p>
                        <h5 class="card-title fw-bold">Web Himatik UAA</h5>
                        <p class="card-text text-muted small">Desain UI Website Profil HIMATIK UAA periode 2023/2024, Yogyakarta.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="Pricing" class="pricing-section py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold">Harga Layanan</h2>
                <div class="title-divider mx-auto"></div>
                <p class="lead text-muted mt-3">
                    Pilih paket jasa pembuatan website yang paling sesuai dengan kebutuhan dan anggaran Anda. Kami menawarkan solusi pembuatan web yang fleksibel untuk semua skala bisnis, mulai dari paket basic hingga custom.
                </p>
            </div>
        </div>

        <div class="row g-3 justify-content-center">
            
            <div class="col-lg-3">
                <div class="card pricing-card h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <h4 class="fw-bold ">Basic</h4>
                        <span class="price-period mt-3">Start from</span>
                        <div class="price-display mb-3">
                            <span class="price-amount">Rp 100.000</span>
                            <span class="price-period">/ project</span>
                        </div>
                        <p class="text-muted">Cocok untuk personal branding atau bisnis skala kecil yang baru memulai.</p>
                        <h6 class="fw-bold mt-3">Fitur Termasuk:</h6>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Desain 3 Halaman</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Domain & Hosting 1 Tahun</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Revisi Desain 2x</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-x-circle-fill  text-danger me-2"></i> <span>Suport 365 hari</span></li>
                        </ul>
                        <div class="mt-auto">
                           <a href="https://wa.me/6282371869118?text=Halo%2C%20saya%20tertarik%20dengan%20layanan%20Web%20Basic%20yang%20ditawarkan.%20Mohon%20informasinya%2C%20terima%20kasih." class="btn btn-primary-custom w-100 text-white">Order</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card pricing-card highlighted-plan h-100">
                    <div class="popular-badge">Paling Populer</div>
                    <div class="card-body p-4 d-flex flex-column">
                        <h4 class="fw-bold">Standard</h4>
                        <span class="price-period mt-3">Start from</span>
                        <div class="price-display mb-3">
                            <span class="price-amount">Rp 1.000.000</span>
                            <span class="price-period">/ project</span>
                        </div>
                        <p>Solusi paling populer untuk UKM dan startup yang ingin tampil profesional.</p>
                        <h6 class="fw-bold mt-3">Fitur Termasuk:</h6>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill me-2"></i> <span>Desain hingga 8 Halaman</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill me-2"></i> <span>Fitur Toko Online</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill me-2"></i> <span>Include Domain & Hosting</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill me-2"></i> <span>Revisi Desain 5x</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill me-2"></i> <span>Suport 365 hari</span></li>
                        </ul>
                        <div class="mt-auto">
                           <a href="https://wa.me/6282371869118?text=Halo%2C%20saya%20tertarik%20dengan%20layanan%20Web%20Paling%20Populer%20yang%20ditawarkan.%20Mohon%20informasinya%2C%20terima%20kasih." class="btn btn-light w-100 fw-bold">Order</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card pricing-card h-100">
                     <div class="card-body p-4 d-flex flex-column">
                        <h4 class="fw-bold">Premium</h4>
                        <span class="price-period mt-3">Start from</span>
                        <div class="price-display mb-3">
                            <span class="price-amount">Rp 3jt -5jt</span>
                            <span class="price-period">/ project</span>
                        </div>
                        <p class="text-muted">Untuk perusahaan yang membutuhkan website kompleks dengan fitur custom.</p>
                        <h6 class="fw-bold mt-3">Fitur Termasuk:</h6>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Halaman Tidak Terbatas</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Fitur Custom Sesuai Request</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Dukungan Prioritas</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Revisi Tidak Terbatas</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Suport 365 hari</span></li>
                        </ul>
                        <div class="mt-auto">
                           <a href="https://wa.me/6282371869118?text=Halo%2C%20saya%20tertarik%20dengan%20layanan%20Web%20Premium%20yang%20ditawarkan.%20Mohon%20informasinya%2C%20terima%20kasih." class="btn btn-primary-custom w-100 text-white">Order</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="card pricing-card h-100">
                     <div class="card-body p-4 d-flex flex-column">
                        <h4 class="fw-bold">Custom</h4>
                        <span class="price-period mt-3">Start from</span>
                        <div class="price-display mb-3">
                            <span class="price-amount">Rp 50k - 3jt</span>
                            <span class="price-period">/ project</span>
                        </div>
                        <p class="text-muted">Untuk yang lebih membutuhkan website fleksible dengan fitur custom.</p>
                        <h6 class="fw-bold mt-3">Fitur Termasuk:</h6>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Halaman tentukan sendiri</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Fitur Custom Sesuai Request</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Dukungan Prioritas</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Revisi custom</span></li>
                            <li class="mb-2 d-flex"><i class="bi bi-check-circle-fill text-primary me-2"></i> <span>Suport custom</span></li>
                        </ul>
                        <div class="mt-auto">
                           <a href="https://wa.me/6282371869118?text=Halo%2C%20saya%20tertarik%20dengan%20layanan%20Web%20Custom%20yang%20ditawarkan.%20Mohon%20informasinya%2C%20terima%20kasih." class="btn btn-primary-custom w-100 text-white">Order</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
 
</style>

<section id="clients-partners" class="py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold">Klien & Partner Kami</h2>
                <div class="title-divider mx-auto"></div>
                <p class="lead text-muted mt-3">
                    Kami bangga telah dipercaya oleh berbagai instansi dan perusahaan untuk menjadi partner digital mereka.
                </p>
            </div>
        </div>
        <div class="row g-4 align-items-center justify-content-center">
            <!-- Contoh Logo Klien 1 -->
            <div class="col-6 col-md-4 col-lg-2 text-center">
                <div class="p-3">
                    <img src="https://mtsn1waykanan.com/img/mtsn1logo.png" alt="Logo Klien A" class="client-logo">
                </div>
            </div>
            <!-- Contoh Logo Klien 2 -->
            <div class="col-6 col-md-4 col-lg-2 text-center">
                <div class="p-3">
                    <img src="https://sdn4waylagabutuah.sch.id/img/sdn1.png" alt="Logo Klien B" class="client-logo">
                </div>
            </div>
            <!-- Contoh Logo Klien 3 -->
            <div class="col-6 col-md-4 col-lg-2 text-center">
                <div class="p-3">
                    <img src="https://raudlatulmutaalliminkasui.sch.id/wp-content/uploads/2024/10/cropped-IMG-20241014-WA0003.jpg" alt="Logo Klien C" class="client-logo">
                </div>
            </div>
            <!-- Contoh Logo Klien 4 -->
            <div class="col-6 col-md-4 col-lg-2 text-center">
                <div class="p-3">
                    <img src="https://tampingan.kendalkab.go.id/upload/umum/Logo.png" alt="Logo Partner A" class="client-logo">
                </div>
            </div>
            <!-- Contoh Logo Klien 5 -->
            <div class="col-6 col-md-4 col-lg-2 text-center">
                <div class="p-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4b/Lambang_Kabupaten_Musi_Banyuasin.png" alt="Logo Partner B" class="client-logo">
                </div>
            </div>
             <!--Contoh Logo Klien 6 -->
            <div class="col-6 col-md-4 col-lg-2 text-center">
                <div class="p-3">
                    <img src="img/Berwarna1.png" alt="Logo Partner C" class="client-logo">
                </div>
            </div>
        </div>
    </div>
</section>



<section class="testimonials-section py-5">
            <div class="container">
                <div class="row justify-content-center text-center mb-3">
                    <div class="col-lg-8">
                        <h2 class="display-6 fw-bold">Testimonials</h2>
                        <div class="title-divider mx-auto"></div>
                        <p class="lead text-muted mt-3">
                            Apa yang dikatakan klien kami tentang Layanan kami.
                        </p>
                    </div>
                </div>

                <div class="swiper testimonials-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide mb-5">
                            <div class="card testimonial-card h-100">
                                <div class="card-body">
                                    <i class="bi bi-quote fs-1 text-primary"></i>
                                    <p class="testimonial-text mt-1">"Mantap, Pengerjaan cepat, fitur lengkap sesuai kebutuhan, harga terjangkau"</p>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <img src="img/kartono.png" class="testimonial-avatar me-3" alt="Emma Parker">
                                        <div>
                                            <h6 class="fw-bold mb-0">Kartono</h6>
                                            <small class="text-muted">Operator MTs N 1 Way Kanan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide mb-5">
                             <div class="card testimonial-card h-100">
                                <div class="card-body">
                                     <i class="bi bi-quote fs-1 text-primary"></i>
                                    <p class="testimonial-text mt-1">"Desain bagus, hasil memuaskan seperti yang di inginkan. <br>"</p>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <img src="img/fitr.png" class="testimonial-avatar me-3" alt="David Miller">
                                        <div>
                                            <h6 class="fw-bold mb-0">Fitri Nasution</h6>
                                            <small class="text-muted">Mahasiswa</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide mb-5">
                            <div class="card testimonial-card h-100">
                                <div class="card-body">
                                     <i class="bi bi-quote fs-1 text-primary"></i>
                                    <p class="testimonial-text mt-1">"Mengutamakan kepuasan Client merupakan bagian dari Tanggung jawab dan Visi Kami"</p>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <img src="img/kyy.png" class="testimonial-avatar me-3" alt="Michael Davis">
                                        <div>
                                            <h6 class="fw-bold mb-0">Riski Nurhadi</h6>
                                            <small class="text-muted">Owner & Founder</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide mb-5">
                             <div class="card testimonial-card h-100">
                                <div class="card-body">
                                     <i class="bi bi-quote fs-1 text-primary"></i>
                                    <p class="testimonial-text mt-1">"Desain bagus, hasil memuaskan seperti yang di inginkan. <br>"</p>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <img src="img/fitr.png" class="testimonial-avatar me-3" alt="David Miller">
                                        <div>
                                            <h6 class="fw-bold mb-0">Fitri Nasution</h6>
                                            <small class="text-muted">Mahasiswa</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination mt-5"></div>
                </div>
            </div>
        </section>
        
<section class="cta-section py-5">
    <div class="container">
        <div class="cta-card">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="cta-content">
                        <p class="badge-pill-custom mt-3">
                            <i class="bi bi-stars me-2"></i> Gabung Sekarang
                        </p>
                        <h2 class="display-5 fw-bold mb-3">Siap Bikin Website Profesional untuk Bisnis Anda?</h2>
                        <p class="text-muted mb-4">
                            Jadilah bagian dari puluhan klien puas yang telah mempercayakan pembuatan website mereka kepada kami. Sebagai penyedia layanan pembuatan web terpercaya, kami siap menjadi partner terbaik untuk mengembangkan bisnis digital Anda.
                        </p>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    <span>Dukungan Penuh 24/7</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    <span>Pengerjaan Cepat</span>
                                </div>
                            </div>
                            <!--<div class="col-md-6">-->
                            <!--    <div class="d-flex align-items-center">-->
                            <!--        <i class="bi bi-check-circle-fill text-primary me-2"></i>-->
                            <!--        <span>Analitik Mendalam</span>-->
                            <!--    </div>-->
                            <!--</div>-->
                             <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    <span>Harga Terjangkau</span>
                                </div>
                            </div>
                        </div>

                        <a href="#Pricing" class="btn btn-primary-custom btn-lg w-100 text-light mb-4">Mulai Proyek Anda</a>
                        <div class="text-center mt-3">
                            <!--<a href="#" class="btn btn-outline-custom">-->
                            <!--    <i class="bi bi-play-circle me-2"></i> Lihat Demo-->
                            <!--</a>-->
                        </div>

                    </div>
                </div>

                <div class="col-lg-6 justify-content-end d-lg-block position-relative d-none d-lg-block">
                    <img src="img/join.png" alt="Wanita menunjuk ke arah teks ajakan" class="img-fluid ms-auto d-block cta-person-img flex-end">
                    
                    <div class="floating-nugget" style="top: 15%; left: 0;">
                         <i class="bi bi-graph-up-arrow text-primary me-2"></i> 5+ Instansi Percaya
                    </div>
                    
                    <div class="floating-nugget" style="bottom: 15%; right: 0;">
                        <i class="bi bi-people-fill text-primary me-2"></i> 90% Klien Merasa Puas
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    
        
    <section id="Kontak" class="contact-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold">Kontak Kami</h2>
                <div class="title-divider mx-auto"></div>
                <p class="lead text-muted mt-3">
                    Tertarik untuk bikin website profesional? Hubungi tim jasa pembuatan website kami sekarang! Kami siap membantu mewujudkan pembuatan web impian Anda. Jangan ragu untuk menghubungi kami melalui detail di bawah atau kirimkan pesan melalui form.
                </p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card info-card text-center h-100">
                    <div class="card-body">
                        <div class="info-icon mx-auto mb-3">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h5 class="card-title fw-bold">Alamat Kami</h5>
                        <p class="card-text text-muted">Jl. Garuda, Gatak, Tamantirto, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55184</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card info-card text-center h-100">
                    <div class="card-body">
                        <div class="info-icon mx-auto mb-3">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <h5 class="card-title fw-bold">Nomor Kontak</h5>
                        <p class="card-text text-muted mb-0">Mobile: +62 823-7186-9118</p>
                        <p class="card-text text-muted">Email: halo@dibikininweb.com</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card info-card text-center h-100">
                    <div class="card-body">
                        <div class="info-icon mx-auto mb-3">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <h5 class="card-title fw-bold">Jam Buka</h5>
                        <p class="card-text text-muted mb-0">Senin - Sabtu: 09:00 - 17:00</p>
                        <p class="card-text text-muted">Minggu: Tutup</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
<form class="contact-form" action="proses-kontak.php" method="POST">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" placeholder="Nama Anda*" name="nama" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" class="form-control" placeholder="Email Anda*" name="email" required>
            </div>
        </div>
        <div class="col-md-6">
             <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input type="tel" class="form-control" placeholder="Nomor Telepon*" name="telepon">
            </div>
        </div>
        <div class="col-md-6">
             <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                <select class="form-select" name="layanan" required>
                    <option selected disabled value="">Pilih Layanan*</option>
                    <option value="web-development">Web Development</option>
                    <option value="ui-ux-design">UI/UX Design</option>
                    <option value="branding">Branding</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text align-items-start pt-3"><i class="bi bi-pencil-fill"></i></span>
                <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..." name="pesan"></textarea>
            </div>
        </div>
        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary-custom px-5 text-light">Kirim Pesan</button>
        </div>
    </div>
</form>
            </div>
        </div>
    </div>
</section>
</div>

<!--<a href="https://wa.me/6281234567890?text=Halo,%20saya%20tertarik%20dengan%20layanan%20Anda." -->
<!--   class="floating-cs-button d-lg-none" -->
<!--   target="_blank">-->
<!--    <i class="bi bi-chat-text-fill"></i>-->
<!--</a>-->


<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-12 footer-info">
                    <a href="#" class="logo d-flex align-items-center">
                        <img src="assets/img/dibikininweb.png" alt="dibikininweb" style="height: 50px;" />
                    </a>
                    <p>Partner digital terpercaya untuk jasa pembuatan website atau jasa web development dan desain grafis yang inovatif. Kami siap membantu Anda bikin website berkualitas dengan hasil yang memuaskan.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Tentang Kami</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Layanan</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Portfolio</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Kontak</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">UI/UX Design</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Branding</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Digital Marketing</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Motion Graphic</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12 footer-contact text-center text-md-start">
                    <h4>Contact Us</h4>
                    <p>
                        Jl. Garuda, Gatak, Tamantirto, <br>
                        Kec. Kasihan, Kabupaten Bantul, <br>
                        Daerah Istimewa Yogyakarta 55184<br><br>
                        <strong>Phone:</strong> +62 823 7186 9118<br>
                        <strong>Email:</strong> halo@dibikininweb.com<br>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>dibikininweb</span></strong> 2025. All Rights Reserved
            </div>
            <div class="credits">
                Designed by <a href="#">dibikininweb Team</a>
            </div>
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const testimonialsSlider = new Swiper('.testimonials-slider', {
            loop: true,
            spaceBetween: 30,
            grabCursor: true,
            autoplay: {
              delay: 4000,
              disableOnInteraction: false,
            },
            slidesPerView: 1,
            breakpoints: {
                768: { slidesPerView: 2 },
                992: { slidesPerView: 3 }
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>
    <script src="js/chat.js"></script>
    
<?php
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo "<script>
            Swal.fire({
              icon: '{$alert['type']}',
              title: '{$alert['title']}',
              text: '{$alert['text']}',
              confirmButtonColor: '#18A7D2' // Menyesuaikan warna tombol dengan tema
            });
          </script>";
    // Hapus session setelah ditampilkan agar tidak muncul lagi saat refresh
    unset($_SESSION['alert']);
}
?>
</body>
</html>