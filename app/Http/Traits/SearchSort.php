<?php

namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

trait SearchSort {

    /**
     * @param Request $request
     * @return Collection
     */
    public static function credits(Request $request): Collection
    {
        return collect([
            'sort' => $request->get('sort') ?: 'id', // default sorting column
            'order' => $request->get('order') ?: 'asc', // default sorting order
            'entries' => $request->get('entries') ?: 10, // default items per page
            'search' => $request->get('search') ?: '%', // default searching value
        ]);
    }
}
