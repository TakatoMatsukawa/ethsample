<?php

namespace App\Http\Controllers;

use App\Models\Activity;
abstract class Controller
{
    /**
     * 操作履歴の登録を行う
     * @param string $desc
     * @param int $action
     * @return void
     */
    protected function setActivity(string $desc, int $action): void
    {
        $desc = \Str::replace(["\r\n", "\r", "\n"], '', $desc);
        Activity::create([
            'user_id' => request()->user()->id,
            'desc' => $desc,
            'action' => $action,
        ]);
    }
}
