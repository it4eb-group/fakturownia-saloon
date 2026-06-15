<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use It4eb\Fakturownia\FakturowniaConnector;

final class FakturowniaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/fakturownia.php', 'fakturownia');

        // Bound as a closure (not a captured instance) so it stays Octane-safe
        // and can later be rebound to read per-tenant credentials.
        $this->app->singleton(FakturowniaConnector::class, static function (Application $app): FakturowniaConnector {
            return new FakturowniaConnector(
                domain: (string) $app['config']->get('fakturownia.domain', ''),
                apiToken: (string) $app['config']->get('fakturownia.token', ''),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/fakturownia.php' => $this->app->configPath('fakturownia.php'),
            ], 'fakturownia-config');
        }
    }
}
