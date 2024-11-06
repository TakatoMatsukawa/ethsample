<?php

namespace App\Enums;

/**
 * 公開状態
 */
enum PublicEnum: string
{
    case ALL = 'all';
    /** 公開 */
    case PUBLIC = '0';
    /** 非公開 */
    case PRIVATE = '9';

    public function label(): string
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            return match ($this) {
                self::ALL => 'ALL',
                self::PUBLIC => 'Public',
                self::PRIVATE => 'Private',
            };
        } elseif (app()->getLocale() === 'am') {
            return match ($this) {
                self::ALL => 'ሁሉም',
                self::PUBLIC => 'የህዝብ',
                self::PRIVATE => 'የግል',
            };
        } elseif (app()->getLocale() === 'ja') {
            return match ($this) {
                self::ALL => '全て',
                self::PUBLIC => '公開',
                self::PRIVATE => '非公開',
            };
        }
    }
}
