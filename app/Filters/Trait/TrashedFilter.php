<?php

namespace App\Filters\Trait;


trait TrashedFilter
{
    public function applyTrashedFilter($query): void
    {
        $query->when(request('trashed', false), fn($q) => $q->onlyTrashed());
    }
}
