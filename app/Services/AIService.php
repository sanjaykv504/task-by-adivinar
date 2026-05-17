<?php

namespace App\Services;

use App\Models\Task;

class AIService
{
    /**
     * Mock AI Integration as per requirements.
     * Generates a summary and AI priority based on task details.
     * 
     * @param Task $task
     * @return array
     */
    public function generateSummary(Task $task): array
    {
        // In a real application, this would call OpenAI/Gemini APIs using Http facade.
        // Mocking the delay and response for the machine test.

        $content = "The objective is to {$task->title}. " . 
                   ($task->description ? "Context: {$task->description}. " : "") .
                   "Ensure timely completion before " . ($task->due_date ? $task->due_date->format('Y-m-d') : 'the deadline') . ".";

        $priority = $this->determineMockPriority($task);

        return [
            'ai_summary' => $content,
            'ai_priority' => $priority,
        ];
    }

    private function determineMockPriority(Task $task): string
    {
        // Simple logic to mock AI decision making
        if (str_contains(strtolower($task->title), 'urgent') || $task->priority === 'high') {
            return 'high';
        }

        if (str_contains(strtolower($task->title), 'low') || $task->priority === 'low') {
            return 'low';
        }

        return 'medium';
    }
}
