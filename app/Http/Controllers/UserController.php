<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(\App\Services\TaskService $taskService)
    {
        // Only admin can see users list
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users = User::latest()->paginate(10);
        $analytics = $taskService->getAnalytics();
        
        return view('users.index', compact('users', 'analytics'));
    }
}
