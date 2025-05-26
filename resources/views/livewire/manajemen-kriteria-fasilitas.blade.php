<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Kriteria</li>
                </ol>
            </nav>
            <h2 class="h4">Manajemen Kriteria Fasilitas</h2>
            @if(session('success'))
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
                        <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Cari kriteria...">
                </div>
            </div>
            <div class="col-3 col-lg-4 d-flex justify-content-end">
                <div class="btn-group">
                    <div class="dropdown me-1">
                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path>
                            </svg>
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pb-0">
                            <span class="small ps-3 fw-bold text-dark">Tampilkan</span>
                            <a class="dropdown-item d-flex align-items-center fw-bold" href="#" wire:click.prevent="changePerPage(10)">10</a>
                            <a class="dropdown-item fw-bold" href="#" wire:click.prevent="changePerPage(20)">20</a>
                            <a class="dropdown-item fw-bold rounded-bottom" href="#" wire:click.prevent="changePerPage(30)">30</a>
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
                        @if($sortField === 'id')
                            <span class="icon icon-sm">
                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                            </span>
                        @endif
                    </th>
                    <th class="border-bottom" wire:click="sortBy('nama_kriteria')">
                        Kriteria
                        @if($sortField === 'nama_kriteria')
                            <span class="icon icon-sm">
                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                            </span>
                        @endif
                    </th>
                    <th class="border-bottom" wire:click="sortBy('jenis')">
                        Jenis
                        @if($sortField === 'jenis')
                            <span class="icon icon-sm">
                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                            </span>
                        @endif
                    </th>
                    <th class="border-bottom" wire:click="sortBy('bobot')">
                        Bobot (%)
                        @if($sortField === 'bobot')
                            <span class="icon icon-sm">
                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                            </span>
                        @endif
                    </th>
                    <th class="border-bottom">Scoring (Rendah)</th>
                    <th class="border-bottom">Scoring (Sedang)</th>
                    <th class="border-bottom">Scoring (Tinggi)</th>
                    <th class="border-bottom">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kriterias as $kriteria)
                    <tr>
                        <td>{{ ($kriterias->currentPage() - 1) * $kriterias->perPage() + $loop->iteration }}</td>
                        <td>{{ $kriteria->nama_kriteria }}</td>
                        <td>
                            <span class="badge {{ $kriteria->jenis === 'benefit' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($kriteria->jenis) }}
                            </span>
                        </td>
                        <td>{{ number_format($kriteria->bobot, 2) }}</td>
                        <td>{{ number_format($kriteria->nilai_rendah, 2) }}</td>
                        <td>{{ number_format($kriteria->nilai_sedang, 2) }}</td>
                        <td>{{ number_format($kriteria->nilai_tinggi, 2) }}</td>
                        <td>
                            <button wire:click="editKriteria({{ $kriteria->id }})" 
                                    class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kriteriaModal">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data kriteria ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="card-footer px-3 border-0 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan <strong>{{ $kriterias->firstItem() }} - {{ $kriterias->lastItem() }}</strong> dari <strong>{{ $kriterias->total() }}</strong> data
            </div>
            <div>
                <button wire:click="previousPage" 
                        wire:loading.attr="disabled"
                        class="btn btn-sm btn-outline-primary {{ $kriterias->onFirstPage() ? 'disabled' : '' }}">
                    Sebelumnya
                </button>
                <button wire:click="nextPage" 
                        wire:loading.attr="disabled"
                        class="btn btn-sm btn-outline-primary ms-2 {{ !$kriterias->hasMorePages() ? 'disabled' : '' }}">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div wire:ignore.self class="modal fade" id="kriteriaModal" tabindex="-1" role="dialog" aria-labelledby="kriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kriteriaModalLabel">Edit Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateKriteria">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" class="form-control bg-light" 
                                   value="{{ $currentKriteria['nama_kriteria'] ?? '' }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Kriteria</label>
                            <input type="text" class="form-control bg-light" 
                                   value="{{ ucfirst($currentKriteria['jenis'] ?? '') }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bobot" class="form-label">Bobot (%)</label>
                            <input type="number" class="form-control" id="bobot" 
                                   wire:model.defer="currentKriteria.bobot" 
                                   step="0.01" min="1" max="100" required>
                            @error('currentKriteria.bobot') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nilai Scoring</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="nilai_rendah">Rendah</label>
                                    <input type="number" id="nilai_rendah" class="form-control" 
                                           wire:model.defer="currentKriteria.nilai_rendah" step="0.01" required>
                                    @error('currentKriteria.nilai_rendah') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="nilai_sedang">Sedang</label>
                                    <input type="number" id="nilai_sedang" class="form-control" 
                                           wire:model.defer="currentKriteria.nilai_sedang" step="0.01" required>
                                    @error('currentKriteria.nilai_sedang') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="nilai_tinggi">Tinggi</label>
                                    <input type="number" id="nilai_tinggi" class="form-control" 
                                           wire:model.defer="currentKriteria.nilai_tinggi" step="0.01" required>
                                    @error('currentKriteria.nilai_tinggi') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="updateKriteria">
                                Update
                            </span>
                            <span wire:loading wire:target="updateKriteria">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        // Modal Control
        Livewire.on('show-modal', () => {
            var modal = new bootstrap.Modal(document.getElementById('kriteriaModal'));
            modal.show();
        });
        
        Livewire.on('hide-modal', () => {
            var modal = bootstrap.Modal.getInstance(document.getElementById('kriteriaModal'));
            if (modal) {
                modal.hide();
            }
        });
    });
</script>
@endpush