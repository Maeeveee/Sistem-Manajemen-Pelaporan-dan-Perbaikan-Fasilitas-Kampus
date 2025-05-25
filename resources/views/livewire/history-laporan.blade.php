@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'History Laporan Kerusakan' => '',
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
<div> <!-- Single root element for Livewire -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Riwayat Laporan Saya</h2>
            <p class="text-muted mb-0">NIM/NIP: {{ Auth::user()->identifier }}</p>
        </div>
    </div>

    <div class="card card-body shadow border-0 mb-4">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="Cari berdasarkan gedung, ruangan, fasilitas..." 
                        wire:model.live="search"
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-filter"></i>
                    </span>
                    <select class="form-select" wire:model.live="statusFilter">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="verifikasi">Verifikasi</option>
                        <option value="reject">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        @if($laporans->isEmpty())
            <div class="alert alert-info mb-0">
                @if($search || $statusFilter !== 'all')
                    Tidak ditemukan laporan yang sesuai dengan filter.
                @else
                    Anda belum membuat laporan kerusakan.
                @endif
            </div>
        @else
        <table class="table table-hover align-items-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Gedung</th>
                    <th>Ruangan</th>
                    <th>Fasilitas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporans as $index => $laporan)
                    @php
                        $statusClass = match($laporan->status_admin) {
                            'verifikasi' => 'bg-success text-white',
                            'pending' => 'bg-gray-400 text-white',
                            'reject' => 'bg-danger text-white',
                            default      => 'bg-secondary text-white',
                        };
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $laporan->created_at->format('d M Y') }}</td>
                        <td>{{ $laporan->gedung->nama_gedung ?? '-' }}</td>
                        <td>{{ $laporan->ruangan->nama_ruangan ?? '-' }}</td>
                        <td>{{ $laporan->fasilitas->nama_fasilitas ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $statusClass }} py-1 px-2 rounded-pill">
                                {{ ucfirst($laporan->status_admin ?? 'Pending') }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" wire:click="showDetail({{ $laporan->id }})">
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Modal Detail menggunakan Livewire -->
    @if($showModal && $selectedLaporan)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Laporan Kerusakan</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pengisian</label>
                                <input type="text" class="form-control bg-white" value="{{ $selectedLaporan->created_at->format('Y-m-d') }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Pelapor</label>
                                <input type="text" class="form-control bg-white" value="{{ $selectedLaporan->nama_pelapor ?? '-' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NIM/NIP</label>
                                <input type="text" class="form-control bg-white" value="{{ $selectedLaporan->identifier ?? '-' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gedung</label>
                                <input type="text" class="form-control bg-white" value="{{ $selectedLaporan->gedung->nama_gedung ?? '-' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ruangan</label>
                                <input type="text" class="form-control bg-white" value="{{ $selectedLaporan->ruangan->nama_ruangan ?? '-' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lantai</label>
                                <input type="text" class="form-control bg-white" value="{{ $selectedLaporan->lantai }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fasilitas</label>
                                <input type="text" class="form-control bg-white" value="{{ $selectedLaporan->fasilitas->nama_fasilitas ?? '-' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi Kerusakan</label>
                                <textarea class="form-control bg-white" rows="3" readonly>{{ $selectedLaporan->deskripsi }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control bg-white" value="{{ ucfirst($selectedLaporan->status_admin) }}" readonly>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">Foto</label><br>
                                @if($selectedLaporan->foto)
                                <img src="{{ asset('storage/' . $selectedLaporan->foto) }}" alt="Foto Kerusakan" class="img-thumbnail" style="max-width: 300px;">
                                @else
                                <p class="text-muted">Tidak ada foto</p>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Komentar Admin</label>
                                <textarea class="form-control bg-white" rows="3" readonly>{{ $selectedLaporan->komentar_admin ?? '-' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>