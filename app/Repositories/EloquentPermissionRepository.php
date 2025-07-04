<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\PermissionRepositoryInterface;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    protected Permission $model;

    /**
     * The columns to select from the permission table
     */
    private const PERMISSION_LIST_COLUMNS = ['id', 'name', 'slug', 'created_at', 'updated_at'];

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    private function baseQuery(): Builder
    {
        ;
        return $this->model->query()->select(self::PERMISSION_LIST_COLUMNS)->orderBy('name');
    }

    public function all(): Collection
    {
        return $this->baseQuery()->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery()->paginate($perPage)->withQueryString();
    }

    public function find(int $id): Permission
    {
        return $this->baseQuery()->findOrFail($id);
    }

    public function create(array $data): Permission
    {
        return $this->model->create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);

        return $permission->fresh();
    }

    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}
