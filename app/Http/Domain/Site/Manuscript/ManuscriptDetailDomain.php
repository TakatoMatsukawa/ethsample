<?php

namespace App\Http\Domain\Site\Manuscript;

use App\Models\Manuscript;

/**
 * 古文書のデータ詳細画面の表示データに関する処理を行う
 */
class ManuscriptDetailDomain
{
    /**
     * 古文書詳細画面の対象データを返却する
     *
     * @param Manuscript $manuscript
     * @return array{
     *     manuscript: Manuscript
     * }
     */
    public function __invoke(Manuscript $manuscript): array
    {
        return [
            'manuscript' => $manuscript,
        ];
    }
}
