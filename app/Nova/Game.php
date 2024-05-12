<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Game extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Game::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'difficulty',
    ];

    public static $with = ['player'];

    /**
     * Get the fields displayed by the resource.
     *
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')
                ->sortable()
                ->readonly(),

            Text::make('UUID')
                ->readonly(),

            BelongsTo::make('Player', 'player', \App\Nova\User::class)
                ->readonly(),

            Text::make('Difficulty', fn () => $this->difficulty->name)
                ->sortable()
                ->readonly(),

            Boolean::make('Is Completed')
                ->sortable()
                ->readonly(),

            DateTime::make('Started At', 'created_at')
                ->sortable()
                ->readonly(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
