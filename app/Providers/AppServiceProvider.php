<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::directive('adminHasPermission', function ($permissions) {
            return "<?php
                \$directiveAuthUser = auth('admin')->user();
                \$perms = is_array($permissions) ? $permissions : [$permissions];
                \$hasPermission = false;

                if (\$directiveAuthUser) {
                    foreach (\$perms as \$perm) {
                        // super-admin bypass works via can() triggering Gate::before
                        if (\$directiveAuthUser->can(\$perm)) {
                            \$hasPermission = true;
                            break;
                        }
                    }
                }

                if (\$hasPermission):
            ?>";
        });

        Blade::directive('elseAdminHasPermission', function () {
            return "<?php else: ?>";
        });


        Blade::directive('endAdminHasPermission', fn() => "<?php endif; ?>");

        Paginator::useTailwind();
    }
}
