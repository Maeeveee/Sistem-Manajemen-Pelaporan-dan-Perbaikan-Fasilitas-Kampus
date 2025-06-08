<div class="position-relative d-inline-block" style="width: 120px;">
    <!-- Foto Profil -->
    <img src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : asset('assets/img/team/default_photo_profile.jpg') }}" 
         class="avatar-xl rounded-circle profile-photo-img" 
         alt="Foto Profil"
         style="width:120px; height:120px; object-fit:cover;">
    
    <!-- Input File -->
    <input wire:model="profile_photo" 
           type="file" 
           id="profile_photo_input"
           class="d-none" 
           accept="image/jpeg,image/png,image/jpg"
           wire:change="uploadProfilePhoto">
    
    <!-- Tombol Upload -->
    <button type="button" 
            class="btn-upload-photo"
            onclick="document.getElementById('profile_photo_input').click()">
        <i class="fas fa-camera"></i>
    </button>
    
    <!-- Loading Indicator -->
    <!-- <div wire:loading wire:target="profile_photo" class="upload-loading">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Mengupload...
    </div> -->
</div>

<!-- Notifikasi -->
@if($showAlert)
    <div class="alert alert-{{ $alertType }} mt-2 animate__animated animate__fadeIn">
        {{ $alertMessage }}
    </div>
@endif

<style>
.btn-upload-photo {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    background: #161d27;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-upload-photo:hover {
    background: #161d27;
    transform: scale(1.1);
}

.upload-loading {
    position: absolute;
    bottom: -30px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 12px;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
</style>

<script>
document.addEventListener('livewire:load', function() {
    // Preview gambar sebelum upload
    document.getElementById('profile_photo_input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.querySelector('.profile-photo-img').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>