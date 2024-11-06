<?php

namespace App\Enums;

/**
 * iiif有無
 */
enum IiifEnum: string
{
    /** 無し */
    case NONE = '0';
    /** 有り */
    case EXIST = '1';

    public function label(): string
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            return match ($this) {
                self::NONE => 'Yes',
                self::EXIST => 'No',
            };
        } elseif (app()->getLocale() === 'am') {
            return match ($this) {
                self::NONE => 'አይደለም',
                self::EXIST => 'አዎ',
            };
        } elseif (app()->getLocale() === 'ja') {
            return match ($this) {
                self::NONE => '無し',
                self::EXIST => '有り',
            };
        }
    }
}
