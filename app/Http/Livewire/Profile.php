<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Profile extends Component
{
    use WithFileUploads;

    public User $user;
    public $profile_photo;
    public $cover_photo;
    public $showSavedAlert = false;
    public $showUploadAlert = false;
    public $showDemoNotification = false;

    public function rules()
    {
        return [
            'profile_photo' => 'image|max:1024', // 1MB Max untuk foto profil
            'cover_photo' => 'image|max:2048', // 2MB Max untuk foto sampul
        ];
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->showUploadAlert = false; // Inisialisasi variabel
    }

    public function save()
    {
        if (env('IS_DEMO')) {
            $this->showDemoNotification = true;
            return;
        }

        try {
            $this->user->save();
            $this->showSavedAlert = true;
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data pengguna: ' . $e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Gagal menyimpan data. Silakan coba lagi.'
            ]);
        }
    }

    public function uploadProfilePhoto()
    {
        if (env('IS_DEMO')) {
            $this->showDemoNotification = true;
            return;
        }

        $this->validate(['profile_photo' => 'image|max:1024']);

        try {
            $path = $this->profile_photo->store('profile-photos', 'public');
            $fullPath = Storage::url($path);

            $this->user->update([
                'profile_photo_path' => $fullPath
            ]);

            $this->showUploadAlert = true;

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Foto profil berhasil diunggah!'
            ]);

            // Reset alert setelah 3 detik menggunakan JavaScript
            $this->dispatchBrowserEvent('reset-alert', ['timeout' => 3000]);
        } catch (\Exception $e) {
            Log::error('Gagal mengunggah foto profil: ' . $e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Gagal mengunggah foto profil. Silakan coba lagi.'
            ]);
        }
    }

    public function uploadCoverPhoto()
    {
        if (env('IS_DEMO')) {
            $this->showDemoNotification = true;
            return;
        }

        $this->validate(['cover_photo' => 'image|max:2048']);

        try {
            $path = $this->cover_photo->store('cover-photos', 'public');
            $fullPath = Storage::url($path);

            $this->user->update([
                'cover_photo_path' => $fullPath
            ]);

            $this->showUploadAlert = true;

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Foto sampul berhasil diunggah!'
            ]);

            // Reset alert setelah 3 detik menggunakan JavaScript
            $this->dispatchBrowserEvent('reset-alert', ['timeout' => 3000]);
        } catch (\Exception $e) {
            Log::error('Gagal mengunggah foto sampul: ' . $e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Gagal mengunggah foto sampul. Silakan coba lagi.'
            ]);
        }
    }

    public function resetAlert()
    {
        $this->showUploadAlert = false;
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
?>