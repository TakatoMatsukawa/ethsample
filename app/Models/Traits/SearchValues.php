<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use \App\Models\Variant;

/**
 * キーワード検索で指定された値に関するwhere条件設定の実装
 */
trait SearchValues
{
    /**
     * キーワード検索する文字列を旧字体を含めて検索できるように正規表現形式に変換する
     * @param string $value
     * @return string
     */
    private function searchValue(string $value): string
    {
        $str = Str::of($value);
        // 地名など別途検索で必要なものを置き換え
        // $str = $str->replace(["佐賀", "佐嘉"], "佐(賀|嘉)");

        // Variantsテーブルから配列作成
        $variants = Variant::select('new', 'old', 'old2', 'old3', 'old4', 'old5')->where('deleted_at', '=', null)->get();
        $search_regex = array();

        $variantValues = $variants->map(function ($item) {
            return [$item['new'], $item['old'], $item['old2'], $item['old3'], $item['old4'], $item['old5']];
        })->toArray();

        foreach ($variantValues as $value) {
            $filteredValues = array_filter($value, function ($value) {
                return $value !== '';
            });
            $separateValue = implode('|', $filteredValues);

            foreach ($filteredValues as $filteredValue) {
                $addArray = array($filteredValue => $separateValue);
                $search_regex = array_merge($search_regex, $addArray);
            }
        }

        $result = '';
        $length = $str->length();
        for ($i = 0; $i < $length; $i++) {
            #文字単位にばらす
            $char = $str->charAt($i);
            if (array_key_exists($char, $search_regex)) {
                $result .= "(" . $search_regex[$char] . ")";
            } else {
                $result .= $char;
            }
        }
        return $result;
    }
}
