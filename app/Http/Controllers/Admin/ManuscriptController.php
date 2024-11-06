<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Manuscript;

use App\Http\Domain\Admin\Manuscript\ManuscriptListDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptTogglePublicDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptAddDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptStoreDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptEditDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptUpdateDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptDeleteDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptCreateManifestDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptCreateAllManifestDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptAddTifDomain;
use App\Http\Domain\Admin\Manuscript\ManuscriptStoreTifDomain;

use App\Http\Requests\Admin\Manuscript\ManuscriptListRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptTogglePublicRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptAddRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptStoreRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptUpdateRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptDeleteRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptCreateManifestRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptCreateAllManifestRequest;
use App\Http\Requests\Admin\Manuscript\ManuscriptStoreTifRequest;

use App\Http\Response\Admin\Manuscript\ManuscriptListResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptTogglePublicResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptAddResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptStoreResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptEditResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptUpdateResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptDeleteResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptCreateManifestResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptCreateAllManifestResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptAddTifResponse;
use App\Http\Response\Admin\Manuscript\ManuscriptStoreTifResponse;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;

class ManuscriptController extends Controller
{
    /**
     * 古文書の一覧画面を表示する
     *
     * @param ManuscriptListRequest $request
     * @param ManuscriptListDomain $domain
     * @param ManuscriptListResponse $response
     * @return InertiaResponse
     */
    public function manuscriptList(ManuscriptListRequest $request, ManuscriptListDomain $domain, ManuscriptListResponse $response): InertiaResponse
    {
        $data = $domain(
            $request->select_public,
            $request->select_license,
            $request->input_keyword,
            $request->select_search,
            $request->select_thumbnail,
            $request->select_pdf,
            $request->order_name,
            $request->order_id,
            $request->page
        );
        return $response->response($data);
    }

    /**
     * 古文書の公開状態を更新（公開・非公開切替え）する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptTogglePublicRequest $request
     * @param ManuscriptTogglePublicDomain $domain
     * @param ManuscriptTogglePublicResponse $response
     * @return RedirectResponse
     */
    public function togglePublic(Manuscript $manuscript, ManuscriptTogglePublicRequest $request, ManuscriptTogglePublicDomain $domain, ManuscriptTogglePublicResponse $response): RedirectResponse
    {
        $data = $domain($manuscript, $request->page);
        return $response->response($data);
    }

    /**
     * 古文書の登録画面を表示する
     *
     * @param ManuscriptAddRequest $request
     * @param ManuscriptAddDomain $domain
     * @param ManuscriptAddResponse $response
     * @return InertiaResponse
     */
    public function manuscriptAdd(ManuscriptAddRequest $request, ManuscriptAddDomain $domain, ManuscriptAddResponse $response): InertiaResponse
    {
        $data = $domain($request->select_license, $request->input_name, $request->input_writer, $request->input_era, $request->input_description, $request->input_file_thumbnail, $request->input_pdfs);
        return $response->response($data);
    }

    /**
     * 古文書登録時の入力値をチェックする
     *
     * @param ManuscriptStoreRequest $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function manuscriptAddValidate(ManuscriptStoreRequest $request): Application|ResponseFactory|\Illuminate\Foundation\Application|Response
    {
        return response('');
    }

    /**
     * 新しい古文書を登録する
     *
     * @param ManuscriptStoreRequest $request
     * @param ManuscriptStoreDomain $domain
     * @param ManuscriptStoreResponse $response
     * @return RedirectResponse
     */
    public function manuscriptStore(ManuscriptStoreRequest $request, ManuscriptStoreDomain $domain, ManuscriptStoreResponse $response): RedirectResponse
    {
        $data = $domain(
            $request->is_public,
            $request->select_license,
            $request->input_name,
            $request->input_writer,
            $request->input_era,
            $request->input_description,
            $request->input_file_thumbnail,
            $request->input_pdfs
        );
        $this->setActivity('Created Manuscript（ID=' . $data['manuscript']->unique_id . ':' . \Str::limit($data['manuscript']->name, 50, '…') . '）', 2);
        return $response->response($data);
    }

    /**
     * 古文書の編集画面を表示する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptEditDomain $domain
     * @param ManuscriptEditResponse $response
     * @return InertiaResponse
     */
    public function manuscriptEdit(Manuscript $manuscript, ManuscriptEditDomain $domain, ManuscriptEditResponse $response): InertiaResponse
    {
        $data = $domain($manuscript);
        return $response->response($data);
    }

    /**
     * 古文書編集時の入力値をチェックする
     *
     * @param ManuscriptUpdateRequest $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function manuscriptEditValidate(ManuscriptUpdateRequest $request): Application|ResponseFactory|\Illuminate\Foundation\Application|Response
    {
        return response('');
    }

    /**
     * 古文書を更新する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptUpdateRequest $request
     * @param ManuscriptUpdateDomain $domain
     * @param ManuscriptUpdateResponse $response
     * @return RedirectResponse
     */
    public function manuscriptUpdate(Manuscript $manuscript, ManuscriptUpdateRequest $request, ManuscriptUpdateDomain $domain, ManuscriptUpdateResponse $response): RedirectResponse
    {
        $data = $domain(
            $manuscript,
            $request->is_public,
            $request->select_license,
            $request->input_name,
            $request->input_writer,
            $request->input_era,
            $request->input_description,
            $request->input_file_thumbnail,
            $request->pdfs,
            $request->pdfFiles,
            $request->is_registered_thumbnail,
            $request->pdf_states
        );
        $this->setActivity('Updated Manuscript（ID=' . $manuscript->unique_id . ':' . \Str::limit($manuscript->name, 50, '…') . '）', 3);
        return $response->response($data);
    }

    /**
     * 古文書を削除する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptDeleteRequest $request
     * @param ManuscriptDeleteDomain $domain
     * @param ManuscriptDeleteResponse $response
     * @return RedirectResponse
     */
    public function manuscriptDelete(Manuscript $manuscript, ManuscriptDeleteRequest $request, ManuscriptDeleteDomain $domain, ManuscriptDeleteResponse $response): RedirectResponse
    {
        $data = $domain($manuscript, $request->page);
        $this->setActivity('Deleted Manuscript（ID=' . $manuscript->unique_id . ':' . \Str::limit($manuscript->name, 50, '…') . '）', 4);
        return $response->response($data);
    }

    /**
     * 古文書のIIIFマニフェストを作成する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptCreateManifestRequest $request
     * @param ManuscriptCreateManifestDomain $domain
     * @param ManuscriptCreateManifestResponse $response
     * @return RedirectResponse
     */
    public function manuscriptCreateManifest(
        Manuscript $manuscript,
        ManuscriptCreateManifestRequest $request,
        ManuscriptCreateManifestDomain $domain,
        ManuscriptCreateManifestResponse $response
    ): RedirectResponse {
        $data = $domain($manuscript, $request->page);
        if ($data['method'] === 'create') {
            $this->setActivity('Created IIIF Manifest of Manuscript（ID=' . $manuscript->unique_id . ':' . \Str::limit($manuscript->name, 50, '…') . '）', 2);
        } elseif ($data['method'] === 'update') {
            $this->setActivity('Updated IIIF Manifest of Manuscript（ID=' . $manuscript->unique_id . ':' . \Str::limit($manuscript->name, 50, '…') . '）のIIIFマニフェストを更新しました。', 3);
        }
        return $response->response($data);
    }

    /**
     * 古文書の全データ分のIIIFマニフェストを作成する
     *
     * @param ManuscriptCreateAllManifestRequest $request
     * @param ManuscriptCreateAllManifestDomain $domain
     * @param ManuscriptCreateAllManifestResponse $response
     * @return InertiaResponse
     */
    public function manuscriptCreateAllManifest(ManuscriptCreateAllManifestRequest $request, ManuscriptCreateAllManifestDomain $domain, ManuscriptCreateAllManifestResponse $response): InertiaResponse
    {
        $data = $domain($request->select_public, $request->select_license, $request->input_keyword, $request->select_search, $request->select_thumbnail, $request->select_pdf);
        $this->setActivity('Created IIIF Manifest of All Manuscript', 2);
        return $response->response($data);
    }

    /**
     * 古文書の登録画面を表示する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptAddTifDomain $domain
     * @param ManuscriptAddTifResponse $response
     * @return InertiaResponse
     */
    public function manuscriptAddTif(Manuscript $manuscript, ManuscriptAddTifDomain $domain, ManuscriptAddTifResponse $response): InertiaResponse
    {
        $data = $domain($manuscript);
        return $response->response($data);
    }

    /**
     * 古文書登録時の入力値をチェックする
     *
     * @param ManuscriptStoreTifRequest $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function manuscriptAddTifValidate(ManuscriptStoreTifRequest $request): Application|ResponseFactory|\Illuminate\Foundation\Application|Response
    {
        return response('');
    }

    /**
     * 古文書を更新する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptStoreTifRequest $request
     * @param ManuscriptStoreTifDomain $domain
     * @param ManuscriptStoreTifResponse $response
     * @return RedirectResponse
     */
    public function manuscriptStoreTif(Manuscript $manuscript, ManuscriptStoreTifRequest $request, ManuscriptStoreTifDomain $domain, ManuscriptStoreTifResponse $response): RedirectResponse
    {
        $data = $domain($manuscript, $request->input_image);
        $this->setActivity('Created Tif Image（ID=' . $manuscript->unique_id . ':' . \Str::limit($manuscript->name, 50, '…') . '）', 2);
        return $response->response($data);
    }
}
