<?php

namespace App\Models;

use App\Enums\IiifEnum;
use App\Enums\LicenseEnum;
use App\Enums\PublicEnum;
use App\Observers\ManuscriptObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\SearchValues;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $writer
 * @property string $description
 * @property string $thumbnail_original_name
 * @property string $thumbnail_file_name
 * @property LicenseEnum $license
 * @property string $unique_id
 * @property PublicEnum $public_flg
 * @property IiifEnum $iiif_flg
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript query()
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereWriter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIiiFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript wherePublicFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereThumbnailFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereThumbnailOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manuscript whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Manuscript extends Model
{
    use HasFactory;
    use SearchValues;

    protected static function boot(): void
    {
        parent::boot();
        self::observe(ManuscriptObserver::class);
    }

    protected $fillable = [
        'name',
        'writer',
        'era',
        'description',
        'thumbnail_original_name',
        'thumbnail_file_name',
        'license',
        'unique_id',
        'public_flg',
        'iiif_flg',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'iiif_flg' => IiifEnum::class,
        'license' => LicenseEnum::class,
        'public_flg' => PublicEnum::class,
    ];

    /**
     * PDF
     *
     * @return HasMany
     */
    public function pdfs(): HasMany
    {
        return $this->hasMany(Pdf::class, 'unique_id', 'unique_id');
    }

    /**
     * キーワードで検索する
     *
     * @param Builder $query
     * @param string $keyword
     * @return void
     */
    public function scopeKeywordSearch(Builder $query, string $keyword): void
    {
        $parseKeyword = addcslashes($keyword, '\_%');
        $searchValue = $this->searchValue($parseKeyword);
        // 資料名
        $query->orWhere('name', 'regexp', $searchValue);
        //  作者名
        $query->orWhere('writer', 'regexp', $searchValue);
        //  時代
        $query->orWhere('era', 'regexp', $searchValue);
        //  内容
        $query->orWhere('description', 'regexp', $searchValue);
    }

    /**
     * サムネイルファイルのパスを生成する
     *
     * @param $id
     * @param $thumbnail
     * @return string
     */
    public function thumbnailFilePath($id = null, $thumbnail = null): string
    {
        if (!$id) {
            $id = $this->id;
        }

        if (!$thumbnail) {
            $thumbnail = $this->thumbnail_file_name;
        }
        if (!$id || !$thumbnail) {
            return '';
        }
        return 'manuscript/' . 'thumbnail/' . $id . '/' . $thumbnail;
    }

    /**
     * PDFファイルのパスを生成する
     *
     * @param $order
     * @param $unique_id
     * @return string
     */
    public function pdfFilePath($order = null, $unique_id = null): string
    {
        if (!$unique_id) {
            $pdfs = $this->pdfs;
            $id = $this->id;
        } else {
            $pdfs = Pdf::where('unique_id', '=', $unique_id)->get();
            $id = (int) substr($unique_id, -6);
        }
        if (!$order || $pdfs->isEmpty()) {
            return '';
        }
        $name = $pdfs->where('order', '=', $order)->first()->file_name;
        return 'manuscript/' . 'pdf/' . $id . '/' . $name;
    }

    /**
     * 全てのPDFファイルのパスを生成し、配列で返す
     *
     * @param $unique_id
     * @return array
     */
    public function allPdfFilePath($unique_id = null): array
    {
        if (!$unique_id) {
            $pdfs = $this->pdfs;
            $id = $this->id;
        } else {
            $pdfs = Pdf::where('unique_id', '=', $unique_id)->get();
            $id = (int) substr($unique_id, -6);
        }
        if ($pdfs->isEmpty()) {
            return '';
        }
        $file_paths = $pdfs
            ->map(function ($pdf) use ($id) {
                return 'manuscript/' . 'pdf/' . $id . '/' . $pdf->file_name;
            })
            ->toArray();

        return $file_paths;
    }

    /**
     * コレクション名を生成する
     *
     * @return string
     */
    public function collectionName(): string
    {
        return '古文書';
    }
}
