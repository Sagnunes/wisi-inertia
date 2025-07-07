<?php

declare(strict_types=1);

namespace App\DTOs\Role;

use App\Models\Role;
use Illuminate\Support\Str;

final readonly class RoleDTO
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?int $id = null,
        public ?string $description = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?\Illuminate\Database\Eloquent\Collection $permissions = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: Str::slug($data['name']),
            description: $data['description'] ?? null,
        );
    }

    public static function fromModel(Role $role): self
    {
        return new self(
            name: $role->name,
            slug: $role->slug,
            id: $role->id,
            description: $role->description,
            created_at: $role->created_at->format('Y-m-d'),
            permissions: $role->permissions,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissions' => collect($this->permissions ?? [])->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'slug' => $permission->slug,
                ];
            })->toArray(),
        ];
    }
}
