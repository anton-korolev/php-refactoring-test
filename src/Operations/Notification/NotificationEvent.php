<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification;

enum NotificationEvent: string
{
    case change = 'changeReturnStatus';
    case new = 'newReturnStatus';

    public const indexMap = [
        1 => self::new,
        2 => self::change,
    ];

    public static function tryFromIndex(mixed $index): self|null
    {
        // return self::indexMap[is_numeric($index) ? (int)$index : null] ?? null;

        $index = filter_var($index, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        return self::indexMap[$index] ?? null;
    }
}
