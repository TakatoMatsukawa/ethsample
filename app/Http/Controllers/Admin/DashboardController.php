<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Inertia\Inertia;
use App\Models\Manuscript;
use App\Models\Activity;
use App\Models\User;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 各コレクションの件数を取得
        $where = ['deleted_at' => null, 'public_flg' => 0];
        $counts = [
            'manuscript' => Manuscript::where($where)->count(),
        ];

        // アクティビティ取得
        $activities = Activity::limit(10)->latest('created_at')->get();

        // 現在の日付のインスタンスを作成
        $now = Carbon::now();

        foreach ($activities as $activity) {
            $user = User::select('name')
                ->where('id', '=', $activity['user_id'])
                ->first();
            $activity['desc'] = $user['name'] . ' : ' . $activity['desc'];

            // 取得したアクティビティ日付のインスタンスを作成
            $activity['activityDate'] = Carbon::parse($activity['created_at']);
            // 相対時間取得
            $activity['dateDiffForHumans'] = $activity['activityDate']->diffForHumans($now);
        }
        return Inertia::render('Admin/Dashboard', ['counts' => $counts, 'activities' => $activities]);
    }
}
