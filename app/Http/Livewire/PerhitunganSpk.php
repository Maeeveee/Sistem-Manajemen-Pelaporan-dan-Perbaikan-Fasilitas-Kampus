<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\LaporanKerusakan;
use App\Models\HasilTopsis;
use App\Models\Periode;

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
    public $periodeId;
    public $daftarPeriode;

    // Properti untuk modal
    public $showProsesModal = false;
    public $showDetailModal = false;
    public $selectedLaporanId;
    public $teknisiId;
    public $laporanId;
    public $statusPerbaikan;
    public $catatanTeknisi;
    public $estimasiWaktu;
    public $daftarTeknisi;
    public $laporanDetail;
    public $hasilSpkDetail;
    public $rankingDetail;
    
    private $criteriaMap = [
        'Frekuensi Penggunaan Fasilitas' => 'frekuensi',
        'Dampak Terhadap Aktivitas Akademik' => 'dampak',
        'Tingkat Resiko Keselamatan' => 'resiko',
        'Tingkat Kerusakan' => 'kerusakan',
        'Estimasi Waktu' => 'estimasi',
        'Banyaknya Laporan' => 'laporan',
    ];

    public function mount()
    {
        // Ambil daftar periode
        $this->daftarPeriode = Periode::all();
        // Set periode default (misalnya periode terbaru)
        $this->periodeId = $this->daftarPeriode->first()->id ?? null;

        // Ambil bobot kriteria untuk periode tertentu
        $this->bobot = DB::table('ahp_bobot_kriteria')
            ->where('periode_id', $this->periodeId)
            ->select('kriteria_id', 'bobot_ahp')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = DB::table('kriterias')->where('id', $item->kriteria_id)->value('nama_kriteria');
                $key = $this->criteriaMap[$key] ?? null;
                return $key ? [$key => $item->bobot_ahp] : [];
            })
            ->toArray();

        $this->daftarTeknisi = User::where('role_id', 5)->get();
        $this->calculateTopsis();
    }
        
    public function calculateTopsis()
    {
        $this->validate([
            'periodeId' => 'required|exists:periodes,id',
        ]);

        try {
            $this->cleanupTables();
            
            $this->resetArrays();

            $rawLaporan = $this->fetchRawReports();

            $this->laporan = $this->processGroupedReports($rawLaporan);

            if (!empty($this->laporan)) {
                $this->performTopsisCalculation();
            }
        } catch (\Exception $e) {
            Log::error('Error in calculateTopsis: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghitung TOPSIS. Silakan coba lagi.');
        }
    }

    private function cleanupTables()
    {
        $tables = [
            'penilaian', 'hasil_topsis', 'solusi_ideal_positif', 
            'solusi_ideal_negatif', 'matriks_normalisasi_bobot_keputusan', 
            'matriks_normalisasi_keputusan', 'matriks_keputusan', 'alternatif'
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->delete();
        }
    }

    public function resetArrays()
    {
        $this->laporan = [];
        $this->normalized = [];
        $this->weighted = [];
        $this->aPlus = [];
        $this->aMin = [];
        $this->results = [];
        $this->finalRanking = [];
        $this->sortedResults = [];
    }

    private function fetchRawReports()
    {
        return DB::table('laporan_kerusakan')
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
            ->where('laporan_kerusakan.periode_id', $this->periodeId)
            ->orderBy('laporan_kerusakan.created_at', 'asc')
            ->get();
    }

    public function updatedPeriodeId()
    {
        // Perbarui bobot saat periode berubah
        $this->bobot = DB::table('ahp_bobot_kriteria')
            ->where('periode_id', $this->periodeId)
            ->select('kriteria_id', 'bobot_ahp')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = DB::table('kriterias')->where('id', $item->kriteria_id)->value('nama_kriteria');
                $key = $this->criteriaMap[$key] ?? null;
                return $key ? [$key => $item->bobot_ahp] : [];
            })
            ->toArray();

        $this->calculateTopsis(); // Hitung ulang TOPSIS saat periode berubah
    }

    private function processGroupedReports($rawLaporan)
    {
        $groupedReports = [];
        $processedKeys = [];

        foreach ($rawLaporan as $item) {
            $key = $item->gedung_id . '_' . $item->lantai . '_' . $item->ruangan_id . '_' . $item->fasilitas;

            if (!in_array($key, $processedKeys)) {
                $filteredReports = $rawLaporan->where('gedung_id', $item->gedung_id)
                    ->where('lantai', $item->lantai)
                    ->where('ruangan_id', $item->ruangan_id)
                    ->where('fasilitas', $item->fasilitas);
                
                $totalLaporan = $filteredReports->count();
                $representativeReport = $filteredReports->first();

                $nilai_laporan = $totalLaporan >= 5 ? 3.00 : ($totalLaporan >= 2 ? 2.00 : 1.00);

                $sumValues = [
                    'frekuensi' => $filteredReports->sum('frekuensi') ?? 1.00,
                    'dampak' => $filteredReports->sum('dampak') ?? 1.00,
                    'resiko' => $filteredReports->sum('resiko') ?? 1.00,
                    'kerusakan' => $filteredReports->sum('kerusakan') ?? 1.00,
                    'estimasi' => $filteredReports->sum('estimasi') ?? 1.00,
                ];

                $allReporters = $filteredReports->pluck('nama_pelapor')->unique()->take(3)->toArray();
                $displayName = implode(', ', $allReporters);
                if ($totalLaporan > 3) {
                    $displayName .= ' (+' . ($totalLaporan - 3) . ' lainnya)';
                }

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
                    'frekuensi' => $sumValues['frekuensi'],
                    'dampak' => $sumValues['dampak'],
                    'resiko' => $sumValues['resiko'],
                    'kerusakan' => $sumValues['kerusakan'],
                    'estimasi' => $sumValues['estimasi'],
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
        try {
            $kriteriaIds = DB::table('kriterias')->pluck('id', 'nama_kriteria')->toArray();
            
            $this->saveDecisionMatrix($kriteriaIds);
            
            $this->normalizeMatrix($kriteriaIds);
            
            $this->applyWeights($kriteriaIds);
            
            $this->findIdealSolutions($kriteriaIds);
            
            $this->calculateDistancesAndPreferences();
            
            $this->rankAlternatives();
            
        } catch (\Exception $e) {
            Log::error('Error in performTopsisCalculation: ' . $e->getMessage());
            session()->flash('error', 'Gagal melakukan perhitungan TOPSIS. Silakan coba lagi.');
        }
    }

    private function saveDecisionMatrix($kriteriaIds)
    {
        $criteriaData = [];
        
        foreach ($this->laporan as $item) {
            foreach ($this->criteriaMap as $criteriaName => $key) {
                $value = $key === 'laporan' ? $item['banyaknya_laporan'] : $item[$key];
                $criteriaData[] = [
                    'nilai' => $value,
                    'kriteria_id' => $kriteriaIds[$criteriaName],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        DB::table('matriks_keputusan')->insert($criteriaData);
    }

    private function normalizeMatrix($kriteriaIds)
    {
        $sumSquares = array_fill_keys(array_values($this->criteriaMap), 0);
        
        foreach ($this->laporan as $item) {
            foreach ($this->criteriaMap as $criteriaName => $key) {
                $value = $key === 'laporan' ? $item['banyaknya_laporan'] : $item[$key];
                $sumSquares[$key] += pow($value, 2);
            }
        }
        
        $sqrtSums = array_map('sqrt', $sumSquares);
        
        $this->normalized = [];
        $normalizedData = [];
        
        foreach ($this->laporan as $index => $item) {
            $normalizedItem = ['nama_pelapor' => $item['nama_pelapor']];
            
            foreach ($this->criteriaMap as $criteriaName => $key) {
                $value = $key === 'laporan' ? $item['banyaknya_laporan'] : $item[$key];
                $normalizedValue = $sqrtSums[$key] > 0 ? $value / $sqrtSums[$key] : 0;
                $normalizedItem[$key] = $normalizedValue;
                
                $normalizedData[] = [
                    'nilai' => $normalizedValue,
                    'alternatif_id' => $item['alternatif_id'],
                    'kriteria_id' => $kriteriaIds[$criteriaName],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $this->normalized[] = $normalizedItem;
        }
        
        DB::table('matriks_normalisasi_keputusan')->insert($normalizedData);
    }

    private function applyWeights($kriteriaIds)
    {
        $this->weighted = [];
        $weightedData = [];
        
        foreach ($this->normalized as $index => $item) {
            $weightedItem = ['nama_pelapor' => $item['nama_pelapor']];
            
            foreach ($this->criteriaMap as $criteriaName => $key) {
                $weightedValue = $item[$key] * ($this->bobot[$key] ?? 0);
                $weightedItem[$key] = $weightedValue;
                
                $weightedData[] = [
                    'nilai' => $weightedValue,
                    'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                    'kriteria_id' => $kriteriaIds[$criteriaName],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $this->weighted[] = $weightedItem;
        }
        
        DB::table('matriks_normalisasi_bobot_keputusan')->insert($weightedData);
    }

    private function findIdealSolutions($kriteriaIds)
    {
        if (empty($this->weighted)) return;
        
        $benefitCriteria = ['frekuensi', 'dampak', 'kerusakan', 'laporan'];
        $costCriteria = ['resiko', 'estimasi'];
        
        $this->aPlus = [];
        $this->aMin = [];
        
        foreach ($this->criteriaMap as $criteriaName => $key) {
            $values = array_column($this->weighted, $key);
            $isBenefit = in_array($key, $benefitCriteria);
            
            $idealValue = $isBenefit ? max($values) : min($values);
            $negativeIdealValue = $isBenefit ? min($values) : max($values);
            
            $idealAltId = null;
            $negativeIdealAltId = null;
            
            foreach ($this->weighted as $index => $item) {
                if ($item[$key] == $idealValue) {
                    $idealAltId = $this->laporan[$index]['alternatif_id'];
                }
                if ($item[$key] == $negativeIdealValue) {
                    $negativeIdealAltId = $this->laporan[$index]['alternatif_id'];
                }
            }
            
            $this->aPlus[$key] = ['value' => $idealValue, 'alternatif_id' => $idealAltId];
            $this->aMin[$key] = ['value' => $negativeIdealValue, 'alternatif_id' => $negativeIdealAltId];
            
            DB::table('solusi_ideal_positif')->insert([
                'nilai' => $idealValue,
                'kriteria_id' => $kriteriaIds[$criteriaName],
                'alternatif_id' => $idealAltId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::table('solusi_ideal_negatif')->insert([
                'nilai' => $negativeIdealValue,
                'kriteria_id' => $kriteriaIds[$criteriaName],
                'alternatif_id' => $negativeIdealAltId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function calculateDistancesAndPreferences()
    {
        $this->results = [];
        
        foreach ($this->weighted as $index => $item) {
            $dPlus = 0;
            $dMin = 0;
            
            foreach ($this->criteriaMap as $key => $value) {
                $dPlus += pow($item[$value] - $this->aPlus[$value]['value'], 2);
                $dMin += pow($item[$value] - $this->aMin[$value]['value'], 2);
            }
            
            $dPlus = sqrt($dPlus);
            $dMin = sqrt($dMin);
            
            $denominator = $dPlus + $dMin;
            $v = ($denominator > 0) ? $dMin / $denominator : 0;
            $v = max(0, min(1, $v));
            
            $this->results[] = [
                'nama_pelapor' => $item['nama_pelapor'],
                'dPlus' => $dPlus,
                'dMin' => $dMin,
                'v' => $v,
            ];
            
            DB::table('hasil_topsis')->insert([
                'nilai' => $v,
                'alternatif_id' => $this->laporan[$index]['alternatif_id'],
                'periode_id' => $this->periodeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function rankAlternatives()
    {
        $ranking = array_column($this->results, 'v');
        arsort($ranking, SORT_NUMERIC);
        
        $this->finalRanking = [];
        $rank = 1;
        foreach ($ranking as $key => $value) {
            $this->finalRanking[$key] = $rank++;
        }
        
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
    }

    public function openProsesModal($laporanId)
    {
        $this->laporanId = $laporanId;
        $this->daftarTeknisi = \App\Models\User::where('role_id', 5)->get(); // Asumsi role_id 5 = teknisi
        $this->showProsesModal = true;
    }

    public function closeProsesModal()
    {
        $this->reset([
            'selectedLaporanId',
            'teknisiId',
            'statusPerbaikan',
            'catatanTeknisi'
        ]);
        $this->showProsesModal = false;
    }

    public function prosesLaporan()
    {
        $this->validate([
            'teknisiId' => 'required|exists:users,id',
            'statusPerbaikan' => 'required|in:menunggu,diproses,selesai,ditolak'
        ]);

        try {
            $updated = \App\Models\LaporanKerusakan::where('id', $this->laporanId)->update([
                'teknisi_id' => $this->teknisiId,
                'status_perbaikan' => $this->statusPerbaikan, 
                'catatan_teknisi' => $this->catatanTeknisi, // Gunakan kolom yang ada
                'updated_at' => now()
            ]);
    
            if ($updated) {
                session()->flash('message', 'Laporan berhasil diproses');
                $this->closeProsesModal();
                $this->emit('refreshComponent'); // Trigger refresh jika perlu
            } else {
                session()->flash('error', 'Gagal memproses laporan');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error: '.$e->getMessage());
        }
    }

    // Method untuk modal detail
    public function openDetailModal($laporanId)
    {
        $this->laporanDetail = LaporanKerusakan::with([
            'gedung', 
            'ruangan', 
            'fasilitas', 
            'teknisi',
            'subKriteria'
        ])->find($laporanId);

        $this->hasilSpkDetail = HasilTopsis::where('alternatif_id', $laporanId)->first();

        if ($this->hasilSpkDetail) {
            $this->rankingDetail = HasilTopsis::where('nilai', '>', $this->hasilSpkDetail->nilai)
                ->count() + 1;
        }

        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->reset(['laporanDetail', 'hasilSpkDetail', 'rankingDetail']);
        $this->showDetailModal = false;
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