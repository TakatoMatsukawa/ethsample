<?php

namespace App\Observers;

use App\Models\Manuscript;

/**
 * 古文書テーブル作成時、更新時のモデルの処理に付帯して行う処理の実装。
 */
class ManuscriptObserver
{
    /**
     * 作成時処理
     *
     * @param Manuscript $manuscript
     * @return void
     */
    public function created(Manuscript $manuscript): void
    {
        // unique_id自動追加処理
        if (is_null($manuscript->unique_id)) {
            $manuscript->unique_id = config('iiif.system_code.manuscript') . \Str::padLeft($manuscript->id, 6, '0');
            $manuscript->save();
        }
    }
}
