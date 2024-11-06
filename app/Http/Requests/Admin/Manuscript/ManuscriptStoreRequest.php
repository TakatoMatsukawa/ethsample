<?php

namespace App\Http\Requests\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\PublicEnum;
use App\Http\Requests\Request;
use App\Http\Requests\RequestField;
use App\Rules\PdfsCheck;
use Illuminate\Http\UploadedFile;

/**
 * 古文書の追加リクエストを処理する
 *
 * @property PublicEnum is_public
 * @property LicenseEnum select_license
 * @property string input_name
 * @property string input_writer
 * @property string input_era
 * @property string input_description
 * @property UploadedFile|null input_file_thumbnail
 * @property array input_pdfs
 */
class ManuscriptStoreRequest extends Request
{
    /**
     * フォームの各フィールドをチェックする
     *
     * @return array|RequestField[]
     */
    protected function fields(): array
    {
        $array = [
            // 公開状態
            RequestField::instance('is_public')
                ->ruleRequired()
                ->ruleBoolean(),
            // ライセンス
            RequestField::instance('select_license')
                ->ruleRequired()
                ->ruleIn(collect(LicenseEnum::cases())->reject(fn(LicenseEnum $e) => $e === LicenseEnum::ALL)),
            // 資料名
            RequestField::instance('input_name')
                ->ruleRequired()
                ->ruleString()
                ->ruleMax(100)
                ->ruleNotRegex('/[\r\n]/u'),
            // 作者名
            RequestField::instance('input_writer')
                ->ruleNullable()
                ->ruleString()
                ->ruleMax(50)
                ->ruleNotRegex('/[\r\n]/u'),
            // 時代
            RequestField::instance('input_era')
                ->ruleNullable()
                ->ruleString()
                ->ruleMax(100)
                ->ruleNotRegex('/[\r\n]/u'),
            // 内容
            RequestField::instance('input_description')
                ->ruleNullable()
                ->ruleString()
                ->ruleMax(800),
            // サムネイル
            RequestField::instance('input_file_thumbnail')
                ->ruleNullable()
                ->ruleFile()
                ->ruleMax(5120)
                ->ruleMimes(['jpg', 'jpeg', 'png', 'bmp']),
            // PDFファイル
            RequestField::instance('input_pdfs')
                ->ruleNullable()
                ->setRule(new PdfsCheck),
        ];
        return $array;
    }

    /**
     * 公開状態を取得する
     *
     * @return PublicEnum
     */
    protected function getFormIsPublic(): PublicEnum
    {
        if ($this->boolean('is_public')) {
            return PublicEnum::PUBLIC;
        }
        return PublicEnum::PRIVATE;
    }

    /**
     * ライセンスを取得する
     *
     * @return LicenseEnum
     */
    protected function getFormSelectLicense(): LicenseEnum
    {
        return LicenseEnum::from($this->integer('select_license'));
    }

    /**
     * 資料名を取得する
     *
     * @return string
     */
    protected function getFormInputName(): string
    {
        return $this->string('input_name');
    }

    /**
     * 作者名を取得する
     *
     * @return string
     */
    protected function getFormInputWriter(): string
    {
        return $this->string('input_writer');
    }

    /**
     * 時代を取得する
     *
     * @return string
     */
    protected function getFormInputEra(): string
    {
        return $this->string('input_era');
    }

    /**
     * 内容を取得する
     *
     * @return string
     */
    protected function getFormInputDescription(): string
    {
        return $this->string('input_description');
    }

    /**
     * サムネイルファイルを取得する
     *
     * @return UploadedFile|null
     */
    protected function getFormInputFileThumbnail(): ?UploadedFile
    {
        return $this->file('input_file_thumbnail');
    }

    /**
     * 複数のPDFファイルを取得する
     *
     * @return array
     */
    public function getFormInputPdfs(): array
    {
        return $this->file('input_pdfs') ?? [];
    }
}
