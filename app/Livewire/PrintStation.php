<?php
// bagian dawam
//bagian yg munculin tampilan laptop
namespace App\Livewire;

use App\Models\Printfile;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Livewire\Attributes\On;

class PrintStation extends Component
{
    #[On('echo:printing-channel,FileUploaded')]
    public function handleFileUploaded()
    {
        
    }
    public function render()
    {
        return view('livewire.print-station', [
            'files' => Printfile::latest()->get(),
            'qrCode' => QrCode::size(250)->generate(url('/upload'))
         ]
        );
    }
}
