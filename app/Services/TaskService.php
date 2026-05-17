<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TaskService
{
    protected $repo;
    protected $aiService;

    public function __construct(TaskRepositoryInterface $repo, AIService $aiService)
    {
        $this->repo = $repo;
        $this->aiService = $aiService;
    }

    public function all(array $filters = [])
    {
        return $this->repo->all($filters);
    }

    public function find(int $id)
    {
        return $this->repo->find($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $task = $this->repo->create($data);
            
            // Trigger AI processing
            $aiData = $this->aiService->generateSummary($task);
            
            return $this->repo->update($task->id, $aiData);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $task = $this->repo->update($id, $data);
            return $task;
        });
    }

    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }

    public function refreshAiSummary(int $id)
    {
        return DB::transaction(function () use ($id) {
            $task = $this->repo->find($id);
            $aiData = $this->aiService->generateSummary($task);
            return $this->repo->update($id, $aiData);
        });
    }

    public function getAnalytics()
    {
        return $this->repo->getAnalytics();
    }
}
