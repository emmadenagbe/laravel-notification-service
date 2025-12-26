<?php

namespace Core\Infrastructure\Gateways;

use Core\Domain\Notification\Enums\NotificationType;
use Core\Domain\Notification\Gateway\GatewayFactoryInterface;
use Core\Domain\Notification\Gateway\NotificationGatewayInterface;
use InvalidArgumentException;

class NotificationGatewayFactory implements GatewayFactoryInterface
{
    public function make(NotificationType $type): NotificationGatewayInterface
    {
        return match ($type) {
            NotificationType::EMAIL => app(EmailGateway::class),
            NotificationType::SMS => app(SmsGateway::class),
            NotificationType::PUSH => app(PushGateway::class),
            NotificationType::WEBHOOK => app(LogGatewayAdapter::class), // Fallback or separate WebhookGateway
            default => throw new InvalidArgumentException("No gateway found for type: {$type->value}"),
        };
    }
}
