<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\TaskStatus;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskResourceCollection;
use App\Models\Task;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\error;

class TaskController extends ApiBaseController
{
    public function list(Request $request): JsonResponse
    {
        $tasksQuery = Task::query();

        if ($request->filled('status')) {
            $tasksQuery->where('status', $request->enum('status', TaskStatus::class));
        }

        if ($request->filled('deadline')) {
            $tasksQuery->where('deadline', '<=', $request->date('deadline', 'Y-m-d'));
        }

        if ($request->filled('assigned_to')) {
            $tasksQuery->where('assigned_to', $request->get('assigned_to'));
        }

        if ($request->filled('author_id')) {
            $tasksQuery->where('author_id', $request->get('author_id'));
        }

        $tasksQuery->orderByDesc('created_at');

        $tasks = $tasksQuery->paginate(20);

        return $this->successResponse([
            'total' => $tasks->total(),
            'items' => (new TaskResourceCollection($tasks))->toArray($request)
        ]);
    }

    public function show(Task $task, Request $request): JsonResponse
    {
        return $this->successResponse((new TaskResource($task))->toArray($request));
    }

    public function create(CreateTaskRequest $request): JsonResponse
    {
        $task              = new Task();
        $task->author_id   = $request->user()->id;
        $task->assigned_to = $request->get('assigned_to');
        $task->title       = $request->get('title');
        $task->description = $request->get('description');
        $task->status      = TaskStatus::READY_TO_DEV;
        $task->deadline    = $request->date('deadline');

        if (!$task->save()) {
            Log::error("Не удалось создать задачу", [
                'task' => $task->toArray(),
            ]);

            return $this->errorResponse('Не удалось создать задачу', 500);
        }

        return $this->successResponse((new TaskResource($task))->toArray($request), 201);
    }

    public function update(Task $task, UpdateTaskRequest $request): JsonResponse
    {
        $task->assigned_to = $request->get('assigned_to');
        $task->title       = $request->get('title');
        $task->description = $request->get('description');
        $task->status      = $request->enum('status', TaskStatus::class);
        $task->deadline    = $request->date('deadline');

        if (!$task->save()) {
            Log::error("Не удалось обновить задачу", [
                'task' => $task->toArray(),
            ]);

            return $this->errorResponse('Не удалось обновить задачу', 500);
        }

        return $this->successResponse((new TaskResource($task))->toArray($request));
    }

    public function delete(Task $task, Request $request): JsonResponse
    {
        try {
            if (!$task->delete()) {
                Log::error('Не удалось удалить задачу', [
                    'task' => $task->toArray(),
                    'user' => $request->user()->toArray(),
                ]);

                return $this->errorResponse('Не удалось удалить задачу. Попробуйте позже', 500);
            }
        } catch (Exception $e) {
            Log::error('Неизвестная ошибка при попытке удалить задачу', [
                'message' => $e->getMessage(),
                'task'    => $task->toArray(),
                'user'    => $request->user()->toArray(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Не удалось удалить задачу. Попробуйте позже', 500);
        }

        return $this->successResponse([]);
    }
}
