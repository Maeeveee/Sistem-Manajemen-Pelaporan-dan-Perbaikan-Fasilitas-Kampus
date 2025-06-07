<div>
    <!-- Notifikasi Sukses -->
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header dan Tombol -->
    <div class="d-flex justify-content-between flex-wrap align-items-center py-3">
        <h2 class="h4 mb-0">Daftar Periode</h2>
        <button wire:click="toggleCreateForm" class="btn btn-primary">
            {{ $showCreateForm ? 'Batal' : 'Tambah Periode' }}
        </button>
    </div>

    <!-- Table Periode -->
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Nama Periode</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($periodes as $periode)
                    <tr>
                        <td>{{ $periode->nama_periode }}</td>
                        <td>{{ $periode->tanggal_mulai }}</td>
                        <td>{{ $periode->tanggal_selesai }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Belum ada periode yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Form Tambah -->
    <div class="modal fade {{ $showCreateForm ? 'show d-block' : '' }}" tabindex="-1" role="dialog" style="{{ $showCreateForm ? 'background-color: rgba(0,0,0,0.3);' : '' }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="store">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Periode Baru</h5>
                        <button type="button" class="btn-close" wire:click="toggleCreateForm"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_periode" class="form-label">Nama Periode</label>
                            <input type="text" id="nama_periode" wire:model="nama_periode" class="form-control @error('nama_periode') is-invalid @enderror">
                            @error('nama_periode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" wire:model="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror">
                            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" id="tanggal_selesai" wire:model="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror">
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" wire:click="toggleCreateForm">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
