<?php

namespace App\Http\Requests\Site\Search;

use App\Enums\SelectSearchEnum;
use App\Http\Requests\Request;
use App\Http\Requests\RequestField;

/**
 * 横断検索の一覧リクエストを処理する
 *
 * @property int|null select_master
 * @property string input_keyword
 * @property SelectSearchEnum select_search
 * @property int page
 */
class SearchListRequest extends Request
{
    /**
     * フォームの各フィールドをチェックする
     *
     * @return array|RequestField[]
     */
    public function fields(): array
    {
        $fields = [
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
     * キーワードを取得する
     *
     * @return string
     */
    protected function getFormInputKeyword(): string
    {
        if (!$this->has('input_keyword')) {
            return $this->session()->get('search.input_keyword', '');
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
            return $this->session()->get('search.select_search', SelectSearchEnum::AND);
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
            return $this->session()->get('search.page', 1);
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
        $this->session()->put('search.input_keyword', $this->getFormInputKeyword());
        $this->session()->put('search.select_search', $this->getFormSelectSearch());
        $this->session()->put('search.page', $this->getFormPage());
    }
}
