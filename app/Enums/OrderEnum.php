<?php

namespace App\Enums;

/**
 * 昇順、降順
 */
enum OrderEnum: string
{
    case NONE = '';
    case ASC = 'asc';
    case DESC = 'desc';

    public function label(): string
    {
        return match ($this) {
            self::NONE => '',
            self::ASC => '昇順',
            self::DESC => '降順',
        };
    }
}
