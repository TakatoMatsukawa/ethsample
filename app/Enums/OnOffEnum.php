<?php

namespace App\Enums;

/**
 * 有無
 */
enum OnOffEnum: string
{
    case ALL = 'all';
    case ON = 'on';
    case OFF = 'off';

    public function label(): string
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            return match ($this) {
                self::ALL => 'ALL',
                self::ON => 'Yes',
                self::OFF => 'No',
            };
        } elseif (app()->getLocale() === 'am') {
            return match ($this) {
                self::ALL => 'ሁሉም',
                self::ON => 'አይደለም',
                self::OFF => 'አዎ',
            };
        } elseif (app()->getLocale() === 'ja') {
            return match ($this) {
                self::ALL => '全て',
                self::ON => '有り',
                self::OFF => '無し',
            };
        }
    }
}
