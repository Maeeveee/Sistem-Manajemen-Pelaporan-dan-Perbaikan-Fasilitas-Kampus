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
        <div id="hero-section" class="vh-100 d-flex justify-content-between align-items-center mb-4">
            <div class="">
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
            <h2 class="text-center mb-4">Tentang <span class="text-secondary"
                    style="position: relative; display: inline-block; font-weight: 700;">Sim Sarpras
                    <span
                        style="position: absolute;left: 0;bottom: 0;width: 100%;height: 8px;background: rgba(246, 93, 50, 0.844);border-radius: 50% 50% 0 0;transform: rotate(-2deg);z-index: -1;display: block;"></span>
                </span>
            </h2>
            <div class="text-center mb-5 w-50 mx-auto">
                <p>Sistem Informasi Manajemen Pelaporan dan Perbaikan Fasilitas Kampus Politeknik Negeri Malang
                    dihadirkan untuk membantu mempermudah kegiatan pelaporan dan perbaikan sarana dan prasarana di
                    Politeknik Negeri Malang. Dengan adanya sistem ini, diharapkan seluruh kegiatan pelaporan dan
                    perbaikan sarana dan prasarana dapat dilakukan secara terstruktur dan terpadu dalam SIM-SARPRAS
                    POLINEMA.</p>
            </div>

            <div>
                <div class="row row-cols-1 row-cols-md-3 g-4 gap-4 justify-content-center mt-5">
                    <div class="card col-md-3">
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
                    <div class="card col-md-6">
                        <div class="p-3">
                            <img src="{{ asset('assets/img/addFasility.png') }}" alt="">
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4 gap-4 justify-content-center mt-5">
                    <div class="card col-md-6">
                        <div class="p-3">
                            <img src="{{ asset('assets/img/reportFacility.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card col-md-3">
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
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4 gap-4 justify-content-center mt-5">
                    <div class="card col-md-3">
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
                    <div class="card col-md-6">
                        <div class="p-3">
                            <img src="{{ asset('assets/img/addFasility.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Alur --}}
    <div id="alur-section" class="vh-100" style="scroll-margin-top: 40px;padding-top: 40px;">
        <h2 class="text-center mb-4">Alur <span class="text-secondary"
                style="position: relative; display: inline-block; font-weight: 700;">Sim Sarpras
                <span
                    style="position: absolute;left: 0;bottom: 0;width: 100%;height: 8px;background: rgba(246, 93, 50, 0.844);border-radius: 50% 50% 0 0;transform: rotate(-2deg);z-index: -1;display: block;"></span>
            </span>
        </h2>

        <div class="container mt-5">
            <div class="row" style="position: relative;">
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
        </div>
    </div>
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
