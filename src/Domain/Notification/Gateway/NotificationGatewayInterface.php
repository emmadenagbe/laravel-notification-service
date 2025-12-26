<?php

namespace Core\Domain\Notification\Gateway;

use Core\Domain\Notification\Entity\Notification;

interface NotificationGatewayInterface
{
    /**
     * Sends the notification via the external provider.
     * Throws an exception if sending fails.
     */
    public function send(Notification $notification): void;
}
