<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Fare;
use App\Models\Driver;
use App\Models\Bus; 
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class AdminBusSchedule extends Component
{
    use WithPagination;
    
    // State properties for the form
    public $editingId = null;
    public $isEditMode = false;
    
    // Form fields
    #[Rule('required|exists:buses,bus_id')]
    public $bus_id = '';
    
    #[Rule('required|exists:drivers,driver_id')]
    public $driver_id = ''; 
    
    #[Rule('required|exists:fares,fare_id')]
    public $fare_id = '';
    
    #[Rule('required|date_format:H:i')]
    public $departure_time = ''; 
    
    // Static selection: Normal or Air-conditioned
    #[Rule('required|in:Normal,Air-conditioned')]
    public $bus_type = '';
    
    public function resetForm()
    {
        $this->reset(['bus_id', 'driver_id', 'fare_id', 'departure_time', 'bus_type', 'editingId', 'isEditMode']);
    }

    // CREATE and UPDATE handler
    public function saveSchedule()
    {
        $this->validate(); // Uses the #[Rule] attributes
        
        // Data structure for the 'schedules' table
        $data = $this->only(['bus_id', 'driver_id', 'fare_id', 'departure_time', 'bus_type']);

        if ($this->isEditMode) {
            // NOTE: Assuming primary key for schedules is 'schedule_id'
            Schedule::findOrFail($this->editingId)->update($data);
            session()->flash('message', '🔄 Schedule updated successfully.');
        } else {
            Schedule::create($data);
            session()->flash('message', '✅ Schedule created successfully.');
        }

        $this->resetForm();
        $this->dispatch('scheduleUpdated'); 
    }
    
    public function editSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        
        $this->editingId = $schedule->schedule_id; 
        $this->isEditMode = true;
        
        $this->bus_id = $schedule->bus_id;
        $this->driver_id = $schedule->driver_id;
        $this->fare_id = $schedule->fare_id;
        $this->departure_time = $schedule->departure_time;
        $this->bus_type = $schedule->bus_type; 
        
        $this->dispatch('scrollToForm');
    }
    
    // DELETE handler
    public function removeSchedule($id)
    {
        Schedule::destroy($id);
        session()->flash('message', '🗑️ Schedule removed successfully.');
        $this->dispatch('scheduleUpdated'); 
    }

    public function render()
    {
        return view('livewire.admin-bus-schedule', [
            // Ensure relationships are eager loaded (bus, driver, fare)
            'schedules' => Schedule::with(['bus', 'driver', 'fare'])->latest('schedule_id')->paginate(10),
            
            // Data for Dropdowns:
            'buses' => Bus::all(),
            'drivers' => Driver::all(),
            'fares' => Fare::all(),
            'busTypeOptions' => ['Normal', 'Air-conditioned'], 
        ]);
    }
}