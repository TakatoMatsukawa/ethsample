<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function __get($key)
    {
        if (method_exists($this, Str::camel('get_form_' . $key))) {
            return $this->{Str::camel('get_form_' . $key)}();
        }
        if (method_exists($this, Str::camel('get_' . $key))) {
            return $this->{Str::camel('get_' . $key)}();
        }
        return parent::__get($key);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return call_user_func_array('array_merge', array_map(function (RequestField $field) {
            return $field->rule();
        }, $this->fields()));
    }

    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return call_user_func_array('array_merge', array_map(function (RequestField $field) {
            return $field->attribute();
        }, $this->fields()));
    }

    /**
     * バリデーション定義
     * @return array<RequestField>
     */
    abstract protected function fields(): array;
}
