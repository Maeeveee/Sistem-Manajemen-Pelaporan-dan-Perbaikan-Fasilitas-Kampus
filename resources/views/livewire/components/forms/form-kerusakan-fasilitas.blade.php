@section('breadcrumbs')
    @php
        $breadcrumbs = [
            'Form Laporan Kerusakan' => '',
        ];
    @endphp
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection
<div> <!-- This is the single root element that wraps everything -->
    <div class="py-4">
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Form Laporan Kerusakan Fasilitas</h1>
                <p class="mb-0">Silakan isi form berikut untuk melaporkan kerusakan fasilitas kampus.</p>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form wire:submit.prevent="submit">

                        <!-- Data Pelapor -->
                        <div class="mb-3">
                            <label class="form-label">Nama Pelapor</label>
                            <input type="text" class="form-control bg-light" wire:model="nama_pelapor" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIM/NIP</label>
                            <input type="text" class="form-control bg-light" wire:model="identifier"  value="{{ Auth::user()->identifier }}" readonly>
                        </div>

                        <hr class="my-4">

                        <!-- Lokasi Kerusakan -->
                        <h5 class="mb-3">Lokasi Kerusakan</h5>
                        
                        <!-- Gedung -->
                        <div class="mb-3">
                            <label class="form-label">Gedung <span class="text-danger">*</span></label>
                            <select class="form-select @error('gedung_id') is-invalid @enderror" wire:model="gedung_id" required>
                                <option value="">Pilih Gedung</option>
                                @if(!empty($gedungList))
                                    @foreach ($gedungList as $ged)
                                        <option value="{{ $ged->id }}">{{ $ged->nama_gedung }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('gedung_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Lantai -->
                        <div class="mb-3">
                            <label class="form-label">Lantai <span class="text-danger">*</span></label>
                            <select class="form-select @error('lantai') is-invalid @enderror" wire:model="lantai" required>
                                <option value="">Pilih Lantai</option>
                                @if(!empty($lantaiList))
                                    @foreach ($lantaiList as $lvl)
                                        <option value="{{ $lvl }}">Lantai {{ $lvl }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('lantai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Ruangan -->
                        <div class="mb-3">
                            <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                            <select class="form-select @error('ruangan_id') is-invalid @enderror" wire:model="ruangan_id" required>
                                <option value="">Pilih Ruangan</option>
                                @if(!empty($ruanganList))
                                    @foreach ($ruanganList as $ruangan)
                                        <option value="{{ $ruangan->id }}">
                                            {{ $ruangan->nama_ruangan }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('ruangan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Fasilitas -->
                        <div class="mb-3">
                            <label class="form-label">Fasilitas <span class="text-danger">*</span></label>
                            <select class="form-select @error('fasilitas_id') is-invalid @enderror" wire:model="fasilitas_id" required>
                                <option value="">Pilih Fasilitas</option>
                                @if(!empty($fasilitasList))
                                    @foreach ($fasilitasList as $fasilitas)
                                        <option value="{{ $fasilitas->id }}">
                                            {{ $fasilitas->nama_fasilitas }} ({{ $fasilitas->kode_fasilitas }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('fasilitas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Detail Kerusakan -->
                        <h5 class="mb-3">Detail Kerusakan</h5>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      wire:model="deskripsi" 
                                      rows="4" 
                                      placeholder="Jelaskan kondisi kerusakan secara detail..."
                                      required></textarea>
                            <div class="form-text">Minimal 10 karakter</div>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Upload Foto <span class="text-danger">*</span></label>
                            <input class="form-control @error('foto') is-invalid @enderror" 
                                type="file" 
                                wire:model="foto"
                                accept="image/*"
                                required>
                            <div class="form-text">Format yang diperbolehkan: JPG, PNG. Ukuran maksimal: 2MB.</div>
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            
                            @if ($foto)
                                <div class="mt-2">
                                    <small class="text-success">✓ Foto berhasil dipilih: {{ $foto->getClientOriginalName() }}</small>
                                </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Kirim Laporan
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    Mengirim...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>