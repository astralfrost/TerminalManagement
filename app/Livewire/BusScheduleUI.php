<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BusSchedule;
use App\Models\Route;
use Livewire\Attributes\On; 

class BusScheduleUi extends Component
{
    public $search = '';
    public $routeFilter = '';
    public $dayFilter = '';

    // Listen for the update event from the Admin component
    #[On('scheduleUpdated')]
    public function refreshSchedule() 
    {
        // Livewire re-renders the component on public properties change or method call
    }

    public function render()
    {
        $schedules = BusSchedule::query()
            ->with(['route', 'busType'])
            ->when($this->search, function ($query) {
                // Search by bus number or route name
                $query->where('bus_number', 'like', "%{$this->search}%")
                      ->orWhereHas('route', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->routeFilter, fn($query) => $query->where('route_id', $this->routeFilter))
            ->when($this->dayFilter, fn($query) => $query->where('day', $this->dayFilter))
            ->orderBy('departure_time')
            ->get();
            
        return view('livewire.bus-schedule-ui', [
            'schedules' => $schedules,
            'routes' => Route::all(),
        ]);
    }
}