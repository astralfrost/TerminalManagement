<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bus Schedule</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom CSS to ensure the background image covers the whole screen */
        .blurry-background {
            /* Using 'tagbiterminal.webp' from the public/images folder */
            background-image: url('{{ asset('images/tagbiterminal.webp') }}'); 
            
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Ensures the background stays put when scrolling */
        }
    </style>
    
    @livewireStyles
</head>
<body class="blurry-background text-white min-h-screen">
    
    <!-- Navigation Bar: Added blur effect -->
    <nav class="bg-gray-800/80 backdrop-blur-sm shadow-lg sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('schedule.public') }}" class="flex-shrink-0 text-xl font-bold text-yellow-400">
                        🚌 Schedule
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('schedule.public') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
                        Public Schedules
                    </a>
                    <a href="{{ route('schedule.admin') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
                        Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Slot: Added dark overlay and blur effect -->
    <main class="py-12 bg-gray-900/80 backdrop-blur-md min-h-[calc(100vh-64px)]">
        {{ $slot }}
    </main>
    
    @livewireScripts
</body>
</html>