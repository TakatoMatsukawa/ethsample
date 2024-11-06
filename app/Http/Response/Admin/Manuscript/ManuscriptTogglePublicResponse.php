<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use Illuminate\Http\RedirectResponse;

/**
 * 古文書の公開状態切替えレスポンスを生成する
 */
class ManuscriptTogglePublicResponse extends Response
{
    /**
     * 古文書の一覧画面にリダイレクトする
     *
     * @param array{
     *     page: int
     * } $data
     * @return RedirectResponse
     */
    public function response(array $data): RedirectResponse
    {
        return to_route('manuscript.manuscript_list', ['page' => $data['page']])
            ->withInput();
    }
}
