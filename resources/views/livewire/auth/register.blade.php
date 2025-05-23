<main>
    <title>Sistem Manajemen Pelaporan dan Perbaikan Fasilitas Kampus - Daftar Akun</title>
    <!-- Section -->
    <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
        <div class="container">
            <div wire:ignore.self class="row justify-content-center form-bg-image"
                data-background-lg="/assets/img/illustrations/signin.svg">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <h1 class="mb-0 h3">Buat Akun</h1>
                        </div>

                        <!-- Notifikasi pesan flash -->
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="register" method="POST">
                            <!-- Identifier -->
                            <div class="form-group mt-4 mb-4">
                                <label for="identifier">NIM/NIP/NIDN</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon-id"><svg
                                            class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z" />
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

                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <span class="fw-normal">
                                Sudah memiliki akun?
                                <a href="{{ route('login') }}" class="fw-bold">Masuk di sini</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
