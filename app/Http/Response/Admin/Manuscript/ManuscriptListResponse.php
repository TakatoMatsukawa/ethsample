<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Enums\IiifEnum;
use App\Enums\LicenseEnum;
use App\Enums\OnOffEnum;
use App\Enums\PublicEnum;
use App\Enums\SelectSearchEnum;
use App\Enums\OrderEnum;
use App\Http\Response\Admin\PaginationTrait;
use App\Http\Response\Response;
use App\Models\Manuscript;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Crypt;
use Inertia\Response as InertiaResponse;

/**
 * 古文書の一覧画面のレスポンスを生成する
 */
class ManuscriptListResponse extends Response
{
    use PaginationTrait;

    /** @var PublicEnum 公開状態 */
    private PublicEnum $selectPublic;

    /** @var LicenseEnum ライセンス */
    private LicenseEnum $selectLicense;

    /** @var string キーワード */
    private string $inputKeyword;

    /** @var SelectSearchEnum キーワード検索条件 */
    private SelectSearchEnum $selectSearch;

    /** @var OnOffEnum サムネイル */
    private OnOffEnum $selectThumbnail;

    /** @var OnOffEnum PDF */
    private OnOffEnum $selectPdf;

    /** @var OrderEnum 資料名順序 */
    private OrderEnum $orderName;

    /** @var OrderEnum ID順序 */
    private OrderEnum $orderId;

    private LengthAwarePaginator $manuscriptList;

    private int $page;

    private array $counts;

    /**
     * 古文書の一覧の表示データを処理し、viewに渡す
     *
     * @param array{
     *     select_public: PublicEnum,
     *     select_license: LicenseEnum,
     *     input_keyword: string,
     *     select_search: SelectSearchEnum,
     *     select_thumbnail: OnOffEnum,
     *     select_pdf: OnOffEnum,
     *     order_name: OrderEnum,
     *     order_id: OrderEnum,
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
        $this->selectPublic = $data['select_public'];
        $this->selectLicense = $data['select_license'];
        $this->inputKeyword = $data['input_keyword'];
        $this->selectSearch = $data['select_search'];
        $this->selectThumbnail = $data['select_thumbnail'];
        $this->selectPdf = $data['select_pdf'];
        $this->orderName = $data['order_name'];
        $this->orderId = $data['order_id'];
        $this->manuscriptList = $data['manuscript_list'];
        $this->page = $data['page'];
        $this->counts = $data['counts'];

        return $this->render('Admin/Manuscript/Index');
    }

    /**
     * 初期選択値を設定する
     *
     * @return array
     */
    protected function getFormDefaultValues(): array
    {
        return [
            'select_public' => PublicEnum::ALL->value,
            'select_license' => LicenseEnum::ALL->value,
            'select_class' => 'all',
            'input_keyword' => null,
            'select_search' => SelectSearchEnum::AND->value,
            'select_thumbnail' => OnOffEnum::ALL->value,
            'select_pdf' => OnOffEnum::ALL->value,
            'order_name' => OrderEnum::ASC->value,
            'order_id' => OrderEnum::NONE->value,
        ];
    }

    /**
     * 公開状態を取得する
     *
     * @return string
     */
    protected function getFormSelectPublic(): string
    {
        return $this->selectPublic->value;
    }

    /**
     * 公開状態プルダウン用のリストを取得する
     *
     * @return array
     */
    protected function getFormPublicList(): array
    {
        return collect(PublicEnum::cases())->map(fn(PublicEnum $e) => ['key' => $e->value, 'value' => $e->label()])->toArray();
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
     * サムネイルを取得する
     *
     * @return string
     */
    protected function getFormSelectThumbnail(): string
    {
        return $this->selectThumbnail->value;
    }

    /**
     * サムネイルプルダウン用のリストを取得する
     *
     * @return array
     */
    protected function getFormSelectThumbnailList(): array
    {
        return collect(OnOffEnum::cases())->map(fn(OnOffEnum $e) => ['key' => $e->value, 'value' => $e->label()])->toArray();
    }

    /**
     * PDFを取得する
     *
     * @return string
     */
    protected function getFormSelectPdf(): string
    {
        return $this->selectPdf->value;
    }

    /**
     * PDFプルダウン用のリストを取得する
     *
     * @return array
     */
    protected function getFormSelectPdfList(): array
    {
        return collect(OnOffEnum::cases())->map(fn(OnOffEnum $e) => ['key' => $e->value, 'value' => $e->label()])->toArray();
    }

    /**
     * 資料名順序を取得する
     *
     * @return string
     */
    protected function getFormOrderName(): string
    {
        return $this->orderName->value;
    }

    /**
     * ID順序を取得する
     *
     * @return string
     */
    protected function getFormOrderId(): string
    {
        return $this->orderId->value;
    }

    /**
     * 古文書の一覧画面に表示するデータを生成する
     *
     * @return object
     */
    protected function getFormManuscriptList(): object
    {
        $no = ($this->manuscriptList->currentPage() - 1) * config('pagination.admin_record') + 1;
        return $this->manuscriptList->map(function (Manuscript $manuscript, $idx) use ($no) {
            // 公開状態
            $isPublic = $manuscript->public_flg == PublicEnum::PUBLIC;

            // サムネイル
            $thumbnail = null;
            if ($manuscript->thumbnail_file_name) {
                $thumbnail = \Storage::url($manuscript->thumbnailFilePath());
            }

            // PDF有無
            // 言語切り替え対応
            if (app()->getLocale() === 'en') {
                $pdfExist = $manuscript->pdfs->isEmpty() ? 'No' : 'Yes';
            } elseif (app()->getLocale() === 'am') {
                $pdfExist = $manuscript->pdfs->isEmpty() ? 'አዎ' : 'አይደለም';
            } elseif (app()->getLocale() === 'ja') {
                $pdfExist = $manuscript->pdfs->isEmpty() ? '無し' : '有り';
            }

            $iiif = null;
            if ($manuscript->iiif_flg == IiifEnum::EXIST) {
                $iiif = config('iiif.iiif_url') . $manuscript->unique_id . '?auth=true';
            }

            // 更新日時
            $updatedAt = $manuscript->updated_at->format('y/m/d H:i');

            // プレビューURL
            $preview = config('app.url') . '/manuscript/preview?id=' . Crypt::encryptString($manuscript->id);

            return [
                'id' => $manuscript->id, // ID
                'no' => $idx + $no, // No.
                'isPublic' => $isPublic, // 公開状態
                'license' => $manuscript->license?->label(), // ライセンス
                'name' => $manuscript->name, // 資料名
                'thumbnail' => $thumbnail, // サムネイル
                'pdfExist' => $pdfExist, // PDF有無
                'iiif' => $iiif, // iiifURL
                'uniqueId' => $manuscript->unique_id, // ユニークID
                'updatedAt' => $updatedAt, // 更新日時
                'preview' => $preview, // プレビューURL
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
            'select_public' => $this->getFormSelectPublic(),
            'select_license' => $this->getFormSelectLicense(),
            'input_keyword' => $this->getFormInputKeyword(),
            'select_search' => $this->getFormSelectSearch(),
            'select_thumbnail' => $this->getFormSelectThumbnail(),
            'select_pdf' => $this->getFormSelectPdf(),
            'order_name' => $this->getFormOrderName(),
            'order_id' => $this->getFormOrderId(),
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
}
