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
                    ->update(['bobot' => round($bobot->bobot_ahp * 100, 2)]);
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

        // Initialize empty matrix
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $matrix[$i][$j] = 0;
            }
        }

        foreach ($kriterias as $i => $kriteria1) {
            foreach ($kriterias as $j => $kriteria2) {
                if ($i == $j) {
                    // Diagonal elements are always 1
                    $matrix[$i][$j] = 1;
                } else {
                    $perbandingan = $perbandingans->first(function ($item) use ($kriteria1, $kriteria2) {
                        return ($item->kriteria_pertama_id == $kriteria1->id && $item->kriteria_kedua_id == $kriteria2->id) ||
                            ($item->kriteria_pertama_id == $kriteria2->id && $item->kriteria_kedua_id == $kriteria1->id);
                    });

                    if ($perbandingan) {
                        if ($perbandingan->kriteria_pertama_id == $kriteria1->id) {
                            // Direct comparison (A vs B = x, then A is x times more important than B)
                            $matrix[$i][$j] = $perbandingan->nilai_perbandingan;
                        } else {
                            // Reverse comparison (B vs A = x, then A is 1/x times more important than B)
                            $matrix[$i][$j] = 1 / $perbandingan->nilai_perbandingan;
                        }
                    } else {
                        // This shouldn't happen if validation is correct, but set to 1 as fallback
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

        // Method 1: Geometric Mean Method (more stable)
        foreach ($matrix as $i => $row) {
            $product = 1.0;
            foreach ($row as $value) {
                if ($value <= 0) {
                    // Handle invalid values
                    $value = 1;
                }
                $product *= $value;
            }
            $eigenvector[$i] = pow($product, 1 / $size);
        }

        // Normalize the eigenvector (sum should equal 1)
        $sum = array_sum($eigenvector);
        if ($sum > 0) {
            foreach ($eigenvector as &$value) {
                $value /= $sum;
            }
        }

        return $eigenvector;
    }

    protected function calculateConsistency($matrix, $eigenvector)
    {
        $size = count($matrix);
        
        // Random Index values for matrix sizes 1-15
        $ri = [
            1 => 0,
            2 => 0,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
            11 => 1.51,
            12 => 1.48,
            13 => 1.56,
            14 => 1.57,
            15 => 1.59
        ];

        // Calculate weighted sum vector (Aw)
        $weightedSum = array_fill(0, $size, 0);
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $eigenvector[$j];
            }
        }

        // Calculate lambda max (λmax)
        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            if ($eigenvector[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $eigenvector[$i];
            }
        }
        $lambdaMax /= $size;

        // Calculate Consistency Index (CI)
        $ci = 0;
        if ($size > 1) {
            $ci = ($lambdaMax - $size) / ($size - 1);
        }

        // Get Random Index (RI)
        $randomIndex = $ri[$size] ?? 1.59;

        // Calculate Consistency Ratio (CR)
        $cr = 0;
        if ($randomIndex > 0) {
            $cr = $ci / $randomIndex;
        }

        return [
            'lambda_max' => round($lambdaMax, 4),
            'consistency_index' => round($ci, 4),
            'random_index' => $randomIndex,
            'consistency_ratio' => round($cr, 4),
            'is_consistent' => $cr <= 0.1,
            'weighted_sum_vector' => array_map(function($val) { return round($val, 4); }, $weightedSum)
        ];
    }

    protected function saveResults($kriterias, $eigenvector, $consistency)
    {
        // Clear previous results
        AhpBobotKriteria::truncate();
        AhpHasilKonsistensi::truncate();

        // Save weights for each criteria
        foreach ($kriterias as $index => $kriteria) {
            AhpBobotKriteria::create([
                'kriteria_id' => $kriteria->id,
                'bobot_ahp' => round($eigenvector[$index], 4),
                'eigen_value' => round($eigenvector[$index], 4)
            ]);
        }

        // Calculate total eigen (should be 1.0 for normalized eigenvector)
        $totalEigen = round(array_sum($eigenvector), 4);

        // Save consistency results
        AhpHasilKonsistensi::create([
            'total_eigen' => $totalEigen,
            'lambda_max' => $consistency['lambda_max'],
            'consistency_index' => $consistency['consistency_index'],
            'random_index' => $consistency['random_index'],
            'consistency_ratio' => $consistency['consistency_ratio'],
            'is_consistent' => $consistency['is_consistent']
        ]);
    }

    public function resetPerhitungan()
    {
        AhpPerbandinganKriteria::truncate();
        AhpHasilKonsistensi::truncate();
        AhpBobotKriteria::truncate();

        session()->flash('success', 'Semua data perhitungan AHP telah direset');
    }

    // Helper method to display matrix for debugging
    public function displayMatrix($matrix)
    {
        $output = "Pairwise Comparison Matrix:\n";
        foreach ($matrix as $i => $row) {
            foreach ($row as $j => $value) {
                $output .= sprintf("%8.4f ", $value);
            }
            $output .= "\n";
        }
        return $output;
    }

    // Validation method to check matrix reciprocity
    protected function validateMatrixReciprocity($matrix)
    {
        $size = count($matrix);
        $tolerance = 0.0001;
        
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                if ($i != $j) {
                    $expected = 1 / $matrix[$j][$i];
                    if (abs($matrix[$i][$j] - $expected) > $tolerance) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}