<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Models\Manuscript;

use App\Http\Domain\Site\Manuscript\ManuscriptListDomain;
use App\Http\Domain\Site\Manuscript\ManuscriptDetailDomain;
use App\Http\Domain\Site\Manuscript\ManuscriptPreviewDomain;

use App\Http\Requests\Site\Manuscript\ManuscriptListRequest;
use App\Http\Requests\Site\Manuscript\ManuscriptPreviewRequest;

use App\Http\Response\Site\Manuscript\ManuscriptListResponse;
use App\Http\Response\Site\Manuscript\ManuscriptDetailResponse;
use App\Http\Response\Site\Manuscript\ManuscriptPreviewResponse;

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
        $data = $domain($request->select_license, $request->input_keyword, $request->select_search, $request->page);
        return $response->response($data);
    }

    /**
     * 古文書の詳細画面を表示する
     *
     * @param Manuscript $manuscript
     * @param ManuscriptDetailDomain $domain
     * @param ManuscriptDetailResponse $response
     * @return InertiaResponse
     */
    public function manuscriptDetail(Manuscript $manuscript, ManuscriptDetailDomain $domain, ManuscriptDetailResponse $response): InertiaResponse
    {
        $data = $domain($manuscript);
        return $response->response($data);
    }

    /**
     * 古文書のプレビュー画面を表示する
     *
     * @param ManuscriptPreviewRequest $request
     * @param ManuscriptPreviewDomain $domain
     * @param ManuscriptPreviewResponse $response
     * @return InertiaResponse
     */
    public function manuscriptPreview(ManuscriptPreviewRequest $request, ManuscriptPreviewDomain $domain, ManuscriptPreviewResponse $response): InertiaResponse
    {
        $data = $domain($request->manuscript);
        return $response->response($data);
    }
}
