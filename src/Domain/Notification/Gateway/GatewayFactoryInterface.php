<?php

namespace Core\Domain\Notification\Gateway;

use Core\Domain\Notification\Enums\NotificationType;

interface GatewayFactoryInterface
{
    public function make(NotificationType $type): NotificationGatewayInterface;
}
