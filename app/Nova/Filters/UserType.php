<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserType extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, Builder $query, $value): Builder
    {
        return $query->{$value}();
    }

    /**
     * Get the filter's available options.
     *
     *
     * @return array
     */
    public function options(Request $request): array
    {
        return [
            'Anonymous' => 'anonymous',
            'Registered' => 'registered',
        ];
    }
}
