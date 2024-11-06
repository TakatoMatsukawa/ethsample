<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use App\Models\Manuscript;
use Illuminate\Http\UploadedFile;
use Storage;

/**
 * 古文書の編集画面レスポンスを生成する
 */
class ManuscriptAddTifResponse extends Response
{
    private Manuscript $manuscript;

    /**
     * 古文書の編集の表示データを処理し、viewに渡す
     *
     * @param array{
     *     manuscript: Manuscript
     *     pdfs: Collection,
     * } $data
     * @return \Inertia\Response
     */
    public function response(array $data): \Inertia\Response
    {
        $this->manuscript = $data['manuscript'];

        if ($this->manuscript->deleted_at !== null) {
            abort('404');
        }
        return $this->render('Admin/Manuscript/AddTif');
    }

    /**
     * IDを取得する
     *
     * @return string
     */
    protected function getFormId(): string
    {
        return old('id', $this->manuscript->id);
    }

    /**
     * サムネイルファイルを取得する
     *
     * @return UploadedFile|null
     */
    protected function getFormInputImage(): ?UploadedFile
    {
        return old('input_image');
    }
}
