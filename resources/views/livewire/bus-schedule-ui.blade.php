<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800/80 p-6 rounded-xl shadow-2xl mb-8">
        <h2 class="text-3xl font-extrabold text-yellow-400 mb-4">Bus Schedule Finder</h2>

        <div class="mb-6">
            <label for="search" class="sr-only">Search Schedules</label>
            <input 
                wire:model.live="search" 
                id="search"
                type="text" 
                placeholder="Search by Bus No., Route, or Type..." 
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-700 text-white shadow-lg focus:border-yellow-500 focus:ring-yellow-500 transition duration-150 ease-in-out p-3 placeholder-gray-400"
            >
        </div>
    </div>

    <div class="mt-8">
        <div class="text-2xl font-semibold text-white mb-4">
            Available Schedules ({{ isset($schedules) ? $schedules->count() : 4 }})
        </div>

        {{-- ========================== --}}
        {{-- STATIC DATA (NO DB DATA) --}}
        {{-- ========================== --}}
        @if (!isset($schedules) || $schedules->isEmpty())
            @php
                // --- STATIC SAMPLE DATA ---
                $schedulesToDisplay = [
                    ['bus_number' => '1001', 'route_name' => 'Tagbilaran - Tubigon', 'departure_time' => '06:00', 'bus_type_name' => 'Air-conditioned', 'estimated_distance' => 54],
                    ['bus_number' => '1002', 'route_name' => 'Tagbilaran - Carmen', 'departure_time' => '07:30', 'bus_type_name' => 'Normal', 'estimated_distance' => 60],
                    ['bus_number' => '1003', 'route_name' => 'Tubigon - Ubay', 'departure_time' => '09:00', 'bus_type_name' => 'Air-conditioned', 'estimated_distance' => 105],
                    ['bus_number' => '1004', 'route_name' => 'Tagbilaran - Tubigon', 'departure_time' => '11:45', 'bus_type_name' => 'Normal', 'estimated_distance' => 54],
                    ['bus_number' => '1005', 'route_name' => 'Carmen - Ubay', 'departure_time' => '13:15', 'bus_type_name' => 'Air-conditioned', 'estimated_distance' => 87],
                ];

                // Fare Logic
                foreach ($schedulesToDisplay as &$schedule) {
                    $farePerKm = 2.00;
                    $acSurcharge = ($schedule['bus_type_name'] === 'Air-conditioned') ? 10.00 : 0.00;
                    $schedule['final_fare'] = ($schedule['estimated_distance'] * $farePerKm) + $acSurcharge;
                }
                unset($schedule);
            @endphp

            {{-- DESKTOP VIEW --}}
            <div class="hidden sm:block">
                <div class="overflow-x-auto rounded-xl shadow-2xl">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Bus No.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Route</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Distance (km)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Departure Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Fare (₱)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-700">
                            @foreach ($schedulesToDisplay as $schedule)
                                <tr class="hover:bg-gray-800 transition">
                                    <td class="px-6 py-4 text-yellow-400 font-medium">{{ $schedule['bus_number'] }}</td>
                                    <td class="px-6 py-4 text-gray-300">{{ $schedule['route_name'] }}</td>
                                    <td class="px-6 py-4 text-gray-400 font-semibold">{{ $schedule['estimated_distance'] }} km</td>
                                    <td class="px-6 py-4 text-white font-bold">{{ \Carbon\Carbon::parse($schedule['departure_time'])->format('h:i A') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                            {{ $schedule['bus_type_name'] === 'Air-conditioned' ? 'bg-green-800 text-green-200' : 'bg-blue-800 text-blue-200' }}">
                                            {{ $schedule['bus_type_name'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-yellow-400 font-bold">₱{{ number_format($schedule['final_fare'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MOBILE VIEW --}}
            <div class="grid grid-cols-1 gap-4 sm:hidden">
                @foreach ($schedulesToDisplay as $schedule)
                    <div class="bg-gray-800 p-4 rounded-xl shadow-lg border-l-4 border-yellow-400 hover:opacity-100 transition">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-yellow-400">{{ $schedule['bus_number'] }}</h3>
                            <span class="text-2xl font-extrabold text-white">{{ \Carbon\Carbon::parse($schedule['departure_time'])->format('h:i A') }}</span>
                        </div>
                        <div class="text-sm text-gray-300 mb-2">
                            {{ $schedule['route_name'] }} 
                            <span class="ml-2 font-medium text-gray-500">({{ $schedule['estimated_distance'] }} km)</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-medium text-lg text-yellow-400">Fare: ₱{{ number_format($schedule['final_fare'], 2) }}</span>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $schedule['bus_type_name'] === 'Air-conditioned' ? 'bg-green-800 text-green-200' : 'bg-blue-800 text-blue-200' }}">
                                {{ $schedule['bus_type_name'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

        {{-- ========================== --}}
        {{-- DATABASE DATA (WHEN EXISTS) --}}
        {{-- ========================== --}}
        @elseif (isset($schedules) && !$schedules->isEmpty())
            <div class="hidden sm:block">
                <div class="overflow-x-auto rounded-xl shadow-2xl">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Bus No.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Route</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Distance (km)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Departure Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Fare (₱)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-700">
                            @foreach ($schedules as $schedule)
                                @php
                                    $distance = $schedule->route->distance_km ?? 50;
                                    $farePerKm = 2.00;
                                    $acSurcharge = ($schedule->busType->name === 'Air-conditioned') ? 10.00 : 0.00;
                                    $finalFare = ($distance * $farePerKm) + $acSurcharge;
                                @endphp
                                <tr class="hover:bg-gray-800 transition">
                                    <td class="px-6 py-4 text-yellow-400 font-medium">{{ $schedule->bus_number }}</td>
                                    <td class="px-6 py-4 text-gray-300">{{ $schedule->route->name }}</td>
                                    <td class="px-6 py-4 text-gray-400 font-semibold">{{ $distance }} km</td>
                                    <td class="px-6 py-4 text-white font-bold">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                            {{ $schedule->busType->name === 'Air-conditioned' ? 'bg-green-800 text-green-200' : 'bg-blue-800 text-blue-200' }}">
                                            {{ $schedule->busType->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-yellow-400 font-bold">₱{{ number_format($finalFare, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p class="text-white/50 text-center py-12">No schedule data available or currently loading...</p>
        @endif
    </div>
</div>
