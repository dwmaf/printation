<?php
// bagian rayhan
// kasih validasi file yg diupload, trus simpan ke db, nama modelnya Printfile
// namespace App\Livewire;

// use App\Models\Printfile;
// use Livewire\Component;

// class UserUpload extends Component
// {
//     public function render()
//     {
//         return view('livewire.user-upload');
//     }
// }

namespace App\Livewire;

use App\Models\Printfile;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserUpload extends Component
{
    use WithFileUploads;

    public $file;

    public function updatedFile()
    {
        $this->save();
    }

    public function save()
    {
        $this->validate([
            'file' => 'required|max:10240', // 10MB
        ]);

        // simpan nanti ke storage / db
        // $this->file->store('uploads');

        session()->flash('uploaded', true);
        $this->reset('file');
    }

    public function render()
    {
        return view('livewire.user-upload');
    }
}
