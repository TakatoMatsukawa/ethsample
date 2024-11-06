<?php

namespace App\Libraries;

use App\Enums\SelectSearchEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class SearchAll
{
    /**
     * ファイルのサイズを返却する
     *
     * @param $query: Builder
     * @param $inputKeyword: string
     * @param $selectSearch: SelectSearchEnum
     * @param $sortColumn: string
     * @return Collection
     */
    public function SearchAll(Builder $query, string $inputKeyword, SelectSearchEnum $selectSearch, string $sortColumn): Collection
    {
        $kw = str_replace('　', ' ', $inputKeyword);
        $kw_arr = preg_split('/\s+/', $kw, -1, PREG_SPLIT_NO_EMPTY);

        if (SelectSearchEnum::AND === $selectSearch) {
            foreach ($kw_arr as $keyword) {
                $query->where(function (Builder $query) use ($keyword) {
                    $query->keywordSearch($keyword);
                });
            }
        }

        if (SelectSearchEnum::OR === $selectSearch) {
            $query->where(function (Builder $query) use ($kw_arr) {
                foreach ($kw_arr as $keyword) {
                    $query->keywordSearch($keyword);
                }
            });
        }

        $result = $query->get()->map(function ($item) use ($sortColumn) {
            $item->sortColumn = $item->$sortColumn;
            return $item;
        });

        return $result;
    }
}
