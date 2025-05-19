<div>
    <title>Manajemen Gedung</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Gedung</li>
                </ol>
            </nav>
            <h2 class="h4">Manajemen Gedung Kampus</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button wire:click="resetInput" class="btn btn-sm btn-gray-800" data-bs-toggle="modal" data-bs-target="#gedungModal">
                Tambah Gedung
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
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Cari gedung">
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
                    <th>No</th>
                    <th>Nama Gedung</th>
                    <th>Jumlah Lantai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gedungs as $index => $gedung)
                    <tr>
                        <td>{{ $gedungs->firstItem() + $index }}</td>
                        <td>{{ $gedung->nama_gedung }}</td>
                        <td>{{ $gedung->jumlah_lantai }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                        </path>
                                    </svg>
                                </button>
                                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                    <button wire:click="edit({{ $gedung->id }})" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#gedungModal">
                                        Edit
                                    </button>
                                    <button wire:click="deleteConfirmed ({{ $gedung->id }})" class="dropdown-item text-danger">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada data gedung.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="card-footer px-3 border-0 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan <strong>{{ $gedungs->firstItem() ?? 0 }} - {{ $gedungs->lastItem() ?? 0 }}</strong> dari <strong>{{ $gedungs->total() }}</strong> data
            </div>
            <div>
                <button 
                    wire:click="previousPage" 
                    wire:loading.attr="disabled"
                    class="btn btn-sm btn-outline-primary {{ $gedungs->onFirstPage() ? 'disabled' : '' }}">
                    Sebelumnya
                </button>
                <button 
                    wire:click="nextPage" 
                    wire:loading.attr="disabled"
                    class="btn btn-sm btn-outline-primary ms-2 {{ !$gedungs->hasMorePages() ? 'disabled' : '' }}">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div wire:ignore.self class="modal fade" id="gedungModal" tabindex="-1" role="dialog" aria-labelledby="gedungModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gedungModalLabel">
                        {{ $isEdit ? 'Edit Gedung' : 'Tambah Gedung Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_gedung" class="form-label">Nama Gedung</label>
                            <input type="text" class="form-control" id="nama_gedung" wire:model.defer="nama_gedung">
                            @error('nama_gedung') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_lantai" class="form-label">Jumlah Lantai</label>
                            <input type="number" min="1" class="form-control" id="jumlah_lantai" wire:model.defer="jumlah_lantai">
                            @error('jumlah_lantai') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="store">
                                {{ $isEdit ? 'Update' : 'Simpan' }}
                            </span>
                            <span wire:loading wire:target="store">
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
    document.addEventListener('livewire:init', function() {
        // Modal Control
        Livewire.on('showModal', () => {
            var modal = new bootstrap.Modal(document.getElementById('gedungModal'));
            modal.show();
        });
        
        Livewire.on('hideModal', () => {
            var modal = bootstrap.Modal.getInstance(document.getElementById('gedungModal'));
            if (modal) {
                modal.hide();
            }
        });
    });
</script>
@endpush