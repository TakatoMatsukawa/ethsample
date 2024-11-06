<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $order
 * @property string $original_file_name
 * @property string $file_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pdf extends Model
{
    use HasFactory;

    protected $fillable = ['unique_id', 'order', 'original_file_name', 'file_name', 'created_at', 'updated_at'];

    /**
     * 古文書
     *
     * @return BelongsTo
     */
    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(Manuscript::class, 'unique_id', 'unique_id');
    }
}
