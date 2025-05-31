@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Sarana Prasarana' => '',
            'Evaluasi Laporan Kerusakan (CRITIC TOPSIS)' => route('dashboard-sarpras'),
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

<title>Evaluasi Laporan - SPK CRITIC TOPSIS</title>

<body>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <h2 class="h4">Evaluasi Laporan Kerusakan dengan Metode CRITIC TOPSIS</h2>
        <p class="mb-0">Sistem Pendukung Keputusan untuk Prioritas Perbaikan Fasilitas Kampus</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Matriks Keputusan Awal</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Pelapor</th>
                                <th>Gedung</th>
                                <th>Ruangan</th>
                                <th>Lantai</th>
                                <th>Fasilitas</th>
                                <th>Frekuensi Penggunaan (C1)</th>
                                <th>Dampak Akademik (C2)</th>
                                <th>Tingkat Resiko (C3)</th>
                                <th>Tingkat Kerusakan (C4)</th>
                                <th>Estimasi Waktu (C5)</th>
                                <th>Banyaknya Laporan (C6)</th>
                                <th>Jumlah Laporan Asli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['nama_pelapor'] }}</td>
                                <td>{{ $item['gedung'] }}</td>
                                <td>{{ $item['ruangan'] }}</td>
                                <td>Lantai {{ $item['lantai'] }}</td>
                                <td>{{ $item['fasilitas'] }}</td>
                                <td>{{ number_format($item['frekuensi'], 2) }}</td>
                                <td>{{ number_format($item['dampak'], 2) }}</td>
                                <td>{{ number_format($item['resiko'], 2) }}</td>
                                <td>{{ number_format($item['kerusakan'], 2) }}</td>
                                <td>{{ number_format($item['estimasi'], 2) }}</td>
                                <td>{{ number_format($item['banyaknya_laporan'], 2) }}</td>
                                <td>{{ $item['total_laporan_asli'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="6" class="text-center"><strong>Bobot Kriteria (W)</strong></td>
                                <td>{{ number_format($bobot['frekuensi'] ?? 0, 4) }}</td>
                                <td>{{ number_format($bobot['dampak'] ?? 0, 4) }}</td>
                                <td>{{ number_format($bobot['resiko'] ?? 0, 4) }}</td>
                                <td>{{ number_format($bobot['kerusakan'] ?? 0, 4) }}</td>
                                <td>{{ number_format($bobot['estimasi'] ?? 0, 4) }}</td>
                                <td>{{ number_format($bobot['laporan'] ?? 0, 4) }}</td>
                                <td>-</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="6" class="text-center"><strong>Atribut Kriteria</strong></td>
                                <td>Benefit</td>
                                <td>Benefit</td>
                                <td>Cost</td>
                                <td>Benefit</td>
                                <td>Cost</td>
                                <td>Benefit</td>
                                <td>-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Matriks Ternormalisasi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Alternatif</th>
                                <th>Frekuensi Penggunaan (C1)</th>
                                <th>Dampak Akademik (C2)</th>
                                <th>Tingkat Resiko (C3)</th>
                                <th>Tingkat Kerusakan (C4)</th>
                                <th>Estimasi Waktu (C5)</th>
                                <th>Banyaknya Laporan (C6)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($normalized as $index => $item)
                            <tr>
                                <td>A{{ $index + 1 }} ({{ $item['nama_pelapor'] }})</td>
                                <td>{{ number_format($item['frekuensi'], 3) }}</td>
                                <td>{{ number_format($item['dampak'], 3) }}</td>
                                <td>{{ number_format($item['resiko'], 3) }}</td>
                                <td>{{ number_format($item['kerusakan'], 3) }}</td>
                                <td>{{ number_format($item['estimasi'], 3) }}</td>
                                <td>{{ number_format($item['laporan'], 3) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Matriks Ternormalisasi Terbobot</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Alternatif</th>
                                <th>Frekuensi Penggunaan (C1)</th>
                                <th>Dampak Akademik (C2)</th>
                                <th>Tingkat Resiko (C3)</th>
                                <th>Tingkat Kerusakan (C4)</th>
                                <th>Estimasi Waktu (C5)</th>
                                <th>Banyaknya Laporan (C6)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($weighted as $index => $item)
                            <tr>
                                <td>A{{ $index + 1 }} ({{ $item['nama_pelapor'] }})</td>
                                <td>{{ number_format($item['frekuensi'], 3) }}</td>
                                <td>{{ number_format($item['dampak'], 3) }}</td>
                                <td>{{ number_format($item['resiko'], 3) }}</td>
                                <td>{{ number_format($item['kerusakan'], 3) }}</td>
                                <td>{{ number_format($item['estimasi'], 3) }}</td>
                                <td>{{ number_format($item['laporan'], 3) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Solusi Ideal Positif (A+) dan Negatif (A-)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Kriteria</th>
                                <th>A+ (Positif)</th>
                                <th>A- (Negatif)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Frekuensi Penggunaan (C1)</td>
                                <td>{{ number_format($aPlus['frekuensi'], 3) }}</td>
                                <td>{{ number_format($aMin['frekuensi'], 3) }}</td>
                            </tr>
                            <tr>
                                <td>Dampak Akademik (C2)</td>
                                <td>{{ number_format($aPlus['dampak'], 3) }}</td>
                                <td>{{ number_format($aMin['dampak'], 3) }}</td>
                            </tr>
                            <tr>
                                <td>Tingkat Resiko (C3)</td>
                                <td>{{ number_format($aPlus['resiko'], 3) }}</td>
                                <td>{{ number_format($aMin['resiko'], 3) }}</td>
                            </tr>
                            <tr>
                                <td>Tingkat Kerusakan (C4)</td>
                                <td>{{ number_format($aPlus['kerusakan'], 3) }}</td>
                                <td>{{ number_format($aMin['kerusakan'], 3) }}</td>
            </tr>
                            <tr>
                                <td>Estimasi Waktu (C5)</td>
                                <td>{{ number_format($aPlus['estimasi'], 3) }}</td>
                                <td>{{ number_format($aMin['estimasi'], 3) }}</td>
                            </tr>
                            <tr>
                                <td>Banyaknya Laporan (C6)</td>
                                <td>{{ number_format($aPlus['laporan'], 3) }}</td>
                                <td>{{ number_format($aMin['laporan'], 3) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Jarak Solusi dan Hasil Akhir</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Alternatif</th>
                                <th>Jarak ke A+ (D+)</th>
                                <th>Jarak ke A- (D-)</th>
                                <th>Nilai Preferensi (V)</th>
                                <th>Ranking</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $index => $item)
                            <tr>
                                <td>A{{ $index + 1 }} ({{ $item['nama_pelapor'] }})</td>
                                <td>{{ number_format($item['dPlus'], 3) }}</td>
                                <td>{{ number_format($item['dMin'], 3) }}</td>
                                <td>{{ number_format($item['v'], 3) }}</td>
                                <td>{{ $finalRanking[$index] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Rekomendasi Prioritas Perbaikan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Ranking</th>
                                <th>Nama Pelapor</th>
                                <th>Lokasi</th>
                                <th>Fasilitas</th>
                                <th>Nilai Preferensi</th>
                                <th>Status</th>
                                <th>Jumlah Laporan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sortedResults as $item)
                            <tr>
                                <td>{{ $item['rank'] }}</td>
                                <td>{{ $item['nama'] }}</td>
                                <td>{{ $item['lokasi'] }}</td>
                                <td>{{ $item['fasilitas'] }}</td>
                                <td>{{ number_format($item['nilai'], 3) }}</td>
                                <td>
                                    @if($item['rank'] == 1)
                                        <span class="badge bg-danger">Prioritas Tinggi</span>
                                    @elseif($item['rank'] <= 3)
                                        <span class="badge bg-warning text-dark">Prioritas Menengah</span>
                                    @else
                                        <span class="badge bg-success">Prioritas Rendah</span>
                                    @endif
                                </td>
                                <td>{{ $item['total_laporan'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">Proses</button>
                                    <button class="btn btn-sm btn-info">Detail</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Prioritas Perbaikan -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Visualisasi Prioritas Perbaikan</h5>
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('priorityChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [@foreach($sortedResults as $item)"A{{ $loop->iteration }} ({{ $item['nama'] }})",@endforeach],
            datasets: [{
                label: 'Nilai Preferensi',
                data: [@foreach($sortedResults as $item){{ $item['nilai'] }},@endforeach],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                borderColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nilai Preferensi'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Alternatif'
                    }
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
</script>
@endpush
</body>