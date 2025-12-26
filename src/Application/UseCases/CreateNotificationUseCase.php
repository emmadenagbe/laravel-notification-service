<?php

namespace Core\Application\UseCases;

use Core\Application\DTOs\CreateNotificationRequest;
use Core\Application\Interfaces\NotificationJobDispatcherInterface;
use Core\Domain\Notification\Entity\Notification;
use Core\Domain\Notification\Enums\NotificationType;
use Core\Domain\Notification\Repository\NotificationRepositoryInterface;
use Illuminate\Support\Str; // Ideally use a UUID generator interface, but Str is fine for now if we treat it as a utility.

class CreateNotificationUseCase
{
    public function __construct(
        private readonly NotificationRepositoryInterface $repository,
        private readonly NotificationJobDispatcherInterface $dispatcher
    ) {
    }

    public function execute(CreateNotificationRequest $request): string
    {
        $id = (string) Str::uuid();

        $notification = Notification::create(
            id: $id,
            channels: $request->channels,
            recipient: $request->recipient,
            payload: $request->payload
        );

        $this->repository->save($notification);

        // Dispatch the background job
        $this->dispatcher->dispatch($id);

        return $id;
    }
}
