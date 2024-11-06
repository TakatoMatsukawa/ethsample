<?php

namespace App\Http\Response;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Inertia\Inertia;

/**
 * レスポンス処理
 */
abstract class Response
{
    use HasAttributes;

    abstract public function response(array $data);

    /**
     * Inertia/Response の作成<br>
     * `getForm` から始まるメソッドからデータを取得し、 `props` に格納する。
     * @param string $view
     * @return \Inertia\Response
     */
    protected function render(string $view): \Inertia\Response
    {
        $methods = get_class_methods($this);
        $props = [];
        foreach ($methods as $method) {
            $snakeMethodName = \Str::snake($method);
            if (\Str::startsWith($snakeMethodName, 'get_form_')) {
                $props[\Str::after($snakeMethodName, 'get_form_')] = $this->$method();
            }
        }

        return Inertia::render($view, $props);
    }
}
