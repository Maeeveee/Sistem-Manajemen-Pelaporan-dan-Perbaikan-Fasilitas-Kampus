<div>
    <title>Manajemen Fasilitas</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Fasilitas</li>
                </ol>
            </nav>
            <h2 class="h4">Manajemen Fasilitas Kampus</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button wire:click="resetInput" class="btn btn-sm btn-gray-800" data-bs-toggle="modal" data-bs-target="#fasilitasModal">
                Tambah Fasilitas
            </button>
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
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Cari fasilitas">
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
                    <th class="border-bottom">No</th>
                    <th class="border-bottom">Nama Fasilitas</th>
                    <th class="border-bottom">Lokasi</th>
                    <th class="border-bottom">Ruangan</th>
                    <th class="border-bottom">Gedung</th>
                    <th class="border-bottom">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fasilitas as $index => $item)
                    <tr>
                        <td>{{ $fasilitas->firstItem() + $index }}</td>
                        <td>{{ $item->nama_fasilitas }}</td>
                        <td>{{ $item->lokasi }}</td>
                        <td>{{ $item->ruang }}</td>
                        <td>{{ $item->gedung->nama_gedung ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button wire:click="edit({{ $item->id }})" 
                                        class="btn btn-sm btn-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#fasilitasModal">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <button wire:click="deleteConfirmed({{ $item->id }})" 
                                        class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada data fasilitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="card-footer px-3 border-0 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan <strong>{{ $fasilitas->firstItem() ?? 0 }} - {{ $fasilitas->lastItem() ?? 0 }}</strong> dari <strong>{{ $fasilitas->total() }}</strong> data
            </div>
            <div>
                <button 
                    wire:click="previousPage" 
                    wire:loading.attr="disabled"
                    class="btn btn-sm btn-outline-primary {{ $fasilitas->onFirstPage() ? 'disabled' : '' }}">
                    Sebelumnya
                </button>
                <button 
                    wire:click="nextPage" 
                    wire:loading.attr="disabled"
                    class="btn btn-sm btn-outline-primary ms-2 {{ !$fasilitas->hasMorePages() ? 'disabled' : '' }}">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div wire:ignore.self class="modal fade" id="fasilitasModal" tabindex="-1" role="dialog" aria-labelledby="fasilitasModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fasilitasModalLabel">
                        {{ $isEdit ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                            <input type="text" class="form-control" id="nama_fasilitas" wire:model.defer="nama_fasilitas">
                            @error('nama_fasilitas') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="gedung_id" class="form-label">Gedung</label>
                            <select class="form-select" id="gedung_id" wire:model="gedung_id">
                                <option value="">Pilih Gedung</option>
                                @foreach($gedungs as $gedung)
                                    <option value="{{ $gedung->id }}">{{ $gedung->nama_gedung }}</option>
                                @endforeach
                            </select>
                            @error('gedung_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lantai</label>
                            <input type="number" class="form-control" id="lokasi" wire:model.defer="lokasi" 
                                   min="1" :max="$jumlah_lantai">
                            @error('lokasi') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="text-muted">Maksimal lantai: {{ $jumlah_lantai }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ruang" class="form-label">Ruangan</label>
                            <input type="text" class="form-control" id="ruang" wire:model.defer="ruang">
                            @error('ruang') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="{{ $isEdit ? 'update' : 'store' }}">
                                {{ $isEdit ? 'Update' : 'Simpan' }}
                            </span>
                            <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}">
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
        Livewire.on('showModal', () => {
            var modal = new bootstrap.Modal(document.getElementById('fasilitasModal'));
            modal.show();
        });
        
        Livewire.on('hideModal', () => {
            var modal = bootstrap.Modal.getInstance(document.getElementById('fasilitasModal'));
            if (modal) {
                modal.hide();
            }
        });
        
        // Delete Confirmation
        Livewire.on('deleteConfirmation', (id) => {
            Swal.fire({
                title: 'Hapus Fasilitas?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteConfirmed', id);
                }
            });
        });
    });
</script>
@endpush