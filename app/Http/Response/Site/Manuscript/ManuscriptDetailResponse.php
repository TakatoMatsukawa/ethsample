<?php

namespace App\Http\Response\Site\Manuscript;

use App\Enums\IiifEnum;
use App\Enums\PublicEnum;
use App\Http\Response\Response;
use App\Libraries\GetFileSize;
use App\Models\Manuscript;

/**
 * 古文書の詳細画面レスポンスを生成する
 */
class ManuscriptDetailResponse extends Response
{
    private Manuscript $manuscript;

    /**
     * 古文書の表示データを処理し、viewに渡す
     *
     * @param array{
     *     manuscript: Manuscript
     * } $data
     * @return \Inertia\Response
     */
    public function response(array $data): \Inertia\Response
    {
        $this->manuscript = $data['manuscript'];
        return $this->render('Site/Manuscript/Detail');
    }

    /**
     * 古文書の詳細画面に表示するデータを生成する
     *
     * @return array
     */
    protected function getFormManuscript(): array
    {
        // 表示可能か
        if ($this->manuscript->public_flg !== PublicEnum::PUBLIC || $this->manuscript->deleted_at !== null) {
            abort('404');
        }

        // ライセンス
        $license = $this->manuscript->license;

        // IIIFマニフェスト
        $iiifURL = null;
        $iiifManifest = null;
        if ($this->manuscript->iiif_flg === IiifEnum::EXIST) {
            $iiifURL = config('iiif.iiif_url') . "{$this->manuscript->unique_id}";
            $iiifManifest = config('app.url') . '/' . $this->manuscript->unique_id . '/manifest';
        }

        // サムネイル
        $thumbnail = null;
        if ($this->manuscript->thumbnail_file_name) {
            $thumbnail = \Storage::url($this->manuscript->thumbnailFilePath());
        }

        $license = [
            'num' => $this->manuscript->license,
            'label' => $this->manuscript->license?->label(),
            'image' => $this->manuscript->license?->image(),
        ];

        $getFileSize = new GetFileSize();

        // pdfFiles
        $pdfFiles = $this->manuscript->pdfs
            ->sortBy('order')
            ->values()
            ->map(function ($item) use ($getFileSize) {
                $file_path = $this->manuscript->pdfFilePath($item->order);
                $item->file = \Storage::url($file_path);
                $item->size = $getFileSize->getFileSize($file_path);
                return $item;
            });

        return [
            'id' => $this->manuscript->id,
            'license' => $license,

            'name' => $this->manuscript->name,
            'era' => $this->manuscript->era,
            'writer' => $this->manuscript->writer,
            'description' => $this->manuscript->description,

            'iiifURL' => $iiifURL,
            'iiifManifest' => $iiifManifest,
            'thumbnail' => $thumbnail,
            'pdfFiles' => $pdfFiles,
        ];
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
        $breadcrumb = [['name' => $topName, 'link' => '/'], ['name' => $listName, 'link' => '/manuscript'], ['name' => $this->manuscript->name, 'link' => '']];
        return $breadcrumb;
    }
}
