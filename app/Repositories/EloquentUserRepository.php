<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentUserRepository implements UserRepositoryInterface
{
    protected User $model;

    /**
     * The columns to select from the permission table
     */
    private const USER_LIST_COLUMNS = ['id', 'name', 'email', 'created_at', 'updated_at'];

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    private function baseQuery(): Builder
    {
        return $this->model->query()->select(self::USER_LIST_COLUMNS)->orderBy('name');
    }

    public function all(): Collection
    {
        return $this->baseQuery()->get();
    }

    public function find(int $id): User
    {
        return $this->baseQuery()->findOrFail($id);
    }

    public function delete(User $user): bool
    {
        $user->roles()->detach();

        return $user->delete();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->baseQuery()->paginate($perPage)->withQueryString();
    }

    public function paginateWithRoles(int $perPage = 15)
    {
        $currentUser = Auth::user();

        return $this->baseQuery()->with(['roles' => function ($query) {
            $query->select('roles.id', 'roles.name');
        }])->paginate($perPage)
            ->through(function ($user) use ($currentUser) {
                return array_merge($user->toArray(), [
                    'can' => [
                        'delete' => $currentUser ? $currentUser->can('delete', $user) : false,
                        'assignRole' => $currentUser ? $currentUser->can('assignRole', $user) : false,
                    ],
                ]);
            })->withQueryString();
    }
}
