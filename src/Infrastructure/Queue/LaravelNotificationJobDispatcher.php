<?php

namespace Core\Infrastructure\Queue;

use Core\Application\Interfaces\NotificationJobDispatcherInterface;
use Core\Infrastructure\Queue\Jobs\ProcessNotificationJob;

class LaravelNotificationJobDispatcher implements NotificationJobDispatcherInterface
{
    public function dispatch(string $notificationId): void
    {
        ProcessNotificationJob::dispatch($notificationId);
    }
}
