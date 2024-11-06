<?php

namespace App\Http\Requests\Admin\Manuscript;

use App\Http\Requests\Request;
use App\Http\Requests\RequestField;

/**
 * 古文書の公開状態更新リクエストを処理する
 *
 * @property int page
 */
class ManuscriptTogglePublicRequest extends Request
{
    /**
     * フォームの各フィールドをチェックする
     *
     * @return array|RequestField[]
     */
    protected function fields(): array
    {
        return [];
    }

    /**
     * ページ番号を取得する
     *
     * @return int
     */
    protected function getFormPage(): int
    {
        if (!is_numeric($this->input('page'))) {
            return 1;
        }
        return $this->integer('page');
    }
}
