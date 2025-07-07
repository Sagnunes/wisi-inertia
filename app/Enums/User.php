<?php

namespace App\Enums;

enum User: string
{
    const MANAGE = 'manage-users';

    const VIEW = 'view-users';

    const DELETE = 'delete-users';
}
