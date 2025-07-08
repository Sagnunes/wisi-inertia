<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?int $id,
        public ?string $created_at,
        public ?Collection $roles,
        public Status $status
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            status: $data['status'] ?? null,
        );
    }

    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            id: $user->id,
            created_at: $user->created_at->format('Y-m-d'),
            roles: $user->roles,
            status: $user->status,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'roles' => collect($this->roles ?? [])->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'slug' => $role->slug,
                ];
            })->toArray(),
        ];
    }
}
