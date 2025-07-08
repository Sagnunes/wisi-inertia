<?php

namespace App\Jobs;

use App\Mail\ITNotificationUserRegister;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendITNewUserNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;

    private string $IT_DEPARTMENT = 'sti.madeira@madeira.gov.pt';

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(ENV('IT_DEPARTMENT_EMAIL', $this->IT_DEPARTMENT))->send(new ITNotificationUserRegister($this->user));
    }
}
