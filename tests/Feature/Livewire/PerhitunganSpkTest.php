<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use App\Models\LaporanKerusakan;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Fasilitas;
use App\Models\Periode;
use App\Models\HasilTopsis;
use App\Models\Alternatif;
use App\Services\TopsisCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Mockery;

class PerhitunganSpkTest extends TestCase
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
    public function it_can_load_perhitungan_spk_component()
    {
        // Arrange
        $periode = Periode::factory()->create();

        // Act & Assert
        Livewire::test('perhitungan-spk')
            ->assertStatus(200)
            ->assertViewIs('livewire.perhitungan-spk');
    }

    /** @test */
    public function it_fetches_raw_reports_with_complete_criteria()
    {
        // Arrange - Create test data with stubs
        $periode = Periode::factory()->create();
        $gedung = Gedung::factory()->create();
        $ruangan = Ruangan::factory()->create(['gedung_id' => $gedung->id]);
        $fasilitas = Fasilitas::factory()->create();

        // Create laporan with complete criteria
        $laporan = LaporanKerusakan::factory()->create([
            'gedung_id' => $gedung->id,
            'ruangan_id' => $ruangan->id,
            'fasilitas_id' => $fasilitas->id,
            'periode_id' => $periode->id,
            'frekuensi_penggunaan_fasilitas' => 1,
            'dampak_terhadap_aktivitas_akademik' => 1,
            'tingkat_resiko_keselamatan' => 1,
            'tingkat_kerusakan' => 1,
            'sub_kriteria_id' => 34, // Estimasi waktu
        ]);

        // Act - Component should process this data
        $component = Livewire::test('perhitungan-spk')
            ->set('periodeId', $periode->id);

        // Assert - Laporan should be processed
        $this->assertNotEmpty($component->get('laporan'));
    }

    /** @test */
    public function it_groups_reports_by_location_and_facility()
    {
        // Arrange - Create multiple reports for same location
        $periode = Periode::factory()->create();
        $gedung = Gedung::factory()->create();
        $ruangan = Ruangan::factory()->create(['gedung_id' => $gedung->id]);
        $fasilitas = Fasilitas::factory()->create();

        // Create 3 reports for same location and facility
        for ($i = 0; $i < 3; $i++) {
            LaporanKerusakan::factory()->create([
                'gedung_id' => $gedung->id,
                'ruangan_id' => $ruangan->id,
                'fasilitas_id' => $fasilitas->id,
                'lantai' => 1,
                'periode_id' => $periode->id,
                'frekuensi_penggunaan_fasilitas' => 1,
                'dampak_terhadap_aktivitas_akademik' => 1,
                'tingkat_resiko_keselamatan' => 1,
                'tingkat_kerusakan' => 1,
                'sub_kriteria_id' => 34,
            ]);
        }

        // Act
        $component = Livewire::test('perhitungan-spk')
            ->set('periodeId', $periode->id)
            ->call('calculateTopsis');

        // Assert - Should be grouped into 1 alternative
        $laporan = $component->get('laporan');
        $this->assertCount(1, $laporan);
        $this->assertEquals(3, $laporan[0]['total_laporan_asli']);
    }

    /** @test */
    public function it_calculates_topsis_with_mocked_service()
    {
        // Arrange
        $mockService = Mockery::mock(TopsisCalculatorService::class);
        
        // Mock the calculate method
        $mockService->shouldReceive('calculate')
            ->once()
            ->andReturn([
                'normalized' => [
                    ['frekuensi' => 0.8, 'dampak' => 0.6],
                    ['frekuensi' => 0.6, 'dampak' => 0.8],
                ],
                'weighted' => [
                    ['frekuensi' => 0.32, 'dampak' => 0.18],
                    ['frekuensi' => 0.24, 'dampak' => 0.24],
                ],
                'idealSolutions' => [
                    'positive' => ['frekuensi' => 0.32, 'dampak' => 0.24],
                    'negative' => ['frekuensi' => 0.24, 'dampak' => 0.18],
                ],
                'distances' => [
                    ['dPlus' => 0.06, 'dMinus' => 0.14],
                    ['dPlus' => 0.10, 'dMinus' => 0.08],
                ],
                'preferences' => [0.7, 0.45],
                'ranking' => [1, 2],
            ]);

        $this->app->instance(TopsisCalculatorService::class, $mockService);

        // Act
        $service = app(TopsisCalculatorService::class);
        $result = $service->calculate([], []);

        // Assert
        $this->assertArrayHasKey('preferences', $result);
        $this->assertEquals(0.7, $result['preferences'][0]);
        $this->assertEquals(1, $result['ranking'][0]);
    }

    /** @test */
    public function it_cleans_up_tables_before_calculation()
    {
        // Arrange - Create old data
        $periode = Periode::factory()->create();
        
        // Create dummy data in tables
        DB::table('penilaian')->insert(['nilai' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('alternatif')->insert(['objek_id' => 999, 'created_at' => now(), 'updated_at' => now()]);

        // Act
        Livewire::test('perhitungan-spk')
            ->set('periodeId', $periode->id)
            ->call('calculateTopsis');

        // Assert - Tables should be cleaned
        $this->assertDatabaseCount('penilaian', 0);
        $this->assertDatabaseCount('alternatif', 0);
    }

    /** @test */
    public function it_validates_periode_before_calculation()
    {
        // Act & Assert
        Livewire::test('perhitungan-spk')
            ->set('periodeId', 999) // Non-existent periode
            ->call('calculateTopsis')
            ->assertHasErrors('periodeId');
    }

    /** @test */
    public function it_updates_bobot_when_periode_changes()
    {
        // Arrange
        $periode1 = Periode::factory()->create();
        $periode2 = Periode::factory()->create();

        // Act & Assert
        $component = Livewire::test('perhitungan-spk')
            ->set('periodeId', $periode1->id)
            ->set('periodeId', $periode2->id);

        // Bobot should be reloaded
        $bobot = $component->get('bobot');
        $this->assertIsArray($bobot);
    }

    /** @test */
    public function it_can_open_and_close_proses_modal()
    {
        // Arrange
        $laporan = LaporanKerusakan::factory()->create();

        // Act & Assert
        Livewire::test('perhitungan-spk')
            ->call('openProsesModal', $laporan->id)
            ->assertSet('showProsesModal', true)
            ->assertSet('laporanId', $laporan->id)
            ->call('closeProsesModal')
            ->assertSet('showProsesModal', false)
            ->assertSet('laporanId', null);
    }

    /** @test */
    public function it_assigns_teknisi_to_laporan()
    {
        // Arrange
        $teknisi = User::factory()->create(['role_id' => 5]); // Teknisi
        $laporan = LaporanKerusakan::factory()->create([
            'status_perbaikan' => 'menunggu',
        ]);

        // Act
        Livewire::test('perhitungan-spk')
            ->set('laporanId', $laporan->id)
            ->set('teknisiId', $teknisi->id)
            ->set('statusPerbaikan', 'diproses')
            ->set('catatanTeknisi', 'Segera ditangani')
            ->call('prosesLaporan')
            ->assertSessionHas('message');

        // Assert
        $this->assertDatabaseHas('laporan_kerusakan', [
            'id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'status_perbaikan' => 'diproses',
            'catatan_teknisi' => 'Segera ditangani',
        ]);
    }

    /** @test */
    public function it_validates_teknisi_assignment()
    {
        // Arrange
        $laporan = LaporanKerusakan::factory()->create();

        // Act & Assert - Missing teknisi
        Livewire::test('perhitungan-spk')
            ->set('laporanId', $laporan->id)
            ->set('statusPerbaikan', 'diproses')
            ->call('prosesLaporan')
            ->assertHasErrors('teknisiId');

        // Invalid status
        Livewire::test('perhitungan-spk')
            ->set('laporanId', $laporan->id)
            ->set('teknisiId', 1)
            ->set('statusPerbaikan', 'invalid')
            ->call('prosesLaporan')
            ->assertHasErrors('statusPerbaikan');
    }

    /** @test */
    public function it_calculates_nilai_laporan_based_on_count()
    {
        // Arrange
        $periode = Periode::factory()->create();
        $gedung = Gedung::factory()->create();
        $ruangan = Ruangan::factory()->create(['gedung_id' => $gedung->id]);
        $fasilitas = Fasilitas::factory()->create();

        // Create 5 reports (should get nilai_laporan = 3)
        for ($i = 0; $i < 5; $i++) {
            LaporanKerusakan::factory()->create([
                'gedung_id' => $gedung->id,
                'ruangan_id' => $ruangan->id,
                'fasilitas_id' => $fasilitas->id,
                'lantai' => 1,
                'periode_id' => $periode->id,
                'frekuensi_penggunaan_fasilitas' => 1,
                'dampak_terhadap_aktivitas_akademik' => 1,
                'tingkat_resiko_keselamatan' => 1,
                'tingkat_kerusakan' => 1,
                'sub_kriteria_id' => 34,
            ]);
        }

        // Act
        $component = Livewire::test('perhitungan-spk')
            ->set('periodeId', $periode->id)
            ->call('calculateTopsis');

        // Assert
        $laporan = $component->get('laporan');
        $this->assertEquals(3.00, $laporan[0]['banyaknya_laporan']); // >= 5 reports
    }

    /** @test */
    public function it_ranks_alternatives_correctly()
    {
        // Arrange - Create 3 different locations with different priorities
        $periode = Periode::factory()->create();
        
        // High priority (high values on all criteria)
        $this->createLaporanWithCriteria($periode, 3, 3, 3, 3, 1);
        
        // Medium priority
        $this->createLaporanWithCriteria($periode, 2, 2, 2, 2, 2);
        
        // Low priority
        $this->createLaporanWithCriteria($periode, 1, 1, 1, 1, 3);

        // Act
        $component = Livewire::test('perhitungan-spk')
            ->set('periodeId', $periode->id)
            ->call('calculateTopsis');

        // Assert
        $sortedResults = $component->get('sortedResults');
        $this->assertCount(3, $sortedResults);
        
        // First result should have highest rank (1)
        $this->assertEquals(1, $sortedResults[0]['rank']);
        
        // Rankings should be sequential
        $this->assertEquals(2, $sortedResults[1]['rank']);
        $this->assertEquals(3, $sortedResults[2]['rank']);
    }

    // Helper method to create laporan with specific criteria
    private function createLaporanWithCriteria($periode, $frekuensi, $dampak, $resiko, $kerusakan, $uniqueId)
    {
        $gedung = Gedung::factory()->create(['nama_gedung' => "Gedung $uniqueId"]);
        $ruangan = Ruangan::factory()->create(['gedung_id' => $gedung->id, 'nama_ruangan' => "Ruangan $uniqueId"]);
        $fasilitas = Fasilitas::factory()->create(['nama_fasilitas' => "Fasilitas $uniqueId"]);

        LaporanKerusakan::factory()->create([
            'gedung_id' => $gedung->id,
            'ruangan_id' => $ruangan->id,
            'fasilitas_id' => $fasilitas->id,
            'lantai' => $uniqueId,
            'periode_id' => $periode->id,
            'frekuensi_penggunaan_fasilitas' => $frekuensi,
            'dampak_terhadap_aktivitas_akademik' => $dampak,
            'tingkat_resiko_keselamatan' => $resiko,
            'tingkat_kerusakan' => $kerusakan,
            'sub_kriteria_id' => 34,
        ]);
    }
}
