<?php

use App\Http\Livewire\BootstrapTables;
use App\Http\Livewire\Components\Buttons;
use App\Http\Livewire\Components\Forms;
use App\Http\Livewire\Components\Modals;
use App\Http\Livewire\Components\Notifications;
use App\Http\Livewire\Components\Typography;
use App\Http\Livewire\Components\Forms\FormKerusakanFasilitas;
use App\Http\Livewire\ManajemenFasilitas;
use App\Http\Livewire\ManajemenGedung;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\DashboardTeknisi;
use App\Http\Livewire\Err404;
use App\Http\Livewire\Err500;
use App\Http\Livewire\ResetPassword;
use App\Http\Livewire\ForgotPassword;
use App\Http\Livewire\Lock;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\DashboardAdmin;
use App\Http\Livewire\DashboardSarpras;
use App\Http\Livewire\ForgotPasswordExample;
use App\Http\Livewire\Index;
use App\Http\Livewire\LoginExample;
use App\Http\Livewire\ManajemenPengguna;
use App\Http\Livewire\ProfileExample;
use App\Http\Livewire\RegisterExample;
use App\Http\Livewire\Transactions;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ResetPasswordExample;
use App\Http\Livewire\UpgradeToPro;
use App\Http\Livewire\LandingPage;
use App\Http\Livewire\Users;
use App\Models\LaporanKerusakan;
use App\Http\Livewire\LihatDetailAdmin;

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
Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
Route::get('/reset-password/{id}', ResetPassword::class)->name('reset-password')->middleware('signed');

Route::get('/404', Err404::class)->name('404');
Route::get('/500', Err500::class)->name('500');

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/profile-example', ProfileExample::class)->name('profile-example');
    Route::get('/users', ManajemenPengguna::class)->name('users');
    Route::get('/user/create', ManajemenPengguna::class)->name('users.create');
    Route::get('/login-example', LoginExample::class)->name('login-example');
    Route::get('/register-example', RegisterExample::class)->name('register-example');
    Route::get('/forgot-password-example', ForgotPasswordExample::class)->name('forgot-password-example');
    Route::get('/reset-password-example', ResetPasswordExample::class)->name('reset-password-example');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/transactions', Transactions::class)->name('transactions');
    Route::get('/bootstrap-tables', BootstrapTables::class)->name('bootstrap-tables');
    Route::get('/lock', Lock::class)->name('lock');
    Route::get('/buttons', Buttons::class)->name('buttons');
    Route::get('/notifications', Notifications::class)->name('notifications');
    Route::get('/forms', Forms::class)->name('forms');
    Route::get('/modals', Modals::class)->name('modals');
    Route::get('/typography', Typography::class)->name('typography');
});

Route::get('/manajemen-fasilitas', ManajemenFasilitas::class)->name('manajemen.fasilitas');

Route::get('/manajemen-gedung', ManajemenGedung::class)->name('manajemen.gedung');

Route::get('/pelaporan/kerusakan-fasilitas', FormKerusakanFasilitas::class)
    ->middleware(['auth'])
    ->name('kerusakan.fasilitas');

Route::get('/teknisi', DashboardTeknisi::class)->name('dashboard-teknisi');


Route::get('/admin', DashboardAdmin::class)->name('dashboard-admin');
Route::get('/admin/laporan/detail/{id}', LihatDetailAdmin::class)->name('lihat-detail-admin');
Route::put('/laporan/{id}/update-status', [LihatDetailAdmin::class, 'updateStatus'])->name('laporan.updateStatus');


Route::get('/sarpras', DashboardSarpras::class)->name('dashboard-sarpras');
