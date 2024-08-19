<?php

namespace Motomedialab\SimpleLaravelAudit\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Motomedialab\SimpleLaravelAudit\Actions\FetchIpAddress;
use Motomedialab\SimpleLaravelAudit\Actions\FetchObfuscatedIpAddress;
use Motomedialab\SimpleLaravelAudit\Actions\FetchUserId;
use Motomedialab\SimpleLaravelAudit\Auditors\SimpleAuditor;
use Motomedialab\SimpleLaravelAudit\Contracts\AuditorContract;
use Motomedialab\SimpleLaravelAudit\Contracts\FetchesIpAddress;
use Motomedialab\SimpleLaravelAudit\Contracts\FetchesUserId;
use Motomedialab\SimpleLaravelAudit\Contracts\IsAuditableEvent;
use Motomedialab\SimpleLaravelAudit\Listeners\AuditableEventListener;

class SimpleAuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        parent::register();

        $baseDir = __DIR__ . '/../../';

        $this->mergeConfigFrom($baseDir . 'config/simple-auditor.php', 'simple-auditor');

        $this->loadMigrationsFrom($baseDir . 'database/migrations');

        $this->publishes([
            $baseDir . 'config/simple-auditor.php' => $this->app->configPath('simple-auditor.php'),
            $baseDir . 'database/migrations' => $this->app->databasePath('migrations'),
        ], 'simple-auditor');
    }

    public function boot(): void
    {
        // register our prune job to run daily
        $this->app->booted(fn () => $this->app->make(Schedule::class)
            ->job(config('simple-auditor.prune_job'))
            ->daily());

        // register our auditor bindings
        $this->app->bind(AuditorContract::class, fn () => new SimpleAuditor());
        $this->app->alias(AuditorContract::class, 'simple-auditor');

        // register our actions - we'll do this for extensibility.
        $this->app->bind(FetchesIpAddress::class, fn() => $this->registerIpFetcher());
        $this->app->bind(FetchesUserId::class, config('simple-auditor.fetch_user_id', FetchUserId::class));

        // bind our event listener
        Event::listen(IsAuditableEvent::class, AuditableEventListener::class);
    }

    private function registerIpFetcher(): FetchesIpAddress
    {
        if ($fetcher = config('simple-auditor.fetch_ip_address')) {
            return new $fetcher;
        }

        $obfuscate = config('simple-auditor.obfuscate_ip_addresses');

        if ($obfuscate) {
            return new FetchObfuscatedIpAddress();
        }

        return new FetchIpAddress();
    }

}
