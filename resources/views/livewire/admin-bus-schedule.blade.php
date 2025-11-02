<div class="max-w-5xl mx-auto p-6 rounded-lg shadow-2xl text-white" style="background-color: rgba(40, 44, 63, 0.9);">
    <h2 class="text-3xl font-light mb-6 border-b pb-2 border-gray-600">Admin: Schedule Management</h2>
    
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm rounded-lg {{ str_contains(session('message'), '✅') ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100' }}">
            {{ session('message') }}
        </div>
    @endif

    <form id="schedule-form" wire:submit.prevent="saveSchedule" class="p-6 mb-8 rounded-lg" style="background-color: #383c4f;">
        <h3 class="text-xl font-semibold mb-4">
            {{ $isEditMode ? 'Edit Existing Schedule (ID: ' . $editingId . ')' : 'Add New Schedule' }}
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium">Bus Number</label>
                <input type="text" wire:model.blur="bus_number" class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white">
                @error('bus_number') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Departure Time</label>
                <input type="time" wire:model.blur="departure_time" class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white">
                @error('departure_time') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Route</label>
                <select wire:model.blur="route_id" class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white">
                    <option value="">Select Route</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                    @endforeach
                </select>
                @error('route_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Bus Type</label>
                <select wire:model.blur="bus_type_id" class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white">
                    <option value="">Select Bus Type</option>
                    @foreach($busTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('bus_type_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="col-span-2" wire:ignore x-data x-init="
                flatpickr($refs.dayInput, { 
                    dateFormat: 'Y-m-d',
                    defaultDate: @js($day),
                    // Force Livewire to see the change
                    onChange: (selectedDates, dateStr) => {$wire.set('day', dateStr)},
                });
                // When component updates with new edit data, update the flatpickr instance
                $wire.on('dayUpdated', (value) => { $refs.dayInput._flatpickr.setDate(value); });
            " x-on:schedule-updated.window="if ($wire.get('isEditMode') === false) $refs.dayInput._flatpickr.clear();" >
                <label class="block mb-2 text-sm font-medium">Date/Day of Travel</label>
                <input x-ref="dayInput" type="text" wire:model="day" placeholder="Click to select date" 
                       class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white cursor-pointer">
                @error('day') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex space-x-4 mt-6">
            <button type="submit" class="flex-1 py-3 font-bold rounded-lg transition duration-200 
                {{ $isEditMode ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                {{ $isEditMode ? '💾 Save Changes' : '✅ Add Schedule' }}
            </button>
            @if ($isEditMode)
                <button type="button" wire:click="resetForm" class="w-32 py-3 bg-gray-600 hover:bg-gray-700 font-bold rounded-lg transition duration-200">
                    Cancel
                </button>
            @endif
        </div>
    </form>

    <hr class="border-gray-600 my-8">

    <h3 class="text-xl font-semibold mb-4">Existing Schedules</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-600 rounded-lg overflow-hidden">
            <thead class="bg-gray-700">
                <tr class="text-left text-sm font-semibold uppercase tracking-wider">
                    <th class="px-6 py-3">Bus No.</th>
                    <th class="px-6 py-3">Route</th>
                    <th class="px-6 py-3">Time</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Day</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse ($schedules as $schedule)
                    <tr class="hover:bg-gray-700 transition duration-150 ease-in-out">
                        <td class="px-6 py-4">{{ $schedule->bus_number }}</td>
                        <td class="px-6 py-4">{{ $schedule->route->name }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</td>
                        <td class="px-6 py-4">{{ $schedule->busType->name }}</td>
                        <td class="px-6 py-4">{{ $schedule->day }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <button wire:click="editSchedule({{ $schedule->id }})" class="text-yellow-400 hover:text-yellow-600 font-bold">
                                Edit
                            </button>
                            <button 
                                wire:click="removeSchedule({{ $schedule->id }})" 
                                wire:confirm="Are you sure you want to delete this schedule?"
                                class="text-red-400 hover:text-red-600 font-bold"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-400">No schedules found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $schedules->links() }}
    </div>

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('scrollToForm', () => {
            document.getElementById('schedule-form').scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>