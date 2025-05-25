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
        <div id="tentang-section" class="vh-100 mt-5" style="scroll-margin-top: 40px;padding-top: 40px;">
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

            <div class="row row-cols-1 row-cols-md-3 g-4 gap-4 justify-content-center mt-5">
                <div class="card col-md-3">
                    <div class="card-header">
                        Fitur
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Penambahan Fasilitas Kampus</h5>
                        <p class="card-text">User Dapat Menambahkan Fasilitas Baru Melalui Sistem Ini</p>
                    </div>
                </div>

                <div class="card col-md-3">
                    <div class="card-header">
                        Fitur
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">pelaporan Fasilitas Kampus</h5>
                        <p class="card-text">User Dapat Melaporkan Kerusakan Fasilitas Kampus Melalui Sistem Ini</p>
                    </div>
                </div>

                <div class="card col-md-3">
                    <div class="card-header">
                        Fitur
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Perhitungan SPK oleh Sistem</h5>
                        <p class="card-text">Sistem ini Dilengkapi dengan Perhitungan keputusan yang Akurat dan Cepat</p>
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

        <div class="row mt-5">
            <div class="col-md-6 d-flex flex-column align-items-center">

                <div class="mb-5 position-relative d-flex align-items-start">
                    <div class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; top: 0; left: 0;">
                        <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            stroke-width="3" stroke="#ffffff" fill="none">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M23.87,51.54,16.31,44,31.77,28.51,24,23.57a.66.66,0,0,1,.2-1.19l26-6.09a.65.65,0,0,1,.79.77l-5.6,26.5a.66.66,0,0,1-1.2.22l-4.91-7.7Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 1</li>
                            <li class="list-group-item">User mengakses website SIM-SARPRAS</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-5 position-relative d-flex align-items-start">
                    <div class="position-absolute  bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; top: 0; left: 0;">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M15 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H15M11 16L15 12M15 12L11 8M15 12H3"
                                    stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                    </div>

                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 2</li>
                            <li class="list-group-item">User login ke dalam SIM-SARPRAS</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-5 position-relative d-flex align-items-start">
                    <div class="position-absolute  bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; top: 0; left: 0;">
                        <svg fill="#ffffff" height="24px" width="24px" version="1.1" id="Layer_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 512 512" xml:space="preserve" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <g>
                                        <path
                                            d="M157.662,102.614c-4.427,0-8.017,3.589-8.017,8.017c0,9.725-7.912,17.637-17.637,17.637s-17.637-7.912-17.637-17.637 s7.912-17.637,17.637-17.637c4.427,0,8.017-3.589,8.017-8.017s-3.589-8.017-8.017-8.017c-18.566,0-33.67,15.105-33.67,33.67 s15.105,33.67,33.67,33.67s33.67-15.105,33.67-33.67C165.679,106.203,162.089,102.614,157.662,102.614z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M157.662,196.676c-4.427,0-8.017,3.589-8.017,8.017c0,9.725-7.912,17.637-17.637,17.637s-17.637-7.912-17.637-17.637 s7.912-17.637,17.637-17.637c4.427,0,8.017-3.589,8.017-8.017s-3.589-8.017-8.017-8.017c-18.566,0-33.67,15.105-33.67,33.67 s15.105,33.67,33.67,33.67s33.67-15.105,33.67-33.67C165.679,200.266,162.089,196.676,157.662,196.676z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M251.724,213.779h-59.858c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h59.858 c4.427,0,8.017-3.589,8.017-8.017S256.152,213.779,251.724,213.779z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M251.724,179.574h-59.858c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h59.858 c4.427,0,8.017-3.589,8.017-8.017S256.152,179.574,251.724,179.574z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M234.622,307.841h-42.756c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h42.756 c4.427,0,8.017-3.589,8.017-8.017S239.049,307.841,234.622,307.841z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M251.724,273.637h-59.858c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h59.858 c4.427,0,8.017-3.589,8.017-8.017S256.152,273.637,251.724,273.637z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M328.685,119.716H191.866c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h136.818 c4.427,0,8.017-3.589,8.017-8.017S333.112,119.716,328.685,119.716z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M294.48,85.511H191.866c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017H294.48 c4.427,0,8.017-3.589,8.017-8.017S298.908,85.511,294.48,85.511z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M157.662,290.739c-4.427,0-8.017,3.589-8.017,8.017c0,9.725-7.912,17.637-17.637,17.637s-17.637-7.912-17.637-17.637 s7.912-17.637,17.637-17.637c4.427,0,8.017-3.589,8.017-8.017s-3.589-8.017-8.017-8.017c-18.566,0-33.67,15.105-33.67,33.67 s15.105,33.67,33.67,33.67s33.67-15.105,33.67-33.67C165.679,294.328,162.089,290.739,157.662,290.739z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M362.889,0H72.15C58.3,0,47.031,11.268,47.031,25.119v359.148c0,13.851,11.268,25.119,25.119,25.119h145.37 c4.427,0,8.017-3.589,8.017-8.017c0-4.427-3.589-8.017-8.017-8.017H72.15c-5.01,0-9.086-4.076-9.086-9.086V25.119 c0-5.01,4.076-9.086,9.086-9.086h290.739c5.01,0,9.086,4.076,9.086,9.086v265.087c0,4.427,3.589,8.017,8.017,8.017 c4.427,0,8.017-3.589,8.017-8.017V25.119C388.008,11.268,376.74,0,362.889,0z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M438.578,325.094c-7.451-0.743-14.898,1.369-20.792,5.844c-4.695-7.878-12.701-13.467-21.964-14.395 c-7.453-0.742-14.899,1.37-20.792,5.844c-4.695-7.878-12.701-13.467-21.964-14.395c-5.69-0.568-11.372,0.528-16.365,3.069V208.969 c0-8.289-3.526-16.235-9.677-21.8c-6.145-5.56-14.426-8.274-22.721-7.444c-14.799,1.482-26.391,14.863-26.391,30.464v102.35 l-23.566,23.566c-12.523,12.523-17.578,30.291-13.521,47.531l17.891,76.037c7.249,30.811,34.418,52.329,66.07,52.329h72.307 c37.426,0,67.875-30.448,67.875-67.875v-88.567C464.969,339.957,453.377,326.576,438.578,325.094z M448.935,444.125 c0,28.585-23.256,51.841-51.841,51.841h-72.307c-24.175,0-44.927-16.435-50.464-39.968l-17.891-76.037 c-2.776-11.795,0.683-23.953,9.251-32.521l12.229-12.229v27.678c0,4.427,3.589,8.017,8.017,8.017s8.017-3.589,8.017-8.017V210.188 c0-7.465,5.251-13.839,11.956-14.509c3.851-0.387,7.534,0.815,10.366,3.379c2.797,2.531,4.401,6.144,4.401,9.912v141.094 c0,4.427,3.589,8.017,8.017,8.017s8.017-3.589,8.017-8.017v-12.827c0-3.768,1.603-7.381,4.401-9.912 c2.834-2.564,6.515-3.767,10.366-3.379c6.704,0.671,11.956,7.045,11.956,14.51v20.157c0,4.427,3.589,8.017,8.017,8.017 c4.427,0,8.017-3.589,8.017-8.017v-12.827c0-3.768,1.603-7.381,4.401-9.912c2.834-2.564,6.516-3.766,10.366-3.379 c6.704,0.671,11.956,7.045,11.956,14.51v20.158c0,4.427,3.589,8.017,8.017,8.017c4.427,0,8.017-3.589,8.017-8.017v-12.827 c0-3.768,1.603-7.381,4.401-9.912c2.834-2.563,6.513-3.767,10.366-3.378c6.704,0.67,11.956,7.044,11.956,14.509V444.125z">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 3</li>
                            <li class="list-group-item">User menginput fasilitas yang ingin diperbaiki</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex flex-column align-items-center">
                <div class="mb-5 position-relative d-flex align-items-start">
                    <div class="position-absolute  bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; top: 0; left: 0;">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" height="24px"
                            width="24px">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M18.22 20.75H5.78C5.43322 20.7359 5.09262 20.6535 4.77771 20.5075C4.4628 20.3616 4.17975 20.155 3.94476 19.8996C3.70977 19.6442 3.52745 19.3449 3.40824 19.019C3.28903 18.693 3.23525 18.3468 3.25 18V15C3.25 14.8011 3.32902 14.6103 3.46967 14.4697C3.61033 14.329 3.80109 14.25 4 14.25C4.19892 14.25 4.38968 14.329 4.53033 14.4697C4.67099 14.6103 4.75 14.8011 4.75 15V18C4.72419 18.2969 4.81365 18.5924 4.99984 18.8251C5.18602 19.0579 5.45465 19.21 5.75 19.25H18.22C18.5154 19.21 18.784 19.0579 18.9702 18.8251C19.1564 18.5924 19.2458 18.2969 19.22 18V15C19.22 14.8011 19.299 14.6103 19.4397 14.4697C19.5803 14.329 19.7711 14.25 19.97 14.25C20.1689 14.25 20.3597 14.329 20.5003 14.4697C20.641 14.6103 20.72 14.8011 20.72 15V18C20.75 18.6954 20.5041 19.3744 20.0359 19.8894C19.5677 20.4045 18.9151 20.7137 18.22 20.75Z"
                                    fill="#ffffff"></path>
                                <path
                                    d="M16 8.74995C15.9015 8.75042 15.8038 8.7312 15.7128 8.69342C15.6218 8.65564 15.5392 8.60006 15.47 8.52995L12 5.05995L8.53 8.52995C8.38782 8.66243 8.19978 8.73455 8.00548 8.73113C7.81118 8.7277 7.62579 8.64898 7.48838 8.51157C7.35096 8.37416 7.27225 8.18877 7.26882 7.99447C7.2654 7.80017 7.33752 7.61213 7.47 7.46995L11.47 3.46995C11.6106 3.3295 11.8012 3.25061 12 3.25061C12.1987 3.25061 12.3894 3.3295 12.53 3.46995L16.53 7.46995C16.6705 7.61058 16.7493 7.8012 16.7493 7.99995C16.7493 8.1987 16.6705 8.38932 16.53 8.52995C16.4608 8.60006 16.3782 8.65564 16.2872 8.69342C16.1962 8.7312 16.0985 8.75042 16 8.74995Z"
                                    fill="#ffffff"></path>
                                <path
                                    d="M12 15.75C11.8019 15.7474 11.6126 15.6676 11.4725 15.5275C11.3324 15.3874 11.2526 15.1981 11.25 15V4C11.25 3.80109 11.329 3.61032 11.4697 3.46967C11.6103 3.32902 11.8011 3.25 12 3.25C12.1989 3.25 12.3897 3.32902 12.5303 3.46967C12.671 3.61032 12.75 3.80109 12.75 4V15C12.7474 15.1981 12.6676 15.3874 12.5275 15.5275C12.3874 15.6676 12.1981 15.7474 12 15.75Z"
                                    fill="#ffffff"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 4</li>
                            <li class="list-group-item">User mengupload laporan perbaikan</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-5 position-relative d-flex align-items-start">
                    <div class="position-absolute  bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; top: 0; left: 0;">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" height="24px"
                            width="24px">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                                    stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M12 6V12" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M16.24 16.24L12 12" stroke="#ffffff" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-secondary">Langkah 5</li>
                            <li class="list-group-item">User menunggu laporan divalidasi oleh admin</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-5 position-relative d-flex align-items-start">
                    <div class="position-absolute  bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; top: 0; left: 0;">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" height="24px"
                            width="24px">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="#ffffff" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="card ms-5 shadow-sm" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item fw-bold text-primary">Langkah 6</li>
                            <li class="list-group-item">Selesai! fasilitas akan segera diperbaiki</li>
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