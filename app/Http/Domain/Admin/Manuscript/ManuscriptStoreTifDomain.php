<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Models\Manuscript;
use Illuminate\Http\UploadedFile;

/**
 * 古文書編集時のデータ更新に関する処理を行う
 */
class ManuscriptStoreTifDomain
{
    /**
     * 古文書テーブルのデータを更新する
     *
     * @param UploadedFile|null $inputImage
     * @return string
     */
    public function __invoke(Manuscript $manuscript, ?UploadedFile $inputImage): string
    {
        $pythonPath = base_path('app/Python/');
        $command = 'export LD_LIBRARY_PATH=/usr/local/lib64 && python3 ' . $pythonPath . 'jpeg2tif.py ' . $inputImage . ' ' . config('app.iiif_images_directory') . ' ' . $manuscript->unique_id;
        exec($command, $outputs);
        if (isset($outputs[0]) && $outputs[0] == 'finish') {
            $error = '';
        } else {
            $error = implode(', ', $outputs);
            if ($error == '') {
                $error = 'error';
            }
        }
        return $error;
    }
}
