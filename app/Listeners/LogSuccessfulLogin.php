<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class LogSuccessfulLogin
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
    public function handle(Login $event): void
    {
        $userId = Auth::id();

        Activity::create([
            'user_id' => $userId,
            'desc' => 'Logged in',
            'action' => 0,
        ]);
    }
}
