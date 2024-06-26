<?php

namespace App\Providers;

use App\Models\BorrowedItem;
use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Gate::define('management', function ($user) {
            return $user->isAdmin() || $user->isLaboran();
        });

        Gate::define('academic', function ($user) {
            return $user->isStudent() || $user->isTeacher();
        });

        Gate::define(('handle-academic-request'), function ($user, BorrowingRequest $borrowingRequest) {
            return $user->id == $borrowingRequest->sender_id;
        });

        Gate::define('update-borrowed-item', function ($user, BorrowedItem $borrowedItem) {
            return ($user->isAdmin() || $user->isLaboran()) && $borrowedItem->requestDetail->status->name == 'approved';
        });

        Gate::define('view-borrowed-item', function ($user, BorrowedItem $borrowedItem) {
            return $user->isAdmin() || $user->isLaboran() || $borrowedItem->requestDetail->request->sender_id == $user->id;
        });

        Gate::define('view-borrowing-request', function ($user, BorrowingRequest $borrowingRequest) {
            return $user->isAdmin() || $user->isLaboran() || $borrowingRequest->sender_id == $user->id;
        });

    }
}
