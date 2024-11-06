<?php

namespace App\Http\Domain\Site\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\PublicEnum;
use App\Enums\SelectSearchEnum;
use App\Models\Manuscript;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * 古文書の一覧表示時のデータに関する処理を行う
 */
class ManuscriptListDomain
{
    /**
     * 古文書の一覧画面に表示するデータを返却する
     *
     * @param LicenseEnum $selectLicense
     * @param string $inputKeyword
     * @param SelectSearchEnum $selectSearch
     * @param int $page
     * @return array{
     *     select_license: LicenseEnum,
     *     input_keyword: string,
     *     select_search: SelectSearchEnum,
     *     manuscript_list: LengthAwarePaginator,
     *     page: int
     * }
     */
    public function __invoke(LicenseEnum $selectLicense, string $inputKeyword, SelectSearchEnum $selectSearch, int $page): array
    {
        // 検索
        $query = Manuscript::select('manuscripts.*')
            ->where('deleted_at', null)
            ->where('public_flg', PublicEnum::PUBLIC);

        // ライセンス
        if ($selectLicense !== LicenseEnum::ALL) {
            $query->where('manuscripts.license', $selectLicense);
        }

        // キーワード
        if (!empty($inputKeyword)) {
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
        }

        $manuscriptList = $query->paginate(config('pagination.site_record'), page: $page);
        $counts = [
            'total' => $manuscriptList->total(),
            'perPage' => $manuscriptList->perPage(),
            'currentPage' => $manuscriptList->currentPage(),
            'lastPage' => $manuscriptList->lastPage(),
        ];

        return [
            'select_license' => $selectLicense,
            'input_keyword' => $inputKeyword,
            'select_search' => $selectSearch,
            'manuscript_list' => $manuscriptList,
            'page' => $page,
            'counts' => $counts,
        ];
    }
}
