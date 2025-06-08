<div>
    <title>Evaluasi Laporan - SPK CRITIC TOPSIS</title>  
    @section('breadcrumbs')
        @php
            $breadcrumbs = [
                'Sarana Prasarana' => '',
                'Evaluasi Laporan Kerusakan (AHP TOPSIS)' => route('perhitungan-spk'),
            ];
        @endphp
        @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
    @endsection

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Evaluasi Laporan Kerusakan dengan Metode AHP dan TOPSIS</h2>
            <p class="mb-0">Sistem Pendukung Keputusan untuk Prioritas Perbaikan Fasilitas Kampus</p>
        </div>
    </div>

    <form wire:submit.prevent="calculateTopsis">
        <div class="mb-4">
            <label for="periodeId" class="block text-gray-700 text-sm font-bold mb-2">Pilih Periode</label>
                <select wire:model="periodeId" class="form-control">
                    <option value="">-- Pilih Periode --</option>
                    @foreach($daftarPeriode as $periode)
                        <option value="{{ $periode->id }}">{{ $periode->nama_periode }} ({{ $periode->tanggal_mulai }} - {{ $periode->tanggal_selesai }})</option>
                    @endforeach
                </select>
            @error('periodeId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rekomendasi Prioritas Perbaikan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Ranking</th>
                                    <th>Lokasi</th>
                                    <th>Fasilitas</th>
                                    <th>Nilai Preferensi</th>
                                    <th>Status</th>
                                    <th>Jumlah Laporan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($sortedResults))
                                    <tr>
                                        <td colspan="8" class="text-center">Data akan muncul setelah klik tombol Hitung TOPSIS</td>
                                    </tr>
                                @else
                                    @foreach ($sortedResults as $item)
                                    <tr>
                                        <td>{{ $item['rank'] }}</td>
                                        <td>{{ $item['lokasi'] }}</td>
                                        <td>{{ $item['fasilitas'] }}</td>
                                        <td>{{ number_format($item['nilai'], 3) }}</td>
                                        <td>
                                            @if($item['rank'] == 1)
                                                <span class="badge bg-danger">Prioritas Tinggi</span>
                                            @elseif($item['rank'] <= 3)
                                                <span class="badge bg-warning text-dark">Prioritas Menengah</span>
                                            @else
                                                <span class="badge bg-success">Prioritas Rendah</span>
                                            @endif
                                        </td>
                                        <td>{{ $item['total_laporan'] }}</td>
                                        <td>
                                            <button wire:click="openProsesModal({{ $laporan[$item['original_index']]['id'] }})" Add commentMore actions
                                                        class="btn btn-sm btn-primary">
                                                    Proses
                                                </button>
                                            <button wire:click="openDetailModal({{ $laporan[$item['original_index']]['id'] }})" 
                                                    class="btn btn-sm btn-info">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Matriks Keputusan Awal</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Gedung</th>
                                    <th>Ruangan</th>
                                    <th>Lantai</th>
                                    <th>Fasilitas</th>
                                    <th>Frekuensi Penggunaan (C1)</th>
                                    <th>Dampak Akademik (C2)</th>
                                    <th>Tingkat Resiko (C3)</th>
                                    <th>Tingkat Kerusakan (C4)</th>
                                    <th>Estimasi Waktu (C5)</th>
                                    <th>Banyaknya Laporan (C6)</th>
                                    <th>Jumlah Laporan Asli</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($laporan))
                                    <tr>
                                        <td colspan="13" class="text-center">Data akan muncul setelah klik tombol Hitung TOPSIS</td>
                                    </tr>
                                @else
                                    @foreach ($laporan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['gedung'] }}</td>
                                        <td>{{ $item['ruangan'] }}</td>
                                        <td>Lantai {{ $item['lantai'] }}</td>
                                        <td>{{ $item['fasilitas'] }}</td>
                                        <td>{{ number_format($item['frekuensi'], 2) }}</td>
                                        <td>{{ number_format($item['dampak'], 2) }}</td>
                                        <td>{{ number_format($item['resiko'], 2) }}</td>
                                        <td>{{ number_format($item['kerusakan'], 2) }}</td>
                                        <td>{{ number_format($item['estimasi'], 2) }}</td>
                                        <td>{{ number_format($item['banyaknya_laporan'], 2) }}</td>
                                        <td>{{ $item['total_laporan_asli'] }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <td colspan="6" class="text-center"><strong>Bobot Kriteria (W)</strong></td>
                                    <td>{{ number_format($bobot['frekuensi'] ?? 0, 4) }}</td>
                                    <td>{{ number_format($bobot['dampak'] ?? 0, 4) }}</td>
                                    <td>{{ number_format($bobot['resiko'] ?? 0, 4) }}</td>
                                    <td>{{ number_format($bobot['kerusakan'] ?? 0, 4) }}</td>
                                    <td>{{ number_format($bobot['estimasi'] ?? 0, 4) }}</td>
                                    <td>{{ number_format($bobot['laporan'] ?? 0, 4) }}</td>
                                    <td>-</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="6" class="text-center"><strong>Atribut Kriteria</strong></td>
                                    <td>Benefit</td>
                                    <td>Benefit</td>
                                    <td>Cost</td>
                                    <td>Benefit</td>
                                    <td>Cost</td>
                                    <td>Benefit</td>
                                    <td>-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Matriks Ternormalisasi</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Alternatif</th>
                                    <th>Frekuensi Penggunaan (C1)</th>
                                    <th>Dampak Akademik (C2)</th>
                                    <th>Tingkat Resiko (C3)</th>
                                    <th>Tingkat Kerusakan (C4)</th>
                                    <th>Estimasi Waktu (C5)</th>
                                    <th>Banyaknya Laporan (C6)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($normalized))
                                    <tr>
                                        <td colspan="7" class="text-center">Data akan muncul setelah klik tombol Hitung TOPSIS</td>
                                    </tr>
                                @else
                                    @foreach ($normalized as $index => $item)
                                    <tr>
                                        <td>A{{ $index + 1 }} ({{ $item['nama_pelapor'] }})</td>
                                        <td>{{ number_format($item['frekuensi'], 3) }}</td>
                                        <td>{{ number_format($item['dampak'], 3) }}</td>
                                        <td>{{ number_format($item['resiko'], 3) }}</td>
                                        <td>{{ number_format($item['kerusakan'], 3) }}</td>
                                        <td>{{ number_format($item['estimasi'], 3) }}</td>
                                        <td>{{ number_format($item['laporan'], 3) }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Matriks Ternormalisasi Terbobot</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Frekuensi Penggunaan (C1)</th>
                                    <th>Dampak Akademik (C2)</th>
                                    <th>Tingkat Resiko (C3)</th>
                                    <th>Tingkat Kerusakan (C4)</th>
                                    <th>Estimasi Waktu (C5)</th>
                                    <th>Banyaknya Laporan (C6)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($weighted))
                                    <tr>
                                        <td colspan="7" class="text-center">Data akan muncul setelah klik tombol Hitung TOPSIS</td>
                                    </tr>
                                @else
                                    @foreach ($weighted as $index => $item)
                                    <tr>
                                        <td>A{{ $index + 1 }} ({{ $item['nama_pelapor'] }})</td>
                                        <td>{{ number_format($item['frekuensi'], 3) }}</td>
                                        <td>{{ number_format($item['dampak'], 3) }}</td>
                                        <td>{{ number_format($item['resiko'], 3) }}</td>
                                        <td>{{ number_format($item['kerusakan'], 3) }}</td>
                                        <td>{{ number_format($item['estimasi'], 3) }}</td>
                                        <td>{{ number_format($item['laporan'], 3) }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Solusi Ideal Positif (A+) dan Negatif (A-)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Kriteria</th>
                                    <th>A+ (Positif)</th>
                                    <th>A- (Negatif)</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (!empty($aPlus) && !empty($aMin))
                                <tr>
                                    <td>Frekuensi Penggunaan (C1)</td>
                                    <td>{{ number_format($aPlus['frekuensi']['value'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($aMin['frekuensi']['value'] ?? 0, 3) }}</td>
                                </tr>
                                <tr>
                                    <td>Dampak Akademik (C2)</td>
                                    <td>{{ number_format($aPlus['dampak']['value'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($aMin['dampak']['value'] ?? 0, 3) }}</td>
                                </tr>
                                <tr>
                                    <td>Tingkat Resiko (C3)</td>
                                    <td>{{ number_format($aPlus['resiko']['value'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($aMin['resiko']['value'] ?? 0, 3) }}</td>
                                </tr>
                                <tr>
                                    <td>Tingkat Kerusakan (C4)</td>
                                    <td>{{ number_format($aPlus['kerusakan']['value'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($aMin['kerusakan']['value'] ?? 0, 3) }}</td>
                                </tr>
                                <tr>
                                    <td>Estimasi Waktu (C5)</td>
                                    <td>{{ number_format($aPlus['estimasi']['value'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($aMin['estimasi']['value'] ?? 0, 3) }}</td>
                                </tr>
                                <tr>
                                    <td>Banyaknya Laporan (C6)</td>
                                    <td>{{ number_format($aPlus['laporan']['value'] ?? 0, 3) }}</td>
                                    <td>{{ number_format($aMin['laporan']['value'] ?? 0, 3) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3">Data akan muncul setelah klik tombol Hitung TOPSIS</td>
                                </tr>
                            @endif
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jarak Solusi dan Hasil Akhir</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Alternatif</th>
                                    <th>Jarak ke A+ (D+)</th>
                                    <th>Jarak ke A- (D-)</th>
                                    <th>Nilai Preferensi (V)</th>
                                    <th>Ranking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($results))
                                    <tr>
                                        <td colspan="5" class="text-center">Data akan muncul setelah klik tombol Hitung TOPSIS</td>
                                    </tr>
                                @else
                                    @foreach ($results as $index => $item)
                                    <tr>
                                        <td>A{{ $index + 1 }} ({{ $item['nama_pelapor'] }})</td>
                                        <td>{{ number_format($item['dPlus'], 3) }}</td>
                                        <td>{{ number_format($item['dMin'], 3) }}</td>
                                        <td>{{ number_format($item['v'], 3) }}</td>
                                        <td>{{ $finalRanking[$index] }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <!-- Modal Proses -->
    @if($showProsesModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; padding-right: 17px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Proses Perbaikan</h5>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="prosesLaporan">
                    <div class="form-group">
                        <label for="teknisi">Pilih Teknisi</label>
                        <select class="form-control" id="teknisi" wire:model="teknisiId" required>
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($daftarTeknisi as $teknisi)
                                <option value="{{ $teknisi->id }}">{{ $teknisi->name }}</option>
                            @endforeach
                        </select>
                        @error('teknisiId') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="statusPerbaikan">Status Perbaikan</label>
                        <select class="form-control" id="statusPerbaikan" wire:model="statusPerbaikan" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="diproses">Ditugaskan</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                        @error('statusPerbaikan') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea class="form-control" id="catatan" wire:model="catatanTeknisi" rows="3"></textarea>
                        @error('catatanTeknisi') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeProsesModal">Batal</button>
                <button type="button" class="btn btn-primary" wire:click="prosesLaporan">
                    <span wire:loading.remove>Simpan</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; padding-right: 17px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detail Laporan Kerusakan</h5>
            </div>
            <div class="modal-body">
                @if($laporanDetail)
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Laporan</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="30%">Pelapor</th>
                                        <td>{{ $laporanDetail->nama_pelapor }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Lapor</th>
                                        <td>{{ $laporanDetail->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lokasi</th>
                                        <td>
                                            {{ $laporanDetail->gedung->nama_gedung }}, 
                                            Lantai {{ $laporanDetail->lantai }}, 
                                            {{ $laporanDetail->ruangan->nama_ruangan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Fasilitas</th>
                                        <td>{{ $laporanDetail->fasilitas->nama_fasilitas }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($laporanDetail->status_perbaikan == 'menunggu')
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @elseif($laporanDetail->status_perbaikan == 'diproses')
                                                <span class="badge bg-primary">Diproses</span>
                                            @elseif($laporanDetail->status_perbaikan == 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Detail Kerusakan</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $laporanDetail->deskripsi }}</p>
                                @if($laporanDetail->foto)
                                    <img src="{{ asset('storage\app\public\laporan-kerusakan' . $laporanDetail->foto) }}" 
                                        class="img-fluid rounded" alt="Foto Kerusakan">
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Hasil Perhitungan SPK</h6>
                            </div>
                            <div class="table-responsive">
                                @php
                                    $hasilSpk = DB::table('hasil_topsis')
                                        ->join('alternatif', 'hasil_topsis.alternatif_id', '=', 'alternatif.id')
                                        ->where('alternatif.objek_id', $laporanDetail->id)
                                        ->select('hasil_topsis.nilai')
                                        ->first();

                                    $ranking = DB::table('hasil_topsis')
                                        ->where('nilai', '>', $hasilSpk->nilai ?? 0)
                                        ->count() + 1;
                                @endphp

                                @if($hasilSpk)


                                <table class="table table-sm">
                                    <tr>
                                        <th width="30%">Skor Prioritas</th>
                                        <td>
                                            <span class="badge bg-{{ $hasilSpk->nilai > 0.7 ? 'danger' : ($hasilSpk->nilai > 0.5 ? 'warning' : 'success') }}">
                                                {{ number_format($hasilSpk->nilai, 4) }}
                                            </span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Frekuensi Penggunaan</th>
                                        <td>{{ $laporanDetail->frekuensi_penggunaan_fasilitas }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dampak Akademik</th>
                                        <td>{{ $laporanDetail->dampak_terhadap_aktivitas_akademik }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tingkat Resiko</th>
                                        <td>{{ $laporanDetail->tingkat_resiko_keselamatan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tingkat Kerusakan</th>
                                        <td>{{ $laporanDetail->tingkat_kerusakan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimasi Waktu</th>
                                        <td>{{ $laporanDetail->subKriteria->nama_subkriteria ?? '-' }}</td>
                                    </tr>
                                </table>
                                @else
                                    <p class="text-muted">Belum ada hasil perhitungan SPK</p>
                                @endif
                            </div>
                        </div>
                    
                        @if($laporanDetail->status_perbaikan != 'menunggu')
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Proses Perbaikan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered ">
                                        <tr>
                                            <th width="50%">Teknisi</th>
                                            <td>{{ $laporanDetail->teknisi->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status Perbaikan terakhir</th>
                                            <td>
                                                @if($laporanDetail->status_perbaikan == 'menunggu')
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @elseif($laporanDetail->status_perbaikan == 'diproses')
                                                    <span class="badge bg-primary">Diproses</span>
                                                @elseif($laporanDetail->status_perbaikan == 'selesai')
                                                    <span class="badge bg-success">Selesai</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Catatan Teknisi</th>
                                            <td>{{ $laporanDetail->catatan_teknisi ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @if (!empty($sortedResults))
    <script>

        document.addEventListener('livewire:load', function() {
                window.livewire.on('showDetailModal', (laporanId) => {
                    var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                });
            });

        document.addEventListener('livewire:load', function() {
            const ctx = document.getElementById('priorityChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [@foreach($sortedResults as $item)"A{{ $loop->iteration }} ({{ $item['nama'] }})",@endforeach],
                    datasets: [{
                        label: 'Nilai Preferensi',
                        data: [@foreach($sortedResults as $item){{ $item['nilai'] }},@endforeach],
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                        borderColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nilai Preferensi'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Alternatif'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>
    @endif
    @endpush
</div>