<div>  
   <title>Riwayat Perbaikan - Teknisi</title> 

    @section('breadcrumbs')
        @php
            $breadcrumbs = [
                'Teknisi' => '',
                'Riwayat Perbaikan' => route('riwayat-perbaikan'),
            ];
        @endphp
        @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
    @endsection

    
    <div class="container py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
            <div>
                <h2 class="h4">Riwayat Perbaikan Fasilitas</h2>
                <p class="mb-0">Laporan perbaikan yang telah Anda selesaikan</p>
            </div>
        </div>

        <div class="card card-body shadow-sm border-0 table-wrapper table-responsive">
            <div class="table-settings mb-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-lg-8 d-md-flex">
                        <div class="input-group me-2 me-lg-3 fmxw-300">
                            <span class="input-group-text">
                                <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari fasilitas/deskripsi" wire:model.debounce.500ms="search">
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Fasilitas</th>
                        <th>Catatan Teknisi</th>
                        <th>Status</th>
                        <th>Foto Sebelum</th>
                        <th>Foto Sesudah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPerbaikan as $riwayat)
                        <tr>
                            <td>{{ $riwayat->updated_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $riwayat->gedung->nama_gedung ?? '-' }}, Lantai {{ $riwayat->lantai }}, {{ $riwayat->ruangan->nama_ruangan ?? '-' }}</td>
                            <td>{{ $riwayat->fasilitas->nama_fasilitas ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($riwayat->catatan_teknisi, 50) ?? '-' }}</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                            <td>
                                @if($riwayat->foto)
                                    <img src="{{ asset('storage/' . $riwayat->foto) }}" alt="Foto Sebelum" class="img-thumbnail" style="max-height: 80px;">
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                @if($riwayat->foto_perbaikan)
                                    <img src="{{ asset('storage/laporan-perbaikan/' . $riwayat->foto_perbaikan) }}" alt="Foto Sesudah" class="img-thumbnail" style="max-height: 80px;">
                                @else
                                    <small class="text-muted">Belum diunggah</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada riwayat perbaikan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($riwayatPerbaikan instanceof \Illuminate\Pagination\LengthAwarePaginator && $riwayatPerbaikan->hasPages())
                <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                    {{ $riwayatPerbaikan->links() }}
                </div>
            @endif
        </div>
    </div>
</div> 