<?php

namespace Core\Domain\Notification\Enums;

enum NotificationStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SENT = 'sent';
    case FAILED = 'failed';
}
