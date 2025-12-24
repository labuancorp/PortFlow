<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;

class ViewBooking extends Component
{
    public PortCall $booking;
    public $isOpen = true;

    public function mount(PortCall $booking)
    {
        $this->booking = $booking;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->dispatch('close-booking-modal');
    }

    public function render()
    {
        return view('livewire.view-booking');
    }
}
