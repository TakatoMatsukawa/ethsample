<?php

namespace App\Http\Response\Site;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * ページネーション トレイト
 */
trait PaginationTrait
{
    /**
     * ページリンク
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    private function pageLinks(LengthAwarePaginator $paginator): array
    {
        $paging = config('pagination.site_paging');;
        $links = [];
        $currentPage = $paginator->currentPage() > $paginator->lastPage() ? $paginator->lastPage() : $paginator->currentPage();
        $startPage = $currentPage - $paging + 3;
        $lastPage = $paginator->lastPage();
        if ($startPage + $paging > $paginator->lastPage()) {
            $startPage = $paginator->lastPage() - $paging + 1;
        }
        if ($startPage < 1) {
            $startPage = 1;
        }

        // 最初
        if ($currentPage !== 1) {
            $links[] = [
                'url' => $currentPage === 1 ? '' : $paginator->url(1),
                'label' => '<<',
                'is_current' => $currentPage === 1,
            ];
        }
        // 前
        $links[] = [
            'url' => $currentPage === 1 ? '' : $paginator->url($currentPage - 1),
            'label' => '<',
            'is_current' => $currentPage === 1
        ];
        for ($i = $startPage; $i < $startPage + $paging; $i++) {
            $links[] = [
                'url' => $paginator->url($i),
                'label' => "" . $i,
                'is_current' => $i === $currentPage,
            ];
            if ($i >= $paginator->lastPage()) {
                break;
            }
        }
        // 次
        $links[] = [
            'url' => $currentPage === $paginator->lastPage() ? '' : $paginator->url($currentPage + 1),
            'label' => '>',
            'is_current' => $currentPage === $paginator->lastPage(),
        ];
        // 最後
        if ($currentPage !== $lastPage) {
            $links[] = [
                'url' =>
                $currentPage === $lastPage
                    ? ''
                    : $paginator->url($lastPage),
                'label' => '>>',
                'is_current' => $currentPage === $lastPage,
            ];
        }
        return $links;
    }
}
