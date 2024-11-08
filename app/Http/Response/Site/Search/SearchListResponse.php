<?php

namespace App\Http\Response\Site\Search;

use App\Enums\IiifEnum;
use App\Enums\LicenseEnum;
use App\Enums\SelectSearchEnum;
use App\Http\Response\Site\PaginationTrait;
use App\Http\Response\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;

/**
 * サンプルの一覧画面のレスポンスを生成する
 */
class SearchListResponse extends Response
{
    use PaginationTrait;

    /** @var string キーワード */
    private string $inputKeyword;

    /** @var SelectSearchEnum キーワード検索条件 */
    private SelectSearchEnum $selectSearch;

    private LengthAwarePaginator $searchList;

    private int $page;

    private array $counts;

    /**
     * 横断検索の一覧の表示データを処理し、viewに渡す
     *
     * @param array{
     *     select_license: LicenseEnum,
     *     input_keyword: string,
     *     select_search: SelectSearchEnum,
     *     search_list: LengthAwarePaginator,
     *     page: int,
     *     counts: array {
     *        total: int,
     *        perPage: int,
     *        currentPage: int,
     *        lastPage: int,
     *     }
     * } $data
     * @return InertiaResponse
     */
    public function response(array $data): InertiaResponse
    {
        $this->inputKeyword = $data['input_keyword'];
        $this->selectSearch = $data['select_search'];
        $this->searchList = $data['search_list'];
        $this->page = $data['page'];
        $this->counts = $data['counts'];

        return $this->render('Site/Search/Index');
    }

    /**
     * 初期選択値を設定する
     *
     * @return array
     */
    protected function getFormDefaultValues(): array
    {
        return [
            'input_keyword' => null,
            'select_search' => SelectSearchEnum::AND->value,
        ];
    }

    /**
     * キーワードを取得する
     *
     * @return string
     */
    protected function getFormInputKeyword(): string
    {
        return $this->inputKeyword;
    }

    /**
     * キーワード検索条件を取得する
     *
     * @return string
     */
    protected function getFormSelectSearch(): string
    {
        return $this->selectSearch->value;
    }

    /**
     * キーワード検索条件プルダウン用のリストを取得する
     *
     * @return array
     */
    protected function getFormSelectSearchList(): array
    {
        return collect(SelectSearchEnum::cases())->map(fn(SelectSearchEnum $s) => ['key' => $s->value, 'value' => $s->label()])->toArray();
    }

    /**
     * サンプルの一覧画面に表示するデータを生成する
     *
     * @return object
     */
    protected function getFormSearchList(): object
    {
        $no = ($this->searchList->currentPage() - 1) * config('pagination.site_record') + 1;
        return $this->searchList->map(function ($search, $idx) use ($no) {
            // テーブル名
            $table = $search->getTable();

            // 詳細route
            $model = Str::singular($table);
            $detailRoute = $model . '.detail';

            // サムネイル
            $thumbnail = null;
            if ($search->thumbnail_file_name) {
                $thumbnail = \Storage::url($search->thumbnailFilePath());
            }

            $iiif = null;
            if ($search->iiif_flg === IiifEnum::EXIST) {
                $iiif = config('iiif.iiif_url') . "?uid={$search->unique_id}";
            }

            // コレクション名（各Modelで定義）
            $collection = $search->collectionName();

            return [
                'id' => $search->id, // ID
                'no' => $idx + $no, // No.
                'license' => $search->license?->label(), // ライセンス
                'name' => $search->name, // 資料名
                'thumbnail' => $thumbnail, // サムネイル
                'iiif' => $iiif, // iiifURL
                'collection' => $collection, // コレクション名
                'detail' => $detailRoute, // 詳細画面route
            ];
        });
    }

    /**
     * リンクを取得する
     *
     * @return array
     */
    protected function getFormLinks(): array
    {
        $this->searchList->appends([
            'input_keyword' => $this->getFormInputKeyword(),
            'select_search' => $this->getFormSelectSearch(),
        ]);
        return $this->pageLinks($this->searchList);
    }

    /**
     * ページ番号を取得する
     *
     * @return int
     */
    protected function getFormPage(): int
    {
        return $this->page;
    }

    /**
     * 件数の配列を取得する
     *
     * @return array
     */
    protected function getFormCounts(): array
    {
        return $this->counts;
    }

    /**
     * パンくずリストの配列を取得する
     *
     * @return array
     */
    protected function getFormBreadcrumb(): array
    {
        // 言語切り替え対応
        $topName = '';
        if (app()->getLocale() === 'en') {
            $topName = 'TOP Page';
        } elseif (app()->getLocale() === 'am') {
            $topName = 'የላይኛው ገጽ';
        } elseif (app()->getLocale() === 'ja') {
            $topName = 'TOPページ';
        }
        $listName = '';
        if (app()->getLocale() === 'en') {
            $listName = 'Cross Search List';
        } elseif (app()->getLocale() === 'am') {
            $listName = 'ተሻጋሪ የፍለጋ ዝርዝር';
        } elseif (app()->getLocale() === 'ja') {
            $listName = '横断検索一覧';
        }
        $breadcrumb = [['name' => $topName, 'link' => '/'], ['name' => $listName, 'link' => '']];
        return $breadcrumb;
    }
}
