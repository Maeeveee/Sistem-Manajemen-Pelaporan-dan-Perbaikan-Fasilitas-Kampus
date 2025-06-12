<div>
    <title>Dashboard - Teknisi</title>

    @section('breadcrumbs')
        @php
            $breadcrumbs = [
                'Laporan Pengguna' => route('dashboard-teknisi'),
            ];
        @endphp
        @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
    @endsection

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Laporan Perbaikan Fasilitas</h2>
            <p class="mb-0">Daftar perbaikan yang ditugaskan kepada Anda</p>
        </div>
    </div>

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <div class="table-settings mb-4">
            <div class="row justify-content-between align-items-center">
                <div class="col-9 col-lg-8 d-md-flex">
                    <div class="input-group me-2 me-lg-3 fmxw-300">
                        <span class="input-group-text">
                            <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <input type="text" class="form-control" placeholder="Cari" wire:model.debounce.500ms="search">
                    </div>
                    <select class="form-select fmxw-200 d-none d-md-inline" wire:model="statusFilter" aria-label="Filter status">
                        <option value="all">Semua</option>
                        <option value="diproses">Sedang Dikerjakan</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditunda">Ditunda</option>
                    </select>
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
                                <a class="dropdown-item d-flex align-items-center fw-bold" href="#" wire:click.prevent="perPage(10)">10</a>
                                <a class="dropdown-item fw-bold" href="#" wire:click.prevent="perPage(20)">20</a>
                                <a class="dropdown-item fw-bold rounded-bottom" href="#" wire:click.prevent="perPage(30)">30</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-hover align-items-center">
            <thead>
                <tr>
                    <th class="border-bottom">Prioritas</th>
                    <th class="border-bottom">Lokasi</th>
                    <th class="border-bottom">Fasilitas</th>
                    <th class="border-bottom">Skor SPK</th>
                    <th class="border-bottom">Tanggal Lapor</th>
                    <th class="border-bottom">Status</th>
                    <th class="border-bottom">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanDiproses as $laporan)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $laporan->hasilTopsis?->nilai > 0.7 ? 'danger' : ($laporan->hasilTopsis?->nilai > 0.5 ? 'warning' : 'success') }}">
                                {{ $loop->iteration }}
                            </span>
                        </td>
                        <td>
                            {{ $laporan->gedung->nama_gedung ?? '-' }}, 
                            Lantai {{ $laporan->lantai }}, 
                            {{ $laporan->ruangan->nama_ruangan ?? '-' }}
                        </td>
                        <td>{{ $laporan->fasilitas->nama_fasilitas ?? '-' }}</td>
                        <td>{{ $laporan->hasilTopsis ? number_format($laporan->hasilTopsis->nilai, 3) : '-' }}</td>
                        <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $this->getStatusColor($laporan->status_perbaikan) }}">
                                {{ $this->getStatusText($laporan->status_perbaikan) }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="selectLaporan({{ $laporan->id }})" 
                                    class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada laporan yang ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($laporanDiproses instanceof \Illuminate\Pagination\LengthAwarePaginator && $laporanDiproses->hasPages())
            <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                {{ $laporanDiproses->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Update Status -->
    @if($showModal)
    <div class="modal fade show d-block" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true" style="padding-right: 17px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="statusModalLabel">Update Status Perbaikan</h5>
                    <button type="button" class="btn-close text-white" wire:click="closeModal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    @if($selectedLaporan)
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Detail Laporan</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Lokasi</th>
                                            <td>
                                                {{ $selectedLaporan->gedung->nama_gedung ?? 'N/A' }}, 
                                                Lantai {{ $selectedLaporan->lantai ?? 'N/A' }}, 
                                                {{ $selectedLaporan->ruangan->nama_ruangan ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Fasilitas</th>
                                            <td>{{ $selectedLaporan->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Skor Prioritas</th>
                                            <td>
                                                @if($selectedLaporan->hasilTopsis)
                                                <span class="badge bg-{{ $selectedLaporan->hasilTopsis->nilai > 0.7 ? 'danger' : ($selectedLaporan->hasilTopsis->nilai > 0.5 ? 'warning' : 'success') }}">
                                                    {{ number_format($selectedLaporan->hasilTopsis->nilai, 3) }}
                                                </span>
                                                @else
                                                <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Foto Kerusakan</th>
                                            <td>
                                                @if($selectedLaporan->foto)
                                                    <img src="{{ asset('storage/' . $selectedLaporan->foto) }}" 
                                                         class="img-thumbnail" 
                                                         style="max-height: 120px;"
                                                         alt="Foto Kerusakan">
                                                @else
                                                    <span class="text-muted">Tidak ada foto</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi Kerusakan</th>
                                            <td>{{ $selectedLaporan->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Update Status</h6>
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="updateStatus">
                                        <div class="mb-3">
                                            <label class="form-label">Status Perbaikan</label>
                                            <select class="form-select" wire:model="statusSelected">
                                                <option value="diproses">Sedang Diproses</option>
                                                <option value="selesai">Selesai</option>
                                                <option value="ditunda">Ditunda</option>
                                            </select>
                                            @error('statusSelected') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Catatan Teknisi</label>
                                            <textarea class="form-control" 
                                                    wire:model="catatanTeknisi"
                                                    rows="4"
                                                    placeholder="Tambahkan catatan tentang perbaikan..."></textarea>
                                            @error('catatanTeknisi') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Upload Foto Setelah Perbaikan</label>
                                            <input type="file" 
                                                   class="form-control @error('fotoPerbaikan') is-invalid @enderror" 
                                                   wire:model="fotoPerbaikan"
                                                   accept="image/*">
                                            @error('fotoPerbaikan') <span class="text-danger small">{{ $message }}</span> @enderror
                                            <div class="form-text">Format: JPG/PNG (Maks. 2MB)</div>
                                            
                                            @if($fotoPerbaikan)
                                                <div class="mt-2">
                                                    <small class="text-success">
                                                        ✓ Foto dipilih: {{ $fotoPerbaikan->getClientOriginalName() }}
                                                    </small>
                                                    <div class="mt-2">
                                                        <img src="{{ $fotoPerbaikan->temporaryUrl() }}" 
                                                             class="img-thumbnail" 
                                                             style="max-height: 120px;"
                                                             alt="Preview Foto Perbaikan">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary me-2" wire:click="closeModal">Tutup</button>
                                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                                <span wire:loading.remove wire:target="updateStatus">Simpan Perubahan</span>
                                                <span wire:loading wire:target="updateStatus">
                                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                                    Menyimpan...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Memuat data laporan...</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        // Handle modal show/hide
        Livewire.on('showModal', () => {
            const modal = document.getElementById('statusModal');
            if (modal) {
                modal.classList.add('show');
                modal.style.display = 'block';
                document.body.classList.add('modal-open');
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        });

        Livewire.on('hideModal', () => {
            const modal = document.getElementById('statusModal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        });
    });
</script>
@endpush