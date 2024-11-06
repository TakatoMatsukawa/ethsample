<?php

namespace App\Enums;

/**
 * 状態管理
 */
enum StateEnum: string
{
    /** 変更無し */
    case UNCHANGED = '0';
    /** 追加 */
    case ADD = '1';
    /** 変更 */
    case MODIFY = '2';
    /** 削除 */
    case DELETE = '3';

    public function label(): string
    {
        return match ($this) {
            self::UNCHANGED => '変更無し',
            self::ADD => '追加',
            self::MODIFY => '変更',
            self::DELETE => '削除',
        };
    }

    public static function isValidValue(string $value): bool
    {
        return in_array($value, array_column(self::cases(), 'value'), true);
    }
}
