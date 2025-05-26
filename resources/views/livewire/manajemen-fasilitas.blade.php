@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Manajemen Fasilitas' => '',
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
<div>
    <title>Manajemen Fasilitas</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Manajemen Fasilitas</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button wire:click="resetInput" class="btn btn-sm btn-gray-800" data-bs-toggle="modal" data-bs-target="#fasilitasModal">
                <i class="fas fa-plus me-1"></i> Tambah Fasilitas
            </button>
        </div>
    </div>
    <div class="card card-body shadow border-0 mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-8 col-lg-6 d-flex">
                <div class="input-group">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Cari fasilitas...">
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
                    <th class="border-0">Kode Fasilitas</th>
                    <th class="border-0">Nama Fasilitas</th>
                    <th class="border-0">Jumlah</th>
                    <th class="border-0">Ruangan</th>
                    <th class="border-0">Lantai</th>
                    <th class="border-0">Gedung</th>
                    <th class="border-0 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fasilitas as $index => $item)
                    <tr>
                        <td>{{ $fasilitas->firstItem() + $index }}</td>
                        <td>{{ $item->kode_fasilitas }}</td>
                        <td>{{ $item->nama_fasilitas }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->ruangan->nama_ruangan }}</td>
                        <td>Lantai {{ $item->ruangan->lantai }}</td>
                        <td>{{ $item->ruangan->gedung->nama_gedung }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button wire:click="edit({{ $item->id }})" 
                                        class="btn btn-sm btn-warning"
                                        data-bs-toggle="tooltip" 
                                        title="Edit Fasilitas">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <button wire:click="$emit('deleteConfirmation', {{ $item->id }})" 
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus Fasilitas">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <svg class="icon icon-xxs text-gray-400 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tidak ada data fasilitas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            {{ $fasilitas->links('pagination::bootstrap-5')}}
        </nav>
    </div>

    <!-- Modal Form -->
    <div wire:ignore.self class="modal fade" id="fasilitasModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEdit ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Fasilitas</label>
                            <input type="text" class="form-control @error('kode_fasilitas') is-invalid @enderror" 
                                   wire:model.defer="kode_fasilitas" required style="text-transform: uppercase">
                            @error('kode_fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Fasilitas</label>
                            <input type="text" class="form-control @error('nama_fasilitas') is-invalid @enderror" 
                                   wire:model.defer="nama_fasilitas" required>
                            @error('nama_fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" min="1" class="form-control @error('jumlah') is-invalid @enderror" 
                                   wire:model.defer="jumlah" required>
                            @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gedung</label>
                            <select class="form-select @error('gedung_id') is-invalid @enderror" wire:model="gedung_id" required>
                                <option value="">Pilih Gedung</option>
                                @foreach ($gedung as $ged)
                                    <option value="{{ $ged->id }}">{{ $ged->nama_gedung }}</option>
                                @endforeach
                            </select>
                            @error('gedung_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if (!empty($lantaiList))
                        <div class="mb-3">
                            <label class="form-label">Lantai</label>
                            <select class="form-select @error('lantai') is-invalid @enderror" wire:model="lantai" required>
                                <option value="">Pilih Lantai</option>
                                @foreach ($lantaiList as $lvl)
                                    <option value="{{ $lvl }}">Lantai {{ $lvl }}</option>
                                @endforeach
                            </select>
                            @error('lantai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endif

                        @if (!empty($ruanganList))
                        <div class="mb-3">
                            <label class="form-label">Ruangan</label>
                            <select class="form-select @error('ruangan_id') is-invalid @enderror" wire:model.defer="ruangan_id" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruanganList as $ruangan)
                                    <option value="{{ $ruangan->id }}">
                                        {{ $ruangan->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ruangan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="{{ $isEdit ? 'update' : 'store' }}">
                                <i class="fas fa-save me-1"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                            </span>
                            <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Script for notifications -->
    <script>
        window.addEventListener('showModal', function() {
            new bootstrap.Modal(document.getElementById('fasilitasModal')).show();
        });
        
        window.addEventListener('hideModal', function() {
            bootstrap.Modal.getInstance(document.getElementById('fasilitasModal')).hide();
        });
        
        window.addEventListener('notify', event => {
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
        
        document.addEventListener('deleteConfirmation', function(event) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Fasilitas ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('deleteConfirmed', event.detail);
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')) {
                    Livewire.emit('deleteConfirmed', event.detail);
                }
            }
        });
    </script>
</div>