<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class UsersPerType extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $query = (new User())->newQuery();

        $results = $query->select(
            'SUM(IF(email IS NOT NULL, 1, 0)) as \'Registered\''
        )->addSelect(
            'SUM(IF(email IS NULL, 1, 0)) as \'Anonymous\''
        )->tap(
            fn ($query) => $this->applyFilterQuery($request, $query)
        )->get();

        return $this->result($results->all());
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'users-per-type';
    }
}
