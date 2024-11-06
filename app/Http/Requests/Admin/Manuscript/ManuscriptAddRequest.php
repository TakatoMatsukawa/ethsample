<?php

namespace App\Http\Requests\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Http\Requests\Request;
use App\Http\Requests\RequestField;
use Illuminate\Http\UploadedFile;

/**
 * 古文書の登録画面リクエストを処理する
 *
 * @property int select_license
 * @property string input_name
 * @property string input_writer
 * @property string input_era
 * @property string input_description
 * @property UploadedFile|null input_file_thumbnail
 * @property array input_pdfs
 */
class ManuscriptAddRequest extends Request
{
    /**
     * フォームの各フィールドをチェックする
     *
     * @return array|RequestField[]
     */
    protected function fields(): array
    {
        return [];
    }

    /**
     * ライセンスを取得する
     *
     * @return string
     */
    protected function getFormSelectLicense(): string
    {
        $value = old('select_license', $this->string('select_license'));
        $license = LicenseEnum::tryFrom($value) ?? LicenseEnum::ALL;
        if ($license !== LicenseEnum::ALL) {
            return $license->value;
        }
        return '';
    }

    /**
     * 資料名を取得する
     *
     * @return string
     */
    protected function getFormInputName(): string
    {
        return old('input_name', $this->string('input_name')) ?? '';
    }

    /**
     * 作者名を取得する
     *
     * @return string
     */
    protected function getFormInputWriter(): string
    {
        return old('input_writer', $this->string('input_writer')) ?? '';
    }

    /**
     * 時代を取得する
     *
     * @return string
     */
    protected function getFormInputEra(): string
    {
        return old('input_era', $this->string('input_era')) ?? '';
    }

    /**
     * 内容を取得する
     *
     * @return string
     */
    protected function getFormInputDescription(): string
    {
        return old('input_description', $this->string('input_description')) ?? '';
    }

    /**
     * サムネイルファイルを取得する
     *
     * @return UploadedFile|null
     */
    protected function getFormInputFileThumbnail(): ?UploadedFile
    {
        return old('input_file_thumbnail', $this->file('input_file_thumbnail'));
    }

    /**
     * 複数のPDFファイルを取得する
     *
     * @return array
     */
    public function getFormInputPdfs(): array
    {
        return old('input_pdfs', $this->collect('input_pdfs')->toArray()) ?? [];
    }
}
