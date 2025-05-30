<title>Detail Laporan Kerusakan</title>

<div class="row justify-content-center">
    <div class="col-md-10">
                {{-- Form tampilan readonly --}}
                <h4 class="mb-3 fw-bold text-primary">📝 Informasi Laporan Kerusakan</h4>
                <form>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengisian</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->created_at->format('Y-m-d') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pelapor</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->nama_pelapor ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIM/NIP</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->identifier ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gedung</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->gedung->nama_gedung ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->ruangan->nama_ruangan ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lantai</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->lantai }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->fasilitas->nama_fasilitas ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Kerusakan</label>
                        <textarea class="form-control bg-white" rows="3" readonly>{{ $laporan->deskripsi }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Laporan Mahasiswa</label>
                        <input type="text" class="form-control bg-white" value="{{ ucfirst($laporan->status) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto</label><br>
                        @if($laporan->foto)
                            <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Kerusakan" class="img-thumbnail" style="max-width: 300px;">
                        @else
                            <p class="text-muted">Tidak ada foto</p>
                        @endif
                    </div>
                </form>

                <hr class="my-4">
                <h5 class="mb-3 fw-bold text-success">🛠️ Form Estimasi Penanganan Kerusakan</h5>

                <form method="POST" action="{{ route('laporan.updateLaporan', $laporan->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="estimasiWaktu" class="form-label fw-bold">Estimasi Waktu</label>
                        <select id="estimasiWaktu" name="sub_kriteria_id" class="form-select shadow-sm"
                                {{ $laporan->sub_kriteria_id ? 'disabled' : '' }}>
                            <option value="1" {{ $laporan->sub_kriteria_id == 1 ? 'selected' : '' }}>
                                Cepat (≤1 hari)
                            </option>
                            <option value="2" {{ $laporan->sub_kriteria_id == 2 ? 'selected' : '' }}>
                                Sedang (2-3 hari)
                            </option>
                            <option value="3" {{ $laporan->sub_kriteria_id == 3 ? 'selected' : '' }}>
                                Lama (≥4 hari)
                            </option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4 shadow-sm"
                                {{ $laporan->sub_kriteria_id ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>



    </div>
</div>
