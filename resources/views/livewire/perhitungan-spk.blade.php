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
                                <th>Urgensi (C1)</th>
                                <th>Tingkat Kerusakan (C2)</th>
                                <th>Frekuensi Penggunaan (C3)</th>
                                <th>Usia Fasilitas (C4)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $laporan = [
                                    [
                                        'nama_pelapor' => 'Nadif', 
                                        'gedung' => 'Teknik Mesin', 
                                        'ruangan' => 'LER P3', 
                                        'lantai' => 2,
                                        'fasilitas' => 'AC Split',
                                        'urgensi' => 5,
                                        'kerusakan' => 4,
                                        'penggunaan' => 3,
                                        'usia' => 2
                                    ],
                                    [
                                        'nama_pelapor' => 'Kamila', 
                                        'gedung' => 'Teknik Sipil', 
                                        'ruangan' => 'LAB 1', 
                                        'lantai' => 1,
                                        'fasilitas' => 'Projector',
                                        'urgensi' => 4,
                                        'kerusakan' => 3,
                                        'penggunaan' => 5,
                                        'usia' => 4
                                    ],
                                    [
                                        'nama_pelapor' => 'Rizky', 
                                        'gedung' => 'Informatika', 
                                        'ruangan' => 'Lab Komputer 2', 
                                        'lantai' => 3,
                                        'fasilitas' => 'Komputer',
                                        'urgensi' => 3,
                                        'kerusakan' => 5,
                                        'penggunaan' => 4,
                                        'usia' => 3
                                    ],
                                    [
                                        'nama_pelapor' => 'Dewi', 
                                        'gedung' => 'Administrasi', 
                                        'ruangan' => 'Ruang Dosen', 
                                        'lantai' => 2,
                                        'fasilitas' => 'Kursi',
                                        'urgensi' => 2,
                                        'kerusakan' => 2,
                                        'penggunaan' => 4,
                                        'usia' => 5
                                    ],
                                    [
                                        'nama_pelapor' => 'Fajar', 
                                        'gedung' => 'Perpustakaan', 
                                        'ruangan' => 'Ruang Baca', 
                                        'lantai' => 1,
                                        'fasilitas' => 'Lampu',
                                        'urgensi' => 4,
                                        'kerusakan' => 3,
                                        'penggunaan' => 5,
                                        'usia' => 2
                                    ]
                                ];
                            @endphp
                            @foreach ($laporan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['nama_pelapor'] }}</td>
                                <td>{{ $item['gedung'] }}</td>
                                <td>{{ $item['ruangan'] }}</td>
                                <td>Lantai {{ $item['lantai'] }}</td>
                                <td>{{ $item['fasilitas'] }}</td>
                                <td>{{ $item['urgensi'] }}</td>
                                <td>{{ $item['kerusakan'] }}</td>
                                <td>{{ $item['penggunaan'] }}</td>
                                <td>{{ $item['usia'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="6" class="text-center"><strong>Bobot Kriteria (W)</strong></td>
                                <td>0.35</td>
                                <td>0.25</td>
                                <td>0.25</td>
                                <td>0.15</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="6" class="text-center"><strong>Atribut Kriteria</strong></td>
                                <td>Benefit</td>
                                <td>Benefit</td>
                                <td>Benefit</td>
                                <td>Cost</td>
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
                                <th>Urgensi (C1)</th>
                                <th>Tingkat Kerusakan (C2)</th>
                                <th>Frekuensi Penggunaan (C3)</th>
                                <th>Usia Fasilitas (C4)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Hitung nilai normalisasi
                                $normalized = [];
                                $max = [
                                    'urgensi' => max(array_column($laporan, 'urgensi')),
                                    'kerusakan' => max(array_column($laporan, 'kerusakan')),
                                    'penggunaan' => max(array_column($laporan, 'penggunaan')),
                                    'usia' => max(array_column($laporan, 'usia'))
                                ];
                                
                                $min = [
                                    'usia' => min(array_column($laporan, 'usia'))
                                ];
                                
                                foreach ($laporan as $item) {
                                    $normalized[] = [
                                        'nama' => $item['nama_pelapor'],
                                        'urgensi' => round($item['urgensi'] / $max['urgensi'], 3),
                                        'kerusakan' => round($item['kerusakan'] / $max['kerusakan'], 3),
                                        'penggunaan' => round($item['penggunaan'] / $max['penggunaan'], 3),
                                        'usia' => round($min['usia'] / $item['usia'], 3)
                                    ];
                                }
                            @endphp
                            @foreach ($normalized as $index => $item)
                            <tr>
                                <td>A{{ $index + 1 }} ({{ $item['nama'] }})</td>
                                <td>{{ $item['urgensi'] }}</td>
                                <td>{{ $item['kerusakan'] }}</td>
                                <td>{{ $item['penggunaan'] }}</td>
                                <td>{{ $item['usia'] }}</td>
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
                                <th>Urgensi (C1)</th>
                                <th>Tingkat Kerusakan (C2)</th>
                                <th>Frekuensi Penggunaan (C3)</th>
                                <th>Usia Fasilitas (C4)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $bobot = [0.35, 0.25, 0.25, 0.15];
                                $weighted = [];
                                
                                foreach ($normalized as $index => $item) {
                                    $weighted[] = [
                                        'nama' => $item['nama'],
                                        'urgensi' => round($item['urgensi'] * $bobot[0], 3),
                                        'kerusakan' => round($item['kerusakan'] * $bobot[1], 3),
                                        'penggunaan' => round($item['penggunaan'] * $bobot[2], 3),
                                        'usia' => round($item['usia'] * $bobot[3], 3)
                                    ];
                                }
                            @endphp
                            @foreach ($weighted as $index => $item)
                            <tr>
                                <td>A{{ $index + 1 }} ({{ $item['nama'] }})</td>
                                <td>{{ $item['urgensi'] }}</td>
                                <td>{{ $item['kerusakan'] }}</td>
                                <td>{{ $item['penggunaan'] }}</td>
                                <td>{{ $item['usia'] }}</td>
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
                            @php
                                $aPlus = [
                                    'urgensi' => max(array_column($weighted, 'urgensi')),
                                    'kerusakan' => max(array_column($weighted, 'kerusakan')),
                                    'penggunaan' => max(array_column($weighted, 'penggunaan')),
                                    'usia' => max(array_column($weighted, 'usia'))
                                ];
                                
                                $aMin = [
                                    'urgensi' => min(array_column($weighted, 'urgensi')),
                                    'kerusakan' => min(array_column($weighted, 'kerusakan')),
                                    'penggunaan' => min(array_column($weighted, 'penggunaan')),
                                    'usia' => min(array_column($weighted, 'usia'))
                                ];
                            @endphp
                            <tr>
                                <td>Urgensi (C1)</td>
                                <td>{{ $aPlus['urgensi'] }}</td>
                                <td>{{ $aMin['urgensi'] }}</td>
                            </tr>
                            <tr>
                                <td>Tingkat Kerusakan (C2)</td>
                                <td>{{ $aPlus['kerusakan'] }}</td>
                                <td>{{ $aMin['kerusakan'] }}</td>
                            </tr>
                            <tr>
                                <td>Frekuensi Penggunaan (C3)</td>
                                <td>{{ $aPlus['penggunaan'] }}</td>
                                <td>{{ $aMin['penggunaan'] }}</td>
                            </tr>
                            <tr>
                                <td>Usia Fasilitas (C4)</td>
                                <td>{{ $aPlus['usia'] }}</td>
                                <td>{{ $aMin['usia'] }}</td>
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
                            @php
                                $results = [];
                                $ranking = [];
                                
                                foreach ($weighted as $index => $item) {
                                    $dPlus = sqrt(
                                        pow($item['urgensi'] - $aPlus['urgensi'], 2) +
                                        pow($item['kerusakan'] - $aPlus['kerusakan'], 2) +
                                        pow($item['penggunaan'] - $aPlus['penggunaan'], 2) +
                                        pow($item['usia'] - $aPlus['usia'], 2)
                                    );
                                    
                                    $dMin = sqrt(
                                        pow($item['urgensi'] - $aMin['urgensi'], 2) +
                                        pow($item['kerusakan'] - $aMin['kerusakan'], 2) +
                                        pow($item['penggunaan'] - $aMin['penggunaan'], 2) +
                                        pow($item['usia'] - $aMin['usia'], 2)
                                    );
                                    
                                    $v = $dMin / ($dPlus + $dMin);
                                    
                                    $results[] = [
                                        'nama' => $item['nama'],
                                        'dPlus' => round($dPlus, 3),
                                        'dMin' => round($dMin, 3),
                                        'v' => round($v, 3)
                                    ];
                                    
                                    $ranking[$index] = $v;
                                }
                                
                                // Urutkan ranking
                                arsort($ranking);
                                $rank = 1;
                                $finalRanking = [];
                                foreach ($ranking as $key => $value) {
                                    $finalRanking[$key] = $rank++;
                                }
                            @endphp
                            @foreach ($results as $index => $item)
                            <tr>
                                <td>A{{ $index + 1 }} ({{ $item['nama'] }})</td>
                                <td>{{ $item['dPlus'] }}</td>
                                <td>{{ $item['dMin'] }}</td>
                                <td>{{ $item['v'] }}</td>
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Urutkan hasil berdasarkan ranking
                                $sortedResults = [];
                                foreach ($finalRanking as $key => $rank) {
                                    $sortedResults[] = [
                                        'rank' => $rank,
                                        'nama' => $results[$key]['nama'],
                                        'lokasi' => $laporan[$key]['gedung'] . ', ' . $laporan[$key]['ruangan'],
                                        'fasilitas' => $laporan[$key]['fasilitas'],
                                        'nilai' => $results[$key]['v'],
                                        'status' => ($rank == 1) ? 'Prioritas Tinggi' : (($rank <= 3) ? 'Prioritas Menengah' : 'Prioritas Rendah')
                                    ];
                                }
                                
                                // Urutkan berdasarkan ranking
                                usort($sortedResults, function($a, $b) {
                                    return $a['rank'] <=> $b['rank'];
                                });
                            @endphp
                            @foreach ($sortedResults as $item)
                            <tr>
                                <td>{{ $item['rank'] }}</td>
                                <td>{{ $item['nama'] }}</td>
                                <td>{{ $item['lokasi'] }}</td>
                                <td>{{ $item['fasilitas'] }}</td>
                                <td>{{ $item['nilai'] }}</td>
                                <td>
                                    @if($item['rank'] == 1)
                                        <span class="badge bg-danger">Prioritas Tinggi</span>
                                    @elseif($item['rank'] <= 3)
                                        <span class="badge bg-warning text-dark">Prioritas Menengah</span>
                                    @else
                                        <span class="badge bg-success">Prioritas Rendah</span>
                                    @endif
                                </td>
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

</body>