<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use Illuminate\Http\RedirectResponse;

/**
 * 古文書のIIIFマニフェスト作成レスポンスを生成する
 */
class ManuscriptCreateManifestResponse extends Response
{
    /**
     * 古文書の一覧画面にリダイレクトし、IIIFマニフェスト作成メッセージを表示する
     *
     * @param array{
     *     method: string
     *     error: string
     *     page: int
     * } $data
     * @return RedirectResponse
     */
    public function response(array $data): RedirectResponse
    {
        // 言語切り替え対応

        if (app()->getLocale() === 'en') {
            $createMessage = 'The IIIF manifest creation has been completed.';
            $updateMessage = 'The IIIF manifest update has been completed.';
        } elseif (app()->getLocale() === 'am') {
            $createMessage = 'የ IIIF አንጸባራቂ ፈጠራ ተጠናቅቋል።';
            $updateMessage = 'የ IIIF አንጸባራቂ ዝማኔ ተጠናቅቋል።';
        } elseif (app()->getLocale() === 'ja') {
            $createMessage = 'IIIFマニフェストの作成が完了しました';
            $updateMessage = 'IIIFマニフェストの更新が完了しました';
        }
        if ($data['error'] === '') {
            if ($data['method'] === 'create') {
                $flashMessage = ['status' => 'success', 'message' => $createMessage];
            } elseif ($data['method'] === 'update') {
                $flashMessage = ['status' => 'success', 'message' => $updateMessage];
            }
        } else {
            $flashMessage = ['status' => 'danger', 'message' => $data['error']];
        }
        return to_route('manuscript.manuscript_list', ['page' => $data['page']])
            ->withInput()
            ->with($flashMessage);
    }
}
