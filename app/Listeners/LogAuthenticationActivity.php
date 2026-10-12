<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class LogAuthenticationActivity
{
    /**
     * Handle user login event.
     */
    public function handleLogin(Login $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'guard' => $event->guard,
            ])
            ->log("Pengguna {$event->user->email} berhasil login ke panel");
    }

    /**
     * Handle failed login attempt.
     */
    public function handleFailed(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'tidak diketahui';

        activity('auth')
            ->withProperties([
                'email' => $email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'guard' => $event->guard,
            ])
            ->log("Percobaan login gagal untuk email: {$email}");
    }

    /**
     * Handle user logout event.
     */
    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            activity('auth')
                ->causedBy($event->user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'guard' => $event->guard,
                ])
                ->log("Pengguna {$event->user->email} telah logout dari panel");
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Failed::class => 'handleFailed',
            Logout::class => 'handleLogout',
        ];
    }
}
