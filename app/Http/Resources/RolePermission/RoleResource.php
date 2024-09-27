<?php

namespace App\Http\Resources\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'name' => $this->name,
        //     'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        // ];
        return [
            'name' => $this->name,
            // 'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'permissions' => $this->permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description,
                ];  }),
        ];
    }
}
