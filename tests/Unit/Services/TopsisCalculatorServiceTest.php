<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TopsisCalculatorService;

class TopsisCalculatorServiceTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TopsisCalculatorService();
    }

    /** @test */
    public function it_normalizes_decision_matrix_correctly()
    {
        // Arrange - Stub decision matrix
        $decisionMatrix = [
            ['frekuensi' => 3, 'dampak' => 2, 'resiko' => 1],
            ['frekuensi' => 2, 'dampak' => 3, 'resiko' => 2],
            ['frekuensi' => 1, 'dampak' => 1, 'resiko' => 3],
        ];

        // Act
        $normalized = $this->service->normalizeMatrix($decisionMatrix);

        // Assert
        $this->assertIsArray($normalized);
        $this->assertCount(3, $normalized);
        
        // Check that all values are between 0 and 1
        foreach ($normalized as $row) {
            foreach ($row as $value) {
                $this->assertGreaterThanOrEqual(0, $value);
                $this->assertLessThanOrEqual(1, $value);
            }
        }

        // Sum of squares for each column should equal 1
        $sumSquaresFrekuensi = 0;
        foreach ($normalized as $row) {
            $sumSquaresFrekuensi += pow($row['frekuensi'], 2);
        }
        $this->assertEqualsWithDelta(1.0, $sumSquaresFrekuensi, 0.0001);
    }

    /** @test */
    public function it_applies_weights_correctly()
    {
        // Arrange
        $normalizedMatrix = [
            ['frekuensi' => 0.8, 'dampak' => 0.5, 'resiko' => 0.3],
            ['frekuensi' => 0.5, 'dampak' => 0.8, 'resiko' => 0.5],
            ['frekuensi' => 0.3, 'dampak' => 0.3, 'resiko' => 0.8],
        ];

        $weights = [
            'frekuensi' => 0.4,
            'dampak' => 0.3,
            'resiko' => 0.3,
        ];

        // Act
        $weighted = $this->service->applyWeights($normalizedMatrix, $weights);

        // Assert
        $this->assertIsArray($weighted);
        $this->assertCount(3, $weighted);
        
        // Verify calculations
        $this->assertEqualsWithDelta(0.32, $weighted[0]['frekuensi'], 0.0001); // 0.8 * 0.4
        $this->assertEqualsWithDelta(0.15, $weighted[0]['dampak'], 0.0001); // 0.5 * 0.3
        $this->assertEqualsWithDelta(0.09, $weighted[0]['resiko'], 0.0001); // 0.3 * 0.3
    }

    /** @test */
    public function it_finds_ideal_positive_and_negative_solutions()
    {
        // Arrange - Weighted matrix
        $weightedMatrix = [
            ['frekuensi' => 0.32, 'dampak' => 0.15, 'resiko' => 0.09, 'kerusakan' => 0.24],
            ['frekuensi' => 0.20, 'dampak' => 0.24, 'resiko' => 0.15, 'kerusakan' => 0.18],
            ['frekuensi' => 0.12, 'dampak' => 0.09, 'resiko' => 0.24, 'kerusakan' => 0.30],
        ];

        // Act
        $idealSolutions = $this->service->findIdealSolutions($weightedMatrix);

        // Assert
        $this->assertArrayHasKey('positive', $idealSolutions);
        $this->assertArrayHasKey('negative', $idealSolutions);
        
        // Benefit criteria: max for positive, min for negative
        $this->assertEquals(0.32, $idealSolutions['positive']['frekuensi']); // max
        $this->assertEquals(0.12, $idealSolutions['negative']['frekuensi']); // min
        
        // Cost criteria: min for positive, max for negative
        $this->assertEquals(0.09, $idealSolutions['positive']['resiko']); // min
        $this->assertEquals(0.24, $idealSolutions['negative']['resiko']); // max
    }

    /** @test */
    public function it_calculates_distances_to_ideal_solutions()
    {
        // Arrange
        $weightedMatrix = [
            ['frekuensi' => 0.32, 'dampak' => 0.15],
            ['frekuensi' => 0.20, 'dampak' => 0.24],
        ];

        $idealSolutions = [
            'positive' => ['frekuensi' => 0.32, 'dampak' => 0.24],
            'negative' => ['frekuensi' => 0.20, 'dampak' => 0.15],
        ];

        // Act
        $distances = $this->service->calculateDistances($weightedMatrix, $idealSolutions);

        // Assert
        $this->assertIsArray($distances);
        $this->assertCount(2, $distances);
        
        foreach ($distances as $distance) {
            $this->assertArrayHasKey('dPlus', $distance);
            $this->assertArrayHasKey('dMinus', $distance);
            $this->assertGreaterThanOrEqual(0, $distance['dPlus']);
            $this->assertGreaterThanOrEqual(0, $distance['dMinus']);
        }

        // First alternative should be close to positive ideal
        $this->assertLessThan($distances[0]['dMinus'], $distances[0]['dPlus']);
    }

    /** @test */
    public function it_calculates_preference_values_correctly()
    {
        // Arrange
        $distances = [
            ['dPlus' => 0.1, 'dMinus' => 0.9],
            ['dPlus' => 0.5, 'dMinus' => 0.5],
            ['dPlus' => 0.9, 'dMinus' => 0.1],
        ];

        // Act
        $preferences = $this->service->calculatePreferences($distances);

        // Assert
        $this->assertIsArray($preferences);
        $this->assertCount(3, $preferences);
        
        // Check values are between 0 and 1
        foreach ($preferences as $pref) {
            $this->assertGreaterThanOrEqual(0, $pref);
            $this->assertLessThanOrEqual(1, $pref);
        }

        // First alternative should have highest preference (close to 1)
        $this->assertEqualsWithDelta(0.9, $preferences[0], 0.0001);
        
        // Second alternative should be around 0.5
        $this->assertEqualsWithDelta(0.5, $preferences[1], 0.0001);
        
        // Third alternative should have lowest preference (close to 0)
        $this->assertEqualsWithDelta(0.1, $preferences[2], 0.0001);
    }

    /** @test */
    public function it_ranks_alternatives_correctly()
    {
        // Arrange - Preferences (higher is better)
        $preferences = [
            0 => 0.8,
            1 => 0.5,
            2 => 0.9,
            3 => 0.3,
        ];

        // Act
        $ranking = $this->service->rankAlternatives($preferences);

        // Assert
        $this->assertEquals(1, $ranking[2]); // 0.9 -> Rank 1 (highest)
        $this->assertEquals(2, $ranking[0]); // 0.8 -> Rank 2
        $this->assertEquals(3, $ranking[1]); // 0.5 -> Rank 3
        $this->assertEquals(4, $ranking[3]); // 0.3 -> Rank 4 (lowest)
    }

    /** @test */
    public function it_performs_complete_topsis_calculation()
    {
        // Arrange - Sample data representing facility damage reports
        $decisionMatrix = [
            ['frekuensi' => 3, 'dampak' => 2, 'resiko' => 1, 'kerusakan' => 3, 'estimasi' => 2, 'laporan' => 1],
            ['frekuensi' => 2, 'dampak' => 3, 'resiko' => 2, 'kerusakan' => 2, 'estimasi' => 3, 'laporan' => 2],
            ['frekuensi' => 1, 'dampak' => 1, 'resiko' => 3, 'kerusakan' => 1, 'estimasi' => 1, 'laporan' => 3],
        ];

        $weights = [
            'frekuensi' => 0.2,
            'dampak' => 0.2,
            'resiko' => 0.15,
            'kerusakan' => 0.2,
            'estimasi' => 0.15,
            'laporan' => 0.1,
        ];

        // Act
        $result = $this->service->calculate($decisionMatrix, $weights);

        // Assert
        $this->assertArrayHasKey('normalized', $result);
        $this->assertArrayHasKey('weighted', $result);
        $this->assertArrayHasKey('idealSolutions', $result);
        $this->assertArrayHasKey('distances', $result);
        $this->assertArrayHasKey('preferences', $result);
        $this->assertArrayHasKey('ranking', $result);

        // Check that we have 3 alternatives
        $this->assertCount(3, $result['normalized']);
        $this->assertCount(3, $result['weighted']);
        $this->assertCount(3, $result['distances']);
        $this->assertCount(3, $result['preferences']);
        $this->assertCount(3, $result['ranking']);

        // All preferences should be between 0 and 1
        foreach ($result['preferences'] as $pref) {
            $this->assertGreaterThanOrEqual(0, $pref);
            $this->assertLessThanOrEqual(1, $pref);
        }

        // Rankings should be 1, 2, 3
        $ranks = array_values($result['ranking']);
        sort($ranks);
        $this->assertEquals([1, 2, 3], $ranks);
    }

    /** @test */
    public function it_handles_empty_decision_matrix()
    {
        // Arrange
        $decisionMatrix = [];
        $weights = [];

        // Act
        $normalized = $this->service->normalizeMatrix($decisionMatrix);

        // Assert
        $this->assertEmpty($normalized);
    }

    /** @test */
    public function it_handles_zero_values_in_normalization()
    {
        // Arrange
        $decisionMatrix = [
            ['frekuensi' => 0, 'dampak' => 0],
            ['frekuensi' => 0, 'dampak' => 0],
        ];

        // Act
        $normalized = $this->service->normalizeMatrix($decisionMatrix);

        // Assert - Should handle division by zero
        $this->assertIsArray($normalized);
        foreach ($normalized as $row) {
            foreach ($row as $value) {
                $this->assertEquals(0, $value);
            }
        }
    }

    /** @test */
    public function it_clamps_preference_values_between_zero_and_one()
    {
        // Arrange - Edge case where denominator might cause issues
        $distances = [
            ['dPlus' => 0.0, 'dMinus' => 1.0],
            ['dPlus' => 1.0, 'dMinus' => 0.0],
            ['dPlus' => 0.0, 'dMinus' => 0.0],
        ];

        // Act
        $preferences = $this->service->calculatePreferences($distances);

        // Assert
        $this->assertEquals(1.0, $preferences[0]); // Should be 1
        $this->assertEquals(0.0, $preferences[1]); // Should be 0
        $this->assertEquals(0.0, $preferences[2]); // Should be 0 (0/0 case)
    }

    /** @test */
    public function it_maintains_weight_sum_constraint()
    {
        // Arrange
        $normalizedMatrix = [
            ['frekuensi' => 0.8, 'dampak' => 0.6],
            ['frekuensi' => 0.6, 'dampak' => 0.8],
        ];

        $weights = [
            'frekuensi' => 0.6,
            'dampak' => 0.4,
        ];

        // Verify weights sum to 1
        $this->assertEqualsWithDelta(1.0, array_sum($weights), 0.0001);

        // Act
        $weighted = $this->service->applyWeights($normalizedMatrix, $weights);

        // Assert - Weighted values should be proportional
        $this->assertIsArray($weighted);
        $this->assertLessThanOrEqual($normalizedMatrix[0]['frekuensi'], $weighted[0]['frekuensi']);
        $this->assertLessThanOrEqual($normalizedMatrix[0]['dampak'], $weighted[0]['dampak']);
    }
}
