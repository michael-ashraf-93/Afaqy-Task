<?php

namespace App\Enum;

/**
 * @const STATUS_OPEN
 * @const STATUS_IN_PROGRESS
 * @const STATUS_CLOSED
 */
enum ServiceStatusEnums
{
    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in-progress';
    public const STATUS_CLOSED = 'closed';
}
