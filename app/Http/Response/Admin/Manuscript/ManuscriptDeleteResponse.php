<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use Illuminate\Http\RedirectResponse;

/**
 * 古文書の削除レスポンスを生成する
 */
class ManuscriptDeleteResponse extends Response
{
    /**
     * 古文書の一覧画面にリダイレクトし、削除済みメッセージを表示する
     *
     * @param array{
     *     page: int
     * } $data
     * @return RedirectResponse
     */
    public function response(array $data): RedirectResponse
    {
        // 言語切り替え対応
        if (app()->getLocale() === 'en') {
            $message = 'Deletion completed.';
        } elseif (app()->getLocale() === 'am') {
            $message = 'ስረዛ ተጠናቅቋል።';
        } elseif (app()->getLocale() === 'ja') {
            $message = '削除が完了しました';
        }
        return to_route('manuscript.manuscript_list', ['page' => $data['page']])
            ->withInput()
            ->with(['status' => 'danger', 'message' => $message]);
    }
}
