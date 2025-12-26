<?php

namespace Core\Application\Interfaces;

interface NotificationJobDispatcherInterface
{
    public function dispatch(string $notificationId): void;
}
