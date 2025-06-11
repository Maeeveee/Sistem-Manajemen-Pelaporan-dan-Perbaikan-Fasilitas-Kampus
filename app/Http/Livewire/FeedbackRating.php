<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use Carbon\Carbon;

class FeedbackRating extends Component
{
    public $totalRating = 0;
    public $rataRataRating = 0;
    public $ratingPerBintang = [];
    public $feedbackTerbaru = [];
    public $laporanDenganRating = 0;
    public $laporanTanpaRating = 0;
    public $ratingPerBulan = [];
    public $persentaseKepuasan = 0;

    public function mount()
    {
        $this->loadStatistikRating();
        $this->loadRatingPerBintang();
        $this->loadFeedbackTerbaru();
        $this->loadRatingPerBulan();
        $this->loadPersentaseKepuasan();
    }

    public function loadStatistikRating()
    {
        // Total laporan yang sudah diberi rating
        $this->laporanDenganRating = LaporanKerusakan::whereNotNull('rating')->count();
        
        // Total laporan selesai tapi belum diberi rating
        $this->laporanTanpaRating = LaporanKerusakan::where('status', 'selesai')
            ->whereNull('rating')
            ->count();

        // Total semua rating
        $this->totalRating = $this->laporanDenganRating;

        // Rata-rata rating
        $this->rataRataRating = LaporanKerusakan::whereNotNull('rating')
            ->avg('rating') ?? 0;
    }

    public function loadRatingPerBintang()
    {
        // Statistik rating per bintang (1-5)
        for ($i = 1; $i <= 5; $i++) {
            $this->ratingPerBintang[$i] = LaporanKerusakan::where('rating', $i)->count();
        }
    }

    public function loadFeedbackTerbaru()
    {
        // 5 feedback terbaru
        $this->feedbackTerbaru = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])
            ->whereNotNull('feedback')
            ->whereNotNull('rating')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($laporan) {
                return [
                    'id' => $laporan->id,
                    'nama_pelapor' => $laporan->nama_pelapor,
                    'fasilitas' => $laporan->fasilitas->nama_fasilitas ?? '-',
                    'ruangan' => $laporan->ruangan->nama_ruangan ?? '-',
                    'gedung' => $laporan->gedung->nama_gedung ?? '-',
                    'rating' => $laporan->rating,
                    'feedback' => $laporan->feedback,
                    'tanggal' => $laporan->updated_at->format('d M Y'),
                ];
            });
    }

    public function loadRatingPerBulan()
    {
        // Rating per bulan dalam tahun ini
        $this->ratingPerBulan = LaporanKerusakan::selectRaw('MONTH(updated_at) as bulan, AVG(rating) as rata_rating, COUNT(*) as total')
            ->whereNotNull('rating')
            ->whereYear('updated_at', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->bulan => [
                        'rata_rating' => round($item->rata_rating, 1),
                        'total' => $item->total
                    ]
                ];
            })
            ->toArray();
    }

    public function loadPersentaseKepuasan()
    {
        // Hitung persentase kepuasan (rating >= 4 dianggap puas)
        $totalRating = LaporanKerusakan::whereNotNull('rating')->count();
        $ratingPuas = LaporanKerusakan::where('rating', '>=', 4)->count();
        
        $this->persentaseKepuasan = $totalRating > 0 ? round(($ratingPuas / $totalRating) * 100, 1) : 0;
    }

    public function render()
    {
        return view('livewire.feedback-rating');
    }
}