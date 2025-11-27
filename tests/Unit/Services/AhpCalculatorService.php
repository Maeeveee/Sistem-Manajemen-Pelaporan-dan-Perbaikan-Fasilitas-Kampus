<?php

namespace App\Services;

/**
 * Service untuk perhitungan AHP (Analytical Hierarchy Process)
 * Memisahkan business logic dari Livewire component untuk kemudahan testing
 */
class AhpCalculatorService
{
    /**
     * Build pairwise comparison matrix
     *
     * @param array $kriterias
     * @param array $perbandingans
     * @return array
     */
    public function buildPairwiseMatrix($kriterias, $perbandingans)
    {
        $matrix = [];
        $size = count($kriterias);

        // Initialize matrix
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $matrix[$i][$j] = 0;
            }
        }

        // Fill matrix
        foreach ($kriterias as $i => $kriteria1) {
            foreach ($kriterias as $j => $kriteria2) {
                if ($i == $j) {
                    $matrix[$i][$j] = 1;
                } else {
                    $perbandingan = collect($perbandingans)->first(function ($item) use ($kriteria1, $kriteria2) {
                        return ($item['kriteria_pertama_id'] == $kriteria1['id'] && $item['kriteria_kedua_id'] == $kriteria2['id']) ||
                            ($item['kriteria_pertama_id'] == $kriteria2['id'] && $item['kriteria_kedua_id'] == $kriteria1['id']);
                    });

                    if ($perbandingan) {
                        if ($perbandingan['kriteria_pertama_id'] == $kriteria1['id']) {
                            $matrix[$i][$j] = $perbandingan['nilai_perbandingan'];
                        } else {
                            $matrix[$i][$j] = 1 / $perbandingan['nilai_perbandingan'];
                        }
                    } else {
                        $matrix[$i][$j] = 1;
                    }
                }
            }
        }

        return $matrix;
    }

    /**
     * Calculate eigenvector (priority weights)
     *
     * @param array $matrix
     * @return array
     */
    public function calculateEigenvector($matrix)
    {
        $size = count($matrix);
        $eigenvector = array_fill(0, $size, 0);

        // Calculate geometric mean for each row
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

        // Normalize
        $sum = array_sum($eigenvector);
        if ($sum > 0) {
            foreach ($eigenvector as &$value) {
                $value /= $sum;
            }
        }

        return $eigenvector;
    }

    /**
     * Calculate consistency ratio
     *
     * @param array $matrix
     * @param array $eigenvector
     * @return array
     */
    public function calculateConsistency($matrix, $eigenvector)
    {
        $size = count($matrix);

        // Random Index values
        $ri = [
            1 => 0, 2 => 0, 3 => 0.58, 4 => 0.90, 5 => 1.12,
            6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49,
            11 => 1.51, 12 => 1.48, 13 => 1.56, 14 => 1.57, 15 => 1.59
        ];

        // Calculate weighted sum vector
        $weightedSum = array_fill(0, $size, 0);
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $eigenvector[$j];
            }
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            if ($eigenvector[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $eigenvector[$i];
            }
        }
        $lambdaMax /= $size;

        // Calculate CI
        $ci = 0;
        if ($size > 1) {
            $ci = ($lambdaMax - $size) / ($size - 1);
        }

        // Calculate CR
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

    /**
     * Validate matrix reciprocity
     *
     * @param array $matrix
     * @return bool
     */
    public function validateMatrixReciprocity($matrix)
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
