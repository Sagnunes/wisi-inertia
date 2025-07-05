<?php

namespace App\Enums;

enum Permission: string
{
    const MANAGE = 'manage-permissions';

    const ASSIGN = 'assign-permissions';
}
