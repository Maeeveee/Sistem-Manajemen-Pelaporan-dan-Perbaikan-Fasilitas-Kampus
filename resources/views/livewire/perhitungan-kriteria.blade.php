

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Perhitungan Bobot Kriteria dengan AHP</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">Tambah Perbandingan Kriteria</h5>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="store">
                                    <div class="mb-3">
                                        <label for="kriteria_pertama" class="form-label">Kriteria Pertama</label>
                                        <select class="form-select" id="kriteria_pertama" wire:model="kriteria_pertama" required>
                                            <option value="">Pilih Kriteria</option>
                                            @foreach($kriterias as $kriteria)
                                                <option value="{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</option>
                                            @endforeach
                                        </select>
                                        @error('kriteria_pertama') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="kriteria_kedua" class="form-label">Kriteria Kedua</label>
                                        <select class="form-select" id="kriteria_kedua" wire:model="kriteria_kedua" required>
                                            <option value="">Pilih Kriteria</option>
                                            @foreach($kriterias as $kriteria)
                                                <option value="{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</option>
                                            @endforeach
                                        </select>
                                        @error('kriteria_kedua') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="nilai" class="form-label">Nilai Perbandingan</label>
                                        <select class="form-select" id="nilai" wire:model="nilai" required>
                                            <option value="">Pilih Nilai</option>
                                            <option value="1">1 - Sama pentingnya</option>
                                            <option value="2">2 - Antara sama dan sedikit lebih penting</option>
                                            <option value="3">3 - Sedikit lebih penting</option>
                                            <option value="4">4 - Antara sedikit lebih dan lebih penting</option>
                                            <option value="5">5 - Lebih penting</option>
                                            <option value="6">6 - Antara lebih dan sangat lebih penting</option>
                                            <option value="7">7 - Sangat lebih penting</option>
                                            <option value="8">8 - Antara sangat lebih dan mutlak lebih penting</option>
                                            <option value="9">9 - Mutlak lebih penting</option>
                                        </select>
                                        <small class="text-muted">Skala 1-9 (1 = sama penting, 9 = mutlak lebih penting)</small>
                                        @error('nilai') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Simpan Perbandingan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">Aksi Perhitungan</h5>
                            </div>
                            <div class="card-body text-center">
                                @if($kriterias->count() >= 2)
                                    <button wire:click="calculate" type="button" class="btn btn-success me-2">
                                        <i class="bi bi-calculator"></i> Hitung Bobot AHP
                                    </button>
                                @endif
                                <button wire:click="resetPerhitungan" type="button" class="btn btn-danger" 
                                    onclick="confirm('Anda yakin ingin mereset semua perhitungan?') || event.stopImmediatePropagation()">
                                    <i class="bi bi-trash"></i> Reset Perhitungan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Matriks Perbandingan Kriteria</h5>
                            </div>
                            <div class="card-body">
                                @if($kriterias->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Kriteria</th>
                                                    @foreach($kriterias as $kriteria1)
                                                        <th>{{ $kriteria1->nama_kriteria }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($kriterias as $kriteria1)
                                                    <tr>
                                                        <td class="fw-bold">{{ $kriteria1->nama_kriteria }}</td>
                                                        @foreach($kriterias as $kriteria2)
                                                            @if($kriteria1->id == $kriteria2->id)
                                                                <td class="bg-light">1</td>
                                                            @else
                                                                @php
                                                                    $perbandingan = $perbandingans->first(function($item) use ($kriteria1, $kriteria2) {
                                                                        return ($item->kriteria_pertama_id == $kriteria1->id && $item->kriteria_kedua_id == $kriteria2->id) ||
                                                                               ($item->kriteria_pertama_id == $kriteria2->id && $item->kriteria_kedua_id == $kriteria1->id);
                                                                    });
                                                                @endphp
                                                                <td>
                                                                    @if($perbandingan)
                                                                        @if($perbandingan->kriteria_pertama_id == $kriteria1->id)
                                                                            {{ number_format($perbandingan->nilai_perbandingan, 2) }}
                                                                        @else
                                                                            {{ number_format(1/$perbandingan->nilai_perbandingan, 2) }}
                                                                        @endif
                                                                    @else
                                                                        -
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

                @if($bobots->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Hasil Perhitungan Bobot Kriteria</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Kriteria</th>
                                                <th>Bobot</th>
                                                <th>Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bobots as $bobot)
                                                <tr>
                                                    <td>{{ $bobot->kriterias->nama_kriteria }}</td>
                                                    <td>{{ number_format($bobot->bobot_ahp, 6) }}</td>
                                                    <td>{{ number_format($bobot->bobot_ahp * 100, 2) }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Uji Konsistensi</h5>
        </div>
        <div class="card-body">
            @if($konsistensi)
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Lambda Max
                        <span class="badge bg-primary rounded-pill">{{ number_format($konsistensi->lambda_max, 6) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Consistency Index (CI)
                        <span class="badge bg-primary rounded-pill">{{ number_format($konsistensi->consistency_index, 6) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Random Index (RI)
                        <span class="badge bg-primary rounded-pill">{{ number_format($konsistensi->random_index, 6) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Consistency Ratio (CR)
                        <span class="badge bg-{{ $konsistensi->is_consistent ? 'success' : 'danger' }} rounded-pill">
                            {{ number_format($konsistensi->consistency_ratio, 6) }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            Status Konsistensi
                            <span class="badge bg-{{ $konsistensi->is_consistent ? 'success' : 'danger' }}">
                                {{ $konsistensi->is_consistent ? 'KONSISTEN' : 'TIDAK KONSISTEN' }}
                            </span>
                        </div>
                        <small class="text-muted">CR ≤ 0.1 = Konsisten</small>
                    </li>
                </ul>

                @php
                    $totalBobot = $bobots->sum('bobot_ahp');
                    $isTotalValid = abs($totalBobot - 1.0) <= 0.0001;
                @endphp

                <div class="alert alert-{{ $isTotalValid ? 'success' : 'danger' }} mb-3">
                    Total Bobot: <strong>{{ number_format($totalBobot * 100, 2) }}%</strong>
                    @if(!$isTotalValid)
                        <br><small>Total bobot harus tepat 100% untuk dapat melakukan update</small>
                    @endif
                </div>

                <button wire:click="updateBobotKriteria" 
                        class="btn btn-{{ $konsistensi->is_consistent && $isTotalValid ? 'primary' : 'secondary' }} w-100"
                        @if(!$konsistensi->is_consistent || !$isTotalValid) disabled @endif>
                    <i class="fas fa-save me-2"></i> Update Bobot Kriteria
                </button>

                @if(!$konsistensi->is_consistent)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Tidak dapat update bobot karena Consistency Ratio tidak konsisten (CR > 0.1)
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    Belum ada hasil perhitungan konsistensi.
                </div>
            @endif
        </div>
    </div>
</div>
                </div>
                @endif
            </div>
        </div>
    </div>
