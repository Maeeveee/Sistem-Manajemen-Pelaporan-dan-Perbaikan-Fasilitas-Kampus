@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Dashboard' => '',
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
<title>Dashboard</title>
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                <div class="d-block mb-3 mb-sm-0">
                    <div class="fs-5 fw-normal mb-2">Total Laporan</div>
                    <h2 class="fs-3 fw-extrabold">{{ array_sum($laporanPerBulan) }}</h2>
                    <div class="small mt-2">
                        @php
                            $bulanSekarang = array_key_last($laporanPerBulan);
                            $bulanKemarin = $bulanSekarang - 1;
                            $jumlahSekarang = $laporanPerBulan[$bulanSekarang] ?? 0;
                            $jumlahKemarin = $laporanPerBulan[$bulanKemarin] ?? 0;
                            $persen =
                                $jumlahKemarin > 0 ? (($jumlahSekarang - $jumlahKemarin) / $jumlahKemarin) * 100 : 0;
                        @endphp
                        <span class="fw-normal me-2">Bulan ini</span>
                        @if ($persen >= 0)
                            <span class="fas fa-angle-up text-success"></span>
                            <span class="text-success fw-bold">{{ number_format($persen, 2) }}%</span>
                        @else
                            <span class="fas fa-angle-down text-danger"></span>
                            <span class="text-danger fw-bold">{{ number_format(abs($persen), 2) }}%</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-4">
                    <div class="card border-0 shadow" style="background-color: #b9fac0">
                        <div class="card-body">
                            <div class="fs-5 fw-normal mb-2">Laporan Selesai</div>
                            <h2 class="fs-3 fw-extrabold">{{ $laporanSelesai }}</h2>
                            <div class="small mt-2 text-success">
                                <span class="fas fa-check-circle"></span>
                                Selesai
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow" style="background-color: #ffe9b9">
                        <div class="card-body">
                            <div class="fs-5 fw-normal mb-2">Laporan Diproses</div>
                            <h2 class="fs-3 fw-extrabold">{{ $laporanDiproses }}</h2>
                            <div class="small mt-2" style="color: #cd9600">
                                <span class="fas fa-spinner"></span>
                                Diproses
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow" style="background-color: #fac0c0">
                        <div class="card-body">
                            <div class="fs-5 fw-normal mb-2">Laporan Ditolak</div>
                            <h2 class="fs-3 fw-extrabold">{{ $laporanDitolak }}</h2>
                            <div class="small mt-2 text-danger">
                                <span class="fas fa-times-circle"></span>
                                Ditolak
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="card border-0 shadow mb-4">
                    <div class="card-header">
                        <h2 class="fs-5 fw-bold mb-0">Jumlah Laporan per Bulan</h2>
                    </div>
                    <div class="card-body">
                        <canvas id="laporanChart" height="100"></canvas>
                    </div>
                </div>
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
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
