<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Fare;
use App\Models\Schedule; // CHANGED: Using the Schedule model

class BusScheduleUI extends Component
{
    use WithPagination;

    // Search filters
    public $selectedFareId = '';
    public $selectedBusType = '';
    public $searchTerm = ''; 

    // Data sources
    public $fares;
    public $busTypeOptions = ['Normal', 'Air-conditioned'];

    /**
     * Load necessary data when the component is initialized.
     */
    public function mount()
    {
        // Fetch all available fares (routes) for the dropdown filter
        $this->fares = Fare::all();
    }

    /**
     * Listener to reset pagination when a filter changes.
     */
    public function updated($property)
    {
        // Now resets pagination when the text search term changes
        if (in_array($property, ['selectedFareId', 'selectedBusType', 'searchTerm'])) {
            $this->resetPage();
        }
    }

    /**
     * Render the component view and calculate the schedules based on filters.
     */
    public function render()
    {
        // Prepare search term for LIKE query
        $searchTerm = '%' . $this->searchTerm . '%'; 

        // CHANGED: Using \App\Models\Schedule::query()
        $schedules = \App\Models\Schedule::query()
            // Eager load relationships to avoid N+1 issues in the view
            ->with(['bus.route', 'bus.busType', 'driver'])

            
            // Filter by Search Term (Bus Number OR Driver Name)
            ->when($this->searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    // Search by Bus Number (via relationship)
                    $q->whereHas('bus', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('bus_number', 'like', $searchTerm);
                    })
                    // OR Search by Driver Name (via relationship)
                    ->orWhereHas('driver', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('driver_name', 'like', $searchTerm);
                    });
                });
            })

            // Filter by Fare/Route
            ->when($this->selectedFareId, function ($query) {
                $query->where('fare_id', $this->selectedFareId);
            })
            
            // Filter by Bus Type
            ->when($this->selectedBusType, function ($query) {
                $query->where('bus_type', $this->selectedBusType);
            })
            
            // Default ordering by departure time for a sensible schedule display
            ->orderBy('departure_time', 'asc')
            ->paginate(10); // Paginate the results

        return view('livewire.bus-schedule-ui', [
            'schedules' => $schedules,
        ]);
    }
}