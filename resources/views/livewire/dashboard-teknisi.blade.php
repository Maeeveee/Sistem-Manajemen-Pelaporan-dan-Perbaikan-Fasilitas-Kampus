@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Teknisi' => '',
            'Laporan Pengguna' => route('dashboard-teknisi'),
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
<title>Dashboard -Teknisi</title>
<body>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <h2 class="h4">Laporan Perbaikan Fasilitas</h2>
        <p class="mb-0">Daftar perbaikan yang ditugaskan kepada Anda</p>
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
                <input type="text" class="form-control" placeholder="Cari">
            </div>
            <select class="form-select fmxw-200 d-none d-md-inline" id="statusFilter" aria-label="Filter status">
                <option value="all" selected>Semua</option>
                <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                <option value="Pending">Pending</option>
                <option value="Selesai">Selesai</option>
            </select>

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
                        <a class="dropdown-item d-flex align-items-center fw-bold" href="#">10</a>
                        <a class="dropdown-item fw-bold" href="#">20</a>
                        <a class="dropdown-item fw-bold rounded-bottom" href="#">30</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-body shadow border-0 table-wrapper table-responsive">
  <table class="table table-hover align-items-center">
    <thead>
      <tr>
        <th>Prioritas</th>
        <th>Lokasi</th>
        <th>Fasilitas</th>
        <th>Skor SPK</th>
        <th>Tanggal Lapor</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($laporanDiproses as $laporan)
        <tr>
            <td>
                <span class="badge bg-{{ $laporan->hasilTopsis->nilai > 0.7 ? 'danger' : ($laporan->hasilTopsis->nilai > 0.5 ? 'warning' : 'success') }}">
                    {{ $loop->iteration }}
                </span>
            </td>
            <td>
                {{ $laporan->gedung->nama_gedung }}, 
                Lantai {{ $laporan->lantai }}, 
                {{ $laporan->ruangan->nama_ruangan }}
            </td>
            <td>{{ $laporan->fasilitas->nama_fasilitas }}</td>
            <td>{{ number_format($laporan->hasilTopsis->nilai, 3) }}</td>
            <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
            <td>
                <button wire:click="selectLaporan({{ $laporan->id }})" 
                        class="btn btn-sm btn-info"
                        data-bs-toggle="modal" 
                        data-bs-target="#detailModal">
                    <i class="fas fa-edit"></i> Update
                </button>
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

  <!-- Bootstrap Volt JS Bundle -->
  {{-- <script src="https://cdn.jsdelivr.net/npm/@themesberg/volt-bootstrap-5-dashboard@latest/dist/js/volt.js"></script> --}}
</body>

<!-- Modal Update Status -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="detailModalLabel">Update Status Perbaikan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              @if($selectedLaporan)
              <div class="row">
                  <div class="col-md-6">
                      <div class="card mb-3">
                          <div class="card-header bg-light">
                              <h6 class="mb-0">Detail Laporan</h6>
                          </div>
                          <div class="card-body">
                              <table class="table table-sm">
                                  <tr>
                                      <th width="40%">Lokasi</th>
                                      <td>
                                          {{ $selectedLaporan->gedung->nama_gedung }}, 
                                          Lantai {{ $selectedLaporan->lantai }}, 
                                          {{ $selectedLaporan->ruangan->nama_ruangan }}
                                      </td>
                                  </tr>
                                  <tr>
                                      <th>Fasilitas</th>
                                      <td>{{ $selectedLaporan->fasilitas->nama_fasilitas }}</td>
                                  </tr>
                                  <tr>
                                      <th>Skor Prioritas</th>
                                      <td>
                                          <span class="badge bg-{{ $selectedLaporan->hasilTopsis->nilai > 0.7 ? 'danger' : ($selectedLaporan->hasilTopsis->nilai > 0.5 ? 'warning' : 'success') }}">
                                              {{ number_format($selectedLaporan->hasilTopsis->nilai, 3) }}
                                          </span>
                                      </td>
                                  </tr>
                                  <tr>
                                      <th>Deskripsi Kerusakan</th>
                                      <td>{{ $selectedLaporan->deskripsi }}</td>
                                  </tr>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card">
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
                                  </div>
                                  
                                  <div class="mb-3">
                                      <label class="form-label">Catatan Teknisi</label>
                                      <textarea class="form-control" 
                                                wire:model="catatanTeknisi"
                                                rows="4"
                                                placeholder="Tambahkan catatan tentang perbaikan..."></textarea>
                                  </div>
                                  
                                  <div class="d-flex justify-content-end">
                                      <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                                      <button type="submit" class="btn btn-primary">
                                          <span wire:loading.remove>Simpan Perubahan</span>
                                          <span wire:loading>
                                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                              Menyimpan...
                                          </span>
                                      </button>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
              @endif
          </div>
      </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('livewire:load', function() {
      // Refresh data ketika modal ditutup
      $('#detailModal').on('hidden.bs.modal', function () {
          Livewire.emit('loadData');
      });
  });
</script>
@endpush
</div>
