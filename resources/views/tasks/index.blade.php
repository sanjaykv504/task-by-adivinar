<x-app-layout>
    <x-slot name="header">Task List</x-slot>

    <!-- Filters -->
    <form method="GET" action="{{ route('tasks.index') }}" class="flex gap-4 mb-6">
        <div class="relative flex-1 max-w-xs bg-white rounded-lg px-3 py-2 flex items-center shadow-sm">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Filter Task" class="bg-transparent border-none focus:ring-0 w-full text-sm text-gray-800 ml-2 placeholder-gray-400 p-0" onkeydown="if(event.key === 'Enter'){this.form.submit();}">
        </div>
        <div class="bg-white rounded-lg px-4 py-2 flex items-center shadow-sm">
            <select name="status" class="bg-transparent border-none focus:ring-0 text-sm text-gray-600 p-0 pr-6 appearance-none" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="bg-white rounded-lg px-4 py-2 flex items-center shadow-sm">
            <select name="priority" class="bg-transparent border-none focus:ring-0 text-sm text-gray-600 p-0 pr-6 appearance-none" onchange="this.form.submit()">
                <option value="">All Priority</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </div>
        <noscript><button type="submit" class="bg-blue-500 text-white px-4 rounded-lg text-sm">Filter</button></noscript>
    </form>

    <!-- Task Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        @foreach($tasks as $task)
        <div class="bg-white rounded-xl shadow-sm p-6 text-gray-800 flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                    <div class="w-5 h-5 rounded-full bg-blue-500 flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    {{ str_replace('_', ' ', Str::title($task->status)) }}
                </div>
                <div class="text-gray-400 font-bold tracking-widest leading-none">....</div>
            </div>

            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $task->title }}</h2>

            <div class="flex gap-2 mb-4">
                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">Status</span>
                <span class="px-2 py-1 {{ $task->priority === 'high' ? 'bg-red-500' : ($task->priority === 'medium' ? 'bg-orange-500' : 'bg-blue-500') }} text-white rounded text-xs font-semibold">Priority {{ ucfirst($task->priority) }}</span>
            </div>

            <p class="text-sm text-gray-500 flex-1 line-clamp-3 mb-6">
                {{ $task->description ?? 'No description provided.' }}
                @if($task->ai_priority)
                <br>AI Priority: {{ ucfirst($task->ai_priority) }}
                @endif
            </p>

            <div class="flex items-end justify-between mt-auto">
                <div class="text-xs text-gray-500">
                    <div>Assigned to: {{ $task->user->name ?? 'Unassigned' }}</div>
                    <div>Due: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'None' }}</div>
                    <div class="text-blue-500 font-medium mt-1">{{ ucfirst($task->priority) }}</div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">Edit</a>
                    <a href="{{ route('tasks.show', $task) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors">View</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
</x-app-layout>
