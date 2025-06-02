<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Kriteria;
use App\Models\AhpPerbandinganKriteria;
use App\Models\AhpHasilKonsistensi;
use App\Models\AhpBobotKriteria;

class PerhitunganKriteria extends Component
{
    public $kriteria_pertama;
    public $kriteria_kedua;
    public $nilai;

    protected $rules = [
        'kriteria_pertama' => 'required|different:kriteria_kedua',
        'kriteria_kedua' => 'required|different:kriteria_pertama',
        'nilai' => 'required|numeric|min:1|max:9'
    ];

    protected $messages = [
        'kriteria_pertama.different' => 'Kriteria pertama dan kedua harus berbeda',
        'kriteria_kedua.different' => 'Kriteria pertama dan kedua harus berbeda'
    ];

    public function render()
    {
        $kriterias = Kriteria::all();
        $perbandingans = AhpPerbandinganKriteria::all();
        $bobots = AhpBobotKriteria::with('kriterias')->get();
        $konsistensi = AhpHasilKonsistensi::latest()->first();

        return view('livewire.perhitungan-kriteria', compact('kriterias', 'perbandingans', 'bobots', 'konsistensi'));
    }

    public function store()
    {
        $this->validate();

        $existing = AhpPerbandinganKriteria::where(function ($query) {
            $query->where('kriteria_pertama_id', $this->kriteria_pertama)
                ->where('kriteria_kedua_id', $this->kriteria_kedua);
        })->orWhere(function ($query) {
            $query->where('kriteria_pertama_id', $this->kriteria_kedua)
                ->where('kriteria_kedua_id', $this->kriteria_pertama);
        })->first();

        if ($existing) {
            $this->addError('kriteria_pertama', 'Perbandingan antara kriteria ini sudah ada');
            return;
        }

        AhpPerbandinganKriteria::create([
            'kriteria_pertama_id' => $this->kriteria_pertama,
            'kriteria_kedua_id' => $this->kriteria_kedua,
            'nilai_perbandingan' => $this->nilai
        ]);

        $this->reset(['kriteria_pertama', 'kriteria_kedua', 'nilai']);
        session()->flash('success', 'Perbandingan kriteria berhasil disimpan');
    }

    public function calculate()
    {
        $kriterias = Kriteria::all();
        $perbandingans = AhpPerbandinganKriteria::all();

        if ($kriterias->count() < 2) {
            session()->flash('error', 'Minimal harus ada 2 kriteria untuk melakukan perhitungan');
            return;
        }

        // Check if all comparisons are complete
        $requiredComparisons = ($kriterias->count() * ($kriterias->count() - 1)) / 2;
        if ($perbandingans->count() < $requiredComparisons) {
            session()->flash('error', 'Belum semua perbandingan kriteria diisi');
            return;
        }

        // Build pairwise comparison matrix
        $matrix = $this->buildPairwiseMatrix($kriterias, $perbandingans);

        // Calculate priority vector (eigenvector)
        $eigenvector = $this->calculateEigenvector($matrix);

        // Calculate consistency
        $consistency = $this->calculateConsistency($matrix, $eigenvector);

        // Save results
        $this->saveResults($kriterias, $eigenvector, $consistency);

        session()->flash('success', 'Perhitungan AHP berhasil dilakukan');
    }

    public function updateBobotKriteria()
    {
        $konsistensi = AhpHasilKonsistensi::latest()->first();
        $bobotKriteria = AhpBobotKriteria::all();
        
        // Check if calculation has been done
        if (!$konsistensi || $bobotKriteria->isEmpty()) {
            session()->flash('error', 'Harap lakukan perhitungan AHP terlebih dahulu');
            return;
        }
        
        // Check consistency
        if (!$konsistensi->is_consistent) {
            session()->flash('error', 'Konsistensi Ratio tidak konsisten (CR > 0.1). Bobot tidak dapat diupdate.');
            return;
        }
        
        // Check if total is 100% (or close to it, considering floating point)
        $totalBobot = $bobotKriteria->sum('bobot_ahp');
        if (abs($totalBobot - 1.0) > 0.0001) { // Allow for small floating point differences
            session()->flash('error', 'Total bobot tidak sama dengan 100% ('.round($totalBobot*100, 2).'%). Bobot tidak dapat diupdate.');
            return;
        }
        
        // Update kriteria bobot
        try {
            foreach ($bobotKriteria as $bobot) {
                Kriteria::where('id', $bobot->kriteria_id)
                    ->update(['bobot' => $bobot->bobot_ahp * 100]);
            }
            
            session()->flash('success', 'Bobot kriteria berhasil diupdate berdasarkan perhitungan AHP');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengupdate bobot: '.$e->getMessage());
        }
    }

    protected function buildPairwiseMatrix($kriterias, $perbandingans)
    {
        $matrix = [];
        $size = $kriterias->count();

        foreach ($kriterias as $i => $kriteria1) {
            foreach ($kriterias as $j => $kriteria2) {
                if ($i == $j) {
                    $matrix[$i][$j] = 1;
                } else {
                    $perbandingan = $perbandingans->first(function ($item) use ($kriteria1, $kriteria2) {
                        return ($item->kriteria_pertama_id == $kriteria1->id && $item->kriteria_kedua_id == $kriteria2->id) ||
                            ($item->kriteria_pertama_id == $kriteria2->id && $item->kriteria_kedua_id == $kriteria1->id);
                    });

                    if ($perbandingan) {
                        $matrix[$i][$j] = ($perbandingan->kriteria_pertama_id == $kriteria1->id)
                            ? $perbandingan->nilai_perbandingan
                            : 1 / $perbandingan->nilai_perbandingan;
                    } else {
                        $matrix[$i][$j] = 1;
                    }
                }
            }
        }

        return $matrix;
    }

    protected function calculateEigenvector($matrix)
    {
        $size = count($matrix);
        $eigenvector = array_fill(0, $size, 0);

        // Calculate geometric mean for each row
        foreach ($matrix as $i => $row) {
            $product = 1.0;
            foreach ($row as $value) {
                $product *= $value;
            }
            $eigenvector[$i] = pow($product, 1 / $size);
        }

        // Normalize the eigenvector
        $sum = array_sum($eigenvector);
        foreach ($eigenvector as &$value) {
            $value /= $sum;
        }

        return $eigenvector;
    }

    protected function calculateConsistency($matrix, $eigenvector)
    {
        $size = count($matrix);
        $ri = [0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49]; // Random Index values

        // Calculate weighted sum vector
        $weightedSum = array_fill(0, $size, 0);
        foreach ($matrix as $i => $row) {
            foreach ($row as $j => $value) {
                $weightedSum[$i] += $value * $eigenvector[$j];
            }
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            $lambdaMax += $weightedSum[$i] / $eigenvector[$i];
        }
        $lambdaMax /= $size;

        // Calculate consistency index
        $ci = ($lambdaMax - $size) / ($size - 1);

        // Calculate consistency ratio
        $cr = $ci / ($ri[$size] ?? 1.49);

        return [
    'lambda_max' => $lambdaMax,
    'consistency_index' => $ci,
    'random_index' => $ri[$size] ?? 1.49,
    'consistency_ratio' => $cr,
    'is_consistent' => $cr <= 0.1,
    'weighted_sum_vector' => $weightedSum, // tambahan
];

    }

    protected function saveResults($kriterias, $eigenvector, $consistency)
    {
        // Clear previous results
        AhpBobotKriteria::truncate();
        AhpHasilKonsistensi::truncate();

        // Save weights
        foreach ($kriterias as $index => $kriteria) {
            AhpBobotKriteria::create([
                'kriteria_id' => $kriteria->id,
                'bobot_ahp' => $eigenvector[$index],
                'eigen_value' => $eigenvector[$index]
            ]);
        }

        // Calculate total eigen (sum of all eigen values)
        $totalEigen = array_sum($eigenvector);

        // Save consistency
        AhpHasilKonsistensi::create([
            'total_eigen' => $totalEigen,
            'lambda_max' => $consistency['lambda_max'],
            'consistency_index' => $consistency['consistency_index'],
            'random_index' => $consistency['random_index'],
            'consistency_ratio' => $consistency['consistency_ratio'],
            'is_consistent' => $consistency['is_consistent']
             // Menambahkan total eigen yang dihitung
        ]);
    }

    public function resetPerhitungan()
    {
        AhpPerbandinganKriteria::truncate();
        AhpHasilKonsistensi::truncate();
        AhpBobotKriteria::truncate();

        session()->flash('success', 'Semua data perhitungan AHP telah direset');
    }
}