<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    protected $internalTables = ['pages', 'layouts', 'user_migrations', 'middlewares', 'resources', 'migrations', 'password_resets'];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $tables = collect(DB::select('SHOW TABLES'))
            ->pluck('Tables_in_' . \Config::get("database.connections." . \Config::get('database.default') . ".database"))
            ->filter(function($table) {
                return ! in_array($table, $this->internalTables);
            })->reverse();


        View::composer('layouts.admin.app', function ($view) use ($tables) {
            $view->with('tables', $tables);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
