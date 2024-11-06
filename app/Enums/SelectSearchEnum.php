<?php

namespace App\Enums;

/**
 * 検索条件
 */
enum SelectSearchEnum: string
{
    case AND = 'and';
    case OR = 'or';

    public function label(): string
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            return match ($this) {
                self::AND => 'AND',
                self::OR => 'OR',
            };
        } elseif (app()->getLocale() === 'am') {
            return match ($this) {
                self::AND => 'እና',
                self::OR => 'ወይም',
            };
        } elseif (app()->getLocale() === 'ja') {
            return match ($this) {
                self::AND => 'すべてを含む',
                self::OR => 'いずれかを含む',
            };
        }
    }
}
