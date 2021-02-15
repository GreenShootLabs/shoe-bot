<?php

namespace App\Providers;

use App\Bot\Dialogflow\DialogflowClient;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\URL;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DialogflowClient::class, function () {
            return new DialogflowClient([
                'language_code' => 'en-GB',
                'environment' => 'draft'
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);

        if (config('app.force_https')) {
            URL::forceScheme('https');
        }
    }
}
