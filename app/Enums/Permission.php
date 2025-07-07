<?php

namespace App\Enums;

enum Permission: string
{
    const VIEW = 'view-permissions';

    const CREATE = 'create-permissions';

    const UPDATE = 'update-permissions';

    const DELETE = 'delete-permissions';

    const ASSIGN = 'assign-permissions';
}
