<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use Illuminate\Http\RedirectResponse;

/**
 * 古文書の編集画面レスポンスを生成する
 */
class ManuscriptUpdateResponse extends Response
{
    /**
     * 古文書の一覧画面にリダイレクトし、更新済みメッセージを表示する
     *
     * @param array{} $data
     * @return RedirectResponse
     */
    public function response(array $data): RedirectResponse
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            $message = 'Update completed.';
        } elseif (app()->getLocale() === 'am') {
            $message = 'ዝማኔ ተጠናቅቋል።';
        } elseif (app()->getLocale() === 'ja') {
            $message = '変更が完了しました';
        }
        return redirect()
            ->route('manuscript.manuscript_list')
            ->with(['status' => 'success', 'message' => $message]);
    }
}
