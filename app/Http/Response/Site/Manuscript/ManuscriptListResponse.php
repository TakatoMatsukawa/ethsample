<?php

namespace App\Http\Response\Site\Manuscript;

use App\Enums\IiifEnum;
use App\Enums\LicenseEnum;
use App\Enums\SelectSearchEnum;
use App\Http\Response\Site\PaginationTrait;
use App\Http\Response\Response;
use App\Models\Manuscript;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Response as InertiaResponse;

/**
 * 古文書の一覧画面のレスポンスを生成する
 */
class ManuscriptListResponse extends Response
{
    use PaginationTrait;

    /** @var LicenseEnum ライセンス */
    private LicenseEnum $selectLicense;

    /** @var string キーワード */
    private string $inputKeyword;

    /** @var SelectSearchEnum キーワード検索条件 */
    private SelectSearchEnum $selectSearch;

    private LengthAwarePaginator $manuscriptList;

    private int $page;

    private array $counts;

    /**
     * 古文書の一覧の表示データを処理し、viewに渡す
     *
     * @param array{
     *     select_license: LicenseEnum,
     *     input_keyword: string,
     *     select_search: SelectSearchEnum,
     *     manuscript_list: LengthAwarePaginator,
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
        $this->selectLicense = $data['select_license'];
        $this->inputKeyword = $data['input_keyword'];
        $this->selectSearch = $data['select_search'];
        $this->manuscriptList = $data['manuscript_list'];
        $this->page = $data['page'];
        $this->counts = $data['counts'];

        return $this->render('Site/Manuscript/Index');
    }

    /**
     * 初期選択値を設定する
     *
     * @return array
     */
    protected function getFormDefaultValues(): array
    {
        return [
            'select_license' => LicenseEnum::ALL->value,
            'input_keyword' => null,
            'select_search' => SelectSearchEnum::AND->value,
        ];
    }

    /**
     * ライセンスを取得する
     *
     * @return string
     */
    protected function getFormSelectLicense(): string
    {
        return $this->selectLicense->value;
    }

    /**
     * ライセンスプルダウン用のリストを取得する
     *
     * @return array
     */
    protected function getFormLicenseList(): array
    {
        return collect(LicenseEnum::cases())->map(fn(LicenseEnum $e) => ['key' => $e->value, 'value' => $e->label()])->toArray();
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
     * 古文書の一覧画面に表示するデータを生成する
     *
     * @return object
     */
    protected function getFormManuscriptList(): object
    {
        $no = ($this->manuscriptList->currentPage() - 1) * config('pagination.site_record') + 1;
        return $this->manuscriptList->map(function (Manuscript $manuscript, $idx) use ($no) {
            // サムネイル
            $thumbnail = null;
            if ($manuscript->thumbnail_file_name) {
                $thumbnail = \Storage::url($manuscript->thumbnailFilePath());
            }

            $iiif = null;
            if ($manuscript->iiif_flg === IiifEnum::EXIST) {
                $iiif = config('iiif.iiif_url') . $manuscript->unique_id;
            }

            return [
                'id' => $manuscript->id, // ID
                'no' => $idx + $no, // No.
                'name' => $manuscript->name, // 資料名
                'writer' => $manuscript->writer, //作者名
                'thumbnail' => $thumbnail, // サムネイル
                'iiif' => $iiif, // iiifURL
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
        $this->manuscriptList->appends([
            'select_license' => $this->getFormSelectLicense(),
            'input_keyword' => $this->getFormInputKeyword(),
            'select_search' => $this->getFormSelectSearch(),
        ]);
        return $this->pageLinks($this->manuscriptList);
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
            $listName = 'Manuscript List';
        } elseif (app()->getLocale() === 'am') {
            $listName = 'የጥንት ሰነዶች ዝርዝር';
        } elseif (app()->getLocale() === 'ja') {
            $listName = '古文書一覧';
        }
        $breadcrumb = [['name' => $topName, 'link' => '/'], ['name' => $listName, 'link' => '']];
        return $breadcrumb;
    }
}
