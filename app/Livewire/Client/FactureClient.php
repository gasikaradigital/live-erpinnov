<?php

namespace App\Livewire\Client;

use Livewire\Component;

class FactureClient extends Component
{
    public function render()
    {
        return view('livewire.client.facture-client')->layout('layouts.main');
    }
}
