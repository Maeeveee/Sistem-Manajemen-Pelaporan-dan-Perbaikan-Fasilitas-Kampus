@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Sarana Prasarana' => '',
            'Laporan Kerusakan' => route('dashboard-sarpras'),
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
                <option value="Verifikasi">Verifikasi</option>
                <option value="Pending">Pending</option>
                <option value="Reject">Reject</option>
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
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @php
        $laporan = [
            ['nama_pelapor' => 'Nadif', 'gedung' => 'Teknik Mesin', 'ruangan' => 'LER P3', 'status' => 'Verifikasi'],
            ['nama_pelapor' => 'Kamila', 'gedung' => 'Teknik Sipil', 'ruangan' => 'LAB 1', 'status' => 'Pending'],
            ['nama_pelapor' => 'Rizky', 'gedung' => 'Informatika', 'ruangan' => 'Lab Komputer 2', 'status' => 'Reject'],
        ];
      @endphp
      @foreach ($laporan as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $item['nama_pelapor'] }}</td>
          <td>{{ $item['gedung'] }}</td>
          <td>{{ $item['ruangan'] }}</td>
          @php
              $statusClass = match($item['status']) {
                'Verifikasi' => 'bg-success text-white',
                'Pending' => 'bg-gray-400 text-white',
                'Reject' => 'bg-danger text-white',
              };
            @endphp

          <td class="status-cell">
            <span  class="badge {{ $statusClass }} py-1 px-2 rounded-pill status-cell fw-bold font-small">
                {{ $item['status'] }}
              </span>
          </td>
          <td>
            <a href="#" class="btn btn-sm btn-info">Lihat Detail</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

  <!-- Bootstrap Volt JS Bundle -->
  {{-- <script src="https://cdn.jsdelivr.net/npm/@themesberg/volt-bootstrap-5-dashboard@latest/dist/js/volt.js"></script> --}}
</body>

<script>
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