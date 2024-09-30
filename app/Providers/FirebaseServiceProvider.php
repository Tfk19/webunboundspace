<?php

namespace App\Providers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase.database', function ($app) {
            $factory = (new Factory)
                ->withDatabaseUri(config('firebase.database_url'));

            return $factory->createDatabase();
        });
    }

    public function boot()
    {
        //
    }
}
