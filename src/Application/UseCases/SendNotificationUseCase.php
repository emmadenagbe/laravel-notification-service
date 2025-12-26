<?php

namespace Core\Application\UseCases;

use Core\Domain\Notification\Enums\NotificationStatus;
use Core\Domain\Notification\Gateway\NotificationGatewayInterface;
use Core\Domain\Notification\Repository\NotificationRepositoryInterface;
use Exception;

use Core\Domain\Notification\Gateway\GatewayFactoryInterface;

class SendNotificationUseCase
{
    public function __construct(
        private readonly NotificationRepositoryInterface $repository,
        private readonly GatewayFactoryInterface $gatewayFactory
    ) {
    }

    public function execute(string $notificationId): void
    {
        $notification = $this->repository->findById($notificationId);

        if (!$notification) {
            throw new Exception("Notification not found: $notificationId");
        }

        if ($notification->getStatus() === NotificationStatus::SENT) {
            return; // Idempotency check
        }

        try {
            $notification->markAsProcessing();
            $this->repository->save($notification);

            foreach ($notification->getChannels() as $channel) {
                $gateway = $this->gatewayFactory->make($channel);
                $gateway->send($notification);
            }

            $notification->markAsSent();
            $this->repository->save($notification);

        } catch (Exception $e) {
            $notification->incrementRetryCount();
            $this->repository->save($notification);
            throw $e;
        }
    }
}
