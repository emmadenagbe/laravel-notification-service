<?php

namespace Core\Infrastructure\Queue\Jobs;

use Core\Application\UseCases\SendNotificationUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessNotificationJob implements ShouldQueue
{
    use Queueable;

    // Retry configuration
    public $tries = 3;
    public $backoff = [10, 60, 180]; // 10s, 1m, 3m

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $notificationId
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(SendNotificationUseCase $useCase): void
    {
        $useCase->execute($this->notificationId);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $repository = app(\Core\Domain\Notification\Repository\NotificationRepositoryInterface::class);
        $notification = $repository->findById($this->notificationId);

        if ($notification) {
            $notification->markAsFailed($exception->getMessage());
            $repository->save($notification);
        }
    }
}