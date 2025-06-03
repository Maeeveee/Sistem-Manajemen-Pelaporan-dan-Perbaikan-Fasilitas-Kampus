<?php

use App\Http\Controllers\PerhitunganKriteria as ControllersPerhitunganKriteria;
use App\Http\Livewire\Components\Forms\FormKerusakanFasilitas;
use App\Http\Livewire\ManajemenFasilitas;
use App\Http\Livewire\ManajemenGedung;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\DashboardTeknisi;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\DashboardAdmin;
use App\Http\Livewire\DashboardSarpras;
use App\Http\Livewire\ManajemenKriteriaFasilitas;
use App\Http\Livewire\ManajemenPengguna;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\LandingPage;
use App\Http\Livewire\Users;
use App\Models\LaporanKerusakan;
use App\Http\Livewire\LihatDetailAdmin;
use App\Http\Livewire\HistoryLaporan;
use App\Http\Livewire\DetailHistoryLaporan;
use App\Http\Livewire\LihatDetailSarpras;
use App\Http\Livewire\PerhitunganSpk;
use App\Http\Livewire\PerhitunganKriteria;
use App\Http\Livewire\ManajemenSubkriteria;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::get('/', LandingPage::class)->name('landing-page');
Route::get('/register', Register::class)->name('register');
Route::get('/login', Login::class)->name('login');


Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/users', ManajemenPengguna::class)->name('users');
    Route::get('/user/create', ManajemenPengguna::class)->name('users.create');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

Route::get('/manajemen-fasilitas', ManajemenFasilitas::class)->name('manajemen.fasilitas');

Route::get('/manajemen-gedung', ManajemenGedung::class)->name('manajemen.gedung');

Route::get('/manajemen-kriteria-fasilitas', ManajemenKriteriaFasilitas::class)->name('manajemen.kriteria.fasilitas');

Route::get('/manajemen-subkriteria', ManajemenSubkriteria::class)->name('manajemen.subkriteria');

Route::get('/pelaporan/kerusakan-fasilitas', FormKerusakanFasilitas::class)
    ->middleware(['auth'])
    ->name('kerusakan.fasilitas');
    
Route::get('/history-laporan', HistoryLaporan::class)->name('history.laporan');
Route::get('/detail-history-laporan/{id}', DetailHistoryLaporan::class)->name('detail.history.laporan');

Route::get('/teknisi', DashboardTeknisi::class)->name('dashboard-teknisi');


Route::get('/admin', DashboardAdmin::class)->name('dashboard-admin');
Route::get('/admin/laporan/detail/{id}', LihatDetailAdmin::class)->name('lihat-detail-admin');
Route::put('/laporan/{id}/update-status', [LihatDetailAdmin::class, 'updateStatus'])->name('laporan.updateStatus');


Route::get('/sarpras', DashboardSarpras::class)->name('dashboard-sarpras');
Route::get('/sarpras/laporan/detail/{id}', LihatDetailSarpras::class)->name('input_laporan-sarpras');
Route::put('/laporan/{id}/update-laporan', [LihatDetailSarpras::class, 'updateEstimasi'])->name('laporan.updateLaporan');

Route::get('/sarpras-spk', PerhitunganSpk::class)->name('perhitungan-spk');

Route::get('/perhitungan-spk', PerhitunganSpk::class)->name('perhitungan-spk');

Route::get('/perhitungan-kriteria', PerhitunganKriteria::class)->name('perhitungan-kriteria');