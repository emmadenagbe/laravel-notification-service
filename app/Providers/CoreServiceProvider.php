<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Core\Domain\Notification\Repository\NotificationRepositoryInterface::class,
            \Core\Infrastructure\Persistence\Eloquent\EloquentNotificationRepository::class
        );

        $this->app->bind(
            \Core\Application\Interfaces\NotificationJobDispatcherInterface::class,
            \Core\Infrastructure\Queue\LaravelNotificationJobDispatcher::class
        );

        $this->app->bind(
            \Core\Domain\Notification\Gateway\GatewayFactoryInterface::class,
            \Core\Infrastructure\Gateways\NotificationGatewayFactory::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
