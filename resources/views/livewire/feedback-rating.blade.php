<div>
    @section('breadcrumbs')
        @php
            $breadcrumbs = [
                'Feedback & Rating' => route('dashboard-admin'),
            ];
        @endphp
        @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
    @endsection
    <title>Feedback & Rating</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Feedback & Rating Laporan</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Rating Diberikan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRating }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Rata-rata Rating
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($rataRataRating, 1) }}/5
                                <div class="star-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $rataRataRating >= $i ? 'text-warning' : 'text-muted' }}">&#9733;</span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tingkat Kepuasan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $persentaseKepuasan }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Belum Rating
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $laporanTanpaRating }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Rating per Bintang</h6>
                </div>
                <div class="card-body">
                    @foreach($ratingPerBintang as $bintang => $jumlah)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="font-weight-bold">{{ $bintang }} Bintang</span>
                                <span>{{ $jumlah }} laporan</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                @php
                                    $percentage = $totalRating > 0 ? ($jumlah / $totalRating) * 100 : 0;
                                    $progressColor = match($bintang) {
                                        5 => 'bg-success',
                                        4 => 'bg-info', 
                                        3 => 'bg-warning',
                                        2 => 'bg-danger',
                                        1 => 'bg-dark',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <div class="progress-bar {{ $progressColor }}" 
                                     style="width: {{ $percentage }}%"
                                     role="progressbar">
                                    @if($percentage > 0)
                                        {{ number_format($percentage, 1) }}%
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rata-rata Rating per Bulan</h6>
                </div>
                <div class="card-body">
                    @php
                        $bulanNama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 
                                     'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    @endphp
                    @if(empty($ratingPerBulan))
                        <p class="text-muted">Belum ada data rating per bulan.</p>
                    @else
                        @foreach($ratingPerBulan as $bulan => $data)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold">{{ $bulanNama[$bulan] }}</span>
                                    <span class="text-muted">({{ $data['total'] }} rating)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="star-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $data['rata_rating'] >= $i ? 'text-warning' : 'text-muted' }}">&#9733;</span>
                                        @endfor
                                    </div>
                                    <span class="font-weight-bold">{{ $data['rata_rating'] }}/5</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Feedback Terbaru</h6>
        </div>
        <div class="card-body">
            @if($feedbackTerbaru->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada feedback yang diberikan.</p>
                </div>
            @else
                @foreach($feedbackTerbaru as $feedback)
                    <div class="media mb-3 pb-3 border-bottom">
                        <div class="media-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 font-weight-bold">{{ $feedback['nama_pelapor'] }}</h6>
                                <small class="text-muted">{{ $feedback['tanggal'] }}</small>
                            </div>
                            <div class="mb-2">
                                <span class="badge badge-info">{{ $feedback['gedung'] }} - {{ $feedback['ruangan'] }}</span>
                                <span class="badge badge-secondary">{{ $feedback['fasilitas'] }}</span>
                            </div>
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $feedback['rating'] >= $i ? 'text-warning' : 'text-muted' }}">&#9733;</span>
                                @endfor
                                <span class="ml-2 font-weight-bold">{{ $feedback['rating'] }}/5</span>
                            </div>
                            <p class="mb-0 text-muted font-italic">"{{ Str::limit($feedback['feedback'], 150) }}"</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <style>
    .star {
        font-size: 1rem;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    </style>
</div>