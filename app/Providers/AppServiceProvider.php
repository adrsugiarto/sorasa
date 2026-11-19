<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production') || str_starts_with((string) config('app.url'), 'https://')) {
            $this->app['request']->server->set('HTTPS', 'on');
            $this->app['request']->headers->set('X-Forwarded-Proto', 'https');
            URL::forceScheme('https');
        }

        Blade::directive('rupiah', function ($expression) {
            return "<?php echo format_rupiah($expression); ?>";
        });
    }
}