<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\Models\User;

final readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?int $id = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
        );
    }

    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            id: $user->id,
            created_at: $user->created_at->format('Y-m-d'),
        );
    }

    public function toArrayForPersistence(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
