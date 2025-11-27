<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\AhpCalculatorService;

class AhpCalculatorServiceTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AhpCalculatorService();
    }

    /** @test */
    public function it_can_build_pairwise_matrix_with_three_criteria()
    {
        // Arrange - Stub data
        $kriterias = [
            ['id' => 1, 'nama_kriteria' => 'Frekuensi'],
            ['id' => 2, 'nama_kriteria' => 'Dampak'],
            ['id' => 3, 'nama_kriteria' => 'Resiko'],
        ];

        $perbandingans = [
            [
                'kriteria_pertama_id' => 1,
                'kriteria_kedua_id' => 2,
                'nilai_perbandingan' => 3 // Frekuensi 3x lebih penting dari Dampak
            ],
            [
                'kriteria_pertama_id' => 1,
                'kriteria_kedua_id' => 3,
                'nilai_perbandingan' => 5 // Frekuensi 5x lebih penting dari Resiko
            ],
            [
                'kriteria_pertama_id' => 2,
                'kriteria_kedua_id' => 3,
                'nilai_perbandingan' => 2 // Dampak 2x lebih penting dari Resiko
            ],
        ];

        // Act
        $matrix = $this->service->buildPairwiseMatrix($kriterias, $perbandingans);

        // Assert
        $this->assertIsArray($matrix);
        $this->assertCount(3, $matrix);
        $this->assertCount(3, $matrix[0]);
        
        // Check diagonal elements are 1
        $this->assertEquals(1, $matrix[0][0]);
        $this->assertEquals(1, $matrix[1][1]);
        $this->assertEquals(1, $matrix[2][2]);
        
        // Check comparison values
        $this->assertEquals(3, $matrix[0][1]); // Frekuensi vs Dampak
        $this->assertEquals(1/3, $matrix[1][0]); // Reciprocal
        $this->assertEquals(5, $matrix[0][2]); // Frekuensi vs Resiko
        $this->assertEquals(1/5, $matrix[2][0]); // Reciprocal
    }

    /** @test */
    public function it_validates_matrix_reciprocity()
    {
        // Arrange - Valid reciprocal matrix
        $validMatrix = [
            [1, 3, 5],
            [1/3, 1, 2],
            [1/5, 1/2, 1],
        ];

        // Act & Assert
        $this->assertTrue($this->service->validateMatrixReciprocity($validMatrix));
    }

    /** @test */
    public function it_detects_invalid_matrix_reciprocity()
    {
        // Arrange - Invalid matrix (not reciprocal)
        $invalidMatrix = [
            [1, 3, 5],
            [1/2, 1, 2], // Should be 1/3
            [1/5, 1/2, 1],
        ];

        // Act & Assert
        $this->assertFalse($this->service->validateMatrixReciprocity($invalidMatrix));
    }

    /** @test */
    public function it_calculates_eigenvector_correctly()
    {
        // Arrange - Sample pairwise matrix
        $matrix = [
            [1, 3, 5],
            [1/3, 1, 2],
            [1/5, 1/2, 1],
        ];

        // Act
        $eigenvector = $this->service->calculateEigenvector($matrix);

        // Assert
        $this->assertIsArray($eigenvector);
        $this->assertCount(3, $eigenvector);
        
        // Sum of eigenvector should be approximately 1
        $sum = array_sum($eigenvector);
        $this->assertEqualsWithDelta(1.0, $sum, 0.0001);
        
        // First element should be largest (most important)
        $this->assertGreaterThan($eigenvector[1], $eigenvector[0]);
        $this->assertGreaterThan($eigenvector[2], $eigenvector[0]);
        
        // All values should be positive
        foreach ($eigenvector as $value) {
            $this->assertGreaterThan(0, $value);
        }
    }

    /** @test */
    public function it_calculates_consistency_ratio_for_consistent_matrix()
    {
        // Arrange - Perfectly consistent matrix
        $matrix = [
            [1, 3, 9],
            [1/3, 1, 3],
            [1/9, 1/3, 1],
        ];
        
        $eigenvector = $this->service->calculateEigenvector($matrix);

        // Act
        $consistency = $this->service->calculateConsistency($matrix, $eigenvector);

        // Assert
        $this->assertIsArray($consistency);
        $this->assertArrayHasKey('consistency_ratio', $consistency);
        $this->assertArrayHasKey('is_consistent', $consistency);
        $this->assertArrayHasKey('lambda_max', $consistency);
        $this->assertArrayHasKey('consistency_index', $consistency);
        
        // CR should be very close to 0 for consistent matrix
        $this->assertLessThan(0.01, $consistency['consistency_ratio']);
        $this->assertTrue($consistency['is_consistent']);
    }

    /** @test */
    public function it_detects_inconsistent_matrix()
    {
        // Arrange - Inconsistent matrix
        $matrix = [
            [1, 9, 2],
            [1/9, 1, 9],
            [1/2, 1/9, 1],
        ];
        
        $eigenvector = $this->service->calculateEigenvector($matrix);

        // Act
        $consistency = $this->service->calculateConsistency($matrix, $eigenvector);

        // Assert
        // CR should be > 0.1 for inconsistent matrix
        $this->assertGreaterThan(0.1, $consistency['consistency_ratio']);
        $this->assertFalse($consistency['is_consistent']);
    }

    /** @test */
    public function it_handles_two_criteria_matrix()
    {
        // Arrange
        $kriterias = [
            ['id' => 1, 'nama_kriteria' => 'Kriteria A'],
            ['id' => 2, 'nama_kriteria' => 'Kriteria B'],
        ];

        $perbandingans = [
            [
                'kriteria_pertama_id' => 1,
                'kriteria_kedua_id' => 2,
                'nilai_perbandingan' => 4
            ],
        ];

        // Act
        $matrix = $this->service->buildPairwiseMatrix($kriterias, $perbandingans);
        $eigenvector = $this->service->calculateEigenvector($matrix);
        $consistency = $this->service->calculateConsistency($matrix, $eigenvector);

        // Assert
        $this->assertCount(2, $matrix);
        $this->assertCount(2, $eigenvector);
        // For 2x2 matrix, CR should always be 0 (always consistent)
        $this->assertEquals(0, $consistency['consistency_ratio']);
        $this->assertTrue($consistency['is_consistent']);
    }

    /** @test */
    public function it_handles_empty_comparisons()
    {
        // Arrange
        $kriterias = [
            ['id' => 1, 'nama_kriteria' => 'Kriteria A'],
            ['id' => 2, 'nama_kriteria' => 'Kriteria B'],
        ];
        $perbandingans = [];

        // Act
        $matrix = $this->service->buildPairwiseMatrix($kriterias, $perbandingans);

        // Assert - Should create identity matrix
        $this->assertEquals(1, $matrix[0][0]);
        $this->assertEquals(1, $matrix[0][1]);
        $this->assertEquals(1, $matrix[1][0]);
        $this->assertEquals(1, $matrix[1][1]);
    }

    /** @test */
    public function it_calculates_random_index_correctly()
    {
        // Arrange - Test with different matrix sizes
        $testCases = [
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
        ];

        foreach ($testCases as $size => $expectedRI) {
            // Create identity matrix of given size
            $matrix = [];
            for ($i = 0; $i < $size; $i++) {
                for ($j = 0; $j < $size; $j++) {
                    $matrix[$i][$j] = ($i == $j) ? 1 : 1;
                }
            }

            $eigenvector = $this->service->calculateEigenvector($matrix);
            
            // Act
            $consistency = $this->service->calculateConsistency($matrix, $eigenvector);

            // Assert
            $this->assertEquals($expectedRI, $consistency['random_index']);
        }
    }

    /** @test */
    public function it_handles_large_matrix_with_six_criteria()
    {
        // Arrange - 6 criteria matrix
        $kriterias = [];
        for ($i = 1; $i <= 6; $i++) {
            $kriterias[] = ['id' => $i, 'nama_kriteria' => "Kriteria $i"];
        }

        // Create all pairwise comparisons
        $perbandingans = [];
        for ($i = 1; $i <= 6; $i++) {
            for ($j = $i + 1; $j <= 6; $j++) {
                $perbandingans[] = [
                    'kriteria_pertama_id' => $i,
                    'kriteria_kedua_id' => $j,
                    'nilai_perbandingan' => rand(1, 9)
                ];
            }
        }

        // Act
        $matrix = $this->service->buildPairwiseMatrix($kriterias, $perbandingans);
        $eigenvector = $this->service->calculateEigenvector($matrix);
        $consistency = $this->service->calculateConsistency($matrix, $eigenvector);

        // Assert
        $this->assertCount(6, $matrix);
        $this->assertCount(6, $eigenvector);
        $this->assertArrayHasKey('consistency_ratio', $consistency);
        $this->assertEquals(1.24, $consistency['random_index']); // RI for 6 criteria
    }
}
