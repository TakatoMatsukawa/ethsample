<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Http\Domain\Site\Search\SearchListDomain;

use App\Http\Requests\Site\Search\SearchListRequest;

use App\Http\Response\Site\Search\SearchListResponse;

use Inertia\Response as InertiaResponse;

class SearchController extends Controller
{
    /**
     * 横断検索の一覧画面を表示する
     *
     * @param SearchListRequest $request
     * @param SearchListDomain $domain
     * @param SearchListResponse $response
     * @return InertiaResponse
     */
    public function searchList(SearchListRequest $request, SearchListDomain $domain, SearchListResponse $response): InertiaResponse
    {
        $data = $domain($request->input_keyword, $request->select_search, $request->page);
        return $response->response($data);
    }
}
