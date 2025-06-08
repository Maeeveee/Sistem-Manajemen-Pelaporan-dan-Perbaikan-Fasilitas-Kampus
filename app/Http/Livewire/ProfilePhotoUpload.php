<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfilePhotoUpload extends Component
{
    use WithFileUploads;

    public $profile_photo;
    public $user;
    public $showAlert = false;
    public $alertType = '';
    public $alertMessage = '';

    protected $rules = [
        'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function uploadProfilePhoto()
    {
        $this->validate();

        try {
            // Hapus foto lama jika ada
            $this->deleteOldPhoto();

            // Generate nama file unik
            $filename = 'profile_'.$this->user->id.'_'.time().'.'.$this->profile_photo->extension();
            
            // Simpan file ke storage
            $path = $this->profile_photo->storeAs('profile-photos', $filename, 'public');
            
            // Update database dengan path relatif
            $this->user->update([
                'profile_photo_path' => $path
            ]);

            // Set alert sukses
            $this->showAlert = true;
            $this->alertType = 'success';
            $this->alertMessage = 'Foto profil berhasil diperbarui!';

            // Reset input file
            $this->reset('profile_photo');

            // Emit event untuk refresh
            $this->emit('profileUpdated');

        } catch (\Exception $e) {
            Log::error('Upload error: '.$e->getMessage());
            $this->showAlert = true;
            $this->alertType = 'danger';
            $this->alertMessage = 'Gagal mengunggah foto: '.$e->getMessage();
        }
    }

    protected function deleteOldPhoto()
    {
        if ($this->user->profile_photo_path && Storage::disk('public')->exists($this->user->profile_photo_path)) {
            Storage::disk('public')->delete($this->user->profile_photo_path);
        }
    }

    public function render()
    {
        return view('livewire.profile-photo-upload');
    }
}