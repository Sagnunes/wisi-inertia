<?php

namespace App\Enums;

enum Role: int
{
    case WATCHER = 1;
    case DIRECTOR = 2;
    case COLLECTOR = 3;

    const VIEW = 'view-roles';

    const CREATE = 'create-roles';

    const UPDATE = 'update-roles';

    const DELETE = 'delete-roles';

    const ASSIGN = 'assign-roles';

    public function getName(): string
    {
        return match ($this) {
            self::WATCHER => 'Watcher',
            self::COLLECTOR => 'Collector',
            self::DIRECTOR => 'Director',
            default => 'Unknown',
        };
    }
}
