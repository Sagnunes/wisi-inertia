<?php

namespace App\Http\Controllers;

use App\DTOs\Status\StatusDTO;
use App\Http\Requests\Statuses\StoreStatusRequest;
use App\Http\Requests\Statuses\UpdateStatusRequest;
use App\Models\Status;
use App\Services\StatusService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class StatusController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly StatusService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = $this->service->getStatusesPaginated(50);

        return Inertia::render('Management/Statuses/Index', [
            'statuses' => $statuses,
            'can' => [
                'create' => auth()->user()->can('create', Status::class),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusRequest $request)
    {
        $dto = StatusDTO::fromRequest($request->validated());

        $this->service->createStatus($dto);

        return to_route('statuses.index')->with('status', 'Status created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Status $status)
    {
        return Inertia::render('Management/Statuses/Edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusRequest $request, Status $status)
    {
        $this->service->updateStatus($status, StatusDTO::fromRequest($request->validated()));

        return to_route('statuses.edit', $status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $this->service->deleteStatus($status);

        return redirect()->back()->with('status', 'Status deleted successfully.');
    }
}
