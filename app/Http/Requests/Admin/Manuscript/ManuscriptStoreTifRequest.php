<?php

namespace App\Http\Requests\Admin\Manuscript;

use App\Http\Requests\Request;
use App\Http\Requests\RequestField;
use Illuminate\Http\UploadedFile;

/**
 * 古文書の追加リクエストを処理する
 *
 * @property UploadedFile|null input_image
 */
class ManuscriptStoreTifRequest extends Request
{
    /**
     * フォームの各フィールドをチェックする
     *
     * @return array|RequestField[]
     */
    protected function fields(): array
    {
        $array = [
            // サムネイル
            RequestField::instance('input_image')
                ->ruleRequired()
                ->ruleFile()
                ->ruleMimes(['jpg', 'jpeg']),
        ];
        return $array;
    }

    /**
     * サムネイルファイルを取得する
     *
     * @return UploadedFile|null
     */
    protected function getFormInputImage(): ?UploadedFile
    {
        return $this->file('input_image');
    }
}
