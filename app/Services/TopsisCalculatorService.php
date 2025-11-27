<?php

namespace App\Services;

/**
 * Service untuk perhitungan TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution)
 * Memisahkan business logic dari Livewire component untuk kemudahan testing
 */
class TopsisCalculatorService
{
    private $criteriaMap = [
        'frekuensi' => 'benefit',
        'dampak' => 'benefit',
        'resiko' => 'cost',
        'kerusakan' => 'benefit',
        'estimasi' => 'cost',
        'laporan' => 'benefit',
    ];

    /**
     * Normalize decision matrix using vector normalization
     *
     * @param array $decisionMatrix
     * @return array
     */
    public function normalizeMatrix($decisionMatrix)
    {
        if (empty($decisionMatrix)) {
            return [];
        }

        // Calculate sum of squares for each criterion
        $sumSquares = [];
        $criteriaKeys = array_keys($decisionMatrix[0]);
        
        foreach ($criteriaKeys as $key) {
            $sumSquares[$key] = 0;
        }

        foreach ($decisionMatrix as $alternative) {
            foreach ($criteriaKeys as $key) {
                $sumSquares[$key] += pow($alternative[$key], 2);
            }
        }

        // Calculate square root of sums
        $sqrtSums = array_map('sqrt', $sumSquares);

        // Normalize
        $normalized = [];
        foreach ($decisionMatrix as $alternative) {
            $normalizedRow = [];
            foreach ($criteriaKeys as $key) {
                $normalizedRow[$key] = $sqrtSums[$key] > 0 
                    ? $alternative[$key] / $sqrtSums[$key] 
                    : 0;
            }
            $normalized[] = $normalizedRow;
        }

        return $normalized;
    }

    /**
     * Apply weights to normalized matrix
     *
     * @param array $normalizedMatrix
     * @param array $weights
     * @return array
     */
    public function applyWeights($normalizedMatrix, $weights)
    {
        $weighted = [];

        foreach ($normalizedMatrix as $alternative) {
            $weightedRow = [];
            foreach ($alternative as $key => $value) {
                $weightedRow[$key] = $value * ($weights[$key] ?? 0);
            }
            $weighted[] = $weightedRow;
        }

        return $weighted;
    }

    /**
     * Find ideal solutions (positive and negative)
     *
     * @param array $weightedMatrix
     * @return array ['positive' => [...], 'negative' => [...]]
     */
    public function findIdealSolutions($weightedMatrix)
    {
        if (empty($weightedMatrix)) {
            return ['positive' => [], 'negative' => []];
        }

        $criteriaKeys = array_keys($weightedMatrix[0]);
        $positive = [];
        $negative = [];

        foreach ($criteriaKeys as $key) {
            $values = array_column($weightedMatrix, $key);
            $isBenefit = $this->criteriaMap[$key] === 'benefit';

            $positive[$key] = $isBenefit ? max($values) : min($values);
            $negative[$key] = $isBenefit ? min($values) : max($values);
        }

        return [
            'positive' => $positive,
            'negative' => $negative
        ];
    }

    /**
     * Calculate distance to ideal solutions
     *
     * @param array $weightedMatrix
     * @param array $idealSolutions
     * @return array
     */
    public function calculateDistances($weightedMatrix, $idealSolutions)
    {
        $distances = [];

        foreach ($weightedMatrix as $alternative) {
            $dPlus = 0;
            $dMinus = 0;

            foreach ($alternative as $key => $value) {
                $dPlus += pow($value - $idealSolutions['positive'][$key], 2);
                $dMinus += pow($value - $idealSolutions['negative'][$key], 2);
            }

            $distances[] = [
                'dPlus' => sqrt($dPlus),
                'dMinus' => sqrt($dMinus)
            ];
        }

        return $distances;
    }

    /**
     * Calculate preference values (closeness coefficient)
     *
     * @param array $distances
     * @return array
     */
    public function calculatePreferences($distances)
    {
        $preferences = [];

        foreach ($distances as $distance) {
            $denominator = $distance['dPlus'] + $distance['dMinus'];
            $v = ($denominator > 0) ? $distance['dMinus'] / $denominator : 0;
            $v = max(0, min(1, $v)); // Clamp between 0 and 1
            $preferences[] = $v;
        }

        return $preferences;
    }

    /**
     * Rank alternatives based on preference values
     *
     * @param array $preferences
     * @return array
     */
    public function rankAlternatives($preferences)
    {
        $ranking = $preferences;
        arsort($ranking, SORT_NUMERIC);

        $finalRanking = [];
        $rank = 1;
        foreach ($ranking as $key => $value) {
            $finalRanking[$key] = $rank++;
        }

        return $finalRanking;
    }

    /**
     * Complete TOPSIS calculation
     *
     * @param array $decisionMatrix
     * @param array $weights
     * @return array
     */
    public function calculate($decisionMatrix, $weights)
    {
        // Step 1: Normalize
        $normalized = $this->normalizeMatrix($decisionMatrix);

        // Step 2: Apply weights
        $weighted = $this->applyWeights($normalized, $weights);

        // Step 3: Find ideal solutions
        $idealSolutions = $this->findIdealSolutions($weighted);

        // Step 4: Calculate distances
        $distances = $this->calculateDistances($weighted, $idealSolutions);

        // Step 5: Calculate preferences
        $preferences = $this->calculatePreferences($distances);

        // Step 6: Rank alternatives
        $ranking = $this->rankAlternatives($preferences);

        return [
            'normalized' => $normalized,
            'weighted' => $weighted,
            'idealSolutions' => $idealSolutions,
            'distances' => $distances,
            'preferences' => $preferences,
            'ranking' => $ranking
        ];
    }
}
