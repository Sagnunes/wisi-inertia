<?php

declare(strict_types=1);

namespace App\DTOs\Status;

use App\Models\Status;
use Illuminate\Support\Str;

final readonly class StatusDTO
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

    public static function fromModel(Status $status): self
    {
        return new self(
            name: $status->name,
            slug: $status->slug,
            id: $status->id,
            description: $status->description,
            created_at: $status->created_at->format('Y-m-d'),
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
        ];
    }
}
