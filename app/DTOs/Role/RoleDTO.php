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
        );
    }

    public function toArrayForPersistence(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ];
    }
}
