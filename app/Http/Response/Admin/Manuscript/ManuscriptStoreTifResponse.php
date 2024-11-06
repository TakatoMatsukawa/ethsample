<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use Illuminate\Http\RedirectResponse;

/**
 * 古文書の登録レスポンスを生成する
 */
class ManuscriptStoreTifResponse extends Response
{
    /**
     * 古文書の一覧画面にリダイレクトし、登録済みメッセージを表示する
     *
     * @param string $data
     * @return RedirectResponse
     */
    public function response($data): RedirectResponse
    {
        if ($data === '') {
            // 言語切り替え対応
            if (app()->getLocale() === 'en') {
                $message = 'IIIF image creation completed.';
            } elseif (app()->getLocale() === 'am') {
                $message = 'IIIF ምስል መፍጠር ተጠናቀቀ።';
            } elseif (app()->getLocale() === 'ja') {
                $message = 'IIIF画像の作成が完了しました';
            }
            return to_route('manuscript.manuscript_list')->with(['status' => 'success', 'message' => $message]);
        } else {
            // 言語切り替え対応
            if (app()->getLocale() === 'en') {
                $message = 'Failed to create IIIF image.';
            } elseif (app()->getLocale() === 'am') {
                $message = 'IIIF ምስል መፍጠር አልተሳካም።';
            } elseif (app()->getLocale() === 'ja') {
                $message = 'IIIF画像の作成に失敗しました';
            }
            return to_route('manuscript.manuscript_list')->with(['status' => 'danger', 'message' => $message]);
        }
    }
}
