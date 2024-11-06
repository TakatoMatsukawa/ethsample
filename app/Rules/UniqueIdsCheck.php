<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Manuscript;

class UniqueIdsCheck implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $collection = [
            '11' => ['model' => new Manuscript(), 'name' => 'manuscript'],
        ];
        foreach ($value as $key => $unique_id) {
            if (!is_array($unique_id)) {
                if (isset($unique_id)) {
                    if (strlen($unique_id) !== 8 || !ctype_digit($unique_id)) {
                        $fail('掲載資料ユニークID（' . $key + 1 . ')は8桁の数字で入力してください。');
                        break;
                    }
                    $cutUniqueId = substr($unique_id, 0, 2);
                    if (array_key_exists($cutUniqueId, $collection)) {
                        $data = $collection[$cutUniqueId]['model']->where('unique_id', '=', $unique_id)->exists();
                        if ($data === false) {
                            $fail('掲載資料ユニークID（' . $key + 1 . ')が存在しません。');
                        }
                    } else {
                        $fail('掲載資料ユニークID（' . $key + 1 . ')が存在しません。');
                    }
                }
            }
        }
    }
}
