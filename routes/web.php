<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

// ミドルウェア
use App\Http\Middleware\BasicAuthMiddleware; // パスワード等はここで設定、ミドルウェア追加はBasicAuthMiddleware::class
use App\Http\Middleware\CheckIPAddressMiddleware; // 許可IPアドレスはここで設定、ミドルウェア追加はCheckIPAddressMiddleware::class
use App\Http\Middleware\LocaleMiddleware;

// 利用者側Controller
use App\Http\Controllers\Site\AppController;
use App\Http\Controllers\Site\ManuscriptController as SiteManuscriptController;
use App\Http\Controllers\Site\SearchController as SiteSearchController;

// IIIF用Controller
use App\Http\Controllers\Site\IIIFController;

// 管理画面側Controller
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManuscriptController as AdminManuscriptController;

//環境によるミドルウェア切り替え、ドメインの取得
switch (config('app.env')) {
    case 'production':
        $AddSiteMiddlewares = [LocaleMiddleware::class];
        $AddAdminMiddlewares = [LocaleMiddleware::class];
        $siteDomain = config('app.url');
        $adminDomain = config('app.admin_url');
        break;
    case 'staging':
        $AddSiteMiddlewares = [LocaleMiddleware::class, BasicAuthMiddleware::class];
        $AddAdminMiddlewares = [LocaleMiddleware::class, BasicAuthMiddleware::class];
        $siteDomain = config('app.url');
        $adminDomain = config('app.admin_url');
        break;
    case 'local':
        $AddSiteMiddlewares = [LocaleMiddleware::class];
        $AddAdminMiddlewares = [LocaleMiddleware::class];
        // ドメインによるrouteの切り替え時にポート番号があると動作しないため除外
        $siteDomain = str_replace(':' . config('app.local_port'), '', config('app.url'));
        $adminDomain = str_replace(':' . config('app.local_port'), '', config('app.admin_url'));
        break;
}
// web.php
$siteMiddlewares = array_merge([], $AddSiteMiddlewares);
$adminMiddlewares = array_merge(['auth'], $AddAdminMiddlewares);
// auth.php
$authSiteMiddlewares = array_merge(['guest'], $AddSiteMiddlewares);
$authAdminMiddlewares = array_merge(['auth'], $AddAdminMiddlewares);

// フロント側route
Route::domain($siteDomain)
    ->middleware($siteMiddlewares)
    ->group(function () {
        Route::get('/', [AppController::class, 'index'])->name('App');
        // 古文書
        Route::get('/manuscript', [SiteManuscriptController::class, 'manuscriptList'])->name('manuscript');
        Route::get('/manuscript/detail/{manuscript}', [SiteManuscriptController::class, 'manuscriptDetail'])->name('manuscript.detail');
        Route::get('/manuscript/preview/', [SiteManuscriptController::class, 'manuscriptPreview'])->name('manuscript.preview');
        // 横断検索
        Route::get('/search', [SiteSearchController::class, 'searchList'])->name('search');
        // IIIF
        Route::get('/iiifviewer/{uid}', [IIIFController::class, 'iiifViewer'])->name('iiifviewer');
        Route::get('{uid}/manifest', [IIIFController::class, 'iiifManifest'])->name('iiifmanifest');
        Route::get('view/{path}', function ($path) {
            $url = config('app.iipimage_server_url') . $path;
            return redirect()->away($url);
        })->where('path', '(.*)');
    });

// 管理画面側route
Route::domain($adminDomain)
    ->middleware($adminMiddlewares)
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        // 古文書
        Route::get('/manuscript/manuscript_list', [AdminManuscriptController::class, 'manuscriptList'])->name('manuscript.manuscript_list');
        Route::get('/manuscript/manuscript_add', [AdminManuscriptController::class, 'manuscriptAdd'])->name('manuscript.manuscript_add');
        Route::post('/manuscript/manuscript_add', [AdminManuscriptController::class, 'manuscriptAddValidate'])->name('manuscript.manuscript_add_validate');
        Route::post('/manuscript/manuscript_list', [AdminManuscriptController::class, 'manuscriptStore'])->name('manuscript.manuscript_store');
        Route::get('/manuscript/manuscript_edit/{manuscript}', [AdminManuscriptController::class, 'manuscriptEdit'])->name('manuscript.manuscript_edit');
        Route::post('/manuscript/manuscript_edit/{manuscript}', [AdminManuscriptController::class, 'manuscriptEditValidate'])->name('manuscript.manuscript_edit_validate');
        Route::post('/manuscript/manuscript_update/{manuscript}', [AdminManuscriptController::class, 'manuscriptUpdate'])->name('manuscript.manuscript_update');
        Route::delete('/manuscript/manuscript_delete/{manuscript}', [AdminManuscriptController::class, 'manuscriptDelete'])->name('manuscript.manuscript_delete');
        Route::patch('/manuscript/manuscript_toggle_public/{manuscript}', [AdminManuscriptController::class, 'togglePublic'])->name('manuscript.toggle_public');
        Route::patch('/manuscript/manuscript_create_manifest/{manuscript}', [AdminManuscriptController::class, 'manuscriptCreateManifest'])->name('manuscript.create_manifest');
        Route::get('/manuscript/create_all_manifest', [AdminManuscriptController::class, 'manuscriptCreateAllManifest'])->name('manuscript.create_all_manifest');
        Route::get('/manuscript/manuscript_add_tif/{manuscript}', [AdminManuscriptController::class, 'manuscriptAddTif'])->name('manuscript.manuscript_add_tif');
        Route::post('/manuscript/manuscript_add_tif/{manuscript}', [AdminManuscriptController::class, 'manuscriptAddTifValidate'])->name('manuscript.manuscript_add_tif_validate');
        Route::post('/manuscript/manuscript_store_tif/{manuscript}', [AdminManuscriptController::class, 'manuscriptStoreTif'])->name('manuscript.manuscript_store_tif');
    });
Route::middleware($siteMiddlewares)
    ->get('/greeting/{locale}', function ($locale) {
        session()->put('locale', $locale);
        return redirect()->back();
    })
    ->name('lang.change');

require __DIR__ . '/auth.php';
