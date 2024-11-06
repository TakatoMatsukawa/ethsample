<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Http\Response\Response;
use Inertia\Response as InertiaResponse;

/**
 * 古文書のIIIFマニフェスト作成レスポンスを生成する
 */
class ManuscriptCreateAllManifestResponse extends Response
{
    /** @var array  */
    private array $results;

    /**
     * IIIFマニフェスト作成の結果を表示する
     *
     * @param array{
     * } $data
     * @return InertiaResponse
     */
    public function response(array $data): InertiaResponse
    {

        $this->results = $data;

        return $this->render('Admin/Manuscript/Result');
    }

    /**
     * 結果に表示するデータを生成する
     *
     * @return array
     */
    protected function getFormResults(): array
    {

        $updateData = array_filter($this->results, function ($item) {
            return $item['method'] === 'update';
        });
        // 配列のキーを再生成
        $updateData = array_values($updateData);
        $updateDataCount = count($updateData);

        $createData = array_filter($this->results, function ($item) {
            return $item['method'] === 'create';
        });
        // 配列のキーを再生成
        $createData = array_values($createData);
        $createDataCount = count($createData);

        $errorData = array_filter($this->results, function ($item) {
            return $item['error'] !== '';
        });
        // 配列のキーを再生成
        $errorData = array_values($errorData);
        $errorDataCount = count($errorData);

        $count = $updateDataCount + $createDataCount + $errorDataCount;

        return [
            'updateData' => $updateData,
            'updateDataCount' => $updateDataCount,
            'createData' => $createData,
            'createDataCount' => $createDataCount,
            'errorData' => $errorData,
            'errorDataCount' => $errorDataCount,
            'count' => $count,
        ];
    }
}
