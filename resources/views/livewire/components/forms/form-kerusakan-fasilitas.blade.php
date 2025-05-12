<title>Volt Laravel Dashboard - Laporan Kerusakan Fasilitas</title>
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
                        <input type="text" class="form-control" id="nama_pelapor" name="nama_pelapor" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategori">Kategori Fasilitas</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option selected disabled>Pilih Kategori</option>
                            <option value="kelas">Ruang Kelas</option>
                            <option value="lab">Laboratorium</option>
                            <option value="toilet">Toilet</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="lokasi">Lokasi Fasilitas</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" required>
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
