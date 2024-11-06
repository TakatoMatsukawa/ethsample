<?php

namespace App\Http\Domain\Admin\Manuscript;

use Illuminate\Http\UploadedFile;

/**
 * 古文書登録画面表示時の表示データに関する処理を行う
 */
class ManuscriptAddDomain
{
    /**
     * 古文書登録画面で表示するデータを準備する
     *
     * @param string $selectLicense
     * @param string $inputName
     * @param string $inputWriter
     * @param string $inputEra
     * @param string $inputDescription
     * @param UploadedFile|null $inputFileThumbnail
     * @param array $inputPdfs
     * @return array{
     *     select_license: string,
     *     input_name: string,
     *     input_writer: string,
     *     input_era: string,
     *     input_description: string,
     *     input_file_thumbnail: UploadedFile|null,
     *     input_pdfs: array,
     * }
     */
    public function __invoke(string $selectLicense, string $inputName, string $inputWriter, string $inputEra, string $inputDescription, ?UploadedFile $inputFileThumbnail, array $inputPdfs = []): array
    {
        return [
            'select_license' => $selectLicense,
            'input_name' => $inputName,
            'input_writer' => $inputWriter,
            'input_era' => $inputEra,
            'input_description' => $inputDescription,
            'input_file_thumbnail' => $inputFileThumbnail,
            'input_pdfs' => $inputPdfs,
        ];
    }
}
