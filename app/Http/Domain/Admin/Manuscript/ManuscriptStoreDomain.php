<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\PublicEnum;
use App\Models\Manuscript;
use App\Models\Pdf;
use Illuminate\Http\UploadedFile;

/**
 * 古文書追加時のデータ登録に関する処理を行う
 */
class ManuscriptStoreDomain
{
    /**
     * 古文書テーブルにデータ登録し、登録したデータを返却する
     *
     * @param PublicEnum $isPublic
     * @param LicenseEnum $selectLicense
     * @param string $inputName
     * @param string $inputWriter
     * @param string $inputEra
     * @param string $inputDescription
     * @param UploadedFile|null $inputFileThumbnail
     * @param array $inputPdfs
     * @return array{
     *     manuscript: Manuscript
     * }
     */
    public function __invoke(
        PublicEnum $isPublic,
        LicenseEnum $selectLicense,
        string $inputName,
        string $inputWriter,
        string $inputEra,
        string $inputDescription,
        ?UploadedFile $inputFileThumbnail,
        array $inputPdfs
    ): array {
        $manuscript = Manuscript::create([
            'name' => $inputName,
            'license' => $selectLicense,
            'writer' => $inputWriter,
            'era' => $inputEra,
            'description' => $inputDescription,
            'public_flg' => $isPublic,
        ]);

        $unique_id = $manuscript->unique_id;

        $thumbnailName = \Str::uuid()->toString() . '.' . $inputFileThumbnail?->getClientOriginalExtension();
        $thumbnailOriginalName = $inputFileThumbnail?->getClientOriginalName();
        $manuscript->update([
            'thumbnail_original_name' => $inputFileThumbnail ? $thumbnailOriginalName : '',
            'thumbnail_file_name' => $inputFileThumbnail ? $thumbnailName : '',
        ]);
        $inputFileThumbnail?->storeAs(dirname($manuscript->thumbnailFilePath()), $thumbnailName);

        $insertPdfs = [];
        foreach ($inputPdfs as $key => $inputPdf) {
            $pdfName = \Str::uuid()->toString() . '.' . $inputPdf?->getClientOriginalExtension();
            $pdf_path = 'manuscript/' . 'pdf/' . $manuscript->id . '/' . $pdfName;
            $pdfOriginalName = $inputPdf?->getClientOriginalName();
            $order = $key + 1;
            $insertPdfData = [
                'unique_id' => $unique_id,
                'order' => $order,
                'original_file_name' => $pdfOriginalName,
                'file_name' => $pdfName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            array_push($insertPdfs, $insertPdfData);
            $inputPdf->storeAs($pdf_path);
        }
        Pdf::insert($insertPdfs);

        return [
            'manuscript' => $manuscript,
        ];
    }
}
