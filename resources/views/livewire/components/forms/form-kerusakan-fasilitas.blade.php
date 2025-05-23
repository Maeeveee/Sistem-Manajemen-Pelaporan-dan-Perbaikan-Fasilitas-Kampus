<title>Dashboard - Laporan Kerusakan Fasilitas</title>
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="/">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="#">Pelaporan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Form Laporan Kerusakan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Form Laporan Kerusakan Fasilitas</h1>
            <p class="mb-0">Silakan isi form berikut untuk melaporkan kerusakan fasilitas kampus.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
            <div class="card-body">
                    <div class="mb-3">
                        <label for="nama_pelapor">Nama Pelapor</label>
                        <input type="text" class="form-control bg-white" id="nama_pelapor" name="nama_pelapor" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="identifier">NIM/NIP</label>
                        <input type="text" class="form-control bg-white" id="identifier" name="identifier" value="{{ Auth::user()->identifier }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="ruang">Gedung</label>
                        <select id="gedungSelect" class="form-select">
                        <option value="">-- Pilih Gedung --</option>
                            @foreach ($gedungList as $g)
                                <option value="{{ $g->id }}">{{ $g->nama_gedung }}</option>
                            @endforeach
                    </select>
                        @error('ruang') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ruang">Ruang</label>
                        <select wire:model="ruang" class="form-select" id="ruang">
                            <option value="">Pilih Ruang</option>
                            @foreach($ruangList as $item)
                                <option value="{{ $item->id }}">{{ $item->ruang }}</option>
                            @endforeach
                        </select>
                        @error('ruang') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fasilitas">Fasilitas</label>
                        <select wire:model="fasilitas" class="form-select" id="fasilitas">
                            <option value="">Pilih fasilitas</option>
                            @foreach($fasilitasList as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_fasilitas }}</option>
                            @endforeach
                        </select>
                        @error('fasilitas') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi">Deskripsi Kerusakan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="foto">Upload Foto (Opsional)</label>
                        <input class="form-control" type="file" id="foto" name="foto">
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                    </div>
            </div>
        </div>
    </div>
</div>
