<?php

namespace App\Livewire\Client;

use Livewire\Component;
class ConfirmCancellation extends Component
{
    public function cancel()
    {
        $this->dispatch('cancelSubscription')->to('home-client');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.client.confirm-cancellation');
    }
}
