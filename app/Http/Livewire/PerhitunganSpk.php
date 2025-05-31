<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

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

        // Ambil data laporan yang lengkap
        $rawLaporan = DB::table('laporan_kerusakan')
            ->join('gedung', 'laporan_kerusakan.gedung_id', '=', 'gedung.id')
            ->join('ruangan', 'laporan_kerusakan.ruangan_id', '=', 'ruangan.id')
            ->join('fasilitas', 'laporan_kerusakan.fasilitas_id', '=', 'fasilitas.id')
            ->leftJoin('sub_kriterias as sk_frekuensi', function ($join) {
                $join->on('laporan_kerusakan.frekuensi_penggunaan_fasilitas', '=', 'sk_frekuensi.id')
                    ->where('sk_frekuensi.kriterias_id', 7); // Frekuensi Penggunaan Fasilitas
            })
            ->leftJoin('sub_kriterias as sk_dampak', function ($join) {
                $join->on('laporan_kerusakan.dampak_terhadap_aktivitas_akademik', '=', 'sk_dampak.id')
                    ->where('sk_dampak.kriterias_id', 8); // Dampak Akademik
            })
            ->leftJoin('sub_kriterias as sk_resiko', function ($join) {
                $join->on('laporan_kerusakan.tingkat_resiko_keselamatan', '=', 'sk_resiko.id')
                    ->where('sk_resiko.kriterias_id', 9); // Tingkat Resiko
            })
            ->leftJoin('sub_kriterias as sk_kerusakan', function ($join) {
                $join->on('laporan_kerusakan.tingkat_kerusakan', '=', 'sk_kerusakan.id')
                    ->where('sk_kerusakan.kriterias_id', 11); // Tingkat Kerusakan
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
            ->whereIn('laporan_kerusakan.sub_kriteria_id', [34, 35, 36]) // Estimasi Waktu
            ->orderBy('laporan_kerusakan.created_at', 'asc')
            ->get();
            
        // Proses penggabungan laporan berdasarkan lokasi dan nama fasilitas
        $this->laporan = $this->processGroupedReports($rawLaporan);

        // Perhitungan TOPSIS
        $this->calculateTopsis();
    }

    private function processGroupedReports($rawLaporan)
    {
        $groupedReports = [];
        $processedKeys = [];

        foreach ($rawLaporan as $item) {
            // Buat key unik berdasarkan gedung_id, lantai, ruangan_id, dan nama_fasilitas
            $key = $item->gedung_id . '_' . $item->lantai . '_' . $item->ruangan_id . '_' . $item->fasilitas;

            if (!in_array($key, $processedKeys)) {
                // Hitung total laporan untuk lokasi dan nama fasilitas yang sama
                $totalLaporan = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->count();

                // Ambil laporan pertama (berdasarkan created_at) sebagai representatif
                $representativeReport = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas)
                    ->first();

                // Konversi total laporan ke nilai sub-kriteria
                $nilai_laporan = 1.00; // Sedikit (1 laporan)
                if ($totalLaporan >= 2 && $totalLaporan <= 4) {
                    $nilai_laporan = 2.00; // Sedang (2-4 laporan)
                } elseif ($totalLaporan >= 5) {
                    $nilai_laporan = 3.00; // Banyak (≥5 laporan)
                }

                // Ambil nilai rata-rata untuk kriteria lain
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

                // Buat nama pelapor gabungan jika ada multiple laporan
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

                $groupedReports[] = [
                    'id' => $representativeReport->id,
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
                    'created_at' => $representativeReport->created_at
                ];

                $processedKeys[] = $key;
            }
        }

        return $groupedReports;
    }

    private function calculateTopsis()
    {
        if (empty($this->laporan)) {
            return;
        }

        // Langkah 1: Normalisasi menggunakan Vector Normalization (Euclidean)
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

        // Lakukan normalisasi
        $this->normalized = [];
        foreach ($this->laporan as $item) {
            $this->normalized[] = [
                'nama_pelapor' => $item['nama_pelapor'],
                'frekuensi' => $sqrtSums['frekuensi'] > 0 ? $item['frekuensi'] / $sqrtSums['frekuensi'] : 0,
                'dampak' => $sqrtSums['dampak'] > 0 ? $item['dampak'] / $sqrtSums['dampak'] : 0,
                'resiko' => $sqrtSums['resiko'] > 0 ? $item['resiko'] / $sqrtSums['resiko'] : 0,
                'kerusakan' => $sqrtSums['kerusakan'] > 0 ? $item['kerusakan'] / $sqrtSums['kerusakan'] : 0,
                'estimasi' => $sqrtSums['estimasi'] > 0 ? $item['estimasi'] / $sqrtSums['estimasi'] : 0,
                'laporan' => $sqrtSums['laporan'] > 0 ? $item['banyaknya_laporan'] / $sqrtSums['laporan'] : 0,
            ];
        }

        // Langkah 2: Normalisasi Terbobot (Weighted Normalized Decision Matrix)
        $this->weighted = [];
        foreach ($this->normalized as $item) {
            $this->weighted[] = [
                'nama_pelapor' => $item['nama_pelapor'],
                'frekuensi' => $item['frekuensi'] * ($this->bobot['frekuensi'] ?? 0),
                'dampak' => $item['dampak'] * ($this->bobot['dampak'] ?? 0),
                'resiko' => $item['resiko'] * ($this->bobot['resiko'] ?? 0),
                'kerusakan' => $item['kerusakan'] * ($this->bobot['kerusakan'] ?? 0),
                'estimasi' => $item['estimasi'] * ($this->bobot['estimasi'] ?? 0),
                'laporan' => $item['laporan'] * ($this->bobot['laporan'] ?? 0),
            ];
        }

        // Langkah 3: Solusi Ideal Positif (A+) dan Negatif (A-)
        // Definisi berdasarkan jenis kriteria:
        // - Benefit (semakin tinggi semakin baik): frekuensi, dampak, kerusakan, laporan
        // - Cost (semakin rendah semakin baik): resiko, estimasi
        
        if (!empty($this->weighted)) {
            $this->aPlus = [
                'frekuensi' => max(array_column($this->weighted, 'frekuensi')),  // Benefit: MAX
                'dampak' => max(array_column($this->weighted, 'dampak')),        // Benefit: MAX
                'resiko' => min(array_column($this->weighted, 'resiko')),        // Cost: MIN
                'kerusakan' => max(array_column($this->weighted, 'kerusakan')),  // Benefit: MAX
                'estimasi' => min(array_column($this->weighted, 'estimasi')),    // Cost: MIN
                'laporan' => max(array_column($this->weighted, 'laporan')),      // Benefit: MAX
            ];

            $this->aMin = [
                'frekuensi' => min(array_column($this->weighted, 'frekuensi')),  // Benefit: MIN
                'dampak' => min(array_column($this->weighted, 'dampak')),        // Benefit: MIN
                'resiko' => max(array_column($this->weighted, 'resiko')),        // Cost: MAX
                'kerusakan' => min(array_column($this->weighted, 'kerusakan')),  // Benefit: MIN
                'estimasi' => max(array_column($this->weighted, 'estimasi')),    // Cost: MAX
                'laporan' => min(array_column($this->weighted, 'laporan')),      // Benefit: MIN
            ];
        }

        // Langkah 4: Hitung Jarak Euclidean ke Solusi Ideal Positif (D+) dan Negatif (D-)
        $this->results = [];
        foreach ($this->weighted as $index => $item) {
            // Jarak ke solusi ideal positif (D+)
            $dPlus = sqrt(
                pow($item['frekuensi'] - $this->aPlus['frekuensi'], 2) +
                pow($item['dampak'] - $this->aPlus['dampak'], 2) +
                pow($item['resiko'] - $this->aPlus['resiko'], 2) +
                pow($item['kerusakan'] - $this->aPlus['kerusakan'], 2) +
                pow($item['estimasi'] - $this->aPlus['estimasi'], 2) +
                pow($item['laporan'] - $this->aPlus['laporan'], 2)
            );

            // Jarak ke solusi ideal negatif (D-)
            $dMin = sqrt(
                pow($item['frekuensi'] - $this->aMin['frekuensi'], 2) +
                pow($item['dampak'] - $this->aMin['dampak'], 2) +
                pow($item['resiko'] - $this->aMin['resiko'], 2) +
                pow($item['kerusakan'] - $this->aMin['kerusakan'], 2) +
                pow($item['estimasi'] - $this->aMin['estimasi'], 2) +
                pow($item['laporan'] - $this->aMin['laporan'], 2)
            );

            // Langkah 5: Hitung Nilai Preferensi (V) menggunakan Relative Closeness
            $denominator = $dPlus + $dMin;
            $v = ($denominator > 0) ? $dMin / $denominator : 0;

            // Pastikan nilai V berada dalam rentang [0, 1]
            $v = max(0, min(1, $v));

            $this->results[] = [
                'nama_pelapor' => $item['nama_pelapor'],
                'dPlus' => $dPlus,
                'dMin' => $dMin,
                'v' => $v,
            ];
        }

        // Langkah 6: Ranking berdasarkan nilai preferensi (V) - descending order
        // Semakin tinggi nilai V, semakin baik (lebih dekat dengan ideal positif)
        $ranking = [];
        foreach ($this->results as $index => $result) {
            $ranking[$index] = $result['v'];
        }

        // Sort descending (nilai V tertinggi = ranking terbaik)
        arsort($ranking, SORT_NUMERIC);

        // Reset ranking array untuk menghindari konflik
        $this->finalRanking = [];
        $rank = 1;
        foreach ($ranking as $key => $value) {
            $this->finalRanking[$key] = $rank++;
        }

        // Langkah 7: Susun hasil akhir dengan prioritas
        $this->sortedResults = [];
        foreach ($this->finalRanking as $key => $rank) {
            // Tentukan status prioritas berdasarkan ranking
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
                // Data tambahan untuk debugging
                'dPlus' => round($this->results[$key]['dPlus'], 4),
                'dMin' => round($this->results[$key]['dMin'], 4),
            ];
        }

        // Sort berdasarkan ranking (ascending)
        usort($this->sortedResults, function ($a, $b) {
            return $a['rank'] <=> $b['rank'];
        });
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