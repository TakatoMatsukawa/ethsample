<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Models\Manuscript;

/**
 * 古文書登録画面表示時の表示データに関する処理を行う
 */
class ManuscriptAddTifDomain
{
    /**
     * 古文書編集画面の編集対象データを返却する
     *
     * @param Manuscript $manuscript
     * @return array{
     *     manuscript: Manuscript,
     * }
     */
    public function __invoke(Manuscript $manuscript): array
    {
        return [
            'manuscript' => $manuscript,
        ];
    }
}
