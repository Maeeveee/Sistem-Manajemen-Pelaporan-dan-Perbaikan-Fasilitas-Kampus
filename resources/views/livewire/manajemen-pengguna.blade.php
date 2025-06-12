@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Manajemen Pengguna' => route('users'),
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
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
        </div>
    </div>

    <div class="table-settings mb-4">
        <div class="card card-body shadow border-0 mb-4">
            <div class="row justify-content-between align-items-center">
                <div class="col-8 col-lg-6 d-flex">
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <input type="text" wire:model.debounce.300ms="search" class="form-control"
                            placeholder="Cari pengguna...">
                    </div>
                </div>
                <div class="col-4 col-lg-2 d-flex justify-content-end">
                    <select wire:model="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
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
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th class="border-0">No</th>
                    <th class="border-0">Nama</th>
                    <th class="border-0">NIM/NIP/NIDN</th>
                    <th class="border-0">Role</th>
                    <th class="border-0 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
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
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-warning" wire:click="edit({{ $user->id }})"
                                    data-bs-toggle="tooltip" title="Edit Pengguna">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="delete({{ $user->id }})"
                                    data-bs-toggle="tooltip" title="Hapus Pengguna">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Tidak ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            <nav aria-label="Page navigation">
                {{ $users->links('pagination::bootstrap-5') }}
            </nav>
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
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" method="POST">
                        <!-- Identifier -->
                        <div class="form-group mt-4 mb-4">
                            <label for="identifier">NIM/NIP/NIDN</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon-id"><svg
                                        class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z" />
                                    </svg></span>
                                <input wire:model="identifier" id="identifier" type="text" class="form-control"
                                    placeholder="Masukkan NIM/NIP/NIDN" required>
                            </div>
                            @error('identifier')
                                <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="form-group mt-4 mb-4">
                            <label for="name">Nama Lengkap Anda</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><svg
                                        class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 6a6 6 0 10-12 0 6 6 0 0012 0zM9 8a3 3 0 116 0 3 3 0 01-6 0z" />
                                    </svg></span>
                                <input wire:model="name" id="name" type="text" class="form-control"
                                    placeholder="Nama Lengkap Anda" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <!-- Password -->
                        <div class="form-group mb-4">
                            <label for="password">Kata Sandi Anda</label>
                            <div class="input-group">
                                <span class="input-group-text"><svg class="icon icon-xs text-gray-600"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                                <input wire:model="password" type="password" placeholder="Kata Sandi"
                                    class="form-control" id="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <!-- Konfirmasi Password -->
                        <div class="form-group mb-4">
                            <label for="confirm_password">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text"><svg class="icon icon-xs text-gray-600"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                                <input wire:model="password_confirmation" type="password"
                                    placeholder="Konfirmasi Kata Sandi" class="form-control" id="confirm_password"
                                    required>
                            </div>
                        </div>


                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="terms" required>
                            <label class="form-check-label fw-normal mb-0" for="terms">
                                Saya setuju dengan <a href="#">syarat dan ketentuan</a>
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-gray-800">Daftar</button>
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
