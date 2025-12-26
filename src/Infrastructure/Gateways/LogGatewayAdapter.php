<?php

namespace Core\Infrastructure\Gateways;

use Core\Domain\Notification\Entity\Notification;
use Core\Domain\Notification\Gateway\NotificationGatewayInterface;
use Illuminate\Support\Facades\Log;

class LogGatewayAdapter implements NotificationGatewayInterface
{
    public function send(Notification $notification): void
    {
        Log::info("Sending Notification [{$notification->getType()->value}] to [{$notification->getRecipient()}]", [
            'id' => $notification->getId(),
            'payload' => $notification->getPayload()
        ]);

        // Integrate real providers (Mailgun/Twilio) here by checking $notification->getType()
        // OR use a Strategy pattern if this class gets too big.

        // Simulating success. To simulate failure, throw an exception based on payload content.
    }
}
