<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\StatusRepositoryInterface;
use App\DTOs\Status\StatusDto;
use App\Models\Status;
use App\Traits\HasPaginationFormatting;

final readonly class StatusService
{
    use HasPaginationFormatting;

    public function __construct(private StatusRepositoryInterface $repository) {}

    private function toDto(Status $status): StatusDto
    {
        return StatusDto::fromModel($status);
    }

    private function dtoToAttributes(StatusDto $dto): array
    {
        return [
            'name' => $dto->name,
            'slug' => $dto->slug,
        ];
    }

    public function getStatus(int $id): StatusDto
    {
        return $this->toDto($this->repository->find($id));
    }

    public function getStatuses(): array
    {
        return $this->repository->all()
            ->map(fn (Status $status) => $this->toDto($status))
            ->toArray();
    }

    public function getStatusesPaginated(int $perPage = 15): array
    {
        $paginated = $this->repository->paginate($perPage);

        $paginated = $paginated->through(function (Status $status) {
            return [
                ...$this->toDto($status)->toArray(), // your DTO logic
                'can' => [
                    'update' => auth()->user()?->can('update', $status) ?? false,
                    'delete' => auth()->user()?->can('delete', $status) ?? false,
                ],
            ];
        });

        return $this->formatPagination($paginated, fn ($item) => $item);
    }

    public function createStatus(StatusDto $dto): StatusDto
    {
        $status = $this->repository->create($this->dtoToAttributes($dto));

        return $this->toDto($status);
    }

    public function updateStatus(Status $status, StatusDto $dto): StatusDto
    {
        $updatedStatus = $this->repository->update(
            $status,
            $this->dtoToAttributes($dto)
        );

        return $this->toDto($updatedStatus);
    }

    public function deleteStatus(Status $status): bool
    {
        return $this->repository->delete($status);
    }
}
