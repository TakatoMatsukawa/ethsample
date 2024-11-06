<?php

namespace App\Http\Response\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\PublicEnum;
use App\Enums\StateEnum;
use App\Http\Response\Response;
use App\Models\Manuscript;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Storage;

/**
 * 古文書の編集画面レスポンスを生成する
 */
class ManuscriptEditResponse extends Response
{
    private Manuscript $manuscript;
    private Collection $pdfs;

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
        $this->pdfs = $data['pdfs'];

        if ($this->manuscript->deleted_at !== null) {
            abort('404');
        }
        return $this->render('Admin/Manuscript/Edit');
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
     * 公開状態を判定し取得する
     *
     * @return bool
     */
    protected function getFormIsPublic(): bool
    {
        return $this->manuscript->public_flg === PublicEnum::PUBLIC;
    }

    /**
     * ライセンスを取得する
     *
     * @return string|null
     */
    protected function getFormSelectLicense(): ?string
    {
        return old('select_license', $this->manuscript->license->value);
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
     * @return string|null
     */
    protected function getFormInputName(): ?string
    {
        return old('input_name', $this->manuscript->name);
    }

    /**
     * 作者名を取得する
     *
     * @return string|null
     */
    protected function getFormInputWriter(): ?string
    {
        return old('input_writer', $this->manuscript->writer);
    }

    /**
     * 時代を取得する
     *
     * @return string|null
     */
    protected function getFormInputEra(): ?string
    {
        return old('input_era', $this->manuscript->era);
    }

    /**
     * 内容を取得する
     *
     * @return string|null
     */
    protected function getFormInputDescription(): ?string
    {
        return old('input_description', $this->manuscript->description);
    }

    /**
     * サムネイルファイルを取得する
     *
     * @return UploadedFile|null
     */
    protected function getFormInputFileThumbnail(): ?UploadedFile
    {
        return old('input_file_thumbnail');
    }

    /**
     * サムネイルファイルの情報を取得する
     *
     * @return array
     */
    protected function getFormThumbnail(): array
    {
        $isRegistered = !empty($this->manuscript->thumbnail_file_name);
        $url = Storage::url($this->manuscript->thumbnailFilePath());
        return [
            'is_registered' => $isRegistered,
            'url' => $url,
            'name' => $this->manuscript->thumbnail_original_name,
        ];
    }

    /**
     * PDFファイルを取得する
     *
     * @return array
     */
    protected function getFormInputPdfs(): array
    {
        return old('input_pdfs', []);
    }

    /**
     * PDFファイルの情報を取得する
     *
     * @return array
     */
    protected function getFormPdfs(): array
    {
        $pdfs = $this->pdfs;
        if ($pdfs->isEmpty()) {
            $arrPdfs = [null];
        } else {
            $arrPdfs = $pdfs->toArray();
        }

        return $arrPdfs;
    }

    /**
     * PDFの状態管理用の配列を取得する
     *
     * @return array
     */
    protected function getFormStateList(): array
    {
        return collect(StateEnum::cases())
            ->mapWithKeys(function ($item) {
                return [\Str::lower($item->name) => $item->value];
            })
            ->toArray();
    }
}
