<?php

namespace Core\Infrastructure\Persistence\Eloquent;

use Core\Domain\Notification\Entity\Notification;
use Core\Domain\Notification\Enums\NotificationStatus;
use Core\Domain\Notification\Enums\NotificationType;
use Core\Domain\Notification\Repository\NotificationRepositoryInterface;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    public function save(Notification $notification): void
    {
        NotificationModel::updateOrCreate(
            ['id' => $notification->getId()],
            [
                'channels' => array_map(fn($c) => $c->value, $notification->getChannels()),
                'recipient' => $notification->getRecipient(),
                'payload' => $notification->getPayload(),
                'status' => $notification->getStatus()->value,
                'retry_count' => $notification->getRetryCount(),
                'error_message' => $notification->getErrorMessage(),
                'created_at' => $notification->getCreatedAt(),
                'updated_at' => $notification->getUpdatedAt(),
            ]
        );
    }

    public function findById(string $id): ?Notification
    {
        $model = NotificationModel::find($id);

        if (!$model) {
            return null;
        }

        return Notification::reconstitute(
            id: $model->id,
            channels: array_map(fn($c) => NotificationType::from($c), $model->channels ?? []),
            recipient: $model->recipient,
            payload: $model->payload,
            status: NotificationStatus::from($model->status),
            retryCount: $model->retry_count,
            errorMessage: $model->error_message,
            createdAt: \DateTimeImmutable::createFromMutable($model->created_at),
            updatedAt: $model->updated_at ? \DateTimeImmutable::createFromMutable($model->updated_at) : null
        );
    }
}
