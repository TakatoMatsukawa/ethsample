<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Models\Manuscript;
use App\Enums\IiifEnum;
use App\Enums\LicenseEnum;
use App\Enums\OnOffEnum;
use App\Enums\PublicEnum;
use App\Enums\SelectSearchEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Libraries\CreateIIIFManifestJson;

/**
 * 古文書の全データIIIFマニフェスト作成時に関する処理を行う
 */
class ManuscriptCreateAllManifestDomain
{
    /**
     * IIIFを作成し結果を返却する
     *
     * @param PublicEnum $selectPublic
     * @param LicenseEnum $selectLicense
     * @param string $inputKeyword
     * @param SelectSearchEnum $selectSearch
     * @param OnOffEnum $selectThumbnail
     * @param OnOffEnum $selectPdf
     * @return array{
     *  $result array{
     *      iiifCode: int
     *      name: string
     *      method: string
     *      error: string
     *  }
     * } $results
     */
    public function __invoke(PublicEnum $selectPublic, LicenseEnum $selectLicense, string $inputKeyword, SelectSearchEnum $selectSearch, OnOffEnum $selectThumbnail, OnOffEnum $selectPdf): array
    {
        // 検索
        $query = Manuscript::select('manuscripts.*')->where('deleted_at', '=', null);

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

        $manuscriptList = $query->get();

        $createIIIFManifestJson = new CreateIIIFManifestJson();

        // 環境変数読み込み
        $frontURL = config('app.url');

        // 結果を格納する配列定義
        $results = [];

        foreach ($manuscriptList as $manuscript) {
            // 結果を格納する配列定義
            $result = [];

            $iiifCode = $manuscript->unique_id;

            $result['iiifCode'] = $iiifCode;
            $result['name'] = $manuscript->name;

            // IIIFの一覧と数を取得
            $iiifimages = Storage::disk('iiifimages_directory')->allFiles($iiifCode);
            $page = count($iiifimages);

            // IIIFがあれば処理
            if ($page !== 0) {
                if ($manuscript->iiif_flg === IiifEnum::NONE) {
                    // iiifのフラグを変更
                    $manuscript->iiif_flg = 1;
                    $manuscript->update();

                    // 作成か更新か
                    $result['method'] = 'create';
                } else {
                    // 作成か更新か
                    $result['method'] = 'update';
                }

                // json挿入データを変数に挿入
                $label = $manuscript->name;

                $metadata = [
                    //  必要な項目を配列で入れる
                    [
                        'label' => 'Title',
                        'value' => $manuscript->name,
                    ],
                ];

                $license = $manuscript->license?->label() . '(' . $frontURL . '/info/)';

                // jsonデータ作成
                $createIIIFManifestJson->CreateIIIFManifestJson($iiifCode, $iiifimages, $page, $frontURL, $label, $metadata, $license);
                $result['error'] = '';
            } else {
                // 言語切り替え対応

                if (app()->getLocale() === 'en') {
                    $result['error'] = 'IIIF image does not exist.';
                } elseif (app()->getLocale() === 'am') {
                    $result['error'] = 'IIIF ምስል የለም።';
                } elseif (app()->getLocale() === 'ja') {
                    $result['error'] = 'IIIF画像が存在しません。';
                }
                $result['method'] = '';
            }
            array_push($results, $result);
        }

        return $results;
    }
}
