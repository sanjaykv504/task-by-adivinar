<x-app-layout>
    <x-slot name="header">Edit Task</x-slot>

    <!-- Filters -->
    <div class="flex gap-4 mb-6">
        <div class="relative flex-1 max-w-xs bg-white rounded-lg px-3 py-2 flex items-center shadow-sm">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Search Filter Task" class="bg-transparent border-none focus:ring-0 w-full text-sm text-gray-800 ml-2 placeholder-gray-400 p-0">
        </div>
        <div class="bg-white rounded-lg px-4 py-2 flex items-center shadow-sm">
            <select class="bg-transparent border-none focus:ring-0 text-sm text-gray-600 p-0 pr-6 appearance-none">
                <option>Status</option>
            </select>
        </div>
        <div class="bg-white rounded-lg px-4 py-2 flex items-center shadow-sm">
            <select class="bg-transparent border-none focus:ring-0 text-sm text-gray-600 p-0 pr-6 appearance-none">
                <option>All Msedech</option>
            </select>
        </div>
        <div class="bg-white rounded-lg px-4 py-2 flex items-center shadow-sm">
            <select class="bg-transparent border-none focus:ring-0 text-sm text-gray-600 p-0 pr-6 appearance-none">
                <option>Priority</option>
            </select>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-xl shadow-sm p-8 text-gray-800 max-w-3xl">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h2>
            <div class="text-gray-400 font-bold tracking-widest leading-none">....</div>
        </div>

        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-gray-50 p-6 rounded-xl space-y-6">
                <!-- Title -->
                <div class="relative">
                    <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Launch New Campaign">
                    <div class="absolute right-3 top-2.5 w-7 h-7 rounded-full bg-gray-200 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name ?? 'User') }}&background=random" alt="Avatar">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <textarea name="description" rows="4" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600" placeholder="Description here...">{{ old('description', $task->description) }}</textarea>
                </div>

                <!-- Priority -->
                <div class="flex items-center gap-4">
                    <label class="font-semibold text-gray-900 w-24">Priority</label>
                    <div class="flex gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="low" class="peer sr-only" {{ old('priority', $task->priority) === 'low' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-colors">Low</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="medium" class="peer sr-only" {{ old('priority', $task->priority) === 'medium' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-orange-50 peer-checked:text-orange-600 peer-checked:border-orange-200 transition-colors">Medium</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="high" class="peer sr-only" {{ old('priority', $task->priority) === 'high' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-red-50 peer-checked:text-red-600 peer-checked:border-red-200 transition-colors">High</div>
                        </label>
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center gap-4">
                    <label class="font-semibold text-gray-900 w-24">Status</label>
                    <div class="flex gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="pending" class="peer sr-only" {{ old('status', $task->status) === 'pending' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-gray-600 peer-checked:text-white transition-colors">Pending</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="in_progress" class="peer sr-only" {{ old('status', $task->status) === 'in_progress' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white transition-colors">In Progress</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="completed" class="peer sr-only" {{ old('status', $task->status) === 'completed' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-green-500 peer-checked:text-white transition-colors">Completed</div>
                        </label>
                    </div>
                </div>

                <!-- Due Date -->
                <div class="relative">
                    <input type="date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Assign To -->
                <div class="relative">
                    <label class="text-sm font-semibold text-gray-900 block mb-2">Assign To</label>
                    <select name="assigned_to" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-center pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-full font-medium transition-colors shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
