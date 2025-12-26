<?php

namespace Core\Infrastructure\Gateways;

use Core\Domain\Notification\Gateway\NotificationGatewayInterface;
use Illuminate\Support\Facades\Log;

class SmsGateway implements NotificationGatewayInterface
{
    public function send(\Core\Domain\Notification\Entity\Notification $notification): void
    {
        Log::info("Sending SMS to {$notification->getRecipient()}", $notification->getPayload());
        // Simulate external API call
    }
}
