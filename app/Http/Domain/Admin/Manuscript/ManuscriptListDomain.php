<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\OnOffEnum;
use App\Enums\PublicEnum;
use App\Enums\SelectSearchEnum;
use App\Enums\OrderEnum;
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
     * @param PublicEnum $selectPublic
     * @param LicenseEnum $selectLicense
     * @param string $inputKeyword
     * @param SelectSearchEnum $selectSearch
     * @param OnOffEnum $selectThumbnail
     * @param OnOffEnum $selectPdf
     * @param OrderEnum $orderName
     * @param OrderEnum $orderId
     * @param int $page
     * @return array{
     *     select_public: PublicEnum,
     *     select_license: LicenseEnum,
     *     input_keyword: string,
     *     select_search: SelectSearchEnum,
     *     select_thumbnail: OnOffEnum,
     *     select_pdf: OnOffEnum,
     *     order_name: OrderEnum,
     *     order_id: OrderEnum,
     *     manuscript_list: LengthAwarePaginator,
     *     page: int
     * }
     */
    public function __invoke(
        PublicEnum $selectPublic,
        LicenseEnum $selectLicense,
        string $inputKeyword,
        SelectSearchEnum $selectSearch,
        OnOffEnum $selectThumbnail,
        OnOffEnum $selectPdf,
        OrderEnum $orderName,
        OrderEnum $orderId,
        int $page,
    ): array {

        // 検索
        $query = Manuscript::select('manuscripts.*')
            ->where('deleted_at', '=', null);
        // 公開状態
        if ($selectPublic !== PublicEnum::ALL) {
            $query->where('manuscripts.public_flg', $selectPublic);
        }
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

        // サムネイル
        if ($selectThumbnail !== OnOffEnum::ALL) {
            if (OnOffEnum::ON === $selectThumbnail) {
                $query->whereNot('thumbnail_file_name', '');
            } else {
                $query->where('thumbnail_file_name', '');
            }
        }
        // PDF
        if ($selectPdf !== OnOffEnum::ALL) {
            if (OnOffEnum::ON === $selectPdf) {
                $query->whereHas('pdfs');
            } else {
                $query->doesntHave('pdfs');
            }
        }

        // 順序初期値
        if ($orderName === OrderEnum::NONE && $orderId === OrderEnum::NONE) {
            $orderName = OrderEnum::ASC;
        }

        // 資料名順序
        if ($orderName === OrderEnum::ASC) {
            $query->orderBy('name', 'asc');
        } elseif ($orderName === OrderEnum::DESC) {
            $query->orderBy('name', 'desc');
        }

        // ID順序
        if ($orderId === OrderEnum::ASC) {
            $query->orderBy('id', 'asc');
        } elseif ($orderId === OrderEnum::DESC) {
            $query->orderBy('id', 'desc');
        }

        $manuscriptList = $query->paginate(config('pagination.admin_record'), page: $page);
        $counts = [
            'total' => $manuscriptList->total(),
            'perPage' => $manuscriptList->perPage(),
            'currentPage' => $manuscriptList->currentPage(),
            'lastPage' => $manuscriptList->lastPage()
        ];

        return [
            'select_public' => $selectPublic,
            'select_license' => $selectLicense,
            'input_keyword' => $inputKeyword,
            'select_search' => $selectSearch,
            'select_thumbnail' => $selectThumbnail,
            'select_pdf' => $selectPdf,
            'order_name' => $orderName,
            'order_id' => $orderId,
            'manuscript_list' => $manuscriptList,
            'page' => $page,
            'counts' => $counts
        ];
    }
}
