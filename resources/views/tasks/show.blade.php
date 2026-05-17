<x-app-layout>
    <x-slot name="header">Task Detail + AI Summary</x-slot>

    <x-slot name="sidebar">
        <!-- Refresh AI Summary Button in Sidebar -->
        <a href="{{ route('tasks.aiSummary', $task) }}" class="flex items-center justify-center gap-2 bg-white text-blue-600 font-medium px-5 py-3 rounded-xl shadow-sm border border-blue-100 hover:bg-blue-50 transition-colors">
            Refresh AI Summary
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </a>
    </x-slot>

    <!-- Task Detail Card -->
    <div class="bg-white rounded-xl shadow-sm p-8 text-gray-800 max-w-3xl">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h2>
            <div class="text-gray-400 font-bold tracking-widest leading-none">....</div>
        </div>

        <div class="flex gap-4 mb-8">
            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">Status <span class="font-bold ml-1">{{ str_replace('_', ' ', Str::title($task->status)) }}</span></span>
            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">Priority <span class="font-bold ml-1">{{ ucfirst($task->priority) }}</span></span>
        </div>

        <div class="bg-gray-50 p-6 rounded-xl space-y-6">
            <!-- Details -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $task->description ?? 'No description provided.' }}</p>
                
                <div class="text-sm text-gray-800 font-medium mb-2">Assigned to: <span class="font-normal">{{ $task->user->name ?? 'Unassigned' }}</span></div>
                
                <div class="bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-600 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Due Date: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'None' }}
                </div>
            </div>

            <!-- AI Summary Section -->
            @if($task->ai_summary)
            <div class="mt-8">
                <h3 class="font-bold text-gray-900 mb-3 text-lg">AI-Generated Summary</h3>
                <div class="bg-white border border-gray-200 rounded-lg p-5 text-sm text-gray-600 leading-relaxed shadow-sm">
                    {{ $task->ai_summary }}
                </div>
                <div class="mt-4 bg-white border border-gray-200 rounded-lg p-5 text-sm text-gray-800 shadow-sm flex items-center gap-2">
                    <span class="font-semibold text-gray-900">AI Summary:</span> AI processed the context. 
                    <span class="ml-2 font-semibold text-gray-900">Priority:</span> {{ ucfirst($task->ai_priority) }}
                </div>
            </div>
            @else
            <div class="mt-8 bg-white border border-gray-200 rounded-lg p-5 text-sm text-gray-500 shadow-sm text-center">
                No AI summary generated yet. Click "Refresh AI Summary" in the sidebar.
            </div>
            @endif
        </div>

        <div class="flex justify-center pt-8">
            <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-full font-medium transition-colors shadow-sm inline-block">
                Edit Task
            </a>
        </div>
    </div>
</x-app-layout>
