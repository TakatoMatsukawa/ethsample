<?php

namespace App\Http\Requests\Admin\Manuscript;

use App\Enums\LicenseEnum;
use App\Enums\PublicEnum;
use App\Enums\SelectSearchEnum;
use App\Enums\OnOffEnum;
use App\Enums\OrderEnum;
use App\Http\Requests\Request;
use App\Http\Requests\RequestField;

/**
 * 古文書の一覧リクエストを処理する
 *
 * @property PublicEnum|null select_public
 * @property LicenseEnum select_license
 * @property string input_keyword
 * @property SelectSearchEnum select_search
 * @property OnOffEnum select_thumbnail
 * @property OnOffEnum select_pdf
 * @property OrderEnum order_name
 * @property OrderEnum order_id
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
            // 公開状態
            RequestField::instance('select_public')
                ->ruleNullable()
                ->ruleIn(PublicEnum::cases()),
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
            // サムネイル
            RequestField::instance('select_thumbnail')
                ->ruleNullable()
                ->ruleIn(OnOffEnum::cases()),
            // PDF
            RequestField::instance('select_pdf')
                ->ruleNullable()
                ->ruleIn(OnOffEnum::cases()),
            // ID順序
            RequestField::instance('order_id')
                ->ruleNullable()
                ->ruleIn(OrderEnum::cases()),
        ];

        return $fields;
    }

    /**
     * 公開状態を取得する
     *
     * @return PublicEnum
     */
    protected function getFormSelectPublic(): PublicEnum
    {
        if (!$this->has('select_public')) {
            return $this->session()->get('manuscript.select_public', PublicEnum::ALL);
        }

        $value = old('select_public', $this->input('select_public'));
        if (is_null($value) || $value === '') {
            return PublicEnum::ALL;
        }
        return PublicEnum::tryFrom($value) ?? PublicEnum::ALL;
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
     * サムネイルファイルの有無を取得する
     *
     * @return OnOffEnum
     */
    protected function getFormSelectThumbnail(): OnOffEnum
    {
        if (!$this->has('select_thumbnail')) {
            return $this->session()->get('manuscript.select_thumbnail', OnOffEnum::ALL);
        }

        $value = old('select_thumbnail', $this->input('select_thumbnail'));
        return OnOffEnum::tryFrom($value) ?? OnOffEnum::ALL;
    }

    /**
     * PDFファイルの有無を取得する
     *
     * @return OnOffEnum
     */
    protected function getFormSelectPdf(): OnOffEnum
    {
        if (!$this->has('select_pdf')) {
            return $this->session()->get('manuscript.select_pdf', OnOffEnum::ALL);
        }

        $value = old('select_pdf', $this->input('select_pdf'));
        return OnOffEnum::tryFrom($value) ?? OnOffEnum::ALL;
    }

    /**
     * 資料名の順序を取得する
     *
     * @return OrderEnum
     */
    protected function getFormOrderName(): OrderEnum
    {
        if (!$this->has('order_name')) {
            return $this->session()->get('manuscript.order_name', OrderEnum::NONE);
        }

        $value = old('order_name', $this->input('order_name'));
        return OrderEnum::tryFrom($value) ?? OrderEnum::NONE;
    }

    /**
     * IDの順序を取得する
     *
     * @return OrderEnum
     */
    protected function getFormOrderId(): OrderEnum
    {
        if (!$this->has('order_id')) {
            return $this->session()->get('manuscript.order_id', OrderEnum::NONE);
        }

        $value = old('order_id', $this->input('order_id'));
        return OrderEnum::tryFrom($value) ?? OrderEnum::NONE;
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
        $this->session()->put('manuscript.select_public', $this->getFormSelectPublic());
        $this->session()->put('manuscript.select_license', $this->getFormSelectLicense());
        $this->session()->put('manuscript.input_keyword', $this->getFormInputKeyword());
        $this->session()->put('manuscript.select_search', $this->getFormSelectSearch());
        $this->session()->put('manuscript.select_thumbnail', $this->getFormSelectThumbnail());
        $this->session()->put('manuscript.select_pdf', $this->getFormSelectPdf());
        $this->session()->put('manuscript.order_name', $this->getFormOrderName());
        $this->session()->put('manuscript.order_id', $this->getFormOrderId());
        $this->session()->put('manuscript.page', $this->getFormPage());
    }
}
