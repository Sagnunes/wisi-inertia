<?php

namespace App\Enums;

enum Role: int
{
    case WATCHER = 1;
    case DIRECTOR = 2;
    case COLLECTOR = 3;

    const VIEW = 'ver-perfis';

    const CREATE = 'criar-perfis';

    const UPDATE = 'atualizar-perfis';

    const DELETE = 'apagar-perfis';

    const ASSIGN = 'atribuir-perfil';

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
