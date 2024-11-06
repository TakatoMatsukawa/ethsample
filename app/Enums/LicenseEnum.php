<?php

namespace App\Enums;

/**
 * ライセンス
 */
enum LicenseEnum: string
{
    /** 全て */
    case ALL = 'all';
    /** 閲覧のみ */
    case READ_ONLY = '0';
    /** PDM */
    case PDM = '1';
    /** CC BY（表示） */
    case CC_BY = '2';
    /** CC BY-SA（表示-継承） */
    case CC_BY_SA = '3';
    /** CC BY-ND（表示-改変禁止） */
    case CC_BY_ND = '4';
    /** CC BY-NC（表示-非営利） */
    case CC_BY_NC = '5';
    /** CC BY-NC-SA（表示-非営利-継承） */
    case CC_BY_NC_SA = '6';
    /** CC BY-NC-ND（表示-非営利-改変禁止） */
    case CC_BY_NC_ND = '7';

    public function label(): string
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            return match ($this) {
                self::ALL => 'ALL',
                self::READ_ONLY => 'READ_ONLY',
                self::PDM => 'PDM',
                self::CC_BY => 'CC BY',
                self::CC_BY_SA => 'CC BY-SA',
                self::CC_BY_ND => 'CC BY-ND',
                self::CC_BY_NC => 'CC BY-NC',
                self::CC_BY_NC_SA => 'CC BY-NC-SA',
                self::CC_BY_NC_ND => 'CC BY-NC-ND',
            };
        } elseif (app()->getLocale() === 'am') {
            return match ($this) {
                self::ALL => 'ሁሉም',
                self::READ_ONLY => 'ማንበብ ብቻ',
                self::PDM => 'PDM(የህዝብ ግዛት)',
                self::CC_BY => 'CC BY',
                self::CC_BY_SA => 'CC BY-SA',
                self::CC_BY_ND => 'CC BY-ND',
                self::CC_BY_NC => 'CC BY-NC',
                self::CC_BY_NC_SA => 'CC BY-NC-SA',
                self::CC_BY_NC_ND => 'CC BY-NC-ND',
            };
        } elseif (app()->getLocale() === 'ja') {
            return match ($this) {
                self::ALL => '全て',
                self::READ_ONLY => '閲覧のみ',
                self::PDM => 'PDM',
                self::CC_BY => 'CC BY（表示）',
                self::CC_BY_SA => 'CC BY-SA（表示-継承）',
                self::CC_BY_ND => 'CC BY-ND（表示-改変禁止）',
                self::CC_BY_NC => 'CC BY-NC（表示-非営利）',
                self::CC_BY_NC_SA => 'CC BY-NC-SA（表示-非営利-継承）',
                self::CC_BY_NC_ND => 'CC BY-NC-ND（表示-非営利-改変禁止）',
            };
        }
    }

    public function image(): string
    {
        return match ($this) {
            self::ALL => '',
            self::READ_ONLY => asset('assets/img/license/viewonly.png'),
            self::PDM => asset('assets/img/license/pd.png'),
            self::CC_BY => asset('assets/img/license/CCBY.png'),
            self::CC_BY_SA => asset('assets/img/license/CCBY-SA.png'),
            self::CC_BY_ND => asset('assets/img/license/CCBY-ND.png'),
            self::CC_BY_NC => asset('assets/img/license/CCBY-NC.png'),
            self::CC_BY_NC_SA => asset('assets/img/license/CCBY-NC-SA.png'),
            self::CC_BY_NC_ND => asset('assets/img/license/CCBY-NC-ND.png'),
        };
    }
}
