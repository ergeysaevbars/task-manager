<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Task $resource
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->resource->id,
            'title'       => $this->resource->title,
            'description' => $this->resource->description,
            'status'      => $this->resource->status->statusTitle(),
            'deadline'    => $this->resource->deadline->format('Y-m-d'),
            'created_at'  => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $this->resource->updated_at->format('Y-m-d H:i:s'),
            'author'      => (new UserResource($this->resource->author))->toArray($request),
            'assignee'    => $this->resource->assignee ? (new UserResource($this->resource->assignee))->toArray($request) : null,
        ];
    }
}
