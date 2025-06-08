<div>  
  @section('breadcrumbs')
      @php
          $breadcrumbs = [
              'Sarana Prasarana' => '',
              'Verifikasi Perbaikan' => route('verifikasi-perbaikan'),
          ];
      @endphp
      @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
  @endsection
  <title>Dashboard - Sarpras</title>
  <body>

  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <h2>Verifikasi Laporan Perbaikan</h2>
      <div class="d-block mb-4 mb-md-0">
      </div>
  </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
              <select class="form-select fmxw-200 d-none d-md-inline me-2" wire:model.live="periode_id">
                  {{-- <option value="">Semua Periode</option>
                  @foreach ($periodes as $periode)
                      <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                  @endforeach --}}
              </select>
              <select class="form-select fmxw-200 d-none d-md-inline" id="statusFilter" aria-label="Filter status">
                  <option value="all" selected>Semua</option>
                  <option value="terkirim">Terkirim</option>
                  <option value="pending">Pending</option>
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
          <th>No</th>
          <th>Nama Pelapor</th>
          <th>Gedung</th>
          <th>Ruangan</th>
          <th>Estimasi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        {{-- @foreach ($laporan as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_pelapor }}</td>
            <td>{{ $item->gedung->nama_gedung }}</td>
            <td>{{ $item->fasilitas->nama_fasilitas }}</td>

            <td class="text-middle">
              @if ($item->sub_kriteria_id)
                <i class="fas fa-check-circle text-success" title="Estimasi sudah diisi"></i>
              @else
                <i class="fas fa-times-circle text-danger" title="Belum ada estimasi"></i>
              @endif
            </td>


            <td>
              <button class="btn btn-sm btn-info lihat-detail" data-id="{{ $item->id }}"><i class="fas fa-eye"></i>  Lihat Detail</button>
            </td>
          </tr>
        @endforeach --}}
      </tbody>
    </table>
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel">Detail Laporan Kerusakan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalDetailContent">
          <!-- Konten akan diisi via AJAX -->
        </div>
      </div>
    </div>
  </div>
</div>