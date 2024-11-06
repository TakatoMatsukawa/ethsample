<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Models\Manuscript;

/**
 * 古文書のデータ編集時の表示データに関する処理を行う
 */
class ManuscriptEditDomain
{
    /**
     * 古文書編集画面の編集対象データを返却する
     *
     * @param Manuscript $manuscript
     * @return array{
     *     manuscript: Manuscript,
     *     pdfs: Collection
     * }
     */
    public function __invoke(Manuscript $manuscript): array
    {
        $pdfs = $manuscript->pdfs->sortBy('order')->values();
        $addNamePdfs = $pdfs->map(function ($item) use ($manuscript) {
            $item->url = \Storage::url($manuscript->pdfFilePath($item->order));
            return $item;
        });
        return [
            'manuscript' => $manuscript,
            'pdfs' => $addNamePdfs
        ];
    }
}
