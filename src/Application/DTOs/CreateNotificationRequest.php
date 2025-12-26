<?php

namespace Core\Application\DTOs;

class CreateNotificationRequest
{
    public function __construct(
        public readonly array $channels,
        public readonly string $recipient,
        public readonly array $payload
    ) {
    }
}
