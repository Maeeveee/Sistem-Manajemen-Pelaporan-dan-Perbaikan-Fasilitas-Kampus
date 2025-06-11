<!-- filepath: resources/views/dashboard.blade.php -->

@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Dashboard' => '',
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

<title>Dashboard</title>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <h2 class="h4">Dashboard</h2>
    </div>
</div>

<div class="row mt-4">
    <!-- Card 1: Total Laporan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Laporan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ array_sum($laporanPerBulan) }}</div>
                        <div class="small mt-2">
                            @php
                                $bulanSekarang = array_key_last($laporanPerBulan);
                                $bulanKemarin = $bulanSekarang - 1;
                                $jumlahSekarang = $laporanPerBulan[$bulanSekarang] ?? 0;
                                $jumlahKemarin = $laporanPerBulan[$bulanKemarin] ?? 0;
                                $persen = $jumlahKemarin > 0 ? (($jumlahSekarang - $jumlahKemarin) / $jumlahKemarin) * 100 : 0;
                            @endphp
                            <span class="font-weight-normal me-2">Bulan ini</span>
                            @if ($persen >= 0)
                                <span class="fas fa-angle-up text-success"></span>
                                <span class="text-success font-weight-bold">{{ number_format($persen, 1) }}%</span>
                            @else
                                <span class="fas fa-angle-down text-danger"></span>
                                <span class="text-danger font-weight-bold">{{ number_format(abs($persen), 1) }}%</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Laporan Selesai -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Laporan Selesai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $laporanSelesai }}</div>
                
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Laporan Diproses -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Laporan Diproses
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $laporanDiproses }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Laporan Ditolak -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Laporan Ditolak
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $laporanDitolak }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Chart Laporan</h6>
            </div>
            <div class="card-body">
                <canvas id="laporanChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('laporanChart').getContext('2d');
        const data = @json(array_values($laporanPerBulan));
        const labels = @json(array_map(fn($b) => 'Bulan ' . $b, array_keys($laporanPerBulan)));
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    });
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
</style>