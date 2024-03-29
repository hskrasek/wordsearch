<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\GamesPerDay;
use App\Nova\Metrics\GamesPerDifficulty;
use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\UsersPerType;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new NewUsers(),
            new UsersPerType(),
            new GamesPerDay(),
            new GamesPerDifficulty(),
        ];
    }
}
