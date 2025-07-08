<?php

namespace App\Enums;

enum Status: int
{
    case PENDING = 1;
    case ACTIVE = 2;
    case BLOCKED = 3;

    const VIEW = 'view-status';

    const CREATE = 'create-status';

    const UPDATE = 'update-status';

    const DELETE = 'delete-status';
}
