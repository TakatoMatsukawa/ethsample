<?php

namespace App\Http\Domain\Site\Search;

use App\Enums\PublicEnum;
use App\Enums\SelectSearchEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use App\Libraries\SearchAll;

// 対象のModel
use App\Models\Manuscript;

/**
 * 横断検索の一覧表示時のデータに関する処理を行う
 */
class SearchListDomain
{
    /**
     * 横断検索の一覧画面に表示するデータを返却する
     *
     * @param string $inputKeyword
     * @param SelectSearchEnum $selectSearch
     * @param int $page
     * @return array{
     *     input_keyword: string,
     *     select_search: SelectSearchEnum,
     *     search_list: LengthAwarePaginator,
     *     page: int
     * }
     */
    public function __invoke(string $inputKeyword, SelectSearchEnum $selectSearch, int $page): array
    {
        $whereQuery = [['deleted_at', null], ['public_flg', PublicEnum::PUBLIC]];

        // 検索対象
        $models = [
            [
                'query' => Manuscript::where($whereQuery),
                'sortColumn' => 'name',
            ],
        ];

        if ($inputKeyword) {
            $searchAll = new SearchAll();

            $results = collect();
            foreach ($models as $model) {
                $result = $searchAll->SearchAll($model['query'], $inputKeyword, $selectSearch, $model['sortColumn']);
                $results = $results->merge($result);
            }

            $results = $results->sortBy('sortColumn')->values();
        } else {
            $results = collect();
        }

        $searchList = $this->paginate($results, config('pagination.site_record'), $page);

        $counts = [
            'total' => $searchList->total(),
            'perPage' => $searchList->perPage(),
            'currentPage' => $searchList->currentPage(),
            'lastPage' => $searchList->lastPage(),
        ];

        return [
            'input_keyword' => $inputKeyword,
            'select_search' => $selectSearch,
            'search_list' => $searchList,
            'page' => $page,
            'counts' => $counts,
        ];
    }

    private function paginate($items, $perPage, $page)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $options = [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ];
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
