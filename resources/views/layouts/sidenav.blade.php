<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse">
    <div class="sidebar-inner px-2 pt-3">

        {{-- View on Mobile --}}
        <div
            class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=random&color=fff"
                        class="avatar rounded-circle me-3" alt="Avatar">
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">{{ auth()->user()->name ?? 'User Name' }}</h2>
                    <a href="/" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>


        <ul class="nav flex-column pt-3 pt-md-0 ">
            {{-- Logo and Title --}}
            <li class="nav-item">
                <a href="/dashboard" class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon me-3">
                        <img src="/assets/img/brand/logo_sarpras2.png" height="50" width="50" alt="Volt Logo">
                    </span>
                    <h3 class="mt-1 ms-1 sidebar-text fw-bold">SarPolma</h3>
                </a>
            </li>

            {{-- @isset(auth()->user()->role_id)
                @php
                    $bolehSemua = [1, 2, 3, 4, 5, 6];
                    $users = [1, 2, 3];
                @endphp --}}
                {{-- Dashboard --}}
                {{-- @if (in_array(auth()->user()->role_id, $bolehSemua)) --}}
                    <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                        <a href="/dashboard" class="nav-link">
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                </svg>
                            </span>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                {{-- @endif --}}


                {{-- Kelola Pengguna --}}
                {{-- @if (auth()->user()->role_id == 6) --}}
                    <li class="nav-item">
                        <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse" data-bs-target="#submenu-Pengguna" aria-expanded="false">
                            <span>
                                <span class="sidebar-icon"><i class="fas fa-users me-2"></i></span>
                                <span class="sidebar-text">Kelola Pengguna</span>
                            </span>
                            <span class="link-arrow">
                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </span>
                        <div class="multi-level collapse" role="list" id="submenu-Pengguna" aria-expanded="true">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Request::segment(1) == 'profile' ? 'active' : '' }}">
                                    <a href="/profile" class="nav-link">
                                        <span class="sidebar-text">Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::segment(1) == 'users' ? 'active' : '' }}">
                                    <a href="/users" class="nav-link">
                                        <span class="sidebar-text">Manajemen Pengguna</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {{-- @endif --}}


                {{-- Laporan Kerusakan --}}
                {{-- @if (in_array(auth()->user()->role_id, $users)) --}}
                    <li class="nav-item {{ Request::routeIs('kerusakan.fasilitas') ? 'active' : '' }}">
                        <a href="{{ route('kerusakan.fasilitas') }}" class="nav-link">
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M18 13V6a2 2 0 00-2-2h-2V3a1 1 0 00-2 0v1H8V3a1 1 0 00-2 0v1H4a2 2 0 00-2 2v7h16zm0 2H2a1 1 0 000 2h16a1 1 0 000-2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="sidebar-text">Laporan Kerusakan</span>
                        </a>
                    </li>


                    {{-- History Laporan --}}
                    <li class="nav-item {{ Request::routeIs('history.laporan') ? 'active' : '' }}">
                        <a href="{{ route('history.laporan') }}" class="nav-link">
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M18 13V6a2 2 0 00-2-2h-2V3a1 1 0 00-2 0v1H8V3a1 1 0 00-2 0v1H4a2 2 0 00-2 2v7h16zm0 2H2a1 1 0 000 2h16a1 1 0 000-2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="sidebar-text">History Laporan</span>
                        </a>
                    </li>
                {{-- @endif --}}


                {{-- Manajemen Gedung --}}
                {{-- @if (auth()->user()->role_id == 6) --}}
                    <li class="nav-item {{ Request::routeIs('manajemen.gedung') ? 'active' : '' }}">
                        <a href="{{ route('manajemen.gedung') }}" class="nav-link">
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 2L2 7v11h16V7L10 2zm0 1.5L16 8v8H4V8l6-4.5z" />
                                    <rect x="6" y="10" width="2" height="4" />
                                    <rect x="12" y="10" width="2" height="4" />
                                    <rect x="9" y="10" width="2" height="4" />
                                </svg>
                            </span>
                            <span class="sidebar-text">Manajemen Gedung</span>
                        </a>
                    </li>


                    {{-- Manajemen Fasilitas --}}
                    <li class="nav-item {{ Request::routeIs('manajemen.fasilitas') ? 'active' : '' }}">
                        <a href="{{ route('manajemen.fasilitas') }}" class="nav-link">
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 2a1 1 0 01.894.553l6 12A1 1 0 0116 16H4a1 1 0 01-.894-1.447l6-12A1 1 0 0110 2zm0 4a1 1 0 100 2 1 1 0 000-2zm1 4H9v4h2v-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="sidebar-text">Manajemen Fasilitas</span>
                        </a>
                    </li>
                {{-- @endif --}}



                {{-- Manajemen Kriteria --}}
                {{-- @if (auth()->user()->role_id == 4) --}}
                <li class="nav-item {{ Request::routeIs('manajemen.kriteria.fasilitas') ? 'active' : '' }}">
                    <a href="{{ route('manajemen.kriteria.fasilitas') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2L2 7v11h16V7L10 2zm0 1.5L16 8v8H4V8l6-4.5z" />
                                <rect x="6" y="10" width="2" height="4" />
                                <rect x="12" y="10" width="2" height="4" />
                                <rect x="9" y="10" width="2" height="4" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Manajemen Kriteria</span>
                    </a>
                </li>


                {{-- Manajemen SubKriteria --}}
                <li class="nav-item {{ Request::routeIs('manajemen.subkriteria') ? 'active' : '' }}">
                    <a href="{{ route('manajemen.subkriteria') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2L2 7v11h16V7L10 2zm0 1.5L16 8v8H4V8l6-4.5z" />
                                <rect x="6" y="10" width="2" height="4" />
                                <rect x="12" y="10" width="2" height="4" />
                                <rect x="9" y="10" width="2" height="4" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Manajemen SubKriteria</span>
                    </a>
                </li>
                {{-- @endif --}}


                {{-- Teknisi --}}
                {{-- @if (auth()->user()->level_id == 5) --}}
                    <li class="nav-item">
                        <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse" data-bs-target="#submenu-teknisi" aria-expanded="false">
                            <span>
                                <span class="sidebar-icon"><i class="fas fa-users me-2"></i></span>
                                <span class="sidebar-text">Teknisi</span>
                            </span>
                            <span class="link-arrow">
                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </span>
                        <div class="multi-level collapse" role="list" id="submenu-teknisi" aria-expanded="false">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Request::segment(1) == 'teknisi' ? 'active' : '' }}">
                                    <a href="/teknisi" class="nav-link">
                                        <span class="sidebar-text">Laporan Pengguna</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::segment(1) == 'riwayat-perbaikan' ? 'active' : '' }}">
                                    <a href="{{ route('riwayat-perbaikan') }}" class="nav-link">
                                        <span class="sidebar-text">Riwayat Perbaikan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                    </li>
                {{-- @endif --}}


                {{-- Sarana Prasarana --}}
                {{-- @if (auth()->user()->role_id == 4) --}}
                    <li class="nav-item">
                        <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse" data-bs-target="#submenu-sarpras" aria-expanded="false">
                            <span>
                                <span class="sidebar-icon"><i class="fas fa-users me-2"></i></span>
                                <span class="sidebar-text">Sarana Prasarana</span>
                            </span>
                            <span class="link-arrow">
                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </span>
                        <div class="multi-level collapse" role="list" id="submenu-sarpras" aria-expanded="true">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Request::segment(1) == 'sarpras' ? 'active' : '' }}">
                                    <a href="/sarpras" class="nav-link">
                                        <span class="sidebar-text">Laporan Kerusakan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="multi-level collapse" role="list" id="submenu-sarpras" aria-expanded="true">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Route::is('verifikasi-perbaikan') ? 'active' : '' }}">
                                    <a href="/verifikasi-perbaikan" class="nav-link">
                                        <span class="sidebar-text">Verifikasi Perbaikan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="multi-level collapse" role="list" id="submenu-sarpras" aria-expanded="true">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Route::is('feedback-rating') ? 'active' : '' }}">
                                    <a href="/feedback-rating" class="nav-link">
                                        <span class="sidebar-text">Feedback & Rating</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="multi-level collapse" role="list" id="submenu-sarpras" aria-expanded="true">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Route::is('perhitungan-kriteria') ? 'active' : '' }}">
                                    <a href="{{ route('perhitungan-kriteria') }}" class="nav-link">
                                        <span class="sidebar-text">Perhitungan Kriteria</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="multi-level collapse" role="list" id="submenu-sarpras" aria-expanded="true">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Route::is('perhitungan-spk') ? 'active' : '' }}">
                                    <a href="{{ route('perhitungan-spk') }}" class="nav-link">
                                        <span class="sidebar-text">Perhitungan SPK</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                    </li>
                {{-- @endif --}}


                {{-- Admin --}}
                {{-- @if (auth()->user()->role_id == 6) --}}
                    <li class="nav-item">
                        <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse" data-bs-target="#submenu-admin" aria-expanded="false">
                            <span>
                                <span class="sidebar-icon"><i class="fas fa-users me-2"></i></span>
                                <span class="sidebar-text">Admin</span>
                            </span>
                            <span class="link-arrow">
                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </span>
                        <div class="multi-level collapse" role="list" id="submenu-admin" aria-expanded="false">
                            <ul class="flex-column nav">
                                <li class="nav-item {{ Route::is('manajemen-periode') ? 'active' : '' }}">
                                    <a href="{{ route('manajemen-periode') }}" class="nav-link">
                                        <span class="sidebar-text">Manajemen Periode</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::segment(1) == 'admin' ? 'active' : '' }}">
                                    <a href="/admin" class="nav-link">
                                        <span class="sidebar-text">Laporan Perbaikan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {{-- @endif --}}
            </ul>
        {{-- @endisset --}}

    </div>
</nav>
