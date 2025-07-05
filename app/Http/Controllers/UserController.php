<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function index(): Response
    {
        $users = $this->service->getUsersWithRolesAssociated(50);

        return Inertia::render('Management/Users/Index', compact('users'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->service->deleteUser($user);

        return redirect()->back()->with('status', 'User deleted successfully.');
    }
}
