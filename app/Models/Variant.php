<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\SearchValues;

/**
 *
 *
 * @property int $id
 * @property string $new
 * @property string $old
 * @property string $old2
 * @property string $old3
 * @property string $old4
 * @property string $old5
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereOld2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereOld3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereOld4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereOld5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Variant extends Model
{
    use HasFactory;
    use SearchValues;

    protected $fillable = ['new', 'old', 'old2', 'old3', 'old4', 'old5', 'deleted_at', 'created_at', 'updated_at'];

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
        // 新字体
        $query->orWhere('new', 'regexp', $searchValue);
        // 旧字体1
        $query->orWhere('old', 'regexp', $searchValue);
        // 旧字体2
        $query->orWhere('old2', 'regexp', $searchValue);
        // 旧字体3
        $query->orWhere('old3', 'regexp', $searchValue);
        // 旧字体4
        $query->orWhere('old4', 'regexp', $searchValue);
        // 旧字体5
        $query->orWhere('old5', 'regexp', $searchValue);
    }
}
