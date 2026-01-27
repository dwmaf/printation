<?php
// bagian rayhan
// kasih validasi file yg diupload, trus simpan ke db, nama modelnya Printfile
namespace App\Livewire;

use App\Models\Printfile;
use Livewire\Component;

class UserUpload extends Component
{
    public function render()
    {
        return view('livewire.user-upload');
    }
}
