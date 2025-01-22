<?php

namespace App\Filters\Global;

use Closure;

class OrderByFilter
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        $query->when((request('sortDirection') && request('sortColumn')),
            fn($q) => $q->orderBy('id', request('sortDirection', 'asc')),
            fn($q) => $q->orderBy('id', request('sortDirection', 'desc'))
        );
        return $query;
    }
}
