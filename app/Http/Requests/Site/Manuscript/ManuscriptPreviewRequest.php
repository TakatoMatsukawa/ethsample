<?php

namespace App\Http\Requests\Site\Manuscript;

use App\Models\Manuscript;
use App\Http\Requests\Request;
use App\Http\Requests\RequestField;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * 古文書の一覧リクエストを処理する
 *
 * @property LicenseEnum select_license
 */
class ManuscriptPreviewRequest extends Request
{
    /**
     * フォームの各フィールドをチェックする
     *
     * @return array|RequestField[]
     */
    public function fields(): array
    {
        $fields = [];
        return $fields;
    }

    /**
     * 詳細情報を取得する
     *
     * @return Manuscript
     */
    protected function getFormManuscript(): Manuscript
    {
        try {
            $manuscript_id = Crypt::decryptString($this->query('id'));
        } catch (DecryptException) {
            abort('404');
        }
        $manuscript = Manuscript::FindOrFail($manuscript_id);

        return $manuscript;
    }
}
