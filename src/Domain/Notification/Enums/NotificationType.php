<?php

namespace Core\Domain\Notification\Enums;

enum NotificationType: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case PUSH = 'push';
    case WEBHOOK = 'webhook';
}
