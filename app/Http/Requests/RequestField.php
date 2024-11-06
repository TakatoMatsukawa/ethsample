<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

/**
 * フォームのリクエストで送信されるデータに関する属性を設定する処理の基底クラス
 */
class RequestField
{
    private ?string $label;
    private string $filed;
    private array $rules = [];

    /**
     * インスタンス生成
     * @param string $field フィールド名
     * @param string|null $label 項目名
     * @return RequestField
     */
    public static function instance(string $field, string $label = null): RequestField
    {
        return new RequestField($field, $label);
    }

    /**
     * コンストラクタ
     * @param string $field
     * @param string|null $label
     */
    private function __construct(string $field, string $label = null)
    {
        $this->filed = $field;
        $this->label = $label;
    }

    /**
     * バリデーションルール設定
     * @param $rule
     * @return $this
     */
    public function setRule($rule): static
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * 必須
     * @return $this
     */
    public function ruleRequired(): static
    {
        return $this->setRule('required');
    }

    /**
     * null 許容
     * @return $this
     */
    public function ruleNullable(): static
    {
        return $this->setRule('nullable');
    }

    /**
     * 文字列
     * @return $this
     */
    public function ruleString(): static
    {
        return $this->setRule('string');
    }

    /**
     * confirmation フィールドと一致
     * @return $this
     */
    public function ruleConfirmed(): static
    {
        return $this->setRule('confirmed');
    }

    /**
     * 最小値
     * @param $min
     * @return $this
     */
    public function ruleMin($min): static
    {
        return $this->setRule('min:' . $min);
    }

    /**
     * 最大値
     * @param $max
     * @return $this
     */
    public function ruleMax($max): static
    {
        return $this->setRule('max:' . $max);
    }

    /**
     * いずれかが含まれる
     * @param $ary
     * @return $this
     */
    public function ruleIn($ary): static
    {
        return $this->setRule(Rule::in($ary));
    }

    /**
     * 日付
     * @return $this
     */
    public function ruleDate(): static
    {
        return $this->setRule('date');
    }

    /**
     * 指定のフィールドが存在しない場合、バリデーションから除外
     * @param string $field
     * @return $this
     */
    public function ruleExcludeWithout(string $field): static
    {
        return $this->setRule('exclude_without:' . $field);
    }

    /**
     * 指定のフィールドより前、または、同じ日付である
     * @param string $field
     * @return $this
     */
    public function ruleBeforeOrEqual(string $field): static
    {
        return $this->setRule('before_or_equal:' . $field);
    }

    /**
     * 指定のフィールドより後、または、同じ日付である
     * @param string $field
     * @return $this
     */
    public function ruleAfterOrEqual(string $field): static
    {
        return $this->setRule('after_or_equal:' . $field);
    }

    /**
     * ファイル
     * @return $this
     */
    public function ruleFile(): static
    {
        return $this->setRule('file');
    }

    /**
     * 数値
     * @return $this
     */
    public function ruleNumeric(): static
    {
        return $this->setRule('numeric');
    }

    /**
     * 指定範囲内であるか
     * @param $min
     * @param $max
     * @return $this
     */
    public function ruleBetween($min, $max): static
    {
        return $this->setRule('between:' . $min . ',' . $max);
    }

    /**
     * 配列であるか
     * @return $this
     */
    public function ruleArray(): static
    {
        return $this->setRule('array');
    }

    /**
     * 整数の桁数が指定値の間にあるか
     * @param $min
     * @param $max
     * @return $this
     */
    public function ruleDigitsBetween($min, $max): static
    {
        return $this->setRule('digits_between:' . $min . ',' . $max);
    }

    /**
     * 真偽値であるか
     * @return $this
     */
    public function ruleBoolean(): static
    {
        return $this->setRule('boolean');
    }

    /**
     * 一意であるか
     * @param string $table
     * @return $this
     */
    public function ruleUnique(string $table): static
    {
        return $this->setRule('unique:' . $table);
    }

    /**
     * メールアドレス形式チェック
     * @param string $rule
     * @return $this
     */
    public function ruleEmail(string $rule = 'rfc'): static
    {
        return $this->setRule('email:' . $rule);
    }

    /**
     * 項目が存在する場合
     * @return $this
     */
    public function ruleSometimes(): static
    {
        return $this->setRule('sometimes');
    }

    /**
     * 他フィールドが値と等しくない場合、バリデーションから除外する
     * @param $field
     * @param $value
     * @return $this
     */
    public function ruleExcludeUnless($field, $value): static
    {
        return $this->setRule('exclude_unless:' . $field . ',' . $value);
    }

    /**
     * データベース存在チェック
     * @param $table
     * @param $column
     * @return $this
     */
    public function ruleExists($table, $column): static
    {
        return $this->setRule('exists:' . $table . ',' . $column);
    }

    /**
     * 正規表現と一致するか
     * @param string $pattern
     * @return $this
     */
    public function ruleRegex(string $pattern): static
    {
        return $this->setRule('regex:' . $pattern);
    }

    /**
     * 正規表現と一致しないか
     * @param string $pattern
     * @return $this
     */
    public function ruleNotRegex(string $pattern): static
    {
        return $this->setRule('not_regex:' . $pattern);
    }

    /**
     * MIMEタイプ
     * @param $mimes
     * @return $this
     */
    public function ruleMimes($mimes): static
    {
        if (is_array($mimes)) {
            return $this->setRule('mimes:' . implode(',', $mimes));
        }
        return $this->setRule('mimes:' . $mimes);
    }

    /**
     * 他フィールドと異なる値であるか
     * @param string $field
     * @return $this
     */
    public function ruleDifferent(string $field): static
    {
        return $this->setRule('different:' . $field);
    }

    /**
     * Enumと一致するか
     * @param string $enum
     * @return $this
     */
    public function ruleEnum(string $enum): static
    {
        return $this->setRule(Rule::enum($enum));
    }

    /**
     * 最大桁数
     * @param $value
     * @return $this
     */
    public function ruleMaxDigits($value): static
    {
        return $this->setRule('max_digits:' . $value);
    }

    /**
     * サイズ
     * @param $value
     * @return $this
     */
    public function ruleSize($value): static
    {
        return $this->setRule('size:' . $value);
    }

    /**
     * バリデーションルールリスト
     * @return array[]
     */
    public function rule(): array
    {
        return [$this->filed => $this->rules];
    }

    /**
     * カスタム属性名
     * @return null[]|string[]
     */
    public function attribute(): array
    {
        return [$this->filed => $this->label];
    }
}
