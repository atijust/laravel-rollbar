<?php
namespace Atijust\LaravelRollbar;

use App, Config;
use Illuminate\Support\ServiceProvider;
use Rollbar;
use RollbarNotifier;

class LaravelRollbarServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('atijust/laravel-rollbar');

        $this->app->log->listen(function ($level, $message, $context) {
            if (!in_array($level, Config::get('laravel-rollbar::config.excluded_levels', []))) {
                $rollbar = App::make('rollbar');

                if ($message instanceof \Exception) {
                    $rollbar->report_exception($message);
                } else {
                    $rollbar->report_message($message, $level, $context);
                }
            }
        });

        $this->app->finish(function () {
            $rollbar = App::make('rollbar');
            $rollbar->flush();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('rollbar', function ($app) {
            $defaults = [
                'environment' => $app->environment(),
                'root'        => base_path(),
            ];

            $config = array_merge($defaults, Config::get('laravel-rollbar::config'));

            $notifier = new RollbarNotifier($config);

            Rollbar::$instance = $notifier;

            return $notifier;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
