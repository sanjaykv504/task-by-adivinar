<x-app-layout>
    <x-slot name="header">Create New Task</x-slot>

    <!-- Create Form Card -->
    <div class="bg-white rounded-xl shadow-sm p-8 text-gray-800 max-w-3xl">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-2xl font-bold text-gray-900">New Task</h2>
        </div>

        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
            @csrf
            
            <div class="bg-gray-50 p-6 rounded-xl space-y-6">
                <!-- Title -->
                <div class="relative">
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Launch New Campaign" required>
                </div>

                <!-- Description -->
                <div>
                    <textarea name="description" rows="4" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-600" placeholder="Description here...">{{ old('description') }}</textarea>
                </div>

                <!-- Priority -->
                <div class="flex items-center gap-4">
                    <label class="font-semibold text-gray-900 w-24">Priority</label>
                    <div class="flex gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="low" class="peer sr-only" {{ old('priority') === 'low' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition-colors">Low</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="medium" class="peer sr-only" {{ old('priority', 'medium') === 'medium' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-orange-50 peer-checked:text-orange-600 peer-checked:border-orange-200 transition-colors">Medium</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="high" class="peer sr-only" {{ old('priority') === 'high' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 peer-checked:bg-red-50 peer-checked:text-red-600 peer-checked:border-red-200 transition-colors">High</div>
                        </label>
                    </div>
                </div>

                <!-- Due Date -->
                <div class="relative">
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Assign To -->
                <div class="relative">
                    <label class="text-sm font-semibold text-gray-900 block mb-2">Assign To</label>
                    <select name="assigned_to" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-center pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-full font-medium transition-colors shadow-sm">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
