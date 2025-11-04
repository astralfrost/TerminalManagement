<div class="p-6 bg-gray-900 min-h-screen text-white">
    <!-- Page Header -->
    <h1 class="text-3xl font-bold mb-8 text-yellow-500">Schedule Management</h1>

    <!-- Session Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="p-4 mb-6 text-sm text-green-200 bg-green-800 rounded-lg shadow-lg" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form Card for Add/Edit Schedule -->
    <div id="schedule-form"
         class="bg-gray-800 p-6 md:p-8 rounded-xl shadow-2xl border border-gray-700 mb-10"
         wire:ignore.self
         x-init="$wire.on('scrollToForm', () => { window.scrollTo({ top: 0, behavior: 'smooth' }) })">
        
        <h2 class="text-xl font-semibold mb-6 text-gray-200">
            {{ $isEditMode ? 'Edit Existing Schedule' : 'Add New Schedule' }}
        </h2>

        {{-- Note: We use wire:model.blur on select and input fields for instant validation after focusing out. --}}
        <form wire:submit.prevent="saveSchedule" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Bus Dropdown (Standard Select) --}}
            <div>
                <label for="bus_id" class="block mb-2 text-sm font-medium text-gray-400">Bus</label>
                <select wire:model.blur="bus_id" id="bus_id" class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white focus:ring-yellow-500 focus:border-yellow-500 transition duration-150">
                    <option value="">Select Bus (Number)</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->bus_id }}">{{ $bus->bus_number }}</option> 
                    @endforeach
                </select>
                @error('bus_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Driver Dropdown (Searchable using Alpine) --}}
            {{-- This component pattern can be applied to Bus and Fare for searchable functionality --}}
            <div x-data="{ 
                open: false, 
                search: '',
                {{-- Convert the PHP Collection to a structured JS array for Alpine --}}
                driversList: @js($drivers->map(fn($d) => ['id' => $d->driver_id, 'name' => $d->name])), 
                
                selectDriver(id, name) {
                    $wire.set('driver_id', id);
                    this.search = name;
                    this.open = false;
                },
                get driverName() {
                    const selected = this.driversList.find(d => d.id == $wire.get('driver_id'));
                    return selected ? selected.name : '';
                }
            }" x-init="$watch('$wire.driver_id', () => { search = driverName; })">
                <label for="driver_search" class="block mb-2 text-sm font-medium text-gray-400">Driver (Searchable)</label>
                
                {{-- Hidden input remains the Livewire binding target for validation and submission --}}
                <input type="hidden" wire:model.blur="driver_id">

                <div class="relative">
                    <input type="text"
                           x-model="search"
                           @focus="open = true"
                           {{-- Close dropdown and reset search text if an ID hasn't been selected --}}
                           @click.outside="open = false; if (!$wire.get('driver_id')) search = ''"
                           @keydown.escape.prevent.stop="open = false"
                           placeholder="Search or Select Driver"
                           class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white focus:ring-yellow-500 focus:border-yellow-500 transition duration-150"
                           autocomplete="off">
                           
                    <div x-show="open"
                         x-cloak
                         class="absolute z-10 w-full mt-1 bg-gray-700 rounded-lg shadow-xl max-h-60 overflow-y-auto border border-gray-600">
                        
                        {{-- Filter the driver list based on the search term --}}
                        <template x-for="driver in driversList.filter(d => d.name.toLowerCase().includes(search.toLowerCase()))" :key="driver.id">
                            <div @click="selectDriver(driver.id, driver.name)"
                                 :class="{ 'bg-yellow-600 text-white': $wire.get('driver_id') == driver.id, 'hover:bg-gray-600': $wire.get('driver_id') != driver.id }"
                                 class="p-3 cursor-pointer text-sm">
                                <span x-text="driver.name"></span>
                            </div>
                        </template>
                        
                        <div x-show="driversList.filter(d => d.name.toLowerCase().includes(search.toLowerCase())).length === 0" class="p-3 text-sm text-gray-400">
                            No drivers found.
                        </div>
                    </div>
                </div>
                @error('driver_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Fare Dropdown (Linked to the route/fare structure) --}}
            <div>
                <label for="fare_id" class="block mb-2 text-sm font-medium text-gray-400">Fare / Route</label>
                <select wire:model.blur="fare_id" id="fare_id" class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white focus:ring-yellow-500 focus:border-yellow-500 transition duration-150">
                    <option value="">Select Fare / Route</option>
                    @foreach($fares as $fare)
                        <option value="{{ $fare->fare_id }}">
                            Fare ID {{ $fare->fare_id }} (Base: ₱{{ number_format($fare->base_fare, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('fare_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Departure Time Input --}}
            <div>
                <label for="departure_time" class="block mb-2 text-sm font-medium text-gray-400">Departure Time (HH:MM)</label>
                <input type="time" wire:model.blur="departure_time" id="departure_time"
                        class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white focus:ring-yellow-500 focus:border-yellow-500 transition duration-150" required>
                @error('departure_time') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            {{-- Arrival Time Input --}}
            <div>
                <label for="arrival_time" class="block mb-2 text-sm font-medium text-gray-400">Estimated Arrival Time (Optional)</label>
                <input type="time" wire:model.blur="arrival_time" id="arrival_time"
                        class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white focus:ring-yellow-500 focus:border-yellow-500 transition duration-150">
                @error('arrival_time') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Bus Type Dropdown REMOVED to fix Undefined variable error (Bus Type is linked via Fare) --}}
            
            {{-- Action Buttons --}}
            <div class="md:col-span-2 flex justify-between gap-4 pt-4">
                <button type="submit"
                        class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 rounded-lg text-white font-semibold transition duration-150 shadow-md">
                    {{ $isEditMode ? 'Save Changes' : '➕ Add Schedule' }}
                </button>
                @if ($isEditMode)
                    <button type="button" wire:click="resetForm"
                            class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg text-white font-semibold transition duration-150 shadow-md">
                        Cancel Edit
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $schedules->links() }}
    </div>
</div>
