<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class WithoutDiphthongs implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNot($builder->raw('REGEXP_LIKE(`text`, \'[a-z]*[aeiouy]{2,}[a-z]*\')'));
    }
}
