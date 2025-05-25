@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'kelola Gedung' => '',
            'Manajemen Gedung' => route('manajemen.gedung'),
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
<div>
    <title>Manajemen Gedung</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Manajemen Gedung Kampus</h2>
            <p class="mb-0">Kelola data gedung dan ruangan</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button wire:click="resetInputGedung" class="btn btn-sm btn-gray-800" data-bs-toggle="modal" data-bs-target="#gedungModal">
                <i class="fas fa-plus me-1"></i> Tambah Gedung
            </button>
        </div>
    </div>

    <div class="card card-body shadow border-0 mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-8 col-lg-6 d-flex">
                <div class="input-group">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Cari gedung...">
                </div>
            </div>
            <div class="col-4 col-lg-2 d-flex justify-content-end">
                <select wire:model="perPage" class="form-select">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th class="border-0">No</th>
                    <th class="border-0">Kode</th>
                    <th class="border-0">Nama Gedung</th>
                    <th class="border-0">Lantai</th>
                    <th class="border-0">Jumlah Ruangan</th>
                    <th class="border-0 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gedungs as $index => $gedung)
                    <tr>
                        <td>{{ $gedungs->firstItem() + $index }}</td>
                        <td>{{ $gedung->kode_gedung }}</td>
                        <td>{{ $gedung->nama_gedung }}</td>
                        <td>{{ $gedung->jumlah_lantai }}</td>
                        <td>{{ $gedung->ruangans_count }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button wire:click="showRuanganModal({{ $gedung->id }})" 
                                        class="btn btn-sm btn-info"
                                        data-bs-toggle="tooltip" 
                                        title="Kelola Ruangan">
                                    <i class="fas fa-door-open"></i>
                                </button>
                                
                                <button wire:click="editGedung({{ $gedung->id }})" 
                                        class="btn btn-sm btn-warning"
                                        data-bs-toggle="tooltip" 
                                        title="Edit Gedung">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <button wire:click="$emit('deleteGedungConfirmation', {{ $gedung->id }})" 
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus Gedung">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <svg class="icon icon-xxs text-gray-400 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tidak ada data gedung
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
            <nav aria-label="Page navigation">
                {{ $gedungs->links() }}
            </nav>
            <div class="fw-normal small mt-4 mt-lg-0">
                Menampilkan <b>{{ $gedungs->firstItem() }}</b> sampai <b>{{ $gedungs->lastItem() }}</b> dari <b>{{ $gedungs->total() }}</b> data
            </div>
        </div>
    </div>

    <!-- Modal Gedung -->
    <div wire:ignore.self class="modal fade" id="gedungModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEditGedung ? 'Edit Gedung' : 'Tambah Gedung Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="storeGedung">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Gedung</label>
                            <input type="text" class="form-control @error('kode_gedung') is-invalid @enderror" 
                                   wire:model.defer="kode_gedung" required>
                            @error('kode_gedung') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Gedung</label>
                            <input type="text" class="form-control @error('nama_gedung') is-invalid @enderror" 
                                   wire:model.defer="nama_gedung" required >
                            @error('nama_gedung') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Lantai</label>
                            <input type="number" min="1" class="form-control @error('jumlah_lantai') is-invalid @enderror" 
                                   wire:model.defer="jumlah_lantai" required>
                            @error('jumlah_lantai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="storeGedung">
                                <i class="fas fa-save me-1"></i> Simpan
                            </span>
                            <span wire:loading wire:target="storeGedung">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Daftar Ruangan -->
    <div wire:ignore.self class="modal fade" id="daftarRuanganModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-door-open me-2"></i> Daftar Ruangan - {{ $selectedGedung->nama_gedung ?? '' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-4">
                        <button wire:click="showTambahRuanganModal" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Ruangan
                        </button>
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" wire:model.debounce.300ms="searchRuangan" class="form-control" placeholder="Cari ruangan...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ruangan</th>
                                    <th>Lantai</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ruangans as $index => $ruangan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ruangan->nama_ruangan }}</td>
                                        <td>Lantai {{ $ruangan->lantai }}</td>
                                        <td class="text-center">
                                            <button wire:click="editRuangan({{ $ruangan->id }})" 
                                                    class="btn btn-sm btn-warning me-1"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="$emit('deleteRuanganConfirmation', {{ $ruangan->id }})" 
                                                    class="btn btn-sm btn-danger"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <svg class="icon icon-xxs text-gray-400 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Tidak ada data ruangan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Ruangan -->
    <div wire:ignore.self class="modal fade" id="ruanganModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEditRuangan ? 'Edit Ruangan' : 'Tambah Ruangan Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="storeRuangan">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Ruangan</label>
                            <input type="text" class="form-control @error('nama_ruangan') is-invalid @enderror" 
                                   wire:model.defer="nama_ruangan" required style="text-transform: uppercase">
                            @error('nama_ruangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lantai</label>
                            <input type="number" min="1" 
                                class="form-control @error('lantai') is-invalid @enderror" 
                                wire:model.defer="lantai" required>
                            @error('lantai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="storeRuangan">
                                <i class="fas fa-save me-1"></i> Simpan
                            </span>
                            <span wire:loading wire:target="storeRuangan">
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

<script>
    window.addEventListener('showModal', event => {
        const id = event.detail.modalId;
        const modal = document.getElementById(id);
        if (modal) new bootstrap.Modal(modal).show();
    });

    window.addEventListener('hideModal', event => {
        const id = event.detail.modalId;
        const modalEl = document.getElementById(id);
        if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
    });

    window.addEventListener('showModalDelayed', event => {
        setTimeout(() => {
            const id = event.detail.modalId;
            const modal = document.getElementById(id);
            if (modal) new bootstrap.Modal(modal).show();
        }, event.detail.delay || 300);
    });

    // SweetAlert untuk notifikasi sukses/error
    window.addEventListener('alert', event => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: event.detail.type,
                title: event.detail.message,
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            alert(event.detail.message);
        }
    });

    // SweetAlert untuk konfirmasi hapus gedung
    document.addEventListener('deleteGedungConfirmation', function(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Gedung dan semua ruangan di dalamnya akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteGedungConfirmed', event.detail);
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus gedung ini? Semua ruangan akan ikut terhapus!')) {
                Livewire.emit('deleteGedungConfirmed', event.detail);
            }
        }
    });

    // SweetAlert untuk konfirmasi hapus ruangan
    document.addEventListener('deleteRuanganConfirmation', function(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Ruangan dan semua fasilitas di dalamnya akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteRuanganConfirmed', event.detail);
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus ruangan ini? Semua fasilitas akan ikut terhapus!')) {
                Livewire.emit('deleteRuanganConfirmed', event.detail);
            }
        }
    });
</script>