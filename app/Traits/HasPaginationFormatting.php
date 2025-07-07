<?php

namespace App\Traits;

trait HasPaginationFormatting
{
    protected function formatPagination($paginated, callable $itemTransformer): array
    {
        return [
            'current_page' => $paginated->currentPage(),
            'data' => $paginated->getCollection()
                ->map($itemTransformer)
                ->all(),
            'first_page_url' => $paginated->url(1),
            'from' => $paginated->firstItem(),
            'last_page' => $paginated->lastPage(),
            'last_page_url' => $paginated->url($paginated->lastPage()),
            'links' => $paginated->linkCollection()->toArray(),
            'next_page_url' => $paginated->nextPageUrl(),
            'path' => $paginated->path(),
            'per_page' => $paginated->perPage(),
            'prev_page_url' => $paginated->previousPageUrl(),
            'to' => $paginated->lastItem(),
            'total' => $paginated->total(),
        ];
    }
}
