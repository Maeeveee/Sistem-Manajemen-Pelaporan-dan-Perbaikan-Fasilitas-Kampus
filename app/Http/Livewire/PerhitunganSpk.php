<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerhitunganSpk extends Component
{
    public $laporan = [];
    public $normalized = [];
    public $weighted = [];
    public $aPlus = [];
    public $aMin = [];
    public $results = [];
    public $finalRanking = [];
    public $sortedResults = [];
    public $bobot = [];

    public function mount()
    {
        // Ambil bobot dari tabel kriteria
        $this->bobot = DB::table('kriterias')
            ->select('nama_kriteria', 'bobot')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = match ($item->nama_kriteria) {
                    'Frekuensi Penggunaan Fasilitas' => 'frekuensi',
                    'Dampak Terhadap Aktivitas Akademik' => 'dampak',
                    'Tingkat Resiko Keselamatan' => 'resiko',
                    'Tingkat Kerusakan' => 'kerusakan',
                    'Estimasi Waktu' => 'estimasi',
                    'Banyaknya Laporan' => 'laporan',
                    default => null,
                };
                return $key ? [$key => $item->bobot / 100] : [];
            })
            ->toArray();
    }

    public function calculateTopsis()
    {
        try {
            // Delete dependent records first to respect foreign key constraints
            DB::table('penilaian')->delete();
            DB::table('hasil_topsis')->delete();
            DB::table('solusi_ideal_positif')->delete();
            DB::table('solusi_ideal_negatif')->delete();
            DB::table('matriks_normalisasi_bobot_keputusan')->delete();
            DB::table('matriks_normalisasi_keputusan')->delete();
            DB::table('matriks_keputusan')->delete();
            DB::table('alternatif')->delete();

            // Reset arrays
            $this->laporan = [];
            $this->normalized = [];
            $this->weighted = [];
            $this->aPlus = [];
            $this->aMin = [];
            $this->results = [];
            $this->finalRanking = [];
            $this->sortedResults = [];

            // Ambil data laporan yang lengkap
            $rawLaporan = DB::table('laporan_kerusakan')
                ->join('gedung', 'laporan_kerusakan.gedung_id', '=', 'gedung.id')
                ->join('ruangan', 'laporan_kerusakan.ruangan_id', '=', 'ruangan.id')
                ->join('fasilitas', 'laporan_kerusakan.fasilitas_id', '=', 'fasilitas.id')
                ->leftJoin('sub_kriterias as sk_frekuensi', function ($join) {
                    $join->on('laporan_kerusakan.frekuensi_penggunaan_fasilitas', '=', 'sk_frekuensi.id')
                        ->where('sk_frekuensi.kriterias_id', 7);
                })
                ->leftJoin('sub_kriterias as sk_dampak', function ($join) {
                    $join->on('laporan_kerusakan.dampak_terhadap_aktivitas_akademik', '=', 'sk_dampak.id')
                        ->where('sk_dampak.kriterias_id', 8);
                })
                ->leftJoin('sub_kriterias as sk_resiko', function ($join) {
                    $join->on('laporan_kerusakan.tingkat_resiko_keselamatan', '=', 'sk_resiko.id')
                        ->where('sk_resiko.kriterias_id', 9);
                })
                ->leftJoin('sub_kriterias as sk_kerusakan', function ($join) {
                    $join->on('laporan_kerusakan.tingkat_kerusakan', '=', 'sk_kerusakan.id')
                        ->where('sk_kerusakan.kriterias_id', 11);
                })
                ->leftJoin('sub_kriterias as sk_estimasi', 'laporan_kerusakan.sub_kriteria_id', '=', 'sk_estimasi.id')
                ->select(
                    'laporan_kerusakan.id',
                    'laporan_kerusakan.nama_pelapor',
                    'gedung.nama_gedung as gedung',
                    'ruangan.nama_ruangan as ruangan',
                    'laporan_kerusakan.lantai',
                    'fasilitas.nama_fasilitas as fasilitas',
                    'fasilitas.id as fasilitas_id',
                    'laporan_kerusakan.gedung_id',
                    'laporan_kerusakan.ruangan_id',
                    'sk_frekuensi.nilai as frekuensi',
                    'sk_dampak.nilai as dampak',
                    'sk_resiko.nilai as resiko',
                    'sk_kerusakan.nilai as kerusakan',
                    'sk_estimasi.nilai as estimasi',
                    'laporan_kerusakan.created_at'
                )
                ->whereNotNull('frekuensi_penggunaan_fasilitas')
                ->whereNotNull('dampak_terhadap_aktivitas_akademik')
                ->whereNotNull('tingkat_resiko_keselamatan')
                ->whereNotNull('tingkat_kerusakan')
                ->whereIn('laporan_kerusakan.sub_kriteria_id', [34, 35, 36])
                ->orderBy('laporan_kerusakan.created_at', 'asc')
                ->get();

            // Proses penggabungan laporan dan simpan ke tabel alternatif
            $this->laporan = $this->processGroupedReports($rawLaporan);

            // Perhitungan TOPSIS dan simpan ke database
            $this->performTopsisCalculation();

        } catch (\Exception $e) {
            Log::error('Error in calculateTopsis: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghitung TOPSIS. Silakan coba lagi.');
        }
    }

    private function processGroupedReports($rawLaporan)
    {
        $groupedReports = [];
        $processedKeys = [];

        foreach ($rawLaporan as $item) {
            $key = $item->gedung_id . '_' . $item->lantai . '_' . $item->ruangan_id . '_' . $item->fasilitas;

            if (!in_array($key, $processedKeys)) {
                $totalLaporan = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->count();

                $representativeReport = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->first();

                $nilai_laporan = 1.00;
                if ($totalLaporan >= 2 && $totalLaporan <= 4) {
                    $nilai_laporan = 2.00;
                } elseif ($totalLaporan >= 5) {
                    $nilai_laporan = 3.00;
                }

                $avgFrekuensi = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->avg('frekuensi');
                $avgDampak = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->avg('dampak');
                $avgResiko = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->avg('resiko');
                $avgKerusakan = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->avg('kerusakan');
                $avgEstimasi = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->avg('estimasi');

                $allReporters = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->pluck('nama_pelapor')
                    ->unique()
                    ->take(3)
                    ->toArray();

                $displayName = implode(', ', $allReporters);
                if ($totalLaporan > 3) {
                    $displayName .= ' (+' . ($totalLaporan - 3) . ' lainnya)';
                }

                // Simpan ke tabel alternatif dengan timestamps
                try {
                    $alternatifId = DB::table('alternatif')->insertGetId([
                        'objek_id' => $representativeReport->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error inserting into alternatif table: ' . $e->getMessage());
                    session()->flash('error', 'Gagal menyimpan data ke tabel alternatif. Silakan coba lagi.');
                    return $groupedReports;
                }

                $groupedReports[] = [
                    'id' => $representativeReport->id,
                    'alternatif_id' => $alternatifId,
                    'nama_pelapor' => $displayName,
                    'gedung' => $representativeReport->gedung,
                    'ruangan' => $representativeReport->ruangan,
                    'lantai' => $representativeReport->lantai,
                    'fasilitas' => $representativeReport->fasilitas,
                    'gedung_id' => $representativeReport->gedung_id,
                    'ruangan_id' => $representativeReport->ruangan_id,
                    'fasilitas_id' => $representativeReport->fasilitas_id,
                    'frekuensi' => $avgFrekuensi ?? 1.00,
                    'dampak' => $avgDampak ?? 1.00,
                    'resiko' => $avgResiko ?? 1.00,
                    'kerusakan' => $avgKerusakan ?? 1.00,
                    'estimasi' => $avgEstimasi ?? 1.00,
                    'banyaknya_laporan' => $nilai_laporan,
                    'total_laporan_asli' => $totalLaporan,
                    'created_at' => $representativeReport->created_at,
                ];

                $processedKeys[] = $key;
            }
        }

        return $groupedReports;
    }

    private function performTopsisCalculation()
    {
        if (empty($this->laporan)) {
            return;
        }

        try {
            // Langkah 1: Simpan ke matriks_keputusan
            $kriteriaIds = DB::table('kriterias')->pluck('id', 'nama_kriteria')->toArray();
            foreach ($this->laporan as $item) {
                DB::table('matriks_keputusan')->insert([
                    [
                        'nilai' => $item['frekuensi'],
                        'kriteria_id' => $kriteriaIds['Frekuensi Penggunaan Fasilitas'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $item['dampak'],
                        'kriteria_id' => $kriteriaIds['Dampak Terhadap Aktivitas Akademik'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $item['resiko'],
                        'kriteria_id' => $kriteriaIds['Tingkat Resiko Keselamatan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $item['kerusakan'],
                        'kriteria_id' => $kriteriaIds['Tingkat Kerusakan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $item['estimasi'],
                        'kriteria_id' => $kriteriaIds['Estimasi Waktu'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $item['banyaknya_laporan'],
                        'kriteria_id' => $kriteriaIds['Banyaknya Laporan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // Langkah 2: Normalisasi menggunakan Vector Normalization (Euclidean)
            $sumSquares = [
                'frekuensi' => 0,
                'dampak' => 0,
                'resiko' => 0,
                'kerusakan' => 0,
                'estimasi' => 0,
                'laporan' => 0,
            ];

            foreach ($this->laporan as $item) {
                $sumSquares['frekuensi'] += pow($item['frekuensi'], 2);
                $sumSquares['dampak'] += pow($item['dampak'], 2);
                $sumSquares['resiko'] += pow($item['resiko'], 2);
                $sumSquares['kerusakan'] += pow($item['kerusakan'], 2);
                $sumSquares['estimasi'] += pow($item['estimasi'], 2);
                $sumSquares['laporan'] += pow($item['banyaknya_laporan'], 2);
            }

            $sqrtSums = [
                'frekuensi' => sqrt($sumSquares['frekuensi']),
                'dampak' => sqrt($sumSquares['dampak']),
                'resiko' => sqrt($sumSquares['resiko']),
                'kerusakan' => sqrt($sumSquares['kerusakan']),
                'estimasi' => sqrt($sumSquares['estimasi']),
                'laporan' => sqrt($sumSquares['laporan']),
            ];

            // Lakukan normalisasi dan simpan ke matriks_normalisasi_keputusan
            $this->normalized = [];
            foreach ($this->laporan as $index => $item) {
                $normalizedItem = [
                    'nama_pelapor' => $item['nama_pelapor'],
                    'frekuensi' => $sqrtSums['frekuensi'] > 0 ? $item['frekuensi'] / $sqrtSums['frekuensi'] : 0,
                    'dampak' => $sqrtSums['dampak'] > 0 ? $item['dampak'] / $sqrtSums['dampak'] : 0,
                    'resiko' => $sqrtSums['resiko'] > 0 ? $item['resiko'] / $sqrtSums['resiko'] : 0,
                    'kerusakan' => $sqrtSums['kerusakan'] > 0 ? $item['kerusakan'] / $sqrtSums['kerusakan'] : 0,
                    'estimasi' => $sqrtSums['estimasi'] > 0 ? $item['estimasi'] / $sqrtSums['estimasi'] : 0,
                    'laporan' => $sqrtSums['laporan'] > 0 ? $item['banyaknya_laporan'] / $sqrtSums['laporan'] : 0,
                ];
                $this->normalized[] = $normalizedItem;

                DB::table('matriks_normalisasi_keputusan')->insert([
                    [
                        'nilai' => $normalizedItem['frekuensi'],
                        'alternatif_id' => $item['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Frekuensi Penggunaan Fasilitas'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $normalizedItem['dampak'],
                        'alternatif_id' => $item['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Dampak Terhadap Aktivitas Akademik'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $normalizedItem['resiko'],
                        'alternatif_id' => $item['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Tingkat Resiko Keselamatan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $normalizedItem['kerusakan'],
                        'alternatif_id' => $item['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Tingkat Kerusakan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $normalizedItem['estimasi'],
                        'alternatif_id' => $item['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Estimasi Waktu'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $normalizedItem['laporan'],
                        'alternatif_id' => $item['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Banyaknya Laporan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // Langkah 3: Normalisasi Terbobot dan simpan ke matriks_normalisasi_bobot_keputusan
            $this->weighted = [];
            foreach ($this->normalized as $index => $item) {
                $weightedItem = [
                    'nama_pelapor' => $item['nama_pelapor'],
                    'frekuensi' => $item['frekuensi'] * ($this->bobot['frekuensi'] ?? 0),
                    'dampak' => $item['dampak'] * ($this->bobot['dampak'] ?? 0),
                    'resiko' => $item['resiko'] * ($this->bobot['resiko'] ?? 0),
                    'kerusakan' => $item['kerusakan'] * ($this->bobot['kerusakan'] ?? 0),
                    'estimasi' => $item['estimasi'] * ($this->bobot['estimasi'] ?? 0),
                    'laporan' => $item['laporan'] * ($this->bobot['laporan'] ?? 0),
                ];
                $this->weighted[] = $weightedItem;

                DB::table('matriks_normalisasi_bobot_keputusan')->insert([
                    [
                        'nilai' => $weightedItem['frekuensi'],
                        'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Frekuensi Penggunaan Fasilitas'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $weightedItem['dampak'],
                        'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Dampak Terhadap Aktivitas Akademik'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $weightedItem['resiko'],
                        'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Tingkat Resiko Keselamatan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $weightedItem['kerusakan'],
                        'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Tingkat Kerusakan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $weightedItem['estimasi'],
                        'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Estimasi Waktu'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'nilai' => $weightedItem['laporan'],
                        'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                        'kriteria_id' => $kriteriaIds['Banyaknya Laporan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // Langkah 4: Solusi Ideal Positif (A+) dan Negatif (A-)
            if (!empty($this->weighted)) {
                // Initialize A+ and A- with values and alternatif_id
                $this->aPlus = [
                    'frekuensi' => ['value' => max(array_column($this->weighted, 'frekuensi')), 'alternatif_id' => null],
                    'dampak' => ['value' => max(array_column($this->weighted, 'dampak')), 'alternatif_id' => null],
                    'resiko' => ['value' => min(array_column($this->weighted, 'resiko')), 'alternatif_id' => null],
                    'kerusakan' => ['value' => max(array_column($this->weighted, 'kerusakan')), 'alternatif_id' => null],
                    'estimasi' => ['value' => min(array_column($this->weighted, 'estimasi')), 'alternatif_id' => null],
                    'laporan' => ['value' => max(array_column($this->weighted, 'laporan')), 'alternatif_id' => null],
                ];

                $this->aMin = [
                    'frekuensi' => ['value' => min(array_column($this->weighted, 'frekuensi')), 'alternatif_id' => null],
                    'dampak' => ['value' => min(array_column($this->weighted, 'dampak')), 'alternatif_id' => null],
                    'resiko' => ['value' => max(array_column($this->weighted, 'resiko')), 'alternatif_id' => null],
                    'kerusakan' => ['value' => min(array_column($this->weighted, 'kerusakan')), 'alternatif_id' => null],
                    'estimasi' => ['value' => max(array_column($this->weighted, 'estimasi')), 'alternatif_id' => null],
                    'laporan' => ['value' => min(array_column($this->weighted, 'laporan')), 'alternatif_id' => null],
                ];

                // Identify contributing alternatives for A+ and A-
                foreach ($this->weighted as $index => $weightedItem) {
                    $altId = $this->laporan[$index]['alternatif_id'];
                    if ($weightedItem['frekuensi'] == $this->aPlus['frekuensi']['value']) {
                        $this->aPlus['frekuensi']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['dampak'] == $this->aPlus['dampak']['value']) {
                        $this->aPlus['dampak']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['resiko'] == $this->aPlus['resiko']['value']) {
                        $this->aPlus['resiko']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['kerusakan'] == $this->aPlus['kerusakan']['value']) {
                        $this->aPlus['kerusakan']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['estimasi'] == $this->aPlus['estimasi']['value']) {
                        $this->aPlus['estimasi']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['laporan'] == $this->aPlus['laporan']['value']) {
                        $this->aPlus['laporan']['alternatif_id'] = $altId;
                    }

                    if ($weightedItem['frekuensi'] == $this->aMin['frekuensi']['value']) {
                        $this->aMin['frekuensi']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['dampak'] == $this->aMin['dampak']['value']) {
                        $this->aMin['dampak']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['resiko'] == $this->aMin['resiko']['value']) {
                        $this->aMin['resiko']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['kerusakan'] == $this->aMin['kerusakan']['value']) {
                        $this->aMin['kerusakan']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['estimasi'] == $this->aMin['estimasi']['value']) {
                        $this->aMin['estimasi']['alternatif_id'] = $altId;
                    }
                    if ($weightedItem['laporan'] == $this->aMin['laporan']['value']) {
                        $this->aMin['laporan']['alternatif_id'] = $altId;
                    }
                }

                // Simpan Solusi Ideal Positif
                foreach ($this->aPlus as $key => $data) {
                    DB::table('solusi_ideal_positif')->insert([
                        'nilai' => $data['value'],
                        'kriteria_id' => $kriteriaIds[match ($key) {
                            'frekuensi' => 'Frekuensi Penggunaan Fasilitas',
                            'dampak' => 'Dampak Terhadap Aktivitas Akademik',
                            'resiko' => 'Tingkat Resiko Keselamatan',
                            'kerusakan' => 'Tingkat Kerusakan',
                            'estimasi' => 'Estimasi Waktu',
                            'laporan' => 'Banyaknya Laporan',
                        }],
                        'alternatif_id' => $data['alternatif_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Simpan Solusi Ideal Negatif
                foreach ($this->aMin as $key => $data) {
                    DB::table('solusi_ideal_negatif')->insert([
                        'nilai' => $data['value'],
                        'kriteria_id' => $kriteriaIds[match ($key) {
                            'frekuensi' => 'Frekuensi Penggunaan Fasilitas',
                            'dampak' => 'Dampak Terhadap Aktivitas Akademik',
                            'resiko' => 'Tingkat Resiko Keselamatan',
                            'kerusakan' => 'Tingkat Kerusakan',
                            'estimasi' => 'Estimasi Waktu',
                            'laporan' => 'Banyaknya Laporan',
                        }],
                        'alternatif_id' => $data['alternatif_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Langkah 5: Hitung Jarak Euclidean dan Nilai Preferensi
            $this->results = [];
            foreach ($this->weighted as $index => $item) {
                $dPlus = sqrt(
                    pow($item['frekuensi'] - $this->aPlus['frekuensi']['value'], 2) +
                    pow($item['dampak'] - $this->aPlus['dampak']['value'], 2) +
                    pow($item['resiko'] - $this->aPlus['resiko']['value'], 2) +
                    pow($item['kerusakan'] - $this->aPlus['kerusakan']['value'], 2) +
                    pow($item['estimasi'] - $this->aPlus['estimasi']['value'], 2) +
                    pow($item['laporan'] - $this->aPlus['laporan']['value'], 2)
                );

                $dMin = sqrt(
                    pow($item['frekuensi'] - $this->aMin['frekuensi']['value'], 2) +
                    pow($item['dampak'] - $this->aMin['dampak']['value'], 2) +
                    pow($item['resiko'] - $this->aMin['resiko']['value'], 2) +
                    pow($item['kerusakan'] - $this->aMin['kerusakan']['value'], 2) +
                    pow($item['estimasi'] - $this->aMin['estimasi']['value'], 2) +
                    pow($item['laporan'] - $this->aMin['laporan']['value'], 2)
                );

                $denominator = $dPlus + $dMin;
                $v = ($denominator > 0) ? $dMin / $denominator : 0;
                $v = max(0, min(1, $v));

                $this->results[] = [
                    'nama_pelapor' => $item['nama_pelapor'],
                    'dPlus' => $dPlus,
                    'dMin' => $dMin,
                    'v' => $v,
                ];

                // Simpan ke hasil_topsis
                DB::table('hasil_topsis')->insert([
                    'nilai' => $v,
                    'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Langkah 6: Ranking
            $ranking = [];
            foreach ($this->results as $index => $result) {
                $ranking[$index] = $result['v'];
            }

            arsort($ranking, SORT_NUMERIC);

            $this->finalRanking = [];
            $rank = 1;
            foreach ($ranking as $key => $value) {
                $this->finalRanking[$key] = $rank++;
            }

            // Langkah 7: Susun hasil akhir
            $this->sortedResults = [];
            foreach ($this->finalRanking as $key => $rank) {
                $status = 'Prioritas Rendah';
                if ($rank == 1) {
                    $status = 'Prioritas Tinggi';
                } elseif ($rank <= 3) {
                    $status = 'Prioritas Menengah';
                }

                $this->sortedResults[] = [
                    'rank' => $rank,
                    'nama' => $this->laporan[$key]['nama_pelapor'],
                    'lokasi' => $this->laporan[$key]['gedung'] . ', ' . $this->laporan[$key]['ruangan'] . ' (Lantai ' . $this->laporan[$key]['lantai'] . ')',
                    'fasilitas' => $this->laporan[$key]['fasilitas'],
                    'nilai' => round($this->results[$key]['v'], 4),
                    'status' => $status,
                    'total_laporan' => $this->laporan[$key]['total_laporan_asli'],
                    'original_index' => $key,
                    'dPlus' => round($this->results[$key]['dPlus'], 4),
                    'dMin' => round($this->results[$key]['dMin'], 4),
                ];
            }

            usort($this->sortedResults, function ($a, $b) {
                return $a['rank'] <=> $b['rank'];
            });

        } catch (\Exception $e) {
            Log::error('Error in performTopsisCalculation: ' . $e->getMessage());
            session()->flash('error', 'Gagal melakukan perhitungan TOPSIS. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.perhitungan-spk', [
            'laporan' => $this->laporan,
            'normalized' => $this->normalized,
            'weighted' => $this->weighted,
            'aPlus' => $this->aPlus,
            'aMin' => $this->aMin,
            'results' => $this->results,
            'finalRanking' => $this->finalRanking,
            'sortedResults' => $this->sortedResults,
            'bobot' => $this->bobot,
        ]);
    }
}