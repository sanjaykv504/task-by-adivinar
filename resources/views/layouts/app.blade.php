<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Task Management System') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-[#1E2530] text-gray-100 min-h-screen">
    <div class="max-w-[1400px] mx-auto p-6 md:p-8 flex flex-col md:flex-row gap-8">
        
        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            <header class="flex justify-between items-center mb-6">
                <h1 class="text-4xl font-bold text-white">{{ $header ?? 'Task List' }}</h1>
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    + New Task
                </a>
            </header>

            {{ $slot }}
        </main>

        <!-- Right Sidebar -->
        <aside class="w-full md:w-[320px] flex flex-col gap-6 shrink-0">
            <!-- User Profile & Nav Card -->
            <div class="bg-white text-gray-800 rounded-xl shadow overflow-hidden">
                <div class="p-4 flex items-center gap-3 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Avatar">
                    </div>
                    <div>
                        <div class="font-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }} User</div>
                    </div>
                </div>
                <nav class="flex flex-col">
                    <a href="{{ route('tasks.index') }}" class="px-5 py-3 border-b border-gray-100 flex items-center justify-between {{ request()->routeIs('tasks.*') ? 'bg-blue-500 text-white' : 'hover:bg-gray-50' }}">
                        <span>Tasks</span>
                    </a>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('users.index') }}" class="px-5 py-3 border-b border-gray-100 flex items-center justify-between {{ request()->routeIs('users.*') ? 'bg-blue-500 text-white' : 'hover:bg-gray-50 text-gray-600' }}">
                        <span>Users <span class="text-xs {{ request()->routeIs('users.*') ? 'text-blue-200' : 'text-gray-400' }} ml-2">(Only visible to Admin)</span></span>
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-5 py-3 text-gray-600 hover:bg-gray-50 transition-colors">
                            Logout
                        </button>
                    </form>
                </nav>

                @isset($analytics)
                <!-- Mini Stats in Sidebar Card -->
                <div class="p-6 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <!-- Total -->
                        <div class="flex flex-col items-center relative w-[72px] h-[72px]">
                            <svg class="w-full h-full transform -rotate-90 absolute inset-0">
                                <circle cx="36" cy="36" r="32" stroke="#f3f4f6" stroke-width="4" fill="transparent" />
                                <circle cx="36" cy="36" r="32" stroke="#3b82f6" stroke-width="4" fill="transparent" stroke-dasharray="201" stroke-dashoffset="0" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pt-1">
                                <span class="text-[8px] text-gray-500 font-medium leading-none mb-1">Total Tasks</span>
                                <span class="text-lg font-bold text-gray-800 leading-none">{{ $analytics['total'] ?? 0 }}</span>
                            </div>
                        </div>
                        
                        <!-- Completed -->
                        <div class="flex flex-col items-center relative w-[72px] h-[72px]">
                            <svg class="w-full h-full transform -rotate-90 absolute inset-0">
                                <circle cx="36" cy="36" r="32" stroke="#f3f4f6" stroke-width="4" fill="transparent" />
                                @php
                                    $compPercent = ($analytics['total'] > 0) ? ($analytics['completed'] / $analytics['total']) : 0;
                                    $compOffset = 201 - (201 * $compPercent);
                                @endphp
                                <circle cx="36" cy="36" r="32" stroke="#3b82f6" stroke-width="4" fill="transparent" stroke-dasharray="201" stroke-dashoffset="{{ $compOffset }}" stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pt-1">
                                <span class="text-[8px] text-gray-500 font-medium leading-none mb-1">Completed</span>
                                <span class="text-lg font-bold text-gray-800 leading-none">{{ $analytics['completed'] ?? 0 }}</span>
                            </div>
                        </div>

                        <!-- Inprogress -->
                        <div class="flex flex-col items-center relative w-[72px] h-[72px]">
                            <svg class="w-full h-full transform -rotate-90 absolute inset-0">
                                <circle cx="36" cy="36" r="32" stroke="#f3f4f6" stroke-width="4" fill="transparent" />
                                @php
                                    $pendPercent = ($analytics['total'] > 0) ? ($analytics['pending'] / $analytics['total']) : 0;
                                    $pendOffset = 201 - (201 * $pendPercent);
                                @endphp
                                <circle cx="36" cy="36" r="32" stroke="#3b82f6" stroke-width="4" fill="transparent" stroke-dasharray="201" stroke-dashoffset="{{ $pendOffset }}" stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pt-1">
                                <span class="text-[8px] text-gray-500 font-medium leading-none mb-1">Inprogress</span>
                                <span class="text-lg font-bold text-gray-800 leading-none">{{ $analytics['pending'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    @if(!empty($analytics['monthly']))
                    <!-- Mini Bar Chart (White Card) -->
                    <div class="text-center text-[10px] font-semibold text-gray-600 mb-4">Monthly Task Completion</div>
                    <div class="flex items-end justify-center gap-4 px-2 h-16 mb-2">
                        @foreach($analytics['monthly'] as $month => $count)
                        @php
                            $height = $analytics['max_monthly'] > 0 ? ($count / $analytics['max_monthly']) * 100 : 0;
                            $height = max(15, min(100, $height)); // Ensure minimum visibility
                        @endphp
                        <div class="flex flex-col items-center justify-end w-8 h-full">
                            <div class="w-full flex items-end justify-center" style="height: 40px;">
                                <div class="w-4 bg-[#3b82f6] rounded-t-sm" style="height: {{ $height }}%;" title="{{ $count }} Tasks"></div>
                            </div>
                            <span class="text-[9px] text-gray-400 mt-1">{{ $month }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- High Priority text to fulfill requirement quietly -->
                    <div class="text-center mt-3 pt-3 border-t border-gray-50">
                        <span class="text-[10px] text-red-500 font-medium px-2 py-1 bg-red-50 rounded-full">High Priority Tasks: {{ $analytics['high_priority'] ?? 0 }}</span>
                    </div>
                </div>
                @endisset
            </div>

            <!-- Custom Content injected via sidebar slot (like Refresh AI button) -->
            @isset($sidebar)
                {{ $sidebar }}
            @endisset

            @if(isset($analytics) && !empty($analytics['monthly']))
            <!-- Monthly Chart Card -->
            <div class="bg-[#2A313C] rounded-xl shadow p-5 border border-gray-700">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Monthly Task Completion</h3>
                <div class="h-40 w-full relative">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('monthlyChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode(array_keys($analytics['monthly'])) !!},
                            datasets: [{
                                label: 'Completed Tasks',
                                data: {!! json_encode(array_values($analytics['monthly'])) !!},
                                backgroundColor: '#3b82f6',
                                borderRadius: 4,
                                barPercentage: 0.6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#374151' }, ticks: { color: '#9ca3af', stepSize: 1 } },
                                x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                            }
                        }
                    });
                });
            </script>
            @endif
        </aside>
    </div>
</body>
</html>
