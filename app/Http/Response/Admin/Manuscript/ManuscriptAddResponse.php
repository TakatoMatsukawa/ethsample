<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Http\Response\Response;
use Illuminate\Http\UploadedFile;

/**
 * 古文書の登録画面のレスポンスを生成する
 */
class ManuscriptAddResponse extends Response
{
    private string $selectLicense;
    private string $inputName;
    private string $inputWriter;
    private string $inputEra;
    private string $inputDescription;
    private ?UploadedFile $inputFileThumbnail;
    private array $inputPdfs;

    /**
     * 古文書の登録の表示データを処理し、viewに渡す
     *
     * @param array{
     *     select_license: string,
     *     input_name: string,
     *     input_writer: string,
     *     input_era: string,
     *     input_description: string,
     *     input_file_thumbnail: UploadedFile|null,
     *     input_pdfs: array,
     * } $data
     * @return \Inertia\Response
     */
    public function response(array $data): \Inertia\Response
    {
        $this->selectLicense = $data['select_license'];
        $this->inputName = $data['input_name'];
        $this->inputWriter = $data['input_writer'];
        $this->inputEra = $data['input_era'];
        $this->inputDescription = $data['input_description'];
        $this->inputFileThumbnail = $data['input_file_thumbnail'];
        $this->inputPdfs = $data['input_pdfs'];
        return $this->render('Admin/Manuscript/Add');
    }

    /**
     * ライセンスを取得する
     *
     * @return string
     */
    protected function getFormSelectLicense(): string
    {
        return $this->selectLicense;
    }

    /**
     * ライセンスプルダウン用のリストを取得する
     *
     * @return array
     */
    protected function getFormLicenseList(): array
    {
        return collect(LicenseEnum::cases())
            ->reject(fn(LicenseEnum $enum) => $enum === LicenseEnum::ALL)
            ->mapWithKeys(function (LicenseEnum $licenseEnum) {
                return [$licenseEnum->value => $licenseEnum->label()];
            })
            ->toArray();
    }

    /**
     * 資料名を取得する
     *
     * @return string
     */
    protected function getFormInputName(): string
    {
        return $this->inputName;
    }

    /**
     * 作者名を取得する
     *
     * @return string
     */
    protected function getFormInputWriter(): string
    {
        return $this->inputWriter;
    }

    /**
     * 時代を取得する
     *
     * @return string
     */
    protected function getFormInputEra(): string
    {
        return $this->inputEra;
    }

    /**
     * 内容を取得する
     *
     * @return string
     */
    protected function getFormInputDescription(): string
    {
        return $this->inputDescription;
    }

    /**
     * サムネイルファイルを取得する
     *
     * @return UploadedFile|null
     */
    protected function getFormInputFileThumbnail(): ?UploadedFile
    {
        return $this->inputFileThumbnail;
    }

    /**
     * PDFファイルを取得する
     *
     * @return array
     */
    protected function getFormInputPdfs(): array
    {
        return $this->inputPdfs;
    }
}
