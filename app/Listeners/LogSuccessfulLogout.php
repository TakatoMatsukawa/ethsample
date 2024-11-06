<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $userId = Auth::id();

        Activity::create([
            'user_id' => $userId,
            'desc' => 'Logged out',
            'action' => 1,
        ]);
    }
}
