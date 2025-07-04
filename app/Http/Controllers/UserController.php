<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select(['id', 'name', 'email'])
            ->with(['roles' => function ($query) {
                $query->select('roles.id', 'roles.name');
            }])
            ->orderBy('name')->get();

        return Inertia::render('Management/Users/Index', compact('users'));
    }
}
