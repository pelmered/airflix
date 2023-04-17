<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $re = '/Domain\\\\(?<domain>.+)\\\\Models\\\\(?<model>.+)/';

            if (preg_match($re, $modelName, $matches, PREG_OFFSET_CAPTURE, 0)) {
                $classPath = 'Database\Factories\\'.$matches['domain'][0].'\\'.$matches['model'][0].'Factory';
                if (class_exists($classPath)) {
                    return $classPath;
                }
            }

            return 'Database\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}
