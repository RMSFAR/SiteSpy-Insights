<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use JoeDixon\Translation\Scanner;
use JoeDixon\Translation\Drivers\Translation;
use App\Http\Package\LaravelTranslation\TranslationManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Translation::class, function ($app) {
            return (new TranslationManager($app, $app['config']['translation'], $app->make(Scanner::class)))->resolve();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
