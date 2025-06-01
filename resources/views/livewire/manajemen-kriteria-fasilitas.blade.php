<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Manajemen Kriteria Fasilitas</h2>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-9 col-lg-8 d-md-flex">
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control"
                        placeholder="Cari kriteria...">
                </div>
            </div>
            <div class="col-3 col-lg-4 d-flex justify-content-end">
                <div class="btn-group">
                    <div class="dropdown me-1">
                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z">
                                </path>
                            </svg>
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pb-0">
                            <span class="small ps-3 fw-bold text-dark">Tampilkan</span>
                            <a class="dropdown-item d-flex align-items-center fw-bold" href="#"
                                wire:click.prevent="changePerPage(10)">10</a>
                            <a class="dropdown-item fw-bold" href="#"
                                wire:click.prevent="changePerPage(20)">20</a>
                            <a class="dropdown-item fw-bold rounded-bottom" href="#"
                                wire:click.prevent="changePerPage(30)">30</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table user-table table-hover align-items-center">
            <thead>
                <tr>
                    <th class="border-bottom" wire:click="sortBy('id')">
                        No
                        @if ($sortField === 'id')
                            <span class="icon icon-sm">
                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                            </span>
                        @endif
                    </th>
                    <th class="border-bottom" wire:click="sortBy('nama_kriteria')">
                        Nama Kriteria
                        @if ($sortField === 'nama_kriteria')
                            <span class="icon icon-sm">
                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                            </span>
                        @endif
                    </th>
                    <th class="border-bottom" wire:click="sortBy('bobot')">
                        Bobot (%)
                        @if ($sortField === 'bobot')
                            <span class="icon icon-sm">
                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                            </span>
                        @endif
                    </th>
                    <th class="border-bottom">Jumlah Sub Kriteria</th>
                    <th class="border-bottom">Sub Kriteria</th>
                    <th class="border-bottom">Jenis</th>
                    <th class="border-bottom">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $jenisList = [
                        'Frekuensi Penggunaan Fasilitas' => 'Benefit',
                        'Dampak Terhadap Aktivitas Akademik' => 'Benefit',
                        'Tingkat Resiko Keselamatan' => 'Cost',
                        'Estimasi Waktu' => 'Benefit',
                        'Tingkat Kerusakan' => 'Cost',
                        'Banyaknya Laporan' => 'Benefit',
                    ];
                @endphp
                @forelse ($kriterias as $kriteria)
                    <tr>
                        <td>{{ ($kriterias->currentPage() - 1) * $kriterias->perPage() + $loop->iteration }}</td>
                        <td>{{ $kriteria->nama_kriteria }}</td>
                        {{-- <td>{{ number_format($kriteria->bobot, 2) }}%</td> --}} 
                        <td>
                            @switch($kriteria->bobot)
                                @case(20)
                                    Sama Penting (20%)
                                    @break
                                @case(30)
                                    Sedikit lebih Penting (30%)
                                    @break
                                @case(50)
                                    Lebih Penting (50%)
                                    @break
                                @case(80)
                                    jauh lebih Penting (80%)
                                    @break
                                @case(100)
                                    mutlak lebih Penting (100%)
                                    @break
                                @default
                                    {{ number_format($kriteria->bobot, 2) }}%
                            @endswitch
                        </td>
                        <td>{{ $kriteria->subKriterias->count() }} Sub Kriteria</td>
                        <td>
                            @if ($kriteria->subKriterias->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($kriteria->subKriterias as $sub)
                                        <small>
                                            {{ $sub->nama_subkriteria }} ({{ number_format($sub->nilai, 2) }})
                                        </small>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">Belum ada sub kriteria</span>
                            @endif
                        </td>
                        <td>
                            {{ $jenisList[$kriteria->nama_kriteria] ?? '-' }}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button wire:click="editKriteria({{ $kriteria->id }})" class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal" data-bs-target="#kriteriaModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada data kriteria ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <nav aria-label="Page navigation">
            {{ $kriterias->links('pagination::bootstrap-5') }}
        </nav>
    </div>

    <!-- Modal Edit Kriteria -->
<div wire:ignore.self class="modal fade" id="kriteriaModal" tabindex="-1" role="dialog" aria-labelledby="kriteriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kriteriaModalLabel">Edit Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="updateKriteria">
                <div class="modal-body">
                    <!-- Nama Kriteria (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" 
                            wire:model.defer="currentKriteria.nama_kriteria"
                            placeholder="Nama Kriteria" readonly>
                    </div>
                    
                    <!-- Jenis Kriteria (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Jenis Kriteria</label>
                        <input type="text" class="form-control" 
                            value="{{ $currentKriteria['jenis'] == 'benefit' ? 'Benefit' : 'Cost' }}"
                            readonly>
                    </div>

                    <!-- Tingkat Kepentingan -->
                    <div class="mb-3">
                        <label for="tingkat_kepentingan" class="form-label">Tingkat Kepentingan</label>
                        <select class="form-select" id="tingkat_kepentingan" 
                                wire:model.defer="currentKriteria.bobot" required>
                            <option value="">Pilih Tingkat Kepentingan</option>
                            <option value="20">Sama Penting (20%)</option>
                            <option value="30">Sedikit Lebih Penting (30%)</option>
                            <option value="50">Lebih Penting (50%)</option>
                            <option value="80">Jauh Lebih Penting (80%)</option>
                            <option value="100">Mutlak Penting (100%)</option>
                        </select>
                        @error('currentKriteria.bobot')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-text">Skala prioritas kriteria</div>
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="updateKriteria">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </span>
                        <span wire:loading wire:target="updateKriteria">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- Modal Manage Sub Kriteria -->
    <div wire:ignore.self class="modal fade" id="subKriteriaModal" tabindex="-1" role="dialog"
        aria-labelledby="subKriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subKriteriaModalLabel">
                        Kelola Sub Kriteria - {{ $currentKriteria['nama_kriteria'] ?? '' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Tambah Sub Kriteria -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Tambah Sub Kriteria Baru</h6>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="addSubKriteria">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Sub Kriteria</label>
                                        <input type="text" class="form-control"
                                            wire:model.defer="newSubKriteria.nama_subkriteria"
                                            placeholder="Masukkan nama sub kriteria" required>
                                        @error('newSubKriteria.nama_subkriteria')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nilai</label>
                                        <input type="number" class="form-control"
                                            wire:model.defer="newSubKriteria.nilai" step="0.01"
                                            placeholder="Nilai" required>
                                        @error('newSubKriteria.nilai')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-success d-block">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Daftar Sub Kriteria -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Daftar Sub Kriteria</h6>
                        </div>
                        <div class="card-body">
                            @if (!empty($currentSubKriterias))
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Sub Kriteria</th>
                                                <th>Nilai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($currentSubKriterias as $index => $subKriteria)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $subKriteria['nama_subkriteria'] }}</td>
                                                    <td>{{ number_format($subKriteria['nilai'], 2) }}</td>
                                                    <td>
                                                        <button wire:click="editSubKriteria({{ $subKriteria['id'] }})"
                                                            class="btn btn-sm btn-warning me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p>Belum ada sub kriteria untuk kriteria ini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            // Modal Control
            Livewire.on('show-modal', (modalId) => {
                var modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();
            });

            Livewire.on('hide-modal', (modalId) => {
                var modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
@endpush