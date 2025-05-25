<x-layouts.base>
    <div>

        @if (in_array(request()->route()->getName(), [
                'dashboard',
                'profile',
                'profile-example',
                'users',
                'bootstrap-tables',
                'transactions',
                'buttons',
                'forms',
                'modals',
                'notifications',
                'typography',
                'kerusakan.fasilitas',
                'manajemen.fasilitas',
                'manajemen.gedung',
                'user.create',
                'dashboard-teknisi',
                'dashboard-sarpras',
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
                'register-example',
                'login',
                'login-example',
                'forgot-password',
                'forgot-password-example',
                'reset-password',
                'reset-password-example',
                'landing-page',
                'lihat-detail-admin'
            ]))
            {{ $slot }}

        @elseif(in_array(request()->route()->getName(), ['404', '500', 'lock']))
            {{ $slot }}
        @endif

    </div>
</x-layouts.base>
