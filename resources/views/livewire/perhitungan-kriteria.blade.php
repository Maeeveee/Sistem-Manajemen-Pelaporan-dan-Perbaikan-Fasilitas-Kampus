<div>
    <title>Evaluasi Laporan - SPK AHP</title>
    @section('breadcrumbs')
        @php
            $breadcrumbs = [
                'Sarana Prasarana' => '',
                'Evaluasi Laporan Kerusakan (AHP)' => route('perhitungan-kriteria'),
            ];
        @endphp
        @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
    @endsection

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Perhitungan Bobot Kriteria dengan AHP</h2>
            <p class="mb-0">Sistem Pendukung Keputusan untuk Perhitungan Bobot Kriteria</p>
        </div>
        <div>
            @if ($kriterias->count() >= 2)
                <button wire:click="calculate" type="button" class="btn btn-primary">
                    <i class="bi bi-calculator"></i> Hitung Bobot AHP
                </button>
            @endif
            <button wire:click="resetPerhitungan" type="button" class="btn btn-danger"
                onclick="confirm('Anda yakin ingin mereset semua perhitungan?') || event.stopImmediatePropagation()">
                <i class="bi bi-trash"></i> Reset Perhitungan
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Matriks Perbandingan Kriteria</h5>
                    @if ($kriterias->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kriteria</th>
                                        @foreach ($kriterias as $kriteria1)
                                            <th>{{ $kriteria1->nama_kriteria }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kriterias as $kriteria1)
                                        <tr>
                                            <td class="fw-bold">{{ $kriteria1->nama_kriteria }}</td>
                                            @foreach ($kriterias as $kriteria2)
                                                @if ($kriteria1->id == $kriteria2->id)
                                                    <td class="bg-light">1</td>
                                                @else
                                                    @php
                                                        $perbandingan = $perbandingans->first(function ($item) use (
                                                            $kriteria1,
                                                            $kriteria2,
                                                        ) {
                                                            return ($item->kriteria_pertama_id == $kriteria1->id &&
                                                                $item->kriteria_kedua_id == $kriteria2->id) ||
                                                                ($item->kriteria_pertama_id == $kriteria2->id &&
                                                                    $item->kriteria_kedua_id == $kriteria1->id);
                                                        });
                                                        $nilai = null;
                                                        $is_reverse = false;
                                                        if ($perbandingan) {
                                                            if ($perbandingan->kriteria_pertama_id == $kriteria1->id) {
                                                                $nilai = $perbandingan->nilai_perbandingan;
                                                            } else {
                                                                $nilai = 1 / $perbandingan->nilai_perbandingan;
                                                                $is_reverse = true;
                                                            }
                                                        }
                                                    @endphp
                                                    <td>
                                                        @if ($kriteria1->id < $kriteria2->id)
                                                            <input type="text"
                                                                wire:model.debounce.500ms="perbandingan.{{ $kriteria1->id }}.{{ $kriteria2->id }}"
                                                                wire:change="updatePerbandingan({{ $kriteria1->id }}, {{ $kriteria2->id }})"
                                                                class="form-control form-control-sm"
                                                                placeholder="{{ $nilai ? number_format($nilai, 2) : '' }}">
                                                            @error("perbandingan.{$kriteria1->id}.{$kriteria2->id}")
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        @else
                                                            {{ $nilai ? number_format($nilai, 2) : '-' }}
                                                        @endif
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Tidak ada kriteria yang tersedia. Silakan tambahkan kriteria terlebih dahulu.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($bobots->count() > 0)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Hasil Perhitungan Bobot Kriteria</h5>
                <div class="table-responsive">
                    <table class="table table-bordered mb-3">
                        <thead class="table-dark">
                            <tr>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bobots as $bobot)
                                <tr>
                                    <td>{{ $bobot->kriterias->nama_kriteria }}</td>
                                    <td>{{ number_format($bobot->bobot_ahp, 6) }}</td>
                                    <td>{{ number_format($bobot->bobot_ahp * 100, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @php
                        $totalBobot = $bobots->sum('bobot_ahp');
                        $isTotalValid = abs($totalBobot - 1.0) <= 0.0001;
                    @endphp
                    <div class="alert alert-{{ $isTotalValid ? 'success' : 'danger' }} mb-3">
                        Total Bobot: <strong>{{ number_format($totalBobot * 100, 2) }}%</strong>
                        @if (!$isTotalValid)
                            <br><small>Total bobot harus tepat 100% untuk dapat melakukan update</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Uji Konsistensi</h5>
                @if ($konsistensi)
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-hover mb-3">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="align-middle">Lambda Max</th>
                                    <th class="align-middle">Consistency Index (CI)</th>
                                    <th class="align-middle">Random Index (RI)</th>
                                    <th class="align-middle">Consistency Ratio (CR)</th>
                                    <th class="align-middle">Status Konsistensi <br>
                                        <small class="text-muted">CR ≤ 0.1 = Konsisten</small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span
                                            class="badge bg-primary rounded-pill">{{ number_format($konsistensi->lambda_max, 6) }}</span>
                                    </td>
                                    <td><span
                                            class="badge bg-primary rounded-pill">{{ number_format($konsistensi->consistency_index, 6) }}</span>
                                    </td>
                                    <td><span
                                            class="badge bg-primary rounded-pill">{{ number_format($konsistensi->random_index, 6) }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $konsistensi->is_consistent ? 'success' : 'danger' }} rounded-pill">
                                            {{ number_format($konsistensi->consistency_ratio, 6) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $konsistensi->is_consistent ? 'success' : 'danger' }}">
                                            {{ $konsistensi->is_consistent ? 'KONSISTEN' : 'TIDAK KONSISTEN' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button wire:click="updateBobotKriteria"
                            class="btn btn-{{ $konsistensi->is_consistent && $isTotalValid ? 'primary' : 'primary' }}"
                            @if (!$konsistensi->is_consistent || !$isTotalValid) disabled @endif>
                            <i class="fas fa-save"></i> Update Bobot Kriteria
                        </button>
                        @if (!$konsistensi->is_consistent)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Tidak dapat update bobot karena Consistency Ratio tidak konsisten (CR > 0.1)
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-info">
                        Belum ada hasil perhitungan konsistensi.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('bobotUpdated', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Bobot kriteria berhasil diupdate.',
            timer: 2000,
            showConfirmButton: false
        });
    });
</script>
@endpush