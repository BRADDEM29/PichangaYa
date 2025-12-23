<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Livewire\StrikeWarning.php
namespace App\Livewire; // 🟢 CORRECCIÓN CRÍTICA: Quitamos "Http"

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StrikeWarning extends Component
{
    // En Livewire 3, las propiedades públicas se pasan solas a la vista.
    public $showOverlay = false;

    public function mount()
    {
        $this->checkStrikes();
    }

    public function checkStrikes()
    {
        // 1. Validar usuario y rol
        if (Auth::check() && Auth::user()->role === 'user') {
            
            // 2. Obtener strikes (con seguridad)
            $strikes = Auth::user()->consecutive_cancellations ?? 0;

            // 3. Si tiene 3 (o más, por seguridad), activamos
            $this->showOverlay = ($strikes >= 3);
            
        } else {
            $this->showOverlay = false;
        }
    }

    public function render()
    {
        // En Livewire 3 no hace falta pasar el array, pero lo dejamos por seguridad.
        return view('livewire.strike-warning');
    }
}