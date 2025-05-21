<div>
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
                    <li class="breadcrumb-item active" aria-current="page">Daftar Pengguna</li>
                </ol>
            </nav>
            <h2 class="h4">Daftar Pengguna</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button wire:click="create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Pengguna Baru
            </button>
            <div class="btn-group ms-2 ms-lg-3">
                <button type="button" class="btn btn-sm btn-outline-gray-600">Bagikan</button>
                <button type="button" class="btn btn-sm btn-outline-gray-600">Ekspor</button>
            </div>
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
                    <input type="text" class="form-control" placeholder="Cari pengguna"
                        wire:model.debounce.500ms="search">
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
                            <a class="dropdown-item d-flex align-items-center fw-bold"
                                wire:click="$set('perPage', 10)">10</a>
                            <a class="dropdown-item fw-bold" wire:click="$set('perPage', 20)">20</a>
                            <a class="dropdown-item fw-bold rounded-bottom" wire:click="$set('perPage', 30)">30</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table user-table table-hover align-items-center">
            <thead>
                <tr>
                    <th class="border-bottom">
                        <div class="form-check dashboard-check">
                            <input class="form-check-input" type="checkbox" id="userCheckAll">
                            <label class="form-check-label" for="userCheckAll"></label>
                        </div>
                    </th>
                    <th class="border-bottom">Nama</th>
                    <th class="border-bottom">NIM/NIP/NIDN</th>
                    <th class="border-bottom">Role</th>
                    <th class="border-bottom">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="form-check dashboard-check">
                                <input class="form-check-input" type="checkbox" id="userCheck{{ $user->id }}">
                                <label class="form-check-label" for="userCheck{{ $user->id }}"></label>
                            </div>
                        </td>
                        <td class="d-flex align-items-center">
                            <a href="#" class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=random&color=fff"
                                    class="avatar rounded-circle me-3" alt="Avatar">
                                    <span class="fw-bold">{{ $user->name ?? '-' }}</span>
                            </a>
                        </td>
                        <td>
                            <span class="text-gray">{{ $user->identifier ?? '-' }}</span>
                        </td>
                        <td><span class="fw-normal">{{ $user->role->name ?? 'Tidak Ditentukan' }}</span></td>
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
                                    <a class="dropdown-item d-flex align-items-center"
                                        wire:click="edit({{ $user->id }})">
                                        <span class="fas fa-edit me-2"></span>
                                        Edit
                                    </a>
                                    <a class="dropdown-item text-danger d-flex align-items-center"
                                        wire:click="delete({{ $user->id }})">
                                        <span class="fas fa-trash me-2"></span>
                                        Hapus Pengguna
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $users->links() }}
        @else
            <p>Tidak ada paginasi yang tersedia.</p>
        @endif
    </div>

    <!-- Modal for Create/Edit -->
    <div class="modal fade" id="userModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <!-- Nama -->
                        <div class="form-group mb-4">
                            <label for="name">Nama</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" wire:model="name" class="form-control" id="name"
                                    placeholder="Nama Lengkap">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Identifier -->
                        <div class="form-group mb-4">
                            <label for="identifier">NIM/NIP/NIDN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" wire:model="identifier" class="form-control" id="identifier"
                                    placeholder="NIM/NIP/NIDN">
                                @error('identifier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Password (only for create) -->
                        @if (!$isEdit)
                            <div class="form-group mb-4">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" wire:model="password" class="form-control" id="password"
                                        placeholder="Password">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <!-- Role -->
                        <div class="form-group mb-4">
                            <label for="role_id">Role</label>
                            <select wire:model="role_id" class="form-select" id="role_id">
                                <option value="">-- Pilih Role --</option>
                                @if (isset($roles) && $roles->isNotEmpty())
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                @else
                                    <option disabled>Tidak ada role tersedia</option>
                                @endif
                            </select>
                            @error('role_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-gray-800">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('open-modal', () => {
                var userModal = new bootstrap.Modal(document.getElementById('userModal'));
                userModal.show();
            });
            
            Livewire.on('close-modal', () => {
                var userModalEl = document.getElementById('userModal');
                var userModal = bootstrap.Modal.getInstance(userModalEl);
                if (userModal) {
                    userModal.hide();
                }
            });
        });
    </script>
</div>
```