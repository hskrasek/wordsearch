<?php

namespace App\Providers;

use donatj\UserAgent\UserAgent;
use donatj\UserAgent\UserAgentParser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('words', function () {
            return json_decode(Storage::get('words_dictionary.json'), true);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Request::macro('userAgentExtended', static function (): UserAgent {
            static $parser = new UserAgentParser();

            return $parser->parse($this->userAgent());
        });

        Model::shouldBeStrict(! $this->app->isProduction());
    }
}
