<div
    style="background-image: url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern.svg');background-position: center;">
    <div class="container py-4 px-3">

        {{-- NAV --}}
        <nav class="sticky-top bg-white">
            <img src="{{ asset('assets/img/brand/logo_sarpras2.png') }}" alt="Logo"
                class="position-absolute top-0 start-0 d-none d-lg-block" style="width: 40px;">
            <ul class="nav justify-content-center mt-2">
                <li class="nav-item">
                    <a class="nav-link text-primary fw-semibold" href="#hero-section">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary fw-semibold" href="#tentang-section">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary fw-semibold" href="#alur-section">Alur</a>
                </li>
            </ul>
        </nav>

        {{-- Beranda --}}
        <div id="hero-section" class="vh-100 d-flex flex-column flex-lg-row justify-content-between align-items-center mb-4 text-center text-lg-start" style="min-height: 100vh;">
            <div class="d-flex flex-column justify-content-center align-items-center align-items-lg-start w-100" style="min-height: 100vh;">
            <p class="fw-bold display-2 lh-base">
                SIM
                <span style="border-radius: 25px;padding: 4px 12px;" class="bg-secondary text-white">
                Pelaporan
                </span>
                Dan
                <span style="border-radius: 25px;padding: 4px 12px;" class="bg-secondary text-white">
                Perbaikan
                </span>
                Fasilitas
            </p>
                <p class="">Platform digital untuk melaporkan dan memantau perbaikan sarana dan prasarana kampus
                    secara cepat dan transparan</p>
                <div class="d-flex flex-column flex-lg-row gap-2 justify-content-center justify-content-lg-start">
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Daftar</a>
                </div>
            </div>
            <div class="d-none d-lg-block">
                <img src="{{ asset('assets/img/landingpage.png') }}" alt="gambar landing page">
            </div>
        </div>

        {{-- Tentang --}}
        <div id="tentang-section" class="mt-5" style="scroll-margin-top: 40px;">
            <h2 class="text-center mb-4 w-100 d-flex justify-content-center align-items-center" style="width: 100%;">
            Tentang 
            <span class="text-secondary ms-2" style="position: relative; display: inline-block; font-weight: 700;">
                Sim Sarpras
                <span
                style="position: absolute;left: 0;bottom: 0;width: 100%;height: 8px;background: rgba(246, 93, 50, 0.844);border-radius: 50% 50% 0 0;transform: rotate(-2deg);z-index: -1;display: block;">
                </span>
            </span>
            </h2>
                <p class="text-md-center text-justify" style="text-align: justify;">
                    Sistem Informasi Manajemen Pelaporan dan Perbaikan Fasilitas Kampus Politeknik Negeri Malang
                    dihadirkan untuk membantu mempermudah kegiatan pelaporan dan perbaikan sarana dan prasarana di
                    Politeknik Negeri Malang. Dengan adanya sistem ini, diharapkan seluruh kegiatan pelaporan dan
                    perbaikan sarana dan prasarana dapat dilakukan secara terstruktur dan terpadu dalam SIM-SARPRAS
                    POLINEMA.
                </p>
            <style>
                @media (max-width: 767.98px) {
                    #tentang-section .text-center,
                    #tentang-section .text-md-center,
                    #tentang-section .text-justify {
                        text-align: justify !important;
                    }
                    #tentang-section .w-50 {
                        width: 100% !important;
                    }
                    #tentang-section .mx-auto {
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                    }
                }
            </style>

            <div>
                {{-- Card 1: Penambahan Fasilitas Kampus --}}
                <div class="row row-cols-1 row-cols-md-3 g-4 gap-4 justify-content-center mt-5 align-items-center">
                    <div class="card col-md-3 order-1 order-md-1">
                        <div class="px-3 pt-3">
                            <h5 class="fw-bold">
                                Penambahan Fasilitas Kampus
                            </h5>
                        </div>
                        <hr>
                        <div class="p-3">
                            <span>User Dapat Menambahkan Fasilitas Baru Melalui Sistem</span>
                        </div>
                    </div>
                    <div class="card col-md-6 order-2 order-md-2">
                        <div class="p-3">
                            <img src="{{ asset('assets/img/addFasility.png') }}" alt="">
                        </div>
                    </div>
                </div>

                {{-- Card 2: Pelaporan Fasilitas Kampus --}}
                <div class="row row-cols-1 row-cols-md-3 g-4 gap-4 justify-content-center mt-5 align-items-center">
                    {{-- On mobile, judul & keterangan di atas gambar --}}
                    <div class="card col-md-6 order-2 order-md-1 d-block d-md-none">
                        <div class="px-3 pt-3">
                            <h5 class="fw-bold">
                                Pelaporan Fasilitas Kampus
                            </h5>
                        </div>
                        <hr>
                        <div class="p-3">
                            <span>User Dapat Melaporkan Fasilitas yang Perlu diperbaiki atau Ditangani oleh
                                Teknisi</span>
                        </div>
                    </div>
                    <div class="card col-md-6 order-1 order-md-1 d-none d-md-block">
                        <div class="p-3">
                            <img src="{{ asset('assets/img/reportFacility.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card col-md-3 order-3 order-md-2 d-none d-md-block">
                        <div class="px-3 pt-3">
                            <h5 class="fw-bold">
                                Pelaporan Fasilitas Kampus
                            </h5>
                        </div>
                        <hr>
                        <div class="p-3">
                            <span>User Dapat Melaporkan Fasilitas yang Perlu diperbaiki atau Ditangani oleh
                                Teknisi</span>
                        </div>
                    </div>
                    <div class="card col-md-6 order-4 d-block d-md-none">
                        <div class="p-3">
                            <img src="{{ asset('assets/img/reportFacility.png') }}" alt="">
                        </div>
                    </div>
                </div>

                {{-- Card 3: Perhitungan SPK oleh Sistem --}}
                <div class="row row-cols-1 row-cols-md-3 g-4 gap-4 justify-content-center mt-5 align-items-center">
                    <div class="card col-md-3 order-1 order-md-1">
                        <div class="px-3 pt-3">
                            <h5 class="fw-bold">
                                Perhitungan SPK oleh Sistem
                            </h5>
                        </div>
                        <hr>
                        <div class="p-3">
                            <span>Sistem ini Dilengkapi dengan Perhitungan keputusan yang Akurat dan Cepat</span>
                        </div>
                    </div>
                    <div class="card col-md-6 order-2 order-md-2">
                        <div class="p-3">
                            <img src="{{ asset('assets/img/priority.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Optional: Remove card border and shadow on mobile for cleaner look */
        @media (max-width: 767.98px) {
            #tentang-section .card {
                border: none;
                box-shadow: none;
            }
        }
    </style>


    {{-- Alur --}}
    <div id="alur-section" class="vh-100" style="scroll-margin-top: 40px;padding-top: 40px;">
        <h2 class="text-center mb-4">Alur <span class="text-secondary"
                style="position: relative; display: inline-block; font-weight: 700;">Sim Sarpras
                <span
                    style="position: absolute;left: 0;bottom: 0;width: 100%;height: 8px;background: rgba(246, 93, 50, 0.844);border-radius: 50% 50% 0 0;transform: rotate(-2deg);z-index: -1;display: block;"></span>
            </span>
        </h2>

        <div class="container mt-5">
            <div class="row d-none d-md-flex" style="position: relative;">
                <!-- Desktop version (original) -->
                <!-- Card 1 -->
                <div class="col-md-4 mb-4" style="position: relative;">
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 1</li>
                            <li class="list-group-item">User mengakses website SIM-SARPRAS</li>
                        </ul>
                    </div>
                    <!-- Garis horizontal ke Card 2 -->
                    <div
                        style="position: absolute; top: 50px; right: -60px; width:90px; height: 6px; z-index: 1;" class="bg-secondary">
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-4 mb-4" style="position: relative;">
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 2</li>
                            <li class="list-group-item">User login ke dalam SIM-SARPRAS</li>
                        </ul>
                    </div>
                    <!-- Garis horizontal ke Card 3 -->
                    <div
                        style="position: absolute; top: 50px; right: -60px; width:90px; height: 6px; z-index: 1;" class="bg-primary">
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-4 mb-4" style="position: relative;">
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 3</li>
                            <li class="list-group-item">User menginput fasilitas yang ingin diperbaiki</li>
                        </ul>
                    </div>
                    <!-- Garis vertikal ke Card 4 -->
                    <div
                        style="position: absolute; bottom: -73px; left: 55%; transform: translateX(-50%); width: 6px; height: 73px; z-index: 1;" class="bg-secondary">
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-md-4 mb-4 mt-5" style="position: relative;">
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 6</li>
                            <li class="list-group-item">Selesai! fasilitas akan segera diperbaiki</li>
                        </ul>
                    </div>
                    <!-- Garis horizontal ke Card 5 -->
                    <div
                        style="position: absolute; top: 50px; right: -60px; width:90px; height: 6px; z-index: 1;" class="bg-secondary">
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="col-md-4 mb-4 mt-5" style="position: relative;">
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 5</li>
                            <li class="list-group-item">User menunggu laporan divalidasi oleh admin</li>
                        </ul>
                    </div>
                    <!-- Garis horizontal ke Card 6 -->
                    <div
                        style="position: absolute; top: 50px; right: -60px; width:90px; height: 6px; z-index: 1;" class="bg-primary">
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="col-md-4 mb-4 mt-5" style="position: relative;">
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 4</li>
                            <li class="list-group-item">User mengupload laporan perbaikan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Mobile version (vertical, no lines/arrows) -->
            <div class="d-block d-md-none">
                <div class="mb-4">
                    <div class="card shadow-sm w-100">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 1</li>
                            <li class="list-group-item">User mengakses website SIM-SARPRAS</li>
                        </ul>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card shadow-sm w-100">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 2</li>
                            <li class="list-group-item">User login ke dalam SIM-SARPRAS</li>
                        </ul>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card shadow-sm w-100">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 3</li>
                            <li class="list-group-item">User menginput fasilitas yang ingin diperbaiki</li>
                        </ul>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card shadow-sm w-100">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 4</li>
                            <li class="list-group-item">User mengupload laporan perbaikan</li>
                        </ul>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card shadow-sm w-100">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 5</li>
                            <li class="list-group-item">User menunggu laporan divalidasi oleh admin</li>
                        </ul>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card shadow-sm w-100">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 6</li>
                            <li class="list-group-item">Selesai! fasilitas akan segera diperbaiki</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Footer -->
<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-4">
                <img src="{{ asset('assets/img/brand/logo_sarpras2.png') }}" alt="Logo" style="width: 60px;" class="mb-3">
                <h5 class="mb-3">SIM Sarpras Polinema</h5>
                <p class="px-3">Sistem Informasi Manajemen Pelaporan dan Perbaikan Fasilitas Kampus Politeknik Negeri Malang.</p>
            </div>
        </div>
        <hr class="my-4 mx-auto" style="width: 80%;">
        <div class="text-center">
            <p class="mb-0">&copy; 2025 SIM Sarpras Polinema. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</div>
</div>

<script>
    document.querySelectorAll('.nav-link').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
</div>
