<?php

namespace App\Providers;

use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Actions\CreateAction;
use App\Services\ModelTraining\Requests\Request;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        CreateAction::configureUsing(function ($action) {
            return $action->slideOver();
        });

        EditAction::configureUsing(function ($action) {
            return $action->slideOver();
        });
    }
}
