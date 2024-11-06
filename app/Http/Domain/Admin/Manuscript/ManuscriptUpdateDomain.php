<?php

namespace App\Http\Domain\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\PublicEnum;
use App\Enums\StateEnum;
use App\Models\Manuscript;
use App\Models\Pdf;
use Illuminate\Http\UploadedFile;

/**
 * 古文書編集時のデータ更新に関する処理を行う
 */
class ManuscriptUpdateDomain
{
    /**
     * 古文書テーブルのデータを更新する
     *
     * @param Manuscript $manuscript
     * @param PublicEnum $isPublic
     * @param LicenseEnum $selectLicense
     * @param string $inputName
     * @param string $inputWriter
     * @param string $inputEra
     * @param string $inputDescription
     * @param UploadedFile|null $inputFileThumbnail
     * @param array $pdfs
     * @param array $pdfFiles
     * @param bool $is_registered_thumbnail
     * @param array $pdfStates
     * @return array{}
     */
    public function __invoke(
        Manuscript $manuscript,
        PublicEnum $isPublic,
        LicenseEnum $selectLicense,
        string $inputName,
        string $inputWriter,
        string $inputEra,
        string $inputDescription,
        ?UploadedFile $inputFileThumbnail,
        array $pdfs,
        array $pdfFiles,
        bool $is_registered_thumbnail,
        array $pdfStates
    ): array {
        $manuscript->name = $inputName;
        $manuscript->license = $selectLicense;
        $manuscript->writer = $inputWriter;
        $manuscript->era = $inputEra;
        $manuscript->description = $inputDescription;
        $manuscript->public_flg = $isPublic;

        if (!$is_registered_thumbnail) {
            $thumbnailName = \Str::uuid()->toString() . '.' . $inputFileThumbnail?->getClientOriginalExtension();
            $thumbnailOriginalName = $inputFileThumbnail?->getClientOriginalName();
            $manuscript->thumbnail_original_name = $inputFileThumbnail ? $thumbnailOriginalName : '';
            $manuscript->thumbnail_file_name = $inputFileThumbnail ? $thumbnailName : '';
            $inputFileThumbnail?->storeAs(dirname($manuscript->thumbnailFilePath()), $thumbnailName);
        }

        $manuscript->save();

        // pdf処理
        $inputPdfs = $pdfs + $pdfFiles;
        ksort($inputPdfs);
        $originalPdfCount = $manuscript->pdfs->count();
        $pdfOrder = 1;
        $deleteAddIndex = 0;
        foreach ($pdfStates as $key => $pdfState) {
            if ($inputPdfs[$key] !== null) {
                switch ($pdfState) {
                    case StateEnum::UNCHANGED->value:
                        // pdfOrderとorderが違うときはorderを更新
                        if ($pdfOrder != $inputPdfs[$key]['order']) {
                            $pdf = $manuscript->pdfs->where('order', '=', $inputPdfs[$key]['order'])->first();
                            $pdf->order = $pdfOrder;
                            $pdf->save();
                        }
                        $pdfOrder++;
                        break;
                    case StateEnum::ADD->value:
                        $pdfInfo = $this->setPdf($manuscript, $inputPdfs[$key]);
                        // もしpdfOrderがoriginalPdfCountを超えたら追加
                        if ($pdfOrder + $deleteAddIndex > $originalPdfCount) {
                            $pdf = Pdf::create([
                                'unique_id' => $manuscript->unique_id,
                                'order' => $pdfOrder,
                                'original_file_name' => $pdfInfo['pdfOriginalName'],
                                'file_name' => $pdfInfo['pdfName'],
                            ]);
                        } else {
                            // こえなければorderを指定して更新
                            $pdf = $manuscript->pdfs->where('order', '=', $pdfOrder)->first();
                            $pdf->original_file_name = $pdfInfo['pdfOriginalName'];
                            $pdf->file_name = $pdfInfo['pdfName'];
                            $pdf->save();
                        }
                        // ファイル保存
                        $inputPdfs[$key]->storeAs($pdfInfo['pdfPath']);
                        $pdfOrder++;
                        break;
                    case StateEnum::MODIFY->value:
                        $pdfInfo = $this->setPdf($manuscript, $inputPdfs[$key]);
                        $pdf = $manuscript->pdfs->where('order', '=', $pdfOrder + $deleteAddIndex)->first();
                        $pdf->order = $pdfOrder;
                        $pdf->original_file_name = $pdfInfo['pdfOriginalName'];
                        $pdf->file_name = $pdfInfo['pdfName'];
                        $pdf->save();
                        // ファイル保存
                        $inputPdfs[$key]->storeAs($pdfInfo['pdfPath']);
                        $pdfOrder++;
                        break;
                }
            } else {
                if ($pdfState == StateEnum::DELETE->value) {
                    $order = $pdfOrder + $deleteAddIndex;
                    // pdf削除
                    $pdf = $manuscript->pdfs->where('order', '=', $order)->first();
                    \Storage::delete($manuscript->pdfFilePath($order));
                    $pdf->delete();
                    $deleteAddIndex++;
                }
            }
        }

        return [];
    }

    protected function setPdf(Manuscript $manuscript, UploadedFile $pdf)
    {
        $pdfName = \Str::uuid()->toString() . '.' . $pdf?->getClientOriginalExtension();
        $pdfPath = 'manuscript/' . 'pdf/' . $manuscript->id . '/' . $pdfName;
        $pdfOriginalName = $pdf?->getClientOriginalName();
        return ['pdfOriginalName' => $pdfOriginalName, 'pdfPath' => $pdfPath, 'pdfName' => $pdfName];
    }
}
