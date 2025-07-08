<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserStatusRequest;
use App\Services\UserStatusService;
use Illuminate\Http\Request;

class UserStatusController extends Controller
{
    public function __construct(private readonly UserStatusService $service)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateUserStatusRequest $request)
    {
        $validated = $request->validated();
        $this->service->updateUserStatus($validated['user'],$validated['status']);

        return redirect()->back();
    }
}
