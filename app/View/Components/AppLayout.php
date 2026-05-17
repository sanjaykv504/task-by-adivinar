<?php

namespace App\View\Components;

use App\Services\TaskService;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $analytics;

    public function __construct(TaskService $taskService)
    {
        $this->analytics = $taskService->getAnalytics();
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
