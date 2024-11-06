<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Models\Manuscript;

/**
 * 古文書のデータ削除時に関する処理を行う
 */
class ManuscriptDeleteDomain
{
    /**
     * 古文書テーブルから指定されたデータを削除し、ページ番号を返却する
     *
     * @param Manuscript $manuscript
     * @param int $page
     * @return array{
     *     page: int
     * }
     */
    public function __invoke(Manuscript $manuscript, int $page): array
    {
        $manuscript->update(['deleted_at' => now()]);

        return [
            'page' => $page,
        ];
    }
}
