<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPhone;

class ManageUserPhones extends Component
{
    public $phone_number;
    public $label;

    protected $rules = [
        'phone_number' => 'required|numeric|digits_between:9,15',
        'label' => 'nullable|string|max:50',
    ];

    public function addPhone()
    {
        $this->validate();

        Auth::user()->secondaryPhones()->create([
            'phone_number' => $this->phone_number,
            'label' => $this->label,
        ]);

        $this->reset(['phone_number', 'label']);
        $this->dispatch('saved'); 
    }

    public function deletePhone($id)
    {
        $phone = Auth::user()->secondaryPhones()->find($id);
        if ($phone) {
            $phone->delete();
        }
    }

    public function render()
    {
        return view('livewire.profile.manage-user-phones', [
            'phones' => Auth::user()->secondaryPhones()->get()
        ]);
    }
}