<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly UserService $service) {}

    public function index(): Response
    {
        $this->authorize('viewAny', User::class);
        $users = $this->service->getUsersWithRolesAssociated(50);

        return Inertia::render('Management/Users/Index', compact('users'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $this->service->deleteUser($user);

        return redirect()->back()->with('status', 'User deleted successfully.');
    }
}
