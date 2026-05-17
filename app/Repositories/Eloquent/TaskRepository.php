<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = Task::query()->with('user');

        if (Auth::user()->role !== 'admin') {
            $query->where('assigned_to', Auth::id());
        }

        // Apply filters using Scope (Bonus requirement)
        $query->filter($filters);

        return $query->latest()->paginate(10);
    }

    public function find(int $id)
    {
        return Task::with('user')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(int $id, array $data)
    {
        $task = $this->find($id);
        $task->update($data);
        return $task;
    }

    public function delete(int $id)
    {
        $task = $this->find($id);
        return $task->delete();
    }

    public function getAnalytics()
    {
        $query = Task::query();
        if (Auth::user()->role !== 'admin') {
            $query->where('assigned_to', Auth::id());
        }

        // Get monthly completed stats
        $completedTasks = (clone $query)->where('status', 'completed')->get();
        $monthly = [];
        $maxMonthly = 0;
        foreach($completedTasks as $task) {
            if ($task->updated_at) {
                $month = $task->updated_at->format('M');
                $monthly[$month] = ($monthly[$month] ?? 0) + 1;
                if ($monthly[$month] > $maxMonthly) {
                    $maxMonthly = $monthly[$month];
                }
            }
        }

        return [
            'total' => $query->count(),
            'completed' => $completedTasks->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'high_priority' => (clone $query)->where('priority', 'high')->count(),
            'monthly' => $monthly,
            'max_monthly' => $maxMonthly,
        ];
    }
}
