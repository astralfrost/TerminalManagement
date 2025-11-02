<div class="max-w-5xl mx-auto p-6 rounded-lg shadow-2xl text-white" style="background-color: rgba(40, 44, 63, 0.9);">
    <h1 class="text-4xl font-light mb-8">Bus Schedule</h1>
    
    <div class="flex space-x-4 mb-8 items-end">
        
        <input type="text" wire:model.live="search" placeholder="🔍 Search Bus No. or Route Name" 
               class="flex-1 p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white placeholder-gray-400">
        
        <select wire:model.live="routeFilter" class="p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white">
            <option value="">Filter by Route (All)</option>
            @foreach($routes as $route)
                <option value="{{ $route->id }}">{{ $route->name }}</option>
            @endforeach
        </select>

        <div wire:ignore x-data x-init="
            flatpickr($refs.dayFilter, { 
                dateFormat: 'Y-m-d',
                onChange: (selectedDates, dateStr) => {$wire.set('dayFilter', dateStr)},
            });" class="w-1/4">
            <input x-ref="dayFilter" type="text" wire:model.live="dayFilter" placeholder="Select Date" 
                   class="w-full p-3 rounded-lg border-2 border-gray-600 bg-gray-700 text-white cursor-pointer placeholder-gray-400">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-600 rounded-lg overflow-hidden">
            <thead class="bg-gray-700">
                <tr class="text-left text-sm font-semibold uppercase tracking-wider">
                    <th class="px-6 py-3">Bus No.</th>
                    <th class="px-6 py-3">Route</th>
                    <th class="px-6 py-3">Departure Time</th>
                    <th class="px-6 py-3">Bus Type</th>
                    <th class="px-6 py-3">Day</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse ($schedules as $schedule)
                    <tr class="hover:bg-gray-700 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 font-bold">{{ $schedule->bus_number }}</td>
                        <td class="px-6 py-4">{{ $schedule->route->name }}</td>
                        <td class="px-6 py-4 text-green-400">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</td>
                        <td class="px-6 py-4">{{ $schedule->busType->name }}</td>
                        <td class="px-6 py-4">{{ $schedule->day }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-400">No schedules found matching your criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>