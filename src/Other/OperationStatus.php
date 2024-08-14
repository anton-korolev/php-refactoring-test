<?php

declare(strict_types=1);

namespace Other;

enum OperationStatus: int
{
    case Completed = 0;
    case Pending = 1;
    case Rejected = 2;

    public static function tryFromMixed(mixed $value): self|null
    {
        // return self::tryFrom(is_numeric($value) ? (int)$value : -1);

        $value = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        return self::tryFrom($value ?? -1);
    }
}
