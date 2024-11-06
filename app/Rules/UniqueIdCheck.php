<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Manuscript;

class UniqueIdCheck implements ValidationRule
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
        if (!is_array($value)) {
            if (isset($value)) {
                if (strlen($value) !== 8 || !ctype_digit($value)) {
                    $fail('アイキャッチ用ユニークIDは8桁の数字で入力してください。');
                }
                $cutUniqueId = substr($value, 0, 2);
                if (array_key_exists($cutUniqueId, $collection)) {
                    $data = $collection[$cutUniqueId]['model']->where('unique_id', '=', $value)->exists();
                    if ($data === false) {
                        $fail('アイキャッチ用ユニークIDが存在しません。');
                    }
                } else {
                    $fail('アイキャッチ用ユニークIDが存在しません。');
                }
            }
        }
    }
}
