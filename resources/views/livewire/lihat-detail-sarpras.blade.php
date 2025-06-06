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
                        <label for="frekuensiPenggunaanFasilitas" class="form-label fw-bold">Frekuensi Penggunaan Fasilitas></label>
                        <select id="frekuensiPenggunaanFasilitas" name="frekuensi_penggunaan_fasilitas" class="form-select shadow-sm"
                            {{ $laporan->frekuensi_penggunaan_fasilitas ? 'disabled' : '' }}>
                            <option value="25" {{ $laporan->frekuensi_penggunaan_fasilitas == 25 ? 'selected' : '' }}>">
                                Jarang (≤1x/minggu)
                            </option>
                            <option value="26" {{ $laporan->frekuensi_penggunaan_fasilitas == 26 ? 'selected' : '' }}>
                                Periodik (2-3x/minggu)
                            </option>
                            <option value="27" {{ $laporan->frekuensi_penggunaan_fasilitas == 27 ? 'selected' : '' }}>
                                Rutin (hampir setiap hari)
                        </select>
                    </div>

                    <div class="mb-3">
                        <label id="tingkatKerusakan" name="tingkat_kerusakan" class="form-label fw-bold">Tingkat Kerusakan></label>
                        <select name="tingkat_kerusakan" id="tingkatKerusakan" class="form-select shadow-sm"
                                {{ $laporan->tingkat_kerusakan ? 'disabled' : '' }}>
                            <option value="28" {{ $laporan->tingkat_kerusakan == 28 ? 'selected' : '' }}>
                                Minimal (tidak mengganggu)
                            </option>
                            <option value="29" {{ $laporan->tingkat_kerusakan == 29 ? 'selected' : '' }}>
                                Parsial (mengganggu sebagian)
                            </option>
                            <option value="30" {{ $laporan->tingkat_kerusakan == 30 ? 'selected' : '' }}>
                                Signifikan (menghentikan aktivitas)
                            </option>
                        </select>
                    </div>

                    <div class="mb-3"> 
                        <label id="dampakTerhadapAktivitasAkademik" name="dampak_terhadap_aktivitas_akademik" class="form-label fw-bold">Dampak Terhadap Aktivitas Akademik></label>
                        <select name="dampak_terhadap_aktivitas_akademik" id="dampak_terhadap_aktivitas_akademik" class="form-select shadow-sm"
                                {{ $laporan->dampak_terhadap_aktivitas_akademik ? 'disabled' : '' }}>
                            <option value="31" {{ $laporan->dampak_terhadap_aktivitas_akademik == 31 ? 'selected' : '' }}>
                                Aman
                            </option>
                            <option value="32" {{ $laporan->dampak_terhadap_aktivitas_akademik == 32 ? 'selected' : '' }}>
                                Waspada
                            </option>
                            <option value="33" {{ $laporan->dampak_terhadap_aktivitas_akademik == 33 ? 'selected' : '' }}>
                                Bahaya
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label id="tingkatResikoKeselamatan" name="tingkat_resiko_keselamatan" class="form-label fw-bold">Tingkat Resiko Keselamatan></label>
                        <select name="tingkat_resiko_keselamatan" id="tingat_resiko_keselamatan" class="form-select shadow-sm"
                                {{ $laporan->tingkat_resiko_keselamatan ? 'disabled' : '' }}>
                            <option value="37" {{ $laporan->tingkat_resiko_keselamatan == 37 ? 'selected' : '' }}>
                                Ringan (minor)
                            </option>
                            <option value="38" {{ $laporan->tingkat_resiko_keselamatan == 38 ? 'selected' : '' }}>
                                Sedang (perlu perbaikan)
                            </option>
                            <option value="39" {{ $laporan->tingkat_resiko_keselamatan == 39 ? 'selected' : '' }}>
                                Berat (ganti total)
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="estimasiWaktu" class="form-label fw-bold">Estimasi Waktu</label>
                        <select id="estimasiWaktu" name="sub_kriteria_id" class="form-select shadow-sm"
                                {{ $laporan->sub_kriteria_id ? 'disabled' : '' }}>
                            <option value="34" {{ $laporan->sub_kriteria_id == 34 ? 'selected' : '' }}>
                                Cepat (≤1 hari)
                            </option>
                            <option value="35" {{ $laporan->sub_kriteria_id == 35 ? 'selected' : '' }}>
                                Sedang (2-3 hari)
                            </option>
                            <option value="36" {{ $laporan->sub_kriteria_id == 36 ? 'selected' : '' }}>
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
