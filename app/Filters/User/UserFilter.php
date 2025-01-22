<?php

namespace App\Filters\User;

use App\Filters\Trait\TrashedFilter;
use Closure;

class UserFilter
{
    use TrashedFilter;

    public function handle($request, Closure $next)
    {
        $query = $next($request);

        $query->when(request()->has('search') && !empty(request('search')), function ($query) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        });

        $this->applyTrashedFilter($query);

        return $query;
    }
}
