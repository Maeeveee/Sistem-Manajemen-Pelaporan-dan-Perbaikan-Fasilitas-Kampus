<div>  
  @section('breadcrumbs')
      @php
          $breadcrumbs = [
              'Verifikasi Perbaikan' => route('verifikasi-perbaikan'),
          ];
      @endphp
      @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
  @endsection
  <title>Dashboard - Sarpras</title>

  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <h2>Verifikasi Laporan Perbaikan</h2>
    <div class="d-block mb-4 mb-md-0"></div>
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
            <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
            </svg>
          </span>
          <input type="text" class="form-control" placeholder="Cari">
        </div>
        <select class="form-select fmxw-200 d-none d-md-inline" id="statusFilter" aria-label="Filter status" wire:model="statusFilter">
          <option value="all" selected>Semua</option>
          <option value="selesai">Selesai</option>
          <option value="pending">Pending</option>
        </select>
      </div>
      <div class="col-3 col-lg-4 d-flex justify-content-end">
        <div class="btn-group">
          <div class="dropdown me-1">
            <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path>
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
          <th>Gedung</th>
          <th>Ruangan</th>
          <th>Fasilitas</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($laporan as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $item->gedung->nama_gedung }}</td>
          <td>{{ $item->ruangan->nama_ruangan }}</td>
          <td>{{ $item->fasilitas->nama_fasilitas }}</td>
          <td>
            @if ($item->status === 'selesai')
              <span class="badge bg-success">Selesai</span>
            @else
              <span class="badge bg-warning text-dark">Belum Terkirim</span>
            @endif
          </td>
          <td>
            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
              <i class="fas fa-eye"></i> Lihat Detail
            </button>
          </td>
        </tr>

        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-3">
            
            <!-- Header -->
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-semibold" id="detailModalLabel{{ $item->id }}">Detail Laporan Kerusakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 py-3">
                <form>

                  <!-- Tanggal Laporan -->
                  <div class="mb-3">
                      <label class="form-label">Tanggal Laporan</label>
                      <input type="text" class="form-control bg-white" value="{{ $item->created_at->format('d/m/Y H:i') }}" readonly>
                  </div>

                    <!-- Gedung -->
                  <div class="mb-3">
                        <label class="form-label">Gedung</label>
                        <input type="text" class="form-control bg-white" value="{{ $item->gedung->nama_gedung }}" readonly>
                   </div>

                    <!-- Ruangan -->
                  <div class="mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" class="form-control bg-white" value="{{ $item->ruangan->nama_ruangan }}" readonly>
                  </div>

                    <!-- Fasilitas -->
                  <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <input type="text" class="form-control bg-white" value="{{ $item->fasilitas->nama_fasilitas }}" readonly>
                  </div>


                    <!-- Status -->
                  <div class="mb-3">
                        <label class="form-label">Status Pengerjaan Teknisi</label>
                        <input type="text" class="form-control bg-white" value="{{ $item->status_teknisi}}" readonly>
                  </div>

                    <!-- Gambar Kerusakan -->
                    @if($item->foto_perbaikan)
    <div class="mb-3">
        <label class="form-label">Gambar Perbaikan Kerusakan</label>
        <div class="border rounded p-2 text-center">
            <img src="{{ asset('storage/laporan-perbaikan/' . $item->foto_perbaikan) }}" alt="Gambar Perbaikan" class="img-fluid rounded" style="max-height: 300px; object-fit: contain;">
        </div>
    </div>
@endif


                    <!-- Catatan Teknisi -->
                    <div class="mb-3">
                        <label class="form-label">Catatan Teknisi</label>
                        <textarea class="form-control bg-white" rows="3" readonly>{{ $item->catatan_teknisi ?? 'Tidak ada catatan' }}</textarea>
                    </div>

                </form>
            </div>

            <!-- Footer -->
            <!-- Tombol di modal-footer -->
              <div class="modal-footer bg-light">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  
                  @if($item->status !== 'selesai')
                  <button type="button" class="btn btn-success btn-kirim text-white" data-id="{{ $item->id }}">
                      Kirim
                  </button>
                  @endif
              </div>

                      </div>
                  </div>
              </div>

              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-kirim').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                Swal.fire({
                    title: 'Kirim Laporan?',
                    text: "Apakah Anda yakin ingin mengirim laporan ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim request ke controller
                        fetch(`/laporan/${id}/kirim`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Terkirim!', data.message, 'success')
                                    .then(() => window.location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    });
</script>
