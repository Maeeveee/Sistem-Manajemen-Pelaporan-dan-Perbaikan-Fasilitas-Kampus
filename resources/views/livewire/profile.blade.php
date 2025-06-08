@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Kelola Pengguna' => '',
            'Profile' => route('profile'),
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

<title>Dashboard - Profile</title>
<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4"></div>
    <div class="card card-body border-0 shadow mb-4">
        <div class="row">
            <!-- Di bagian Profile Section -->
                <div class="col-12 col-xl-4">
                    <div class="text-center p-0">
                        <div class="pb-5">
                            <div class="position-relative d-inline-block profile-photo-wrapper" style="width: 120px;">
                                @livewire('profile-photo-upload', key('profile-photo-upload-'.$user->id))
                            </div>  
                            <h4 class="h3 mt-3">{{ $user->name ?? 'Nama Pengguna' }}</h4>
                        </div>
                    </div>
                </div>
            <!-- General Information Section -->
            <div class="col-12 col-xl-8">
                @if($showSavedAlert)
                <div class="alert alert-success" role="alert">
                    Tersimpan!
                </div>
                @endif
                @if($showUploadAlert)
                <div class="alert alert-success" role="alert">
                    Foto berhasil diunggah!
                </div>
                @endif
                @error('profile_photo')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
                @enderror
                @if($showDemoNotification)
                <div class="alert alert-info mt-2" role="alert">
                    Anda tidak dapat melakukan itu di versi demo.
                </div>
                @endif
                <h2 class="h5 mb-4">Informasi Umum</h2>
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="name">Nama Lengkap</label>
                                <input wire:model="user.name" class="form-control bg-white" id="name" type="text"
                                    placeholder="Masukkan nama" value="{{ $user->name ?? 'Nama Pengguna' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="identifier">Nomor Induk</label>
                                <input wire:model="user.identifier" class="form-control bg-white" id="identifier" type="text"
                                    placeholder="{{ $user->identifier ?? 'Nomor Induk' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="role">Role</label>
                                <input wire:model="user.role.name" class="form-control bg-white" id="role" type="text"
                                    placeholder="{{ $user->role->name ?? 'Belum diatur' }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-gray-800 mt-2 animate-up-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
.profile-photo-btn-camera {
    cursor: pointer;
    transition: background 0.2s;
    z-index: 2;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    padding: 0 !important;
}
.profile-photo-btn-camera i {
    margin: 0;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.profile-photo-btn-camera:hover {
    background: #0d6efd;
}
</style>

@section('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('fotoProfilDiperbarui', () => {
                // Tampilkan pesan sukses
                const alert = document.createElement('div');
                alert.className = 'alert alert-success';
                alert.textContent = 'Foto profil berhasil diperbarui!';
                document.querySelector('.col-12.col-xl-8').prepend(alert);
                
                // Sembunyikan pesan setelah 3 detik
                setTimeout(() => {
                    alert.remove();
                }, 3000);
                
                // Refresh halaman setelah 1.5 detik
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            });
        });

        // Preview gambar sebelum upload
        document.addEventListener('livewire:init', () => {
            Livewire.on('uploadProgress', (event) => {
                // Tampilkan progress bar jika diperlukan
                console.log('Progress upload: ', event.progress);
            });
        });
    </script>
@endsection