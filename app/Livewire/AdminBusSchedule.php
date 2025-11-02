<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BusSchedule;
use App\Models\Route;
use App\Models\BusType;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class AdminBusSchedule extends Component
{
    use WithPagination;
    
    // State properties for the form
    public $editingId = null;
    public $isEditMode = false;
    
    #[Rule('required|string|max:10')]
    public $bus_number = '';

    #[Rule('required|exists:routes,id')]
    public $route_id = ''; 
    
    #[Rule('required|date_format:H:i')]
    public $departure_time = ''; 
    
    #[Rule('required|exists:bus_types,id')]
    public $bus_type_id = ''; 
    
    #[Rule('required|date')]
    public $day = ''; 

    // Lookup data
    public $routes;
    public $busTypes;
    
    public function mount()
    {
        $this->routes = Route::all();
        $this->busTypes = BusType::all();
    }
    
    public function resetForm()
    {
        $this->reset(['bus_number', 'route_id', 'departure_time', 'bus_type_id', 'day', 'editingId', 'isEditMode']);
    }

    // CREATE and UPDATE handler
    public function saveSchedule()
    {
        // Custom validation for unique bus_number, adjusting for update mode
        $uniqueRule = 'required|string|max:10|unique:bus_schedules,bus_number';
        if ($this->isEditMode) {
            $uniqueRule .= ',' . $this->editingId;
        }

        $this->validate([
            'bus_number' => $uniqueRule,
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'bus_type_id' => 'required|exists:bus_types,id',
            'day' => 'required|date',
        ]);
        
        $data = $this->only(['bus_number', 'route_id', 'departure_time', 'bus_type_id', 'day']);

        if ($this->isEditMode) {
            BusSchedule::findOrFail($this->editingId)->update($data);
            session()->flash('message', '🔄 Schedule updated successfully.');
        } else {
            BusSchedule::create($data);
            session()->flash('message', '✅ Schedule created successfully.');
        }

        $this->resetForm();
        // Global event to notify the public UI to refresh
        $this->dispatch('scheduleUpdated'); 
    }
    
    public function editSchedule($id)
    {
        $schedule = BusSchedule::findOrFail($id);
        
        $this->editingId = $schedule->id;
        $this->isEditMode = true;
        
        $this->bus_number = $schedule->bus_number;
        $this->route_id = $schedule->route_id;
        $this->departure_time = $schedule->departure_time;
        $this->bus_type_id = $schedule->bus_type_id;
        $this->day = $schedule->day;
        
        // Dispatch event to scroll to the top form
        $this->dispatch('scrollToForm');
    }
    
    // DELETE handler
    public function removeSchedule($id)
    {
        BusSchedule::destroy($id);
        session()->flash('message', '🗑️ Schedule removed successfully.');
        $this->dispatch('scheduleUpdated'); 
    }

    public function render()
    {
        return view('livewire.admin-bus-schedule', [
            'schedules' => BusSchedule::with(['route', 'busType'])->latest()->paginate(10),
        ]);
    }
}