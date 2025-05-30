<title>Detail Laporan Kerusakan</title>

<div class="row justify-content-center">
    <div class="col-md-10">
                {{-- Form tampilan readonly --}}
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
                        <label class="form-label">Tingkat Kerusakan</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->tingkatKerusakan->nama_subkriteria ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dampak Terhadap Aktivitas Akademik</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->dampakAkademik->nama_subkriteria ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tingkat Risiko Keselamatan</label>
                        <input type="text" class="form-control bg-white" value="{{ $laporan->resikoKeselamatan->nama_subkriteria ?? '-' }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Kerusakan</label>
                        <textarea class="form-control bg-white" rows="3" readonly>{{ $laporan->deskripsi }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control bg-white" value="{{ ucfirst($laporan->status ?? 'pending') }}" readonly>
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

                <hr>
                     <form method="POST" action="{{ route('laporan.updateStatus', $laporan->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Ubah Status</label>
                            <select name="status_admin" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="verifikasi" {{ $laporan->status_admin === 'verifikasi' ? 'selected' : '' }}>verifikasi</option>
                                <option value="reject" {{ $laporan->status_admin === 'reject' ? 'selected' : '' }}>Reject</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Komentar</label>
                            <textarea name="komentar_admin" class="form-control" rows="3" placeholder="Berikan alasan/verifikasi...">{{ old('komentar_admin', $laporan->komentar_admin) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success text-white">Simpan Perubahan</button>
                    </form>
    </div>
</div>
