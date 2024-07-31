<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->resource->id,
            'name'              => $this->resource->name,
            'email'             => $this->resource->email,
            'is_email_verified' => (bool)$this->resource->email_verified_at,
            'created_at'        => $this->resource->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
