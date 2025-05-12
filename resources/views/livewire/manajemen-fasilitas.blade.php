<title>Volt Laravel Dashboard - Manajemen Fasilitas</title>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="#">Volt</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Fasilitas</li>
            </ol>
        </nav>
        <h2 class="h4">Manajemen Fasilitas Kampus</h2>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
    <a href="{{ route('fasilitas.create') }}" class="btn btn-sm btn-gray-800">Tambah Fasilitas</a>

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
                <input type="text" class="form-control" placeholder="Cari fasilitas">
            </div>
            <select class="form-select fmxw-200 d-none d-md-inline" aria-label="Filter status fasilitas">
                <option selected>Semua</option>
                <option value="1">Tersedia</option>
                <option value="2">Tidak Tersedia</option>
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
    <div class="d-flex mb-3">
        <select class="form-select fmxw-200" aria-label="Pilih aksi massal">
            <option selected>Aksi Massal</option>
            <option value="1">Hapus Fasilitas</option>
        </select>
        <button class="btn btn-sm px-3 btn-secondary ms-3">Terapkan</button>
    </div>
    <table class="table user-table table-hover align-items-center">
        <thead>
            <tr>
                <th></th>
                <th>Nama Fasilitas</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fasilitas as $item)
                <tr>
                    <td>
                        <div class="form-check dashboard-check">
                            <input class="form-check-input" type="checkbox" id="fasilitasCheck{{ $item->id }}">
                            <label class="form-check-label" for="fasilitasCheck{{ $item->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $item->nama_fasilitas }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>
                        <span class="fw-normal">{{ $item->status == 1 ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                    </path>
                                </svg>
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                <a href="{{ route('fasilitas.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <a class="dropdown-item text-danger d-flex align-items-center" href="{{ route('fasilitas.destroy', $item->id) }}"
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                                    <span class="fas fa-trash me-2"></span>
                                    Hapus
                                </a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('fasilitas.destroy', $item->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data fasilitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
