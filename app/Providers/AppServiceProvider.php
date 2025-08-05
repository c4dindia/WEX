<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;
use Illuminate\Support\Facades\View;
use Auth;

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
        View::composer('*', function ($view) {
            $user = auth()->user();
            if ($user) {
                if ($user->is_admin === 1) {
                    $modules = Module::where('id', '!=', 17)->get();
                    $view->with('modules', $modules);
                } elseif ($user->is_admin === 2) {
                    $user_id = $user->id;

                    $modules = Module::join('assign_modules', 'assign_modules.module_id', '=', 'modules.id')
                        ->join('roles', 'roles.id', '=', 'assign_modules.role_id')
                        ->join('assign_roles', 'assign_roles.role_id', '=', 'roles.id')
                        ->select('modules.*')
                        ->where('assign_roles.user_id', $user_id)
                        ->distinct()
                        ->get();
                    $view->with('modules', $modules);
                }
                elseif( $user->is_admin === 3) {
                    $modules = Module::whereIn('id', [1, 17])->get();
                    $view->with('modules', $modules);
                }
            } else {
                $view->with('modules', []);
            }
        });
    }
}
