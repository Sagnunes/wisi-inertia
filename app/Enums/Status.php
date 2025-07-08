<?php

namespace App\Enums;

enum Status: int
{
    case PENDING = 1;
    case ACTIVE = 2;
    case BLOCKED = 3;

    const VIEW = 'ver-estados';

    const CREATE = 'criar-estados';

    const UPDATE = 'atualizar-estados';

    const DELETE = 'apagar-estados';
}
