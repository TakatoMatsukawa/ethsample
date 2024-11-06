<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Enums\PublicEnum;
use App\Models\Manuscript;

/**
 * 古文書の公開状態を変更した場合の処理を行う
 */
class ManuscriptTogglePublicDomain
{
    /**
     * 古文書テーブルの公開状態を更新し、ページ番号を返却する
     *
     * @param Manuscript $manuscript
     * @param int $page
     * @return array{
     *     page: int
     * }
     */
    public function __invoke(Manuscript $manuscript, int $page): array
    {
        $manuscript->public_flg = $manuscript->public_flg === PublicEnum::PUBLIC
            ? PublicEnum::PRIVATE
            : PublicEnum::PUBLIC;
        $manuscript->update();

        return [
            'page' => $page,
        ];
    }
}
