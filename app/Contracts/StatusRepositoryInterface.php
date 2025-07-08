<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;

interface StatusRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): Status;

    public function create(array $data): Status;

    public function update(Status $status, array $data): Status;

    public function delete(Status $status): bool;

    public function paginate(int $perPage = 10);
}
