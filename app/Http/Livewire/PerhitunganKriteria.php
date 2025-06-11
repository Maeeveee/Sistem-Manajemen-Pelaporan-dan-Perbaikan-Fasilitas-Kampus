<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Kriteria;
use App\Models\AhpPerbandinganKriteria;
use App\Models\AhpHasilKonsistensi;
use App\Models\AhpBobotKriteria;
use App\Models\Periode;

class PerhitunganKriteria extends Component
{
    public $perbandingan = [];
    public $selectedPeriodeId;
    public $periodes;

    protected $rules = [
        'perbandingan.*.*' => 'nullable|numeric|min:0.111111111|max:9',
        'selectedPeriodeId' => 'required|exists:periodes,id',
    ];

    protected $messages = [
        'perbandingan.*.*.required' => 'Nilai perbandingan harus diisi',
        'perbandingan.*.*.numeric' => 'Nilai harus berupa angka',
        'perbandingan.*.*.min' => 'Nilai minimal adalah 1/9 (0.111)',
        'perbandingan.*.*.max' => 'Nilai maksimal adalah 9',
        'selectedPeriodeId.required' => 'Pilih periode terlebih dahulu',
        'selectedPeriodeId.exists' => 'Periode yang dipilih tidak valid',
    ];

    public function mount()
    {
        $this->periodes = Periode::orderBy('created_at', 'desc')->get();
        $this->selectedPeriodeId = $this->periodes->first()->id ?? null;
        $this->loadPerbandingan();
    }

    public function loadPerbandingan()
    {
        $kriterias = Kriteria::all();
        $this->perbandingan = [];

        $perbandingans = [];
        if ($this->selectedPeriodeId) {
            $perbandingans = AhpPerbandinganKriteria::where('periode_id', $this->selectedPeriodeId)->get();
        }

        foreach ($kriterias as $k1) {
            foreach ($kriterias as $k2) {
                if ($k1->id < $k2->id) {
                    $perbandingan = $perbandingans
                        ? $perbandingans->first(function ($item) use ($k1, $k2) {
                            return ($item->kriteria_pertama_id == $k1->id && $item->kriteria_kedua_id == $k2->id)
                                || ($item->kriteria_pertama_id == $k2->id && $item->kriteria_kedua_id == $k1->id);
                        })
                        : null;

                    if ($perbandingan) {
                        $this->perbandingan[$k1->id][$k2->id] = $perbandingan->kriteria_pertama_id == $k1->id
                            ? $perbandingan->nilai_perbandingan
                            : 1 / $perbandingan->nilai_perbandingan;
                    } else {
                        $this->perbandingan[$k1->id][$k2->id] = null;
                    }
                }
            }
        }
    }

    public function isPeriodeAktif()
    {
        if (!$this->selectedPeriodeId) return false;

        $periodeAktif = $this->periodes->first();
        return $this->selectedPeriodeId == $periodeAktif->id;
    }

    public function updatedSelectedPeriodeId()
    {
        $this->loadPerbandingan();
    }

    public function render()
    {
        $kriterias = Kriteria::all();
        $perbandingans = $this->selectedPeriodeId
            ? AhpPerbandinganKriteria::where('periode_id', $this->selectedPeriodeId)->get()
            : collect([]);
        $bobots = $this->selectedPeriodeId
            ? AhpBobotKriteria::with('kriterias')->where('periode_id', $this->selectedPeriodeId)->get()
            : collect([]);
        $konsistensi = $this->selectedPeriodeId
            ? AhpHasilKonsistensi::where('periode_id', $this->selectedPeriodeId)->latest()->first()
            : null;

        return view('livewire.perhitungan-kriteria', compact('kriterias', 'perbandingans', 'bobots', 'konsistensi'));
    }

    public function updatePerbandingan($kriteria1_id, $kriteria2_id)
    {
        $this->validate([
            "perbandingan.{$kriteria1_id}.{$kriteria2_id}" => 'required|numeric|min:0.111111111|max:9',
            'selectedPeriodeId' => 'required|exists:periodes,id',
        ]);

        $nilai = $this->perbandingan[$kriteria1_id][$kriteria2_id] ?? null;

        $perbandingan = AhpPerbandinganKriteria::where('periode_id', $this->selectedPeriodeId)
            ->betweenKriteria($kriteria1_id, $kriteria2_id)
            ->first();

        if (!$perbandingan) {
            $perbandingan = new AhpPerbandinganKriteria();
            $perbandingan->kriteria_pertama_id = $kriteria1_id;
            $perbandingan->kriteria_kedua_id = $kriteria2_id;
            $perbandingan->periode_id = $this->selectedPeriodeId;
        }

        $perbandingan->nilai_perbandingan = $nilai >= 1 ? $nilai : 1 / $nilai;
        $perbandingan->save();

        session()->flash('success', 'Perbandingan kriteria berhasil diperbarui');
        $this->dispatchBrowserEvent('bobotUpdated');
    }

    public function calculate()
    {
        $this->validate([
            'selectedPeriodeId' => 'required|exists:periodes,id',
        ]);

        $kriterias = Kriteria::all();
        $perbandingans = AhpPerbandinganKriteria::where('periode_id', $this->selectedPeriodeId)->get();

        if ($kriterias->count() < 2) {
            session()->flash('error', 'Minimal harus ada 2 kriteria untuk melakukan perhitungan');
            return;
        }

        $requiredComparisons = ($kriterias->count() * ($kriterias->count() - 1)) / 2;
        if ($perbandingans->count() < $requiredComparisons) {
            session()->flash('error', 'Belum semua perbandingan kriteria diisi');
            return;
        }

        $matrix = $this->buildPairwiseMatrix($kriterias, $perbandingans);
        $eigenvector = $this->calculateEigenvector($matrix);
        $consistency = $this->calculateConsistency($matrix, $eigenvector);
        $this->saveResults($kriterias, $eigenvector, $consistency);

        session()->flash('success', 'Perhitungan AHP berhasil dilakukan');
    }

    public function updateBobotKriteria()
    {
        $konsistensi = AhpHasilKonsistensi::where('periode_id', $this->selectedPeriodeId)->latest()->first();
        $bobotKriteria = AhpBobotKriteria::where('periode_id', $this->selectedPeriodeId)->get();

        if (!$konsistensi || $bobotKriteria->isEmpty()) {
            session()->flash('error', 'Harap lakukan perhitungan AHP terlebih dahulu');
            return;
        }

        if (!$konsistensi->is_consistent) {
            session()->flash('error', 'Konsistensi Ratio tidak konsisten (CR > 0.1). Bobot tidak dapat diupdate.');
            return;
        }

        $totalBobot = $bobotKriteria->sum('bobot_ahp'); // Fixed: Use $bobotKriteria instead of querying again
        if (abs($totalBobot - 1.0) > 0.0001) {
            session()->flash('error', 'Total bobot tidak sama dengan 100% (' . round($totalBobot * 100, 2) . '%). Bobot tidak dapat diupdate.');
            return;
        }

        try {
            foreach ($bobotKriteria as $bobot) {
                Kriteria::where('id', $bobot->kriteria_id)
                    ->update(['bobot' => round($bobot->bobot_ahp * 100, 2)]);
            }

            session()->flash('success', 'Bobot kriteria berhasil diupdate berdasarkan perhitungan AHP');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengupdate bobot: ' . $e->getMessage());
        }
    }

    public function resetPerhitungan()
    {
        if ($this->selectedPeriodeId) {
            AhpPerbandinganKriteria::where('periode_id', $this->selectedPeriodeId)->delete();
            AhpHasilKonsistensi::where('periode_id', $this->selectedPeriodeId)->delete();
            AhpBobotKriteria::where('periode_id', $this->selectedPeriodeId)->delete();

            $this->perbandingan = [];
            session()->flash('success', 'Semua data perhitungan AHP untuk periode ini telah direset');
        } else {
            session()->flash('error', 'Pilih periode terlebih dahulu');
        }
    }

    protected function buildPairwiseMatrix($kriterias, $perbandingans)
    {
        $matrix = [];
        $size = $kriterias->count();

        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $matrix[$i][$j] = 0;
            }
        }

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
                        if ($perbandingan->kriteria_pertama_id == $kriteria1->id) {
                            $matrix[$i][$j] = $perbandingan->nilai_perbandingan;
                        } else {
                            $matrix[$i][$j] = 1 / $perbandingan->nilai_perbandingan;
                        }
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

        foreach ($matrix as $i => $row) {
            $product = 1.0;
            foreach ($row as $value) {
                if ($value <= 0) {
                    $value = 1;
                }
                $product *= $value;
            }
            $eigenvector[$i] = pow($product, 1 / $size);
        }

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

        $weightedSum = array_fill(0, $size, 0);
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $eigenvector[$j];
            }
        }

        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            if ($eigenvector[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $eigenvector[$i];
            }
        }
        $lambdaMax /= $size;

        $ci = 0;
        if ($size > 1) {
            $ci = ($lambdaMax - $size) / ($size - 1);
        }

        $randomIndex = $ri[$size] ?? 1.59;
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
            'weighted_sum_vector' => array_map(function ($val) {
                return round($val, 4);
            }, $weightedSum)
        ];
    }

    protected function saveResults($kriterias, $eigenvector, $consistency)
    {
        AhpBobotKriteria::where('periode_id', $this->selectedPeriodeId)->delete();
        AhpHasilKonsistensi::where('periode_id', $this->selectedPeriodeId)->delete();

        foreach ($kriterias as $index => $kriteria) {
            AhpBobotKriteria::create([
                'kriteria_id' => $kriteria->id,
                'bobot_ahp' => round($eigenvector[$index], 4),
                'eigen_value' => round($eigenvector[$index], 4),
                'periode_id' => $this->selectedPeriodeId,
            ]);
        }

        $totalEigen = round(array_sum($eigenvector), 4);

        AhpHasilKonsistensi::create([
            'total_eigen' => $totalEigen,
            'lambda_max' => $consistency['lambda_max'],
            'consistency_index' => $consistency['consistency_index'],
            'random_index' => $consistency['random_index'],
            'consistency_ratio' => $consistency['consistency_ratio'],
            'is_consistent' => $consistency['is_consistent'],
            'periode_id' => $this->selectedPeriodeId,
        ]);
    }

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
