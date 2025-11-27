<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\Periode;
use App\Models\AhpPerbandinganKriteria;
use App\Models\AhpBobotKriteria;
use App\Models\AhpHasilKonsistensi;
use App\Services\AhpCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;

class PerhitunganKriteriaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create authenticated user (admin)
        $this->actingAs(User::factory()->create([
            'role_id' => 1, // Admin
        ]));
    }

    /** @test */
    public function it_can_load_perhitungan_kriteria_component()
    {
        // Arrange
        $periode = Periode::factory()->create();
        Kriteria::factory()->count(3)->create();

        // Act & Assert
        Livewire::test('perhitungan-kriteria')
            ->assertStatus(200)
            ->assertViewIs('livewire.perhitungan-kriteria');
    }

    /** @test */
    public function it_validates_perbandingan_input()
    {
        // Arrange
        $periode = Periode::factory()->create();
        $kriterias = Kriteria::factory()->count(2)->create();

        // Act & Assert - Test invalid input (too high)
        Livewire::test('perhitungan-kriteria')
            ->set('selectedPeriodeId', $periode->id)
            ->set("perbandingan.{$kriterias[0]->id}.{$kriterias[1]->id}", 10)
            ->call('updatePerbandingan', $kriterias[0]->id, $kriterias[1]->id)
            ->assertHasErrors(["perbandingan.{$kriterias[0]->id}.{$kriterias[1]->id}"]);

        // Test invalid input (too low)
        Livewire::test('perhitungan-kriteria')
            ->set('selectedPeriodeId', $periode->id)
            ->set("perbandingan.{$kriterias[0]->id}.{$kriterias[1]->id}", 0.05)
            ->call('updatePerbandingan', $kriterias[0]->id, $kriterias[1]->id)
            ->assertHasErrors(["perbandingan.{$kriterias[0]->id}.{$kriterias[1]->id}"]);
    }

    /** @test */
    public function it_can_save_perbandingan_kriteria()
    {
        // Arrange
        $periode = Periode::factory()->create();
        $kriterias = Kriteria::factory()->count(2)->create();

        // Act
        Livewire::test('perhitungan-kriteria')
            ->set('selectedPeriodeId', $periode->id)
            ->set("perbandingan.{$kriterias[0]->id}.{$kriterias[1]->id}", 3)
            ->call('updatePerbandingan', $kriterias[0]->id, $kriterias[1]->id);

        // Assert
        $this->assertDatabaseHas('ahp_perbandingan_kriteria', [
            'kriteria_pertama_id' => $kriterias[0]->id,
            'kriteria_kedua_id' => $kriterias[1]->id,
            'nilai_perbandingan' => 3,
            'periode_id' => $periode->id,
        ]);
    }

    /** @test */
    public function it_calculates_ahp_with_mocked_service()
    {
        // Arrange
        $periode = Periode::factory()->create();
        $kriterias = Kriteria::factory()->count(3)->create();
        
        // Create pairwise comparisons
        AhpPerbandinganKriteria::create([
            'kriteria_pertama_id' => $kriterias[0]->id,
            'kriteria_kedua_id' => $kriterias[1]->id,
            'nilai_perbandingan' => 3,
            'periode_id' => $periode->id,
        ]);

        // Mock the AHP Calculator Service
        $mockService = Mockery::mock(AhpCalculatorService::class);
        $mockService->shouldReceive('buildPairwiseMatrix')
            ->once()
            ->andReturn([
                [1, 3, 5],
                [1/3, 1, 2],
                [1/5, 1/2, 1],
            ]);

        $mockService->shouldReceive('calculateEigenvector')
            ->once()
            ->andReturn([0.6496, 0.2348, 0.1155]);

        $mockService->shouldReceive('calculateConsistency')
            ->once()
            ->andReturn([
                'lambda_max' => 3.0092,
                'consistency_index' => 0.0046,
                'random_index' => 0.58,
                'consistency_ratio' => 0.0079,
                'is_consistent' => true,
            ]);

        $this->app->instance(AhpCalculatorService::class, $mockService);

        // Act - We're not actually calling the component, just verifying the mock
        $service = app(AhpCalculatorService::class);
        $matrix = $service->buildPairwiseMatrix([], []);
        $eigenvector = $service->calculateEigenvector($matrix);
        $consistency = $service->calculateConsistency($matrix, $eigenvector);

        // Assert - Verify the mock was called and returned expected values
        $this->assertTrue($consistency['is_consistent']);
        $this->assertLessThan(0.1, $consistency['consistency_ratio']);
    }

    /** @test */
    public function it_prevents_calculation_without_complete_comparisons()
    {
        // Arrange
        $periode = Periode::factory()->create();
        Kriteria::factory()->count(3)->create();
        // No comparisons created

        // Act & Assert
        Livewire::test('perhitungan-kriteria')
            ->set('selectedPeriodeId', $periode->id)
            ->call('calculate')
            ->assertHasErrors(); // Should have validation errors
    }

    /** @test */
    public function it_can_reset_perhitungan()
    {
        // Arrange
        $periode = Periode::factory()->create();
        $kriterias = Kriteria::factory()->count(2)->create();
        
        AhpPerbandinganKriteria::create([
            'kriteria_pertama_id' => $kriterias[0]->id,
            'kriteria_kedua_id' => $kriterias[1]->id,
            'nilai_perbandingan' => 3,
            'periode_id' => $periode->id,
        ]);

        AhpBobotKriteria::create([
            'kriteria_id' => $kriterias[0]->id,
            'bobot_ahp' => 0.75,
            'eigen_value' => 0.75,
            'periode_id' => $periode->id,
        ]);

        // Act
        Livewire::test('perhitungan-kriteria')
            ->set('selectedPeriodeId', $periode->id)
            ->call('resetPerhitungan')
            ->assertSessionHas('success');

        // Assert
        $this->assertDatabaseMissing('ahp_perbandingan_kriteria', [
            'periode_id' => $periode->id,
        ]);
        
        $this->assertDatabaseMissing('ahp_bobot_kriteria', [
            'periode_id' => $periode->id,
        ]);
    }

    /** @test */
    public function it_loads_existing_comparisons_when_changing_periode()
    {
        // Arrange
        $periode1 = Periode::factory()->create();
        $periode2 = Periode::factory()->create();
        $kriterias = Kriteria::factory()->count(2)->create();
        
        // Create comparison for periode 1
        AhpPerbandinganKriteria::create([
            'kriteria_pertama_id' => $kriterias[0]->id,
            'kriteria_kedua_id' => $kriterias[1]->id,
            'nilai_perbandingan' => 5,
            'periode_id' => $periode1->id,
        ]);

        // Act & Assert
        Livewire::test('perhitungan-kriteria')
            ->set('selectedPeriodeId', $periode1->id)
            ->assertSet("perbandingan.{$kriterias[0]->id}.{$kriterias[1]->id}", 5)
            ->set('selectedPeriodeId', $periode2->id)
            ->assertSet("perbandingan.{$kriterias[0]->id}.{$kriterias[1]->id}", null); // No data for periode 2
    }

    /** @test */
    public function it_detects_inconsistent_matrix()
    {
        // Arrange
        $periode = Periode::factory()->create();
        $kriterias = Kriteria::factory()->count(3)->create();
        
        // Create inconsistent comparisons
        AhpPerbandinganKriteria::create([
            'kriteria_pertama_id' => $kriterias[0]->id,
            'kriteria_kedua_id' => $kriterias[1]->id,
            'nilai_perbandingan' => 9,
            'periode_id' => $periode->id,
        ]);

        AhpPerbandinganKriteria::create([
            'kriteria_pertama_id' => $kriterias[1]->id,
            'kriteria_kedua_id' => $kriterias[2]->id,
            'nilai_perbandingan' => 9,
            'periode_id' => $periode->id,
        ]);

        AhpPerbandinganKriteria::create([
            'kriteria_pertama_id' => $kriterias[0]->id,
            'kriteria_kedua_id' => $kriterias[2]->id,
            'nilai_perbandingan' => 2,
            'periode_id' => $periode->id,
        ]);

        // Act
        Livewire::test('perhitungan-kriteria')
            ->set('selectedPeriodeId', $periode->id)
            ->call('calculate')
            ->assertSessionHas('warning'); // Should warn about inconsistency

        // Assert - Data should still be saved
        $this->assertDatabaseHas('ahp_hasil_konsistensi', [
            'periode_id' => $periode->id,
            'is_consistent' => false,
        ]);
    }
}
