<?php

namespace App\Providers;

use App\{
    Repositories\Contracts\Auth\AuthRepositoryContract,
    Repositories\Eloquent\Auth\AuthRepository,
    Models\Notification\Notification

};

use Illuminate\{
    Support\Facades\Auth,
    Support\Facades\View,
    Support\ServiceProvider
};

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthRepositoryContract::class, AuthRepository::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notifications = Notification::where('user_id', Auth::user()->id)
        
                    ->get()
                    ->groupBy(function ($item) {
                        if (str_contains($item->link, '/setting')) {
                            return 'Setting';
                        } elseif (str_contains($item->link, '/report')) {
                            return 'Report';
                        } else {
                            return 'Other';
                        }
                    });

                $view->with('notificationsGrouped', $notifications);
            }
        });
    }
}
