<?php

namespace Core\Domain\Notification\Repository;

use Core\Domain\Notification\Entity\Notification;

interface NotificationRepositoryInterface
{
    public function save(Notification $notification): void;
    public function findById(string $id): ?Notification;
}
