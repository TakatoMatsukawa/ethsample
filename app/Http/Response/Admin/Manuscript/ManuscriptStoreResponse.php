<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use Illuminate\Http\RedirectResponse;

/**
 * 古文書の登録レスポンスを生成する
 */
class ManuscriptStoreResponse extends Response
{
    /**
     * 古文書の一覧画面にリダイレクトし、登録済みメッセージを表示する
     *
     * @param array{} $data
     * @return RedirectResponse
     */
    public function response(array $data): RedirectResponse
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            $message = 'Creation completed.';
        } elseif (app()->getLocale() === 'am') {
            $message = 'ፍጥረት ተጠናቅቋል።';
        } elseif (app()->getLocale() === 'ja') {
            $message = '登録が完了しました';
        }
        return to_route('manuscript.manuscript_list')->with(['status' => 'success', 'message' => $message]);
    }
}
