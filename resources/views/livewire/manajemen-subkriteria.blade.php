@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Manajemen Sub Kriteria' => '',
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
<div>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h2 class="h4">Manajemen Sub Kriteria</h2>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
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
                        <input type="text" wire:model.debounce.300ms="search" class="form-control"
                            placeholder="Cari Subkriteria...">
                    </div>
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
                                <a class="dropdown-item d-flex align-items-center fw-bold" href="#"
                                    wire:click.prevent="changePerPage(10)">10</a>
                                <a class="dropdown-item fw-bold" href="#"
                                    wire:click.prevent="changePerPage(20)">20</a>
                                <a class="dropdown-item fw-bold rounded-bottom" href="#"
                                    wire:click.prevent="changePerPage(30)">30</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="subKriteriaModal" tabindex="-1" role="dialog"
            aria-labelledby="kriteriaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="kriteriaModalLabel">Edit SubKriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="updateSubKriteria">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama SubKriteria</label>
                                <input type="text" class="form-control"
                                    wire:model.defer="currentSubKriteria.nama_subkriteria"
                                    placeholder="Masukkan nama subkriteria" readonly>
                                @error('currentSubKriteria.nama_subkriteria')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bobot" class="form-label">Nilai</label>
                                <input type="number" class="form-control" id="bobot"
                                    wire:model.defer="currentSubKriteria.nilai" min="1" max="100"
                                    placeholder="Masukkan nilai" required>
                                @error('currentSubKriteria.nilai')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="form-text">Masukkan nilai antara 1 - 100</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove wire:target="updateSubKriteria">
                                    Update SubKriteria
                                </span>
                                <span wire:loading wire:target="updateSubKriteria">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card card-body shadow border-0 table-wrapper table-responsive">
            <table class="table user-table table-hover align-items-center">
                <thead>
                    <tr>
                        <th class="border-bottom" wire:click="sortBy('id')">
                            No
                            @if ($sortField === 'id')
                                <span class="icon icon-sm">
                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                </span>
                            @endif
                        </th>
                        <th class="border-bottom" wire:click="sortBy('nama_subkriteria')">
                            Nama SubKriteria
                            @if ($sortField === 'nama_subkriteria')
                                <span class="icon icon-sm">
                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                </span>
                            @endif
                        </th>
                        <th class="border-bottom" wire:click="sortBy('bobot')">
                            Nilai
                            @if ($sortField === 'bobot')
                                <span class="icon icon-sm">
                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                </span>
                            @endif
                        </th>
                        <th class="border-bottom">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subKriterias as $subkriteria)
                        <tr>
                            <td>{{ ($subKriterias->currentPage() - 1) * $subKriterias->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $subkriteria->nama_subkriteria }}</td>
                            <td>{{ $subkriteria->nilai }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button wire:click="editSubKriteria({{ $subkriteria->id }})"
                                        class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#subKriteriaModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data subkriteria ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center align-items-center mt-3">
                <div class="justify-content-beetween d-flex align-items-center">
                    <div>
                        <h5>Total Nilai: {{ $totalNilai }}</h5>
                    </div>

                </div>
            </div>

            <nav aria-label="Page navigation">
                {{ $subKriterias->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('show-modal', event => {
        var myModalEl = document.getElementById(modalId);
        var modal = new bootstrap.Modal(myModalEl);
        modal.show();
    });

    window.addEventListener('hide-modal', event => {
        var myModalEl = document.getElementById(modalId);
        var modal = bootstrap.Modal.getInstance(myModalEl);
        if (modal) {
            modal.hide();
        }
    });
</script>
@endpush