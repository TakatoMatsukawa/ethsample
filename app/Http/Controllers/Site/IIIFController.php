<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Enums\PublicEnum;

// 対象のmodelを指定
use App\Models\Manuscript;

class IIIFController extends Controller
{
    /**
     * 公開状況の確認
     *
     * @param string $uid
     * @return void
     */
    public function checkPublic(string $uid, $request)
    {
        // uidから値を取得
        $system_code = substr($uid, 0, 2);
        $id = (int) substr($uid, 2, 6);

        // 管理画面からだと公開状況を見ない
        if ($request->query('auth')) {
            $whereQuery = [['deleted_at', null]];
        } else {
            $whereQuery = [['deleted_at', null], ['public_flg', PublicEnum::PUBLIC]];
        }

        $queries = [
            '11' => Manuscript::where($whereQuery),
        ];

        $query = $queries[$system_code];

        // 資料が公開されていなければ404エラー
        $query->findOrFail($id);

        // ファイルがなければ404エラー
        if (!Storage::disk('public')->exists('iiifmanifests/' . $uid . '.json')) {
            abort('404');
        }
    }

    /**
     * IIIFビューワーを表示する
     *
     * @param string $uid
     * @return void
     */
    public function iiifViewer(string $uid, Request $request)
    {
        $this->checkPublic($uid, $request);

        //CORS
        header('Access-Control-Allow-Origin: *');

        $manifest_url = Storage::url('iiifmanifests/' . $uid . '.json');

        return Inertia::render('Site/IIIF/Index', ['manifest' => $manifest_url]);
    }

    /**
     * IIIFマニフェストを表示する
     *
     * @param string $uid
     * @return void
     */
    public function iiifManifest(string $uid, Request $request)
    {
        //CORS
        header('Access-Control-Allow-Origin: *');

        // ストレージのファイルパス
        $filePath = storage_path("app/public/iiifmanifests/{$uid}.json");

        $this->checkPublic($uid, $request);

        // JSONファイルの内容を返す
        return response()->file($filePath, [
            'Content-Type' => 'application/json',
        ]);
    }
}
