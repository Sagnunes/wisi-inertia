<?php

namespace App\Enums;

enum Permission: string
{
    const VIEW = 'ver-permissoes';

    const CREATE = 'criar-permissoes';

    const UPDATE = 'atualizar-permissoes';

    const DELETE = 'apagar-permissoes';

    const ASSIGN = 'atribuir-permissao';
}
