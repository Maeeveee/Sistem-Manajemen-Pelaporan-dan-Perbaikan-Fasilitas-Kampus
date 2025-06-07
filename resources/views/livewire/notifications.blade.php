@php
    $user = auth()->user();
@endphp

@forelse($notifications as $notification)
    @php
        $href = 'javascript:void(0)';
        if ($user) {
            if ($user->role_id == 6) {
                $href = route('dashboard-admin');
            } elseif ($user->role_id == 4) {
                $href = route('dashboard-sarpras');
            } elseif ($user->role_id == 1 || $user->role_id == 2 || $user->role_id == 3) {
                $href = route('history.laporan');
            } elseif ($user->role_id == 5) {
                $href = route('dashboard-teknisi');
            }
        }
    @endphp
    <a href="{{ $href }}" class="list-group-item list-group-item-action border-bottom"
        data-id="{{ $notification['report_id'] }}">
        <div class="row align-items-center">
            <div class="col-auto">
                <img alt="Laporan" src="{{ asset('storage/' . $notification['foto']) }}" class="avatar-md rounded">
            </div>
            <div class="col ps-0 ms-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="h6 mb-0 text-small">Sistem</h4>
                    </div>
                    <div class="text-end">
                        <small>{{ $notification['time'] }}</small>
                    </div>
                </div>
                <p class="font-small mt-1 mb-0">{{ $notification['message'] }}</p>
            </div>
        </div>
    </a>
@empty
    <a href="#" class="list-group-item list-group-item-action border-bottom">
        <p class="font-small mt-1 mb-0 text-center">Tidak ada notifikasi baru.</p>
    </a>
@endforelse