<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('routeIs', function ($expression) {
            return "<?php echo request()->routeIs($expression) ? 'true' : 'false'; ?>";
        });
    }
}
