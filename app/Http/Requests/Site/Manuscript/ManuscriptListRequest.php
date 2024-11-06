<?php

namespace App\Http\Requests\Site\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\SelectSearchEnum;
use App\Http\Requests\Request;
use App\Http\Requests\RequestField;

/**
 * 古文書の一覧リクエストを処理する
 *
 * @property LicenseEnum select_license
 * @property string input_keyword
 * @property SelectSearchEnum select_search
 * @property int page
 */
class ManuscriptListRequest extends Request
{
    /**
     * フォームの各フィールドをチェックする
     *
     * @return array|RequestField[]
     */
    public function fields(): array
    {
        $fields = [
            // ライセンス
            RequestField::instance('select_license')
                ->ruleNullable()
                ->ruleIn(LicenseEnum::cases()),
            // キーワード
            RequestField::instance('input_keyword')
                ->ruleNullable()
                ->ruleString(),
            // キーワード検索条件
            RequestField::instance('select_search')
                ->ruleNullable()
                ->ruleIn(SelectSearchEnum::cases()),
        ];

        return $fields;
    }

    /**
     * ライセンスを取得する
     *
     * @return LicenseEnum
     */
    protected function getFormSelectLicense(): LicenseEnum
    {
        if (!$this->has('select_license')) {
            return $this->session()->get('manuscript.select_license', LicenseEnum::ALL);
        }

        $value = old('select_license', $this->input('select_license'));
        if (is_null($value) || $value === '') {
            return LicenseEnum::ALL;
        }
        return LicenseEnum::tryFrom($value) ?? LicenseEnum::ALL;
    }

    /**
     * キーワードを取得する
     *
     * @return string
     */
    protected function getFormInputKeyword(): string
    {
        if (!$this->has('input_keyword')) {
            return $this->session()->get('manuscript.input_keyword', '');
        }

        return old('input_keyword', $this->string('input_keyword')) ?? '';
    }

    /**
     * キーワード検索条件を取得する
     *
     * @return SelectSearchEnum
     */
    protected function getFormSelectSearch(): SelectSearchEnum
    {
        if (!$this->has('select_search')) {
            return $this->session()->get('manuscript.select_search', SelectSearchEnum::AND);
        }

        $value = old('select_search', $this->input('select_search'));
        return SelectSearchEnum::tryFrom($value) ?? SelectSearchEnum::AND;
    }

    /**
     * ページ番号を取得する
     *
     * @return int
     */
    protected function getFormPage(): int
    {
        if (!$this->has('page')) {
            return $this->session()->get('manuscript.page', 1);
        }
        $value = old('page', $this->input('page'));
        if (!is_numeric($value)) {
            return 1;
        }
        return (int)$value;
    }

    /**
     * デストラクター
     */
    public function __destruct()
    {
        // セッションに現在の検索条件を保存する。
        $this->session()->put('manuscript.select_license', $this->getFormSelectLicense());
        $this->session()->put('manuscript.input_keyword', $this->getFormInputKeyword());
        $this->session()->put('manuscript.select_search', $this->getFormSelectSearch());
        $this->session()->put('manuscript.page', $this->getFormPage());
    }
}
