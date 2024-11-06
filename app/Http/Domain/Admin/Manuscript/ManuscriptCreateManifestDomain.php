<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Models\Manuscript;
use App\Enums\IiifEnum;
use App\Enums\LicenseEnum;
use Illuminate\Support\Facades\Storage;
use App\Libraries\CreateIIIFManifestJson;

/**
 * 古文書のIIIFマニフェスト作成時に関する処理を行う
 */
class ManuscriptCreateManifestDomain
{
    /**
     * 古文書テーブルから指定されたデータでIIIFマニフェストを作成し、ページ番号等を返却する
     *
     * @param Manuscript $manuscript
     * @param int $page
     * @return array{
     *     method: string
     *     error: string
     *     page: int
     * }
     */
    public function __invoke(Manuscript $manuscript, int $page): array
    {
        $createIIIFManifestJson = new CreateIIIFManifestJson();

        $iiifCode = $manuscript->unique_id;

        // IIIFの一覧と数を取得
        $iiifimages = Storage::disk('iiifimages_directory')->allFiles($iiifCode);
        $page = count($iiifimages);

        // エラー用変数、メソッド判定用変数作成
        $error = '';
        $method = '';

        // IIIFがあれば処理
        if ($page !== 0) {
            if ($manuscript->iiif_flg === IiifEnum::NONE) {
                // iiifのフラグを変更
                $manuscript->iiif_flg = 1;
                $manuscript->update();

                // 作成か更新か
                $method = 'create';
            } else {
                // 作成か更新か
                $method = 'update';
            }

            // 環境変数読み込み
            $frontURL = config('app.url');

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
        } else {
            // 言語切り替え対応
            if (app()->getLocale() === 'en') {
                $error = 'IIIF image does not exist.';
            } elseif (app()->getLocale() === 'am') {
                $error = 'IIIF ምስል የለም።';
            } elseif (app()->getLocale() === 'ja') {
                $error = 'IIIF画像が存在しません。';
            }
        }

        return [
            'method' => $method,
            'error' => $error,
            'page' => $page,
        ];
    }
}
