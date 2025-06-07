 <div>
  @section('breadcrumbs')
      @php
          $breadcrumbs = [
              'Admin' => '',
              'laporan Perbaikan' => route('dashboard-admin'),
          ];
      @endphp
      @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
  @endsection
  <title>Dashboard -Teknisi</title>
  <body>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
      <div class="d-block mb-4 mb-md-0">
          <h2 class="h4">Laporan Perbaikan Fasilitas</h2>
      </div>
  </div>

   <!-- Tambahkan dropdown periode di table-settings -->
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
                  <input type="text" class="form-control" placeholder="Cari" wire:model.live="search">
              </div>
              <select class="form-select fmxw-200 d-none d-md-inline me-2" wire:model.live="periode_id">
                  <option value="">Semua Periode</option>
                  @foreach ($periodes as $periode)
                      <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                  @endforeach
              </select>
              <select class="form-select fmxw-200 d-none d-md-inline" wire:model.live="statusFilter">
                  <option value="all">Semua</option>
                  <option value="verifikasi">Verifikasi</option>
                  <option value="pending">Pending</option>
                  <option value="reject">Reject</option>
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

  @if(session('success'))
      <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <script>
          // Setelah 4 detik, hilangkan alert secara perlahan
          setTimeout(() => {
              const alert = document.getElementById('successAlert');
              if (alert) {
                  alert.classList.remove('show');
                  alert.classList.add('fade');
                  setTimeout(() => alert.remove(), 500); // Hapus elemen dari DOM setelah animasi
              }
          }, 4000); // 4000 ms = 4 detik
      </script>
  @endif


  <div class="card card-body shadow border-0 table-wrapper table-responsive">
    <table class="table table-hover align-items-center">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Pelapor</th>
          <th>Gedung</th>
          <th>Ruangan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($laporan as $index => $item)
        @php
                $statusClass = match($item['status_admin']) {
                  'verifikasi' => 'bg-success text-white',
                  'pending' => 'bg-gray-400 text-white',
                  'reject' => 'bg-danger text-white',
                  default      => 'bg-secondary text-white',
                };
              @endphp
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_pelapor }}</td>
            <td>{{ $item->gedung->nama_gedung }}</td>
            <td>{{ $item->ruangan->nama_ruangan }}</td>


            <td class="status-cell">
              <span  class="badge {{ $statusClass }} py-1 px-2 rounded-pill status-cell fw-bold font-small">
                  {{ $item['status_admin'] }}
                </span>
            </td>
            <td>
              <button class="btn btn-sm btn-info lihat-detail" data-id="{{ $item->id }}">Lihat Detail</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <!-- Modal -->
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

    <!-- Bootstrap Volt JS Bundle -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/@themesberg/volt-bootstrap-5-dashboard@latest/dist/js/volt.js"></script> --}}
  </body>

  <script>
    
    document.addEventListener('DOMContentLoaded', function () {
      const detailButtons = document.querySelectorAll('.lihat-detail');

      detailButtons.forEach(button => {
        button.addEventListener('click', function () {
          const id = this.getAttribute('data-id');
          
          fetch(`/admin/laporan/detail/${id}`)
            .then(response => response.text())
            .then(data => {
              document.getElementById('modalDetailContent').innerHTML = data;
              const modal = new bootstrap.Modal(document.getElementById('detailModal'));
              modal.show();
            })
            .catch(error => {
              console.error('Terjadi kesalahan:', error);
              alert('Gagal memuat detail laporan.');
            });
        });
      });
    });


  const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.querySelector('.form-control[placeholder="Cari"]');
    const rows = document.querySelectorAll('tbody tr');

    function filterTable() {
      const keyword = searchInput.value.toLowerCase();
      const selectedStatus = statusFilter.value;
      
      rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        const statusCell = row.querySelector('.status-cell');
        const statusText = statusCell ? statusCell.textContent.trim() : '';
        
        const matchesStatus = selectedStatus === 'all' || statusText === selectedStatus;
        const matchesSearch = rowText.includes(keyword);

        row.style.display = matchesStatus && matchesSearch ? '' : 'none';
      });
    }

    // Jalankan filter saat halaman dimuat
    document.addEventListener('DOMContentLoaded', filterTable);

    // Event listeners
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
  </script>
</div> 