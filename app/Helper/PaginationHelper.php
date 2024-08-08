<?php

namespace App\Helper;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaginationHelper
{
    public static function paginateRawQuery($query, $bindings, $perPage = 5, $page = null, $totalQuery = null): LengthAwarePaginator
    {
        $page = $page ?: request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        $query .= " LIMIT :limit OFFSET :offset";
        $bindings['limit'] = $perPage;
        $bindings['offset'] = $offset;

        $results = DB::select($query, $bindings);

        if ($totalQuery) {
            $totalCount = DB::selectOne($totalQuery)->total;
        } else {
            $totalCount = count($results);
        }

        return new LengthAwarePaginator(
            $results,
            $totalCount,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

}
