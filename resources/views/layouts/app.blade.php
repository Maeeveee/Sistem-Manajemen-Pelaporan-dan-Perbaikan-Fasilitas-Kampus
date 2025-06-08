<x-layouts.base>
    <div>

        @if (in_array(request()->route()->getName(), [
                'dashboard',
                'profile',
                'users',
                'notifications',
                'kerusakan.fasilitas',
                "manajemen.kriteria.fasilitas" ,
                'manajemen.fasilitas',
                'manajemen.gedung',
                'user.create',
                'dashboard-teknisi',
                'dashboard-sarpras',
                'dashboard-admin',
                'history.laporan',
                'perhitungan-spk',
                'perhitungan-kriteria',
                'manajemen.subkriteria',
                'manajemen-periode',
                'verifikasi-perbaikan',
                ]))
            @include('layouts.nav')
            @include('layouts.sidenav')
            <main class="content">
                @include('layouts.topbar')
                {{ $slot }}
                @include('layouts.footer')
            </main>
            
            @elseif(in_array(request()->route()->getName(), [
                'register',
                'login',
                'landing-page',
                'lihat-detail-admin',
                'detail-history-laporan',
                'input_laporan-sarpras',
                'notifications',

            ]))
            {{ $slot }}
        @endif
    </div>
</x-layouts.base>
