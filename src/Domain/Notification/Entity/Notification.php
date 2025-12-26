<?php

namespace Core\Domain\Notification\Entity;

use Core\Domain\Notification\Enums\NotificationStatus;
use Core\Domain\Notification\Enums\NotificationType;
use InvalidArgumentException;

class Notification
{
    private function __construct(
        private readonly string $id,
        private readonly array $channels,
        private readonly string $recipient,
        private readonly array $payload,
        private NotificationStatus $status,
        private int $retryCount = 0,
        private ?string $errorMessage = null,
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private ?\DateTimeImmutable $updatedAt = null
    ) {
    }

    public static function create(
        string $id,
        array $channels,
        string $recipient,
        array $payload
    ): self {
        // Basic domain validation can go here
        if (empty($recipient)) {
            throw new InvalidArgumentException("Recipient cannot be empty.");
        }
        if (empty($channels)) {
            throw new InvalidArgumentException("At least one channel must be specified.");
        }

        $typedChannels = array_map(function ($channel) {
            return $channel instanceof NotificationType ? $channel : NotificationType::from($channel);
        }, $channels);

        return new self(
            id: $id,
            channels: $typedChannels,
            recipient: $recipient,
            payload: $payload,
            status: NotificationStatus::PENDING
        );
    }

    public static function reconstitute(
        string $id,
        array $channels,
        string $recipient,
        array $payload,
        NotificationStatus $status,
        int $retryCount,
        ?string $errorMessage,
        \DateTimeImmutable $createdAt,
        ?\DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            channels: $channels,
            recipient: $recipient,
            payload: $payload,
            status: $status,
            retryCount: $retryCount,
            errorMessage: $errorMessage,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function markAsProcessing(): void
    {
        $this->status = NotificationStatus::PROCESSING;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsSent(): void
    {
        $this->status = NotificationStatus::SENT;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsFailed(string $error): void
    {
        $this->status = NotificationStatus::FAILED;
        $this->errorMessage = $error;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function incrementRetryCount(): void
    {
        $this->retryCount++;
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getChannels(): array
    {
        return $this->channels;
    }
    public function getRecipient(): string
    {
        return $this->recipient;
    }
    public function getPayload(): array
    {
        return $this->payload;
    }
    public function getStatus(): NotificationStatus
    {
        return $this->status;
    }
    public function getRetryCount(): int
    {
        return $this->retryCount;
    }
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
